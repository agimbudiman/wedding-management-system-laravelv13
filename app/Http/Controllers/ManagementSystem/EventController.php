<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\ManagementUser;
use App\Models\Vendor;
use App\Models\EventTodo;
use App\Models\EventRundown;
use App\Models\EventNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    public function index(Request $request, $category_slug)
    {
        $category = EventCategory::where('slug', $category_slug)->firstOrFail();

        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $status = $request->get('status');
            $monthYear = $request->get('month_year'); // YYYY-MM

            $query = Event::where('category_id', $category->id);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('client_name', 'LIKE', "%{$search}%")
                        ->orWhere('venue', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($status)) {
                $query->where('status', $status);
            }

            if (!empty($monthYear)) {
                $parts = explode('-', $monthYear);
                if (count($parts) === 2) {
                    $query->whereYear('date', $parts[0])
                        ->whereMonth('date', $parts[1]);
                }
            }

            $query->orderBy('date', 'asc');

            $events = $query->paginate(10);

            $user = auth()->guard('management')->user();

            // Format dates and prepare URLs and permissions
            $events->getCollection()->transform(function ($event) use ($user) {
                $event->formatted_date = $event->date->format('d M Y');
                $event->raw_date = $event->date->format('Y-m-d');
                $event->detail_url = route('management.event.show', $event->id);
                $event->update_url = route('management.event.update', $event->id);
                $event->destroy_url = route('management.event.destroy', $event->id);

                $event->can_view = $user->hasPermission('event-view');
                $event->can_edit = $user->hasPermission('event-edit');
                $event->can_delete = $user->hasPermission('event-delete');

                return $event;
            });

            return response()->json($events);
        }

        $events = Event::where('category_id', $category->id)->latest()->paginate(10);
        $packages = \App\Models\Package::all();
        $dpNominal = (float) \App\Models\WebsiteSetting::get('reservation_dp_nominal', 5000000);
        return view('management_system.event.list', compact('category', 'events', 'packages', 'dpNominal'));
    }

    public function store(Request $request)
    {
        if (!auth()->guard('management')->user()->hasPermission('event-create')) {
            abort(403, 'Unauthorized action. You do not have permission to create events.');
        }

        $request->validate([
            'category_id' => 'required|exists:event_categories,id',
            'name' => 'required|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'google_maps_link' => 'nullable|string',
            'type' => 'required|string|max:255',
            'package_id' => 'required|string',
            'payment_type' => 'required|string|in:dp,partial,full',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $slug = \Illuminate\Support\Str::slug($request->name) . '-' . mt_rand(100, 999);
            $clientQrToken = (string) \Illuminate\Support\Str::uuid();
            $guestQrToken = (string) \Illuminate\Support\Str::uuid();

            $event = Event::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'groom_name' => $request->groom_name,
                'bride_name' => $request->bride_name,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'client_email' => $request->client_email,
                'date' => $request->date,
                'venue' => $request->venue,
                'google_maps_link' => $request->google_maps_link,
                'type' => $request->type,
                'status' => 'In Queue',
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

            $dateStr = now()->format('Ymd');
            $invoiceNo = 'INV-' . $dateStr . '-' . strtoupper(\Illuminate\Support\Str::random(6));

            $packageId = $request->package_id;
            $isCustom = ($packageId === 'custom');
            $packageModel = null;
            $customPackageName = null;
            $customPackagePrice = null;

            if ($isCustom) {
                $customPackageName = 'Paket Kustom (Custom)';
                $customPackagePrice = 0.00;
            } else {
                $packageModel = \App\Models\Package::find($packageId);
            }

            \App\Models\Payment::create([
                'invoice_no' => $invoiceNo,
                'event_id' => $event->id,
                'package_id' => $isCustom ? null : $packageId,
                'custom_package_name' => $customPackageName,
                'custom_package_price' => $isCustom ? $customPackagePrice : ($packageModel ? $packageModel->final_price : 0),
                'payment_type' => strtoupper($request->payment_type),
                'amount' => $request->payment_amount,
                'payment_date' => now()->toDateString(),
                'notes' => 'Manual entry by Admin.',
                'status' => 'Paid',
            ]);

            // Send Notification for New Event
            $usersToNotify = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                $query->where('name', 'notification-event');
            })->get();
            \Illuminate\Support\Facades\Notification::send($usersToNotify, new \App\Notifications\EventCreatedNotification($event));
        });

        return redirect()->back()->with('success', 'Event and Payment created successfully!');
    }

    public function show($id)
    {
        if (!auth()->guard('management')->user()->hasPermission('event-view')) {
            abort(403, 'Unauthorized action. You do not have permission to view event details.');
        }

        $event = Event::with(['category', 'crews', 'vendors', 'todos.assignedTo', 'rundowns', 'notes'])->findOrFail($id);

        // Get available crews (role 'crew' and status 'Available')
        $availableCrews = ManagementUser::where('role', 'crew')
            ->where('status', 'Available')
            ->get();

        $vendors = Vendor::all();

        return view('management_system.event.detail', compact('event', 'availableCrews', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeManagement($event, 'event-edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'google_maps_link' => 'nullable|string',
            'type' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        $oldStatus = $event->status;
        $event->update($request->all());

        if ($oldStatus !== $request->status) {
            $usersToNotify = \App\Models\ManagementUser::whereHas('role_relation.permissions', function ($query) {
                $query->where('name', 'notification-event');
            })->get();
            \Illuminate\Support\Facades\Notification::send($usersToNotify, new \App\Notifications\EventStatusChangedNotification($event));
        }

        return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeManagement($event, 'event-delete');
        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }

    private function authorizeManagement(Event $event, $permission = 'event-edit')
    {
        $currentUser = auth()->guard('management')->user();
        if (!$currentUser)
            abort(401);

        $isAdministrator = $currentUser->role === 'admin';
        $hasPermission = $currentUser->hasPermission($permission);

        $isTeamLeader = $event->crews()
            ->where('management_user_id', $currentUser->id)
            ->where('is_leader', true)
            ->exists();

        if (!$isAdministrator && !$isTeamLeader && !$hasPermission) {
            abort(403, 'Unauthorized action. You do not have the required permission.');
        }
    }

    public function exportPdf($id)
    {
        $event = Event::with(['category', 'crews', 'vendors', 'todos.assignedTo', 'rundowns', 'notes'])->findOrFail($id);

        $pdf = Pdf::loadView('management_system.event.pdf_brief', compact('event'));

        // Use event name as filename
        $filename = 'Event_Brief_' . str_replace(' ', '_', $event->name) . '_' . $event->date->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    // Event Detail Actions
    public function addCrew(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeManagement($event);
        $hasExistingLeader = $event->crews()->wherePivot('is_leader', true)->exists();
        $rules = [
            'management_user_ids' => 'required|array',
            'management_user_ids.*' => 'exists:management_users,id',
        ];
        if (!$hasExistingLeader) {
            $rules['leader_id'] = 'required|exists:management_users,id';
        } else {
            $rules['leader_id'] = 'nullable|exists:management_users,id';
        }

        $request->validate($rules, [
            'management_user_ids.required' => 'Harap pilih setidaknya satu crew.',
            'leader_id.required' => 'Harap pilih satu Leader untuk tim crew ini.'
        ]);

        DB::transaction(function () use ($event, $request) {
            foreach ($request->management_user_ids as $userId) {
                $isLeader = ($userId == $request->leader_id);
                $event->crews()->syncWithoutDetaching([
                    $userId => ['is_leader' => $isLeader]
                ]);

                $user = \App\Models\ManagementUser::find($userId);
                if ($user) {
                    $roleStr = $isLeader ? 'Leader' : 'Crew';
                    $user->notify(new \App\Notifications\CrewAssignedNotification($event, $roleStr));
                }
            }
        });

        return redirect()->back()->with('success', 'Crews assigned successfully!');
    }

    public function removeCrew($eventId, $crewId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeManagement($event);
        $event->crews()->detach($crewId);

        return redirect()->back()->with('success', 'Crew removed successfully!');
    }

    public function addVendor(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeManagement($event);
        $request->validate([
            'vendor_ids' => 'required|array',
            'vendor_ids.*' => 'exists:vendors,id',
        ]);
        $event->vendors()->syncWithoutDetaching($request->vendor_ids);

        return redirect()->back()->with('success', 'Vendors assigned successfully!');
    }

    public function removeVendor($eventId, $vendorId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeManagement($event);
        $event->vendors()->detach($vendorId);

        return redirect()->back()->with('success', 'Vendor removed successfully!');
    }

    public function addTodo(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeManagement($event);
        $request->validate([
            'todos' => 'required|array|min:1',
            'todos.*.title' => 'required|string|max:255',
            'todos.*.category' => 'required|string',
            'todos.*.due_date' => 'nullable|date',
            'todos.*.management_user_id' => 'nullable|exists:management_users,id',
        ]);

        DB::transaction(function () use ($eventId, $request) {
            $event = Event::find($eventId);
            foreach ($request->todos as $todoData) {
                $todo = EventTodo::create([
                    'event_id' => $eventId,
                    'title' => $todoData['title'],
                    'category' => $todoData['category'],
                    'due_date' => $todoData['due_date'],
                    'management_user_id' => $todoData['management_user_id'],
                ]);

                if (!empty($todoData['management_user_id'])) {
                    $user = \App\Models\ManagementUser::find($todoData['management_user_id']);
                    if ($user) {
                        $user->notify(new \App\Notifications\TodoAssignedNotification($todo, $event));
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'To-do items added!');
    }

    public function updateTodo(Request $request, EventTodo $todo)
    {
        $this->authorizeManagement($todo->event);
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'due_date' => 'nullable|date',
            'management_user_id' => 'nullable|exists:management_users,id',
        ]);

        $todo->update($request->all());

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function deleteTodo(EventTodo $todo)
    {
        $this->authorizeManagement($todo->event);
        $todo->delete();

        return redirect()->back()->with('success', 'Task removed successfully!');
    }

    public function toggleTodo(EventTodo $todo)
    {
        $event = $todo->event;
        $currentUser = auth()->guard('management')->user();
        $isAdministrator = $currentUser && $currentUser->role === 'admin';
        $isTeamLeader = $currentUser && $event->crews()
            ->where('management_user_id', $currentUser->id)
            ->where('is_leader', true)
            ->exists();
        $isAssigned = $currentUser && $todo->management_user_id === $currentUser->id;

        if (!($isAdministrator || $isTeamLeader || $isAssigned)) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya crew yang di-assign atau Team Leader/Administrator yang dapat mengubah status tugas.'
            ], 403);
        }

        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        if ($todo->is_completed) {
            $leader = $event->crews()->wherePivot('is_leader', true)->first();
            if ($leader) {
                $leader->notify(new \App\Notifications\TodoCompletedNotification($todo, $event));
            }
        }

        return response()->json([
            'success' => true,
            'is_completed' => $todo->is_completed,
            'message' => $todo->is_completed ? 'Tugas berhasil diselesaikan!' : 'Tugas dikembalikan ke pending.'
        ]);
    }

    public function startEvent($id)
    {
        $event = Event::findOrFail($id);
        $this->authorizeManagement($event);
        $event->status = 'In Progress';
        $event->save();

        return redirect()->back()->with('success', 'Event started! Status is now In Progress.');
    }

    public function endEvent($id)
    {
        $event = Event::with('crews')->findOrFail($id);
        $this->authorizeManagement($event);

        DB::transaction(function () use ($event) {
            $event->status = 'Completed';
            $event->save();

            // Increment total_events_handled for each crew member
            foreach ($event->crews as $crew) {
                $crew->increment('total_events_handled');
            }
        });

        return redirect()->back()->with('success', 'Event finished! All crews handled points updated.');
    }

    public function addRundown(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorizeManagement($event);
        $request->validate([
            'rundowns' => 'required|array|min:1',
            'rundowns.*.day' => 'required|integer|min:1',
            'rundowns.*.time_start' => 'required',
            'rundowns.*.activity' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($eventId, $request) {
            foreach ($request->rundowns as $rundownData) {
                EventRundown::create([
                    'event_id' => $eventId,
                    'day' => $rundownData['day'],
                    'time_start' => $rundownData['time_start'],
                    'time_end' => $rundownData['time_end'] ?? null,
                    'activity' => $rundownData['activity'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Rundown items added!');
    }

    public function updateRundown(Request $request, EventRundown $rundown)
    {
        $this->authorizeManagement($rundown->event);
        $request->validate([
            'day' => 'required|integer|min:1',
            'time_start' => 'required',
            'activity' => 'required|string|max:255',
        ]);

        $rundown->update($request->all());

        return redirect()->back()->with('success', 'Rundown item updated!');
    }

    public function deleteRundown(EventRundown $rundown)
    {
        $this->authorizeManagement($rundown->event);
        $rundown->delete();

        return redirect()->back()->with('success', 'Rundown item removed!');
    }

    public function updateNotes(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $currentUser = auth()->guard('management')->user();
        if (!$currentUser) {
            abort(401);
        }

        $isAdministrator = $currentUser->role === 'admin';
        $hasEditPermission = $currentUser->hasPermission('event-edit');
        $isCrewInvolved = $event->crews()->where('management_user_id', $currentUser->id)->exists();

        if (!$isAdministrator && !$hasEditPermission && !$isCrewInvolved) {
            abort(403, 'Unauthorized action');
        }

        $eventNote = EventNote::firstOrNew(['event_id' => $eventId]);
        $eventNote->content = $request->content;
        $eventNote->save();

        return redirect()->back()->with('success', 'Notes updated!');
    }
}
