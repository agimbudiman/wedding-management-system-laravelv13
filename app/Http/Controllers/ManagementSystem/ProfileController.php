<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManagementUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('management')->user();
        return view('management_system.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = ManagementUser::findOrFail(Auth::guard('management')->id());

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:management_users,email,' . $user->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|string|in:Available,Busy,Off,Unavailable',
            'avatar_base64' => 'nullable|string',
            'delete_avatar' => 'nullable|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'birth_date', 'gender', 'phone_number', 'address', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle Avatar Deletion
        if ($request->delete_avatar) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $data['avatar'] = null;
            }
        }

        // Handle Base64 Cropped Avatar
        if ($request->filled('avatar_base64')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $imageData = $request->avatar_base64;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'avatars/' . uniqid() . '.png';

            Storage::disk('public')->put($imageName, base64_decode($imageData));
            $data['avatar'] = $imageName;
        }

        try {
            $user->fill($data);
            
            if ($user->save()) {
                Log::info('Profile updated for user ID: ' . $user->id, $data);
                return redirect()->route('management.profile')->with('success', 'Profile updated successfully!');
            }
            
            Log::warning('Profile update failed for user ID: ' . $user->id);
            return redirect()->back()->with('error', 'Failed to update profile.');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating profile.');
        }
    }

    public function updateStatus(Request $request)
    {
        $user = ManagementUser::findOrFail(Auth::guard('management')->id());

        $request->validate([
            'status' => 'required|string|in:Available,Busy,Off,Unavailable',
        ]);

        try {
            $user->status = $request->status;
            if ($user->save()) {
                Log::info('Profile status updated for user ID: ' . $user->id . ' to ' . $user->status);
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully to ' . $user->status,
                    'status' => $user->status,
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Profile status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating status.',
            ], 500);
        }
    }
}
