<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        // 1. Total Revenue
        $totalRevenue = \App\Models\Payment::sum('amount');
        
        // 2. Chart Data (Monthly Revenue for current year)
        $currentYear = date('Y');
        $monthlyRevenue = \App\Models\Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $chartLabels = [];
        $chartData = [];
        
        for($i = 1; $i <= 12; $i++) {
            $monthName = \Carbon\Carbon::create()->month($i)->format('M');
            $chartLabels[] = $monthName;
            
            $monthData = $monthlyRevenue->firstWhere('month', $i);
            $chartData[] = $monthData ? $monthData->total : 0;
        }

        // 3. Unpaid Events & Total Remaining
        $events = \App\Models\Event::with(['payments.package'])->get();
        $unpaidEvents = collect();
        $totalRemaining = 0;

        foreach ($events as $event) {
            if ($event->payments->count() > 0) {
                $lastPayment = $event->payments->sortByDesc('created_at')->first();
                
                if ($lastPayment->package_id && $lastPayment->package) {
                    $packagePrice = $lastPayment->package->final_price;
                } else {
                    $packagePrice = $lastPayment->custom_package_price ?? 0;
                }
                
                $totalPaid = $event->payments->sum('amount');
                $remaining = max(0, $packagePrice - $totalPaid);
                
                if ($remaining > 0) {
                    $unpaidEvents->push((object)[
                        'event_id' => $event->id,
                        'event_name' => $event->name,
                        'client_name' => $event->client_name,
                        'event_status' => $event->status,
                        'package_id' => $lastPayment->package_id ? $lastPayment->package_id : 'custom',
                        'custom_package_name' => $lastPayment->custom_package_name,
                        'custom_package_price' => $packagePrice,
                        'package_price' => $packagePrice,
                        'formatted_package_price' => 'Rp ' . number_format($packagePrice, 0, ',', '.'),
                        'total_paid' => $totalPaid,
                        'formatted_total_paid' => 'Rp ' . number_format($totalPaid, 0, ',', '.'),
                        'remaining' => $remaining,
                        'formatted_remaining' => 'Rp ' . number_format($remaining, 0, ',', '.'),
                        'create_payment_url' => route('management.payment.create') . '?event_id=' . $event->id
                    ]);
                    $totalRemaining += $remaining;
                }
            }
        }

        // Apply Search if requested (AJAX / JSON)
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');

            if (!empty($search)) {
                $unpaidEvents = $unpaidEvents->filter(function ($item) use ($search) {
                    return str_contains(strtolower($item->event_name), strtolower($search)) ||
                           str_contains(strtolower($item->client_name), strtolower($search));
                });
            }

            // Paginate manually
            $perPage = 10;
            $page = (int) $request->get('page', 1);
            $pagedData = $unpaidEvents->slice(($page - 1) * $perPage, $perPage)->values();
            
            $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData,
                $unpaidEvents->count(),
                $perPage,
                $page,
                ['path' => route('management.financial.index')]
            );

            return response()->json($paginated);
        }

        return view('management_system.financial.index', compact(
            'totalRevenue', 'totalRemaining', 'chartLabels', 'chartData', 'currentYear'
        ));
    }
}
