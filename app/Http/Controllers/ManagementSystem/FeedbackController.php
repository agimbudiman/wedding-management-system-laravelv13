<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\EventTestimonial;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the testimonials.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $query = EventTestimonial::with('event');
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('testimony', 'LIKE', "%{$search}%")
                      ->orWhere('rating', 'LIKE', "%{$search}%")
                      ->orWhereHas('event', function($eventQuery) use ($search) {
                          $eventQuery->where('name', 'LIKE', "%{$search}%")
                                    ->orWhere('client_name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $testimonials = $query->latest()->paginate(10);
            
            // Format data and prepare dates
            $testimonials->getCollection()->transform(function($t) {
                $t->event_name = $t->event->name ?? '-';
                $t->client_name = $t->event->client_name ?? '-';
                $t->formatted_date = $t->created_at->format('d M Y');
                return $t;
            });
            
            return response()->json($testimonials);
        }

        $testimonials = EventTestimonial::with('event')->latest()->paginate(10);
        return view('management_system.event.feedback', compact('testimonials'));
    }
}
