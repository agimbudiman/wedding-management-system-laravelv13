<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Package;
use App\Models\Payment;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    /**
     * Display the reservation landing page.
     */
    public function index()
    {
        return view('landing.reservasi');
    }

    /**
     * Check date availability based on maximum events per day setting.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->date;
        $maxEvents = (int) WebsiteSetting::get('max_events_per_day', 3);
        
        $currentEventsCount = Event::whereDate('date', $date)->count();

        if ($currentEventsCount >= $maxEvents) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal yang dipilih sudah fullbook'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tanggal tersedia'
        ]);
    }

    /**
     * Generate a temporary Snap Token for Midtrans payment without writing to the database yet.
     */
    public function getSnapToken(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_whatsapp' => 'required|string|max:20',
            'package_id' => 'required|string',
            'event_date' => 'required|date',
        ]);

        try {
            // Server-side availability check
            $maxEvents = (int) WebsiteSetting::get('max_events_per_day', 3);
            $currentEventsCount = Event::whereDate('date', $request->event_date)->count();

            if ($currentEventsCount >= $maxEvents) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal yang dipilih sudah fullbook'
                ], 422);
            }

            $isCustom = ($request->package_id === 'custom');
            $packageName = 'Custom Wedding Package';

            if (!$isCustom) {
                $packageModel = Package::find($request->package_id);
                if ($packageModel) {
                    $packageName = $packageModel->name;
                }
            }

            // Generate temporary unique invoice number
            $dateStr = now()->format('Ymd');
            $invoiceNo = 'INV-' . $dateStr . '-' . strtoupper(Str::random(6));

            // Dynamic DP nominal
            $dpNominal = (float) WebsiteSetting::get('reservation_dp_nominal', 5000000);

            $midtransPayload = [
                'transaction_details' => [
                    'order_id' => $invoiceNo,
                    'gross_amount' => (int) $dpNominal,
                ],
                'item_details' => [
                    [
                        'id' => 'DP-' . $invoiceNo,
                        'price' => (int) $dpNominal,
                        'quantity' => 1,
                        'name' => 'DP Reservasi: ' . $packageName,
                    ]
                ],
                'customer_details' => [
                    'first_name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_whatsapp,
                ],
            ];

            $midtransService = resolve(\App\Services\MidtransService::class);
            $snapToken = $midtransService->getSnapToken($midtransPayload);

            // Save the complete reservation form fields in cache for 24 hours
            \Illuminate\Support\Facades\Cache::put('reservation_payload_' . $invoiceNo, [
                'client_name' => $request->client_name,
                'client_whatsapp' => $request->client_whatsapp,
                'client_email' => $request->client_email,
                'groom_name' => $request->groom_name,
                'bride_name' => $request->bride_name,
                'event_date' => $request->event_date,
                'package_id' => $request->package_id,
                'event_notes' => $request->event_notes,
                'invoice_no' => $invoiceNo,
            ], now()->addHours(24));

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'invoice_no' => $invoiceNo,
                'amount' => $dpNominal,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mendapatkan Snap Token: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan gerbang pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new reservation in the database.
     * Generates a Wedding Event and a corresponding DP Payment invoice.
     * Only executed AFTER payment is successfully verified from Midtrans.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_whatsapp' => 'required|string|max:50',
            'client_email' => 'required|email|max:255',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'package_id' => 'required|string', // can be 'custom' or numeric database id
            'event_notes' => 'nullable|string',
            'invoice_no' => 'required|string',
            'snap_token' => 'required|string',
        ]);

        try {
            // Verify payment status with Midtrans API for security
            $midtransService = resolve(\App\Services\MidtransService::class);
            $statusData = $midtransService->getTransactionStatus($request->invoice_no);

            $transactionStatus = $statusData['transaction_status'] ?? '';
            $fraudStatus = $statusData['fraud_status'] ?? '';

            $isPaid = ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept'));

            if (!$isPaid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran belum diselesaikan atau transaksi tidak valid.'
                ], 400);
            }

            $response = DB::transaction(function () use ($request) {
                // 1. Fetch or fallback to an EventCategory for Weddings
                $category = EventCategory::where('slug', 'like', '%wedding%')->first()
                    ?: EventCategory::first();

                if (!$category) {
                    $category = EventCategory::create([
                        'name' => 'Wedding Organizer',
                        'slug' => 'wedding-organizer',
                        'description' => 'Kategori Acara Pernikahan'
                    ]);
                }

                // 2. Set Event Name: (bride_name & groom_name + Wedding) or fallback to client name
                $groom = trim($request->groom_name);
                $bride = trim($request->bride_name);
                if ($bride && $groom) {
                    $eventName = "{$bride} & {$groom} Wedding";
                } else {
                    $eventName = trim($request->client_name) . " Wedding";
                }

                // 3. Generate secure slug, tokens, and dates
                $slug = Str::slug($eventName) . '-' . mt_rand(100, 999);
                $clientQrToken = (string) Str::uuid();
                $guestQrToken = (string) Str::uuid();

                // 4. Create the Wedding Event record (status: 'In Queue', venue: 'Belum Ditentukan')
                $event = Event::create([
                    'category_id' => $category->id,
                    'name' => $eventName,
                    'groom_name' => $groom ?: null,
                    'bride_name' => $bride ?: null,
                    'client_name' => $request->client_name,
                    'client_phone' => $request->client_whatsapp,
                    'client_email' => $request->client_email,
                    'date' => $request->event_date,
                    'venue' => 'Belum Ditentukan', // non-nullable field fallback
                    'type' => 'Wedding',
                    'status' => 'In Queue', // status as requested
                    'slug' => $slug,
                    'client_qr_token' => $clientQrToken,
                    'guest_qr_token' => $guestQrToken,
                    'is_client_qr_active' => true,
                    'is_guest_qr_active' => true,
                    'personalization' => [
                        'photos' => [
                            'hero' => '',
                            'couple_groom' => '',
                            'couple_bride' => '',
                            'event' => '',
                            'footer' => '',
                        ],
                        'gallery' => ['', '', '', '', '', ''],
                        'sections' => [
                            'hero' => true,
                            'couple' => true,
                            'event' => true,
                            'gallery' => true,
                            'wishes' => true,
                            'footer' => true,
                        ],
                    ]
                ]);

                // 5. Use the paid invoice number directly
                $invoiceNo = $request->invoice_no;

                // 6. Handle Package Details
                $packageId = $request->package_id;
                $isCustom = ($packageId === 'custom');
                $packageModel = null;
                $customPackageName = null;
                $customPackagePrice = null;

                if ($isCustom) {
                    $customPackageName = 'Paket Kustom (Custom)';
                    $customPackagePrice = 0.00;
                } else {
                    $packageModel = Package::find($packageId);
                }

                // Get dynamic DP nominal from settings
                $dpNominal = (float) WebsiteSetting::get('reservation_dp_nominal', 5000000);

                // 7. Create Payment record with 'Paid' status directly!
                $payment = Payment::create([
                    'invoice_no' => $invoiceNo,
                    'event_id' => $event->id,
                    'package_id' => $isCustom ? null : $packageId,
                    'custom_package_name' => $customPackageName,
                    'custom_package_price' => $isCustom ? $customPackagePrice : ($packageModel ? $packageModel->final_price : 0),
                    'payment_type' => 'DP',
                    'amount' => $dpNominal, // dynamic DP nominal
                    'payment_date' => now()->toDateString(),
                    'notes' => $request->event_notes ?: 'Down Payment Paid via Midtrans Snap popup',
                    'status' => 'Paid', // marked as Paid directly!
                    'snap_token' => $request->snap_token,
                ]);

                // Send Notification for New Event
                $usersToNotifyEvent = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                    $query->where('name', 'notification-event');
                })->get();
                \Illuminate\Support\Facades\Notification::send($usersToNotifyEvent, new \App\Notifications\EventCreatedNotification($event));

                // Send Notification for New Payment
                $usersToNotifyPayment = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                    $query->where('name', 'notification-financial');
                })->get();
                \Illuminate\Support\Facades\Notification::send($usersToNotifyPayment, new \App\Notifications\PaymentReceivedNotification($payment));

                // Send Mailtrap Sandbox Email Notification
                try {
                    $usersToEmail = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                        $query->where('name', 'notification-mailtrap');
                    })->get();
                    if ($usersToEmail->isNotEmpty()) {
                        $recipientEmails = $usersToEmail->pluck('email')->toArray();
                        \Illuminate\Support\Facades\Mail::to($recipientEmails)->send(new \App\Mail\MidtransOrderPaidMail($payment, $request->client_email, $request->client_whatsapp));
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email Mailtrap: ' . $e->getMessage());
                }

                // 8. Generate dynamic Booking Code
                $dateStr = now()->format('Ymd');
                $bookingCode = 'BR-' . $dateStr . '-' . mt_rand(1000, 9999);

                // Clean up cache payload
                \Illuminate\Support\Facades\Cache::forget('reservation_payload_' . $invoiceNo);

                return [
                    'success' => true,
                    'booking_code' => $bookingCode,
                    'invoice_no' => $invoiceNo,
                    'client_name' => $event->client_name,
                    'package_name' => $isCustom ? 'Paket Kustom (Custom)' : ($packageModel ? $packageModel->name : 'Wedding Package'),
                    'event_date' => $event->date->format('Y-m-d'),
                    'amount' => $dpNominal,
                ];
            });

            return response()->json($response);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storing reservation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan reservasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a public invoice for a specific reservation.
     */
    public function invoice($invoiceNo)
    {
        $payment = Payment::with(['event', 'package'])->where('invoice_no', $invoiceNo)->firstOrFail();

        if ($payment->package_id && $payment->package) {
            $packagePrice = $payment->package->final_price;
        } else {
            $packagePrice = $payment->custom_package_price ?? 0;
        }

        $totalPaid = Payment::where('event_id', $payment->event_id)
            ->where('id', '<=', $payment->id)
            ->sum('amount');

        $remainingBalance = max(0, $packagePrice - $totalPaid);

        return view('landing.invoice', compact('payment', 'packagePrice', 'totalPaid', 'remainingBalance'));
    }

    public function exportPdf($invoiceNo)
    {
        $payment = Payment::with(['event', 'package'])->where('invoice_no', $invoiceNo)->firstOrFail();

        if ($payment->package_id && $payment->package) {
            $packagePrice = $payment->package->final_price;
        } else {
            $packagePrice = $payment->custom_package_price ?? 0;
        }

        $totalPaid = Payment::where('event_id', $payment->event_id)
            ->where('id', '<=', $payment->id)
            ->sum('amount');

        $remainingBalance = max(0, $packagePrice - $totalPaid);

        $pdf = Pdf::loadView('management_system.payment.pdf_invoice', compact('payment', 'packagePrice', 'totalPaid', 'remainingBalance'));
        
        return $pdf->download('Invoice_' . $payment->invoice_no . '.pdf');
    }

    /**
     * Handle Midtrans asynchronous webhook payment notifications.
     * Functions as a fallback in case browser window closes immediately after payment.
     */
    public function midtransNotification(Request $request)
    {
        $data = $request->all();
        \Illuminate\Support\Facades\Log::info('Midtrans Webhook Received', $data);

        $midtransService = resolve(\App\Services\MidtransService::class);

        // Verify the notification signature
        if (!$midtransService->verifySignature($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature key'
            ], 403);
        }

        $orderId = $data['order_id'];
        $transactionStatus = $data['transaction_status'];
        $paymentType = $data['payment_type'] ?? null;
        $fraudStatus = $data['fraud_status'] ?? null;

        // Check if transaction is successful
        $isPaid = ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept'));

        // Find the payment record by invoice_no
        $payment = Payment::where('invoice_no', $orderId)->first();

        if (!$payment) {
            // Edge-case: User closed browser before frontend onSuccess call finished
            if ($isPaid) {
                $cachedPayload = \Illuminate\Support\Facades\Cache::get('reservation_payload_' . $orderId);
                if ($cachedPayload) {
                    \Illuminate\Support\Facades\Log::info('Creating reservation from cached payload via webhook fallback', ['order_id' => $orderId]);
                    
                    DB::transaction(function () use ($cachedPayload, $orderId, $data, $paymentType) {
                        $category = EventCategory::where('slug', 'like', '%wedding%')->first() ?: EventCategory::first();
                        if (!$category) {
                            $category = EventCategory::create([
                                'name' => 'Wedding Organizer',
                                'slug' => 'wedding-organizer',
                                'description' => 'Kategori Acara Pernikahan'
                            ]);
                        }

                        $groom = trim($cachedPayload['groom_name'] ?? '');
                        $bride = trim($cachedPayload['bride_name'] ?? '');
                        if ($bride && $groom) {
                            $eventName = "{$bride} & {$groom} Wedding";
                        } else {
                            $eventName = trim($cachedPayload['client_name']) . " Wedding";
                        }

                        $slug = Str::slug($eventName) . '-' . mt_rand(100, 999);
                        $event = Event::create([
                            'category_id' => $category->id,
                            'name' => $eventName,
                            'groom_name' => $groom ?: null,
                            'bride_name' => $bride ?: null,
                            'client_name' => $cachedPayload['client_name'],
                            'client_phone' => $cachedPayload['client_whatsapp'] ?? null,
                            'client_email' => $cachedPayload['client_email'] ?? null,
                            'date' => $cachedPayload['event_date'],
                            'venue' => 'Belum Ditentukan',
                            'type' => 'Wedding',
                            'status' => 'In Queue',
                            'slug' => $slug,
                            'client_qr_token' => (string) Str::uuid(),
                            'guest_qr_token' => (string) Str::uuid(),
                            'is_client_qr_active' => true,
                            'is_guest_qr_active' => true,
                            'personalization' => [
                                'photos' => ['hero' => '', 'couple_groom' => '', 'couple_bride' => '', 'event' => '', 'footer' => ''],
                                'gallery' => ['', '', '', '', '', ''],
                                'sections' => ['hero' => true, 'couple' => true, 'event' => true, 'gallery' => true, 'wishes' => true, 'footer' => true]
                            ]
                        ]);

                        $packageId = $cachedPayload['package_id'];
                        $isCustom = ($packageId === 'custom');
                        $packageModel = $isCustom ? null : Package::find($packageId);
                        $dpNominal = (float) WebsiteSetting::get('reservation_dp_nominal', 5000000);

                        $payment = Payment::create([
                            'invoice_no' => $orderId,
                            'event_id' => $event->id,
                            'package_id' => $isCustom ? null : $packageId,
                            'custom_package_name' => $isCustom ? 'Paket Kustom (Custom)' : null,
                            'custom_package_price' => $isCustom ? 0 : ($packageModel ? $packageModel->final_price : 0),
                            'payment_type' => 'DP',
                            'amount' => $dpNominal,
                            'payment_date' => now()->toDateString(),
                            'notes' => "DP Paid via Midtrans Webhook Fallback ({$paymentType})",
                            'status' => 'Paid',
                            'snap_token' => $data['transaction_id'] ?? null,
                        ]);
                        
                        // Send Notification for New Event
                        $usersToNotifyEvent = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                            $query->where('name', 'notification-event');
                        })->get();
                        \Illuminate\Support\Facades\Notification::send($usersToNotifyEvent, new \App\Notifications\EventCreatedNotification($event));
                        
                        // Send Notification for New Payment
                        $usersToNotifyPayment = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                            $query->where('name', 'notification-financial');
                        })->get();
                        \Illuminate\Support\Facades\Notification::send($usersToNotifyPayment, new \App\Notifications\PaymentReceivedNotification($payment));

                        // Send Mailtrap Sandbox Email Notification
                        try {
                            $usersToEmail = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                                $query->where('name', 'notification-mailtrap');
                            })->get();
                            if ($usersToEmail->isNotEmpty()) {
                                $recipientEmails = $usersToEmail->pluck('email')->toArray();
                                $clientEmail = $cachedPayload['client_email'] ?? null;
                                $clientWhatsapp = $cachedPayload['client_whatsapp'] ?? null;
                                \Illuminate\Support\Facades\Mail::to($recipientEmails)->send(new \App\Mail\MidtransOrderPaidMail($payment, $clientEmail, $clientWhatsapp));
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Gagal mengirim email Mailtrap Webhook: ' . $e->getMessage());
                        }

                        \Illuminate\Support\Facades\Cache::forget('reservation_payload_' . $orderId);
                    });
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Processed successfully'
            ]);
        }

        // Standard update if payment already exists
        if ($isPaid && $payment->status !== 'Paid') {
            $payment->status = 'Paid';
            $payment->payment_date = now()->toDateString();
            $payment->notes = "Payment via Midtrans Webhook: {$paymentType} | Status: {$transactionStatus}";
            $payment->save();

            // Send Notification for Payment Update to Paid
            $usersToNotifyPayment = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                $query->where('name', 'notification-financial');
            })->get();
            \Illuminate\Support\Facades\Notification::send($usersToNotifyPayment, new \App\Notifications\PaymentReceivedNotification($payment));

            // Send Mailtrap Sandbox Email Notification
            try {
                $usersToEmail = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                    $query->where('name', 'notification-mailtrap');
                })->get();
                if ($usersToEmail->isNotEmpty()) {
                    $recipientEmails = $usersToEmail->pluck('email')->toArray();
                    $cachedPayload = \Illuminate\Support\Facades\Cache::get('reservation_payload_' . $payment->invoice_no);
                    $clientEmail = $cachedPayload['client_email'] ?? null;
                    $clientWhatsapp = $cachedPayload['client_whatsapp'] ?? null;
                    \Illuminate\Support\Facades\Mail::to($recipientEmails)->send(new \App\Mail\MidtransOrderPaidMail($payment, $clientEmail, $clientWhatsapp));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email Mailtrap Webhook Update: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification processed successfully'
        ]);
    }
}

