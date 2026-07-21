<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $search = $request->get('search');
            $categoryId = $request->get('category_id');
            
            $query = \App\Models\Package::with('category');
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('original_price', 'LIKE', "%{$search}%")
                      ->orWhere('final_price', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($categoryId)) {
                $query->where('category_id', $categoryId);
            }
            
            $packages = $query->latest()->paginate(10);
            
            // Format pricing and prepare URLs
            $packages->getCollection()->transform(function($package) {
                $package->formatted_original = 'Rp ' . number_format($package->original_price, 0, ',', '.');
                $package->formatted_final = 'Rp ' . number_format($package->final_price, 0, ',', '.');
                $package->show_url = route('management.package.show', $package->id);
                $package->update_url = route('management.package.update', $package->id);
                $package->destroy_url = route('management.package.destroy', $package->id);
                return $package;
            });
            
            return response()->json($packages);
        }

        $packages = \App\Models\Package::with('category')->latest()->paginate(10);
        $categories = \App\Models\EventCategory::all();
        
        return view('management_system.package.index', compact('packages', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'original_price' => 'required|numeric|min:0',
            'final_price' => 'required|numeric|min:0|lt:original_price',
        ], [
            'final_price.lt' => 'Harga final harus lebih kecil dari harga original.',
        ]);

        $package = \App\Models\Package::create($request->all());

        return redirect()->route('management.package.show', $package->id)->with('success', 'Package created successfully!');
    }

    public function show($id)
    {
        $package = \App\Models\Package::with(['category', 'items'])->findOrFail($id);
        $categories = \App\Models\EventCategory::all();
        
        return view('management_system.package.detail', compact('package', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $package = \App\Models\Package::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'original_price' => 'required|numeric|min:0',
            'final_price' => 'required|numeric|min:0|lt:original_price',
        ], [
            'final_price.lt' => 'Harga final harus lebih kecil dari harga original.',
        ]);

        $package->update($request->all());

        return redirect()->back()->with('success', 'Package updated successfully!');
    }

    public function destroy($id)
    {
        $package = \App\Models\Package::findOrFail($id);
        $package->delete();

        return redirect()->route('management.package.index')->with('success', 'Package deleted successfully!');
    }

    public function addItem(Request $request, $id)
    {
        $package = \App\Models\Package::findOrFail($id);
        
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
        ]);

        foreach ($request->items as $itemData) {
            \App\Models\PackageItem::create([
                'package_id' => $package->id,
                'name' => $itemData['name'],
            ]);
        }

        return redirect()->back()->with('success', 'Items added successfully!');
    }

    public function removeItem($packageId, $itemId)
    {
        $item = \App\Models\PackageItem::where('package_id', $packageId)->findOrFail($itemId);
        $item->delete();

        return redirect()->back()->with('success', 'Item removed successfully!');
    }
}
