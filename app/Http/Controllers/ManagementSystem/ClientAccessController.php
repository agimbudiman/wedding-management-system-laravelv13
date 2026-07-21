<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventGuestBook;
use App\Models\EventTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientAccessController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $query = Event::with('category');
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('client_name', 'LIKE', "%{$search}%")
                      ->orWhereHas('category', function($catQuery) use ($search) {
                          $catQuery->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $events = $query->latest()->paginate(10);
            
            // Format dates and prepare URLs
            $events->getCollection()->transform(function($event) {
                $event->category_name = $event->category->name ?? '-';
                $event->formatted_date = $event->date->format('d M Y');
                $event->show_url = route('management.client-access.show', $event->id);
                return $event;
            });
            
            return response()->json($events);
        }

        $events = Event::with('category')->latest()->get();
        return view('management_system.event.client_access.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        if (!$event->client_qr_token) {
            $event->update(['client_qr_token' => (string) Str::uuid()]);
        }
        if (!$event->guest_qr_token) {
            $event->update(['guest_qr_token' => (string) Str::uuid()]);
        }
        if (!$event->slug) {
            $event->update(['slug' => Str::slug($event->name)]);
        }

        return view('management_system.event.client_access.show', compact('event'));
    }

    public function regenerate(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $type = $request->input('type');

        if ($type === 'client') {
            $event->update(['client_qr_token' => (string) Str::uuid()]);
        } elseif ($type === 'guest') {
            $event->update(['guest_qr_token' => (string) Str::uuid()]);
        }

        return back()->with('success', 'QR Code regenerated successfully.');
    }

    public function toggle(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $type = $request->input('type');

        if ($type === 'client') {
            $event->update(['is_client_qr_active' => !$event->is_client_qr_active]);
        } elseif ($type === 'guest') {
            $event->update(['is_guest_qr_active' => !$event->is_guest_qr_active]);
        }

        return back()->with('success', 'Access status updated successfully.');
    }

    public function personalize(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $data = $request->all();
        unset($data['_token']);

        $currentP = $event->personalization ?? [];
        $photos = $data['photos'] ?? [];

        foreach ($photos as $type => $value) {
            $oldPath = $currentP['photos'][$type] ?? null;

            if (str_starts_with($value, 'data:image')) {
                // Remove old local file if exists
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }

                // Save new file
                $image_service_str = substr($value, strpos($value, ",") + 1);
                $image_data = base64_decode($image_service_str);
                $filename = 'invitations/' . $event->id . '/' . $type . '_' . time() . '.jpg';
                Storage::disk('public')->put($filename, $image_data);
                $data['photos'][$type] = 'storage/' . $filename;
            } elseif ($value === '__REMOVE__') {
                // Remove old local file
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }
                $data['photos'][$type] = '';
            }
        }

        // Handle Gallery
        $gallery = $request->input('gallery', []);
        $newGallery = $currentP['gallery'] ?? [];

        foreach ($gallery as $index => $value) {
            $oldPath = $newGallery[$index] ?? null;

            if (str_starts_with($value, 'data:image')) {
                // Remove old local file
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }

                // Save new file
                $image_service_str = substr($value, strpos($value, ",") + 1);
                $image_data = base64_decode($image_service_str);
                $filename = 'invitations/' . $event->id . '/gallery_' . $index . '_' . time() . '.jpg';
                Storage::disk('public')->put($filename, $image_data);
                $newGallery[$index] = 'storage/' . $filename;
            } elseif ($value === '__REMOVE__') {
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }
                $newGallery[$index] = '';
            } else {
                $newGallery[$index] = $value;
            }
        }
        $data['gallery'] = $newGallery;

        $event->update([
            'personalization' => $data
        ]);

        return back()->with('success', 'Invitation personalized successfully.');
    }

    public function clientPersonalize(Request $request, $token)
    {
        $event = Event::where('client_qr_token', $token)->firstOrFail();
        $data = $request->all();
        unset($data['_token']);

        $currentP = $event->personalization ?? [];
        $photos = $data['photos'] ?? [];

        foreach ($photos as $type => $value) {
            $oldPath = $currentP['photos'][$type] ?? null;

            if (str_starts_with($value, 'data:image')) {
                // Remove old local file if exists
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }

                // Save new file
                $image_service_str = substr($value, strpos($value, ",") + 1);
                $image_data = base64_decode($image_service_str);
                $filename = 'invitations/' . $event->id . '/' . $type . '_' . time() . '.jpg';
                Storage::disk('public')->put($filename, $image_data);
                $data['photos'][$type] = 'storage/' . $filename;
            } elseif ($value === '__REMOVE__') {
                // Remove old local file
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }
                $data['photos'][$type] = '';
            }
        }

        // Handle Gallery
        $gallery = $request->input('gallery', []);
        $newGallery = $currentP['gallery'] ?? [];

        foreach ($gallery as $index => $value) {
            $oldPath = $newGallery[$index] ?? null;

            if (str_starts_with($value, 'data:image')) {
                // Remove old local file
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }

                // Save new file
                $image_service_str = substr($value, strpos($value, ",") + 1);
                $image_data = base64_decode($image_service_str);
                $filename = 'invitations/' . $event->id . '/gallery_' . $index . '_' . time() . '.jpg';
                Storage::disk('public')->put($filename, $image_data);
                $newGallery[$index] = 'storage/' . $filename;
            } elseif ($value === '__REMOVE__') {
                if ($oldPath && !str_starts_with($oldPath, 'http')) {
                    $cleanOldPath = str_replace('storage/', '', $oldPath);
                    if (Storage::disk('public')->exists($cleanOldPath)) {
                        Storage::disk('public')->delete($cleanOldPath);
                    }
                }
                $newGallery[$index] = '';
            } else {
                $newGallery[$index] = $value;
            }
        }
        $data['gallery'] = $newGallery;

        $event->update([
            'personalization' => $data
        ]);

        return back()->with('success', 'Invitation personalized successfully.');
    }

    public function clientQrRedirect($token)
    {
        $event = Event::where('client_qr_token', $token)->firstOrFail();
        
        if (!$event->is_client_qr_active) {
            return view('client_access.not_active');
        }

        return view('client_access.qr_redirect', [
            'type' => 'Client', 
            'event' => $event,
            'token' => $token
        ]);
    }

    public function guestQrRedirect($token)
    {
        $event = Event::where('guest_qr_token', $token)->firstOrFail();
        
        if (!$event->is_guest_qr_active) {
            return view('client_access.not_active');
        }

        // Redirect directly to guest book form
        return view('client_access.guest_book_form', [
            'event' => $event,
            'token' => $token,
            'is_guest_portal' => true
        ]);
    }

    public function showInvitation(Request $request, $token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->firstOrFail();
        
        return view('client_access.invitation', [
            'event' => $event,
            'groom' => $event->groom_name ?? $event->name,
            'bride' => $event->bride_name ?? '',
            'guestName' => $request->query('to'),
        ]);
    }

    public function showRundown($token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->with(['rundowns' => function($q) {
                        $q->orderBy('day')->orderBy('time_start');
                    }])
                    ->firstOrFail();
        
        return view('client_access.rundown', [
            'event' => $event,
            'token' => $token
        ]);
    }

    public function showDocumentation($token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->firstOrFail();
        
        return view('client_access.documentation', [
            'event' => $event,
            'token' => $token
        ]);
    }

    public function showGuestBook(Request $request, $token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->firstOrFail();
        
        $isClient = ($event->client_qr_token === $token);
        
        $search = $request->input('search');
        
        $guests = $event->guestBooks()
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        return view('client_access.guest_book', [
            'event' => $event,
            'token' => $token,
            'guests' => $guests,
            'is_client' => $isClient
        ]);
    }

    public function showGuestBookForm($token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->firstOrFail();
        
        return view('client_access.guest_book_form', [
            'event' => $event,
            'token' => $token
        ]);
    }

    public function storeGuestBook(Request $request, $token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $event->guestBooks()->create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        // Redirect back to guest QR (which now shows the form)
        return redirect()->route('qr.guest.redirect', $token)->with('success', 'Thank you for signing our guest book!');
    }

    public function showTestimonial($token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->with('testimonial')
                    ->firstOrFail();
        
        return view('client_access.testimonial', [
            'event' => $event,
            'token' => $token,
            'testimonial' => $event->testimonial
        ]);
    }

    public function storeTestimonial(Request $request, $token)
    {
        $event = Event::where('client_qr_token', $token)
                    ->orWhere('guest_qr_token', $token)
                    ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'testimony' => 'required|string',
        ]);

        $event->testimonial()->updateOrCreate(
            ['event_id' => $event->id],
            [
                'rating' => $request->rating,
                'testimony' => $request->testimony,
            ]
        );

        return back()->with('success', 'Thank you for your feedback! Your testimony has been saved.');
    }

    public function previewQR($id)
    {
        $event = Event::findOrFail($id);
        return view('management_system.event.client_access.preview', compact('event'));
    }

    public function showInvitationBySlug(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        
        return view('client_access.invitation', [
            'event' => $event,
            'groom' => $event->groom_name ?? $event->name,
            'bride' => $event->bride_name ?? '',
            'guestName' => $request->query('to'),
        ]);
    }
}
