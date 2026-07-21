<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->guard('management')->user()->notifications()->paginate(15);
        return view('management_system.notification.index', compact('notifications'));
    }

    public function getRecent()
    {
        $user = auth()->guard('management')->user();
        if (!$user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $notifications = $user->notifications()->take(5)->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->guard('management')->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        auth()->guard('management')->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        $notification = auth()->guard('management')->user()->notifications()->findOrFail($id);
        $notification->delete();
        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function clearAll()
    {
        auth()->guard('management')->user()->notifications()->delete();
        return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
