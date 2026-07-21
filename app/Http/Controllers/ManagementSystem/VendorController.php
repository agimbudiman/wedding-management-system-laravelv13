<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $category = $request->get('category');
            
            $query = Vendor::query();
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('address', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($category)) {
                $query->where('category', $category);
            }
            
            $vendors = $query->latest()->paginate(10);
            
            // Format attributes and URLs
            $vendors->getCollection()->transform(function($vendor) {
                $vendor->logo_url = $vendor->logo ? asset('storage/' . $vendor->logo) : null;
                $vendor->update_url = route('management.vendor.update', $vendor->id);
                return $vendor;
            });
            
            return response()->json($vendors);
        }

        $vendors = Vendor::latest()->paginate(10);
        return view('management_system.vendor.index', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('vendors', 'public');
        }

        Vendor::create([
            'name' => $request->name,
            'category' => $request->category,
            'phone' => $request->phone,
            'address' => $request->address,
            'logo' => $logoPath,
        ]);

        return redirect()->back()->with('success', 'Vendor added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'category' => $request->category,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            $data['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        $vendor->update($data);

        return redirect()->back()->with('success', 'Vendor updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        if ($vendor->logo) {
            Storage::disk('public')->delete($vendor->logo);
        }

        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }
}
