<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $type = $request->get('type');
            
            $query = \App\Models\Payment::with(['event']);
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('invoice_no', 'LIKE', "%{$search}%")
                      ->orWhere('payment_type', 'LIKE', "%{$search}%")
                      ->orWhere('amount', 'LIKE', "%{$search}%")
                      ->orWhereHas('event', function($sq) use ($search) {
                          $sq->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }

            if (!empty($type)) {
                $query->where('payment_type', $type);
            }
            
            $payments = $query->latest()->paginate(10);
            
            // Format amounts, invoice links, dates and prepare URLs
            $payments->getCollection()->transform(function($payment) {
                $payment->formatted_amount = 'Rp ' . number_format($payment->amount, 0, ',', '.');
                $payment->formatted_date = $payment->payment_date ? date('d M Y', strtotime($payment->payment_date)) : '-';
                $payment->show_url = route('management.payment.show', $payment->id);
                $payment->download_url = route('management.payment.export-pdf', $payment->invoice_no);
                $payment->proof_url = $payment->proof_document ? asset('storage/' . $payment->proof_document) : null;
                return $payment;
            });
            
            return response()->json($payments);
        }

        $payments = \App\Models\Payment::with('event')->latest()->paginate(10);
        return view('management_system.payment.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $events = \App\Models\Event::all();
        $packages = \App\Models\Package::all();
        $selectedEventId = $request->query('event_id');

        $previousPayment = null;
        if ($selectedEventId) {
            $previousPayment = \App\Models\Payment::where('event_id', $selectedEventId)
                ->orderBy('id', 'desc')
                ->first();
        }

        return view('management_system.payment.create', compact('events', 'packages', 'selectedEventId', 'previousPayment'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'payment_type' => 'required|in:DP,Partial,Final',
                'package_id' => 'required|string', // can be 'custom' or numeric id
                'custom_package_name' => 'required_if:package_id,custom|nullable|string',
                'custom_package_price' => 'required|numeric',
                'amount' => 'nullable|numeric',
                'payment_date' => 'required|date',
                'notes' => 'nullable|string',
                'proof_document' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

            $data = $request->except(['proof_document', 'package_id', 'amount']);

            // Handle Package
            if ($request->package_id === 'custom') {
                $data['package_id'] = null;
            } else {
                $data['package_id'] = $request->package_id;
                $data['custom_package_name'] = null;
            }

            // Handle Payment Amount
            if ($request->payment_type === 'Final') {
                $packagePrice = $request->custom_package_price;
                $totalPaidSoFar = \App\Models\Payment::where('event_id', $request->event_id)->sum('amount');
                $data['amount'] = max(0, $packagePrice - $totalPaidSoFar);
            } else {
                $data['amount'] = $request->amount ?? 0;
            }

            if ($request->hasFile('proof_document')) {
                $path = $request->file('proof_document')->store('payments', 'public');
                $data['proof_document'] = $path;
            }

            // Generate Unique Invoice No
            $dateStr = date('Ymd', strtotime($request->payment_date));
            $data['invoice_no'] = 'INV-' . $dateStr . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            $data['status'] = 'Paid';

            $payment = \App\Models\Payment::create($data);

            // Send Notification to users with notification-financial permission
            $usersToNotify = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                $query->where('name', 'notification-financial');
            })->get();
            \Illuminate\Support\Facades\Notification::send($usersToNotify, new \App\Notifications\PaymentReceivedNotification($payment));

            return redirect()->route('management.payment.show', $payment->id)->with('success', 'Payment recorded successfully!');
        } catch (\Throwable $e) {
            \Log::error("Payment Save Error: " . $e->getMessage() . " on line " . $e->getLine());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $payment = \App\Models\Payment::with(['event', 'package'])->findOrFail($id);
        
        if ($payment->package_id && $payment->package) {
            $packagePrice = $payment->package->final_price;
        } else {
            $packagePrice = $payment->custom_package_price ?? 0;
        }
        
        // Total paid for this event up to this payment
        $totalPaid = \App\Models\Payment::where('event_id', $payment->event_id)
                        ->where('id', '<=', $payment->id)
                        ->sum('amount');
                        
        $remainingBalance = max(0, $packagePrice - $totalPaid);
        
        return view('management_system.payment.invoice', compact('payment', 'packagePrice', 'totalPaid', 'remainingBalance'));
    }

    public function exportPdf($invoiceNo)
    {
        $payment = \App\Models\Payment::with(['event', 'package'])->where('invoice_no', $invoiceNo)->firstOrFail();
        
        if ($payment->package_id && $payment->package) {
            $packagePrice = $payment->package->final_price;
        } else {
            $packagePrice = $payment->custom_package_price ?? 0;
        }
        
        $totalPaid = \App\Models\Payment::where('event_id', $payment->event_id)
                        ->where('id', '<=', $payment->id)
                        ->sum('amount');
                        
        $remainingBalance = max(0, $packagePrice - $totalPaid);
        
        $pdf = Pdf::loadView('management_system.payment.pdf_invoice', compact('payment', 'packagePrice', 'totalPaid', 'remainingBalance'));
        
        return $pdf->download('Invoice_' . $payment->invoice_no . '.pdf');
    }
}
