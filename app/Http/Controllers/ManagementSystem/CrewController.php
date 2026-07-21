<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementUser;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $status = $request->get('status');
            $gender = $request->get('gender');
            
            $query = ManagementUser::where('role', 'crew')
                ->withCount(['events' => function ($query) {
                    $query->whereIn('status', ['Upcoming', 'In Progress']);
                }]);
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone_number', 'LIKE', "%{$search}%")
                      ->orWhere('address', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($status)) {
                $query->where('status', $status);
            }

            if (!empty($gender)) {
                $query->where('gender', $gender);
            }
            
            $crews = $query->latest()->paginate(10);
            
            // Format dates and prepare URLs
            $crews->getCollection()->transform(function($crew) {
                $crew->formatted_joined = $crew->joined_at ? $crew->joined_at->format('d M Y') : '-';
                $crew->formatted_birth = $crew->birth_date ? $crew->birth_date->format('Y-m-d') : '';
                $crew->show_url = route('management.crew.show', $crew->id);
                $crew->update_url = route('management.crew.update', $crew->id);
                $crew->avatar_url = $crew->avatar ? asset('storage/' . $crew->avatar) : null;
                return $crew;
            });
            
            return response()->json($crews);
        }

        $crews = ManagementUser::where('role', 'crew')
            ->withCount(['events' => function ($query) {
                $query->whereIn('status', ['Upcoming', 'In Progress']);
            }])
            ->latest()
            ->paginate(10);
        return view('management_system.crew.index', compact('crews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:management_users,email',
            'password' => 'required|min:8',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        ManagementUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'crew',
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'avatar' => $avatarPath,
            'status' => 'Available',
            'joined_at' => now(),
            'total_events_handled' => 0,
        ]);

        return redirect()->back()->with('success', 'Crew member added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $crew = ManagementUser::where('role', 'crew')->findOrFail($id);

        $eventHistory = $crew->events()
            ->where('status', 'Completed')
            ->orderBy('date', 'desc')
            ->get();

        return view('management_system.crew.show', compact('crew', 'eventHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $crew = ManagementUser::where('role', 'crew')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:management_users,email,' . $crew->id,
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'status' => 'required|in:Available,Busy,Off',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($crew->avatar) {
                Storage::disk('public')->delete($crew->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $crew->update($data);

        return redirect()->back()->with('success', 'Crew member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $crew = ManagementUser::where('role', 'crew')->findOrFail($id);
        
        if ($crew->avatar) {
            Storage::disk('public')->delete($crew->avatar);
        }

        $crew->delete();

        return redirect()->route('management.crew.index')->with('success', 'Crew member deleted successfully!');
    }
}
