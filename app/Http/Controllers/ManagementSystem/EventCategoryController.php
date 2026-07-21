<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventCategory;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::latest()->get();
        return view('management_system.event.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('event-categories', 'public');
        }

        EventCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Event category added successfully!');
    }

    public function update(Request $request, EventCategory $eventCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($eventCategory->image) {
                Storage::disk('public')->delete($eventCategory->image);
            }
            $data['image'] = $request->file('image')->store('event-categories', 'public');
        }

        $eventCategory->update($data);

        return redirect()->back()->with('success', 'Event category updated successfully!');
    }

    public function destroy(EventCategory $eventCategory)
    {
        if ($eventCategory->image) {
            Storage::disk('public')->delete($eventCategory->image);
        }

        $eventCategory->delete();

        return redirect()->back()->with('success', 'Event category deleted successfully!');
    }
}
