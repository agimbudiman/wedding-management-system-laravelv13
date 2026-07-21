<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\ManagementUser;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function getDefaultQuotes()
    {
        return [
            [
                'text' => 'Hidup itu seperti bersepeda. Untuk menjaga keseimbangan, Anda harus terus bergerak.',
                'author' => 'Albert Einstein',
                'active' => true
            ],
            [
                'text' => 'Satu-satunya cara untuk melakukan pekerjaan hebat adalah dengan mencintai apa yang Anda lakukan.',
                'author' => 'Steve Jobs',
                'active' => true
            ],
            [
                'text' => 'Banyak dari kegagalan hidup adalah orang-orang yang tidak menyadari seberapa dekat mereka dengan kesuksesan ketika mereka menyerah.',
                'author' => 'Thomas A. Edison',
                'active' => true
            ],
            [
                'text' => 'Jangan melihat jam; lakukan apa yang dilakukannya. Teruslah berjalan.',
                'author' => 'Sam Levenson',
                'active' => true
            ],
            [
                'text' => 'Masa depan adalah milik mereka yang percaya pada keindahan impian mereka.',
                'author' => 'Eleanor Roosevelt',
                'active' => true
            ],
            [
                'text' => 'Bukan seberapa banyak yang kita lakukan, tetapi seberapa banyak kasih sayang yang kita berikan pada tindakan kita.',
                'author' => 'Bunda Teresa',
                'active' => true
            ],
            [
                'text' => 'Ubah pikiran Anda dan Anda akan mengubah dunia Anda.',
                'author' => 'Norman Vincent Peale',
                'active' => true
            ]
        ];
    }

    public function index()
    {
        $user = auth()->guard('management')->user();
        $isCrew = $user->role === 'crew';

        if ($isCrew) {
            // Crew Dashboard Data
            $completedEvents = Event::where('status', 'Completed')
                ->whereHas('crews', function($query) use ($user) {
                    $query->where('management_user_id', $user->id);
                })->count();

            $upcomingEventsCount = Event::where('status', 'Upcoming')
                ->where('date', '>=', Carbon::today()->toDateString())
                ->whereHas('crews', function($query) use ($user) {
                    $query->where('management_user_id', $user->id);
                })->count();

            $inProgressEvents = Event::where('status', 'In Progress')
                ->whereHas('crews', function($query) use ($user) {
                    $query->where('management_user_id', $user->id);
                })->count();

            $inQueueEvents = Event::where('status', 'In Queue')
                ->whereHas('crews', function($query) use ($user) {
                    $query->where('management_user_id', $user->id);
                })->count();

            $recentUpcomingEvents = Event::where('status', 'Upcoming')
                ->where('date', '>=', Carbon::today()->toDateString())
                ->whereHas('crews', function($query) use ($user) {
                    $query->where('management_user_id', $user->id);
                })
                ->orderBy('date', 'asc')
                ->take(5)
                ->get();

            // Fetch tasks assigned to the crew
            $yourTasks = \App\Models\EventTodo::where('management_user_id', $user->id)
                ->with('event')
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get();

        } else {
            // General/Admin Dashboard Data
            $completedEvents = Event::where('status', 'Completed')->count();
            $upcomingEventsCount = Event::where('status', 'Upcoming')
                ->where('date', '>=', Carbon::today()->toDateString())
                ->count();
            $inProgressEvents = Event::where('status', 'In Progress')->count();
            $inQueueEvents = Event::where('status', 'In Queue')->count();

            $recentUpcomingEvents = Event::where('status', 'Upcoming')
                ->where('date', '>=', Carbon::today()->toDateString())
                ->orderBy('date', 'asc')
                ->take(5)
                ->get();
            
            $yourTasks = collect();
        }

        $categories = EventCategory::all();

        $allEvents = Event::all()->map(function ($event) {
            $color = 'blue';
            if ($event->status === 'Completed') {
                $color = 'green';
            } elseif ($event->status === 'In Progress') {
                $color = 'orange';
            } elseif ($event->status === 'In Queue') {
                $color = 'red';
            }
            return [
                'eventName' => $event->name,
                'date' => $event->date ? $event->date->format('Y-m-d') : null,
                'color' => $color,
                'venue' => $event->venue,
                'status' => $event->status
            ];
        });

        // Get Crew Stats
        $crewTotal = ManagementUser::where('role', 'crew')->count();
        $crewAvailable = ManagementUser::where('role', 'crew')->where('status', 'Available')->count();
        $crewNotAvailable = $crewTotal - $crewAvailable;

        // Yearly Overview Chart Data
        $currentYear = Carbon::now()->year;
        $monthlyActivityData = array_fill(0, 12, 0);

        $eventsQuery = Event::whereYear('date', $currentYear);
        if ($isCrew) {
            $eventsQuery->whereHas('crews', function($query) use ($user) {
                $query->where('management_user_id', $user->id);
            });
        }
        $eventsThisYear = $eventsQuery->get();
        
        foreach ($eventsThisYear as $event) {
            if ($event->date) {
                $monthIndex = $event->date->month - 1; // 0-indexed
                $monthlyActivityData[$monthIndex]++;
            }
        }

        // Get all unique years for dropdown selector
        $availableYearsQuery = Event::query();
        if ($isCrew) {
            $availableYearsQuery->whereHas('crews', function($query) use ($user) {
                $query->where('management_user_id', $user->id);
            });
        }
        
        $availableYears = $availableYearsQuery->get()
            ->map(function ($event) {
                return $event->date ? $event->date->year : null;
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
            
        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
        }
        rsort($availableYears);

        // Fetch Daily Quotes list for the slideshow
        $quotesJson = \App\Models\WebsiteSetting::get('quotes_list');
        $allQuotes = $quotesJson ? json_decode($quotesJson, true) : $this->getDefaultQuotes();

        // Filter only active quotes
        $quotes = array_values(array_filter($allQuotes, function ($q) {
            return !isset($q['active']) || $q['active'] === true || $q['active'] === 'true' || $q['active'] === 1 || $q['active'] === '1';
        }));

        $slideshowActive = \App\Models\WebsiteSetting::get('quotes_slideshow_active', '1');
        $slideshowDuration = \App\Models\WebsiteSetting::get('quotes_slideshow_duration', '5');

        if ($slideshowActive === '0' || $slideshowActive === 0) {
            // Select one random quote to display statically on refresh
            if (count($quotes) > 0) {
                $randomIndex = array_rand($quotes);
                $quotes = [$quotes[$randomIndex]];
            }
        }

        return view('management_system.dashboard.index', compact(
            'completedEvents',
            'upcomingEventsCount',
            'inProgressEvents',
            'inQueueEvents',
            'recentUpcomingEvents',
            'categories',
            'allEvents',
            'crewTotal',
            'crewAvailable',
            'crewNotAvailable',
            'monthlyActivityData',
            'availableYears',
            'currentYear',
            'quotes',
            'slideshowActive',
            'slideshowDuration',
            'isCrew',
            'yourTasks'
        ));
    }

    public function getCalendarEvents(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $events = Event::whereBetween('date', [$start, $end])
            ->get(['id', 'name as title', 'date as start', 'venue', 'status']);

        $formattedEvents = $events->map(function($event) {
            $color = '#6D9C4C'; // Default (Upcoming)
            if ($event->status === 'In Progress') $color = '#D97706';
            if ($event->status === 'Completed') $color = '#38A169';
            if ($event->status === 'In Queue') $color = '#EF4444';

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start->format('Y-m-d'),
                'url' => route('management.event.show', $event->id),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'venue' => $event->venue,
                    'status' => $event->status
                ]
            ];
        });

        return response()->json($formattedEvents);
    }

    public function getYearlyOverviewData(Request $request)
    {
        $year = intval($request->get('year', Carbon::now()->year));
        $user = auth()->guard('management')->user();
        
        $monthlyActivity = array_fill(1, 12, 0);
        $query = Event::whereYear('date', $year);
        
        if ($user->role === 'crew') {
            $query->whereHas('crews', function($q) use ($user) {
                $q->where('management_user_id', $user->id);
            });
        }
        
        $eventsThisYear = $query->get();
        foreach ($eventsThisYear as $event) {
            $month = $event->date->month;
            $monthlyActivity[$month]++;
        }
        $monthlyActivityData = array_values($monthlyActivity);

        return response()->json($monthlyActivityData);
    }
}
