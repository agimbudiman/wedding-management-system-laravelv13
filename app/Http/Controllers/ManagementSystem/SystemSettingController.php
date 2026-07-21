<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ManagementUser;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('search');
            $roleId = $request->get('role_id');

            $query = ManagementUser::with('role_relation');

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($roleId)) {
                $query->where('role_id', $roleId);
            }

            $users = $query->paginate(10);
            
            // Format for JSON
            $users->getCollection()->transform(function($user) {
                $user->avatar_url = $user->avatar ? asset('storage/' . $user->avatar) : null;
                $user->role_display = $user->role_relation ? $user->role_relation->display_name : 'No Role';
                $user->initial = substr($user->name, 0, 1);
                return $user;
            });

            return response()->json($users);
        }

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');
        $users = ManagementUser::with('role_relation')->paginate(10);
        
        return view('management_system.system_setting.index', compact('roles', 'permissions', 'users'));
    }

    public function eventCategoryIndex()
    {
        $categories = \App\Models\EventCategory::latest()->get();
        return view('management_system.system_setting.event_category', compact('categories'));
    }

    public function quotesIndex()
    {
        $quotesJson = \App\Models\WebsiteSetting::get('quotes_list');
        $quotes = $quotesJson ? json_decode($quotesJson, true) : $this->getDefaultQuotes();
        
        $slideshowActive = \App\Models\WebsiteSetting::get('quotes_slideshow_active', '1');
        $slideshowDuration = \App\Models\WebsiteSetting::get('quotes_slideshow_duration', '5');
        
        return view('management_system.system_setting.quotes', compact('quotes', 'slideshowActive', 'slideshowDuration'));
    }

    public function updateQuotesConfig(Request $request)
    {
        $request->validate([
            'slideshow_active' => 'required|in:0,1',
            'slideshow_duration' => 'required_if:slideshow_active,1|nullable|integer|min:1|max:60',
        ]);

        \App\Models\WebsiteSetting::updateOrCreate(
            ['key' => 'quotes_slideshow_active'],
            ['value' => $request->slideshow_active, 'type' => 'boolean', 'group' => 'system']
        );

        if ($request->has('slideshow_duration') && $request->slideshow_duration !== null) {
            \App\Models\WebsiteSetting::updateOrCreate(
                ['key' => 'quotes_slideshow_duration'],
                ['value' => $request->slideshow_duration, 'type' => 'integer', 'group' => 'system']
            );
        }

        return redirect()->back()->with('success', 'Pengaturan slideshow quotes berhasil diperbarui');
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
        ]);

        $role = Role::create($request->only('name', 'display_name', 'description'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->back()->with('success', 'Role berhasil ditambahkan');
    }

    public function roleUpdate(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'required',
        ]);

        $role->update($request->only('display_name', 'description'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->back()->with('success', 'Role berhasil diperbarui');
    }

    public function roleDestroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Role Admin tidak dapat dihapus');
        }

        $role->delete();
        return redirect()->back()->with('success', 'Role berhasil dihapus');
    }

    public function userUpdateRole(Request $request, ManagementUser $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'role_id' => $request->role_id,
            'role' => Role::find($request->role_id)->name // Sync with old column for compatibility
        ]);

        return redirect()->back()->with('success', 'Role user berhasil diperbarui');
    }

    public function quoteStore(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'author' => 'required',
            'active' => 'nullable|in:0,1',
        ]);

        $quotesJson = \App\Models\WebsiteSetting::get('quotes_list');
        $quotes = $quotesJson ? json_decode($quotesJson, true) : $this->getDefaultQuotes();

        $quotes[] = [
            'text' => $request->text,
            'author' => $request->author,
            'active' => $request->has('active') ? (bool)$request->active : true
        ];

        \App\Models\WebsiteSetting::updateOrCreate(
            ['key' => 'quotes_list'],
            [
                'value' => json_encode($quotes),
                'type' => 'json',
                'group' => 'system'
            ]
        );

        return redirect()->back()->with('success', 'Quote berhasil ditambahkan');
    }

    public function quoteUpdate(Request $request, $index)
    {
        $request->validate([
            'text' => 'required',
            'author' => 'required',
            'active' => 'nullable|in:0,1',
        ]);

        $quotesJson = \App\Models\WebsiteSetting::get('quotes_list');
        $quotes = $quotesJson ? json_decode($quotesJson, true) : $this->getDefaultQuotes();

        if (isset($quotes[$index])) {
            $quotes[$index] = [
                'text' => $request->text,
                'author' => $request->author,
                'active' => $request->has('active') ? (bool)$request->active : true
            ];

            \App\Models\WebsiteSetting::updateOrCreate(
                ['key' => 'quotes_list'],
                ['value' => json_encode($quotes)]
            );

            return redirect()->back()->with('success', 'Quote berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Quote tidak ditemukan');
    }

    public function quoteToggleActive($index)
    {
        $quotesJson = \App\Models\WebsiteSetting::get('quotes_list');
        $quotes = $quotesJson ? json_decode($quotesJson, true) : $this->getDefaultQuotes();

        if (isset($quotes[$index])) {
            $currentActive = isset($quotes[$index]['active']) ? (bool)$quotes[$index]['active'] : true;
            $quotes[$index]['active'] = !$currentActive;

            \App\Models\WebsiteSetting::updateOrCreate(
                ['key' => 'quotes_list'],
                ['value' => json_encode($quotes)]
            );

            return redirect()->back()->with('success', 'Status quote berhasil diubah');
        }

        return redirect()->back()->with('error', 'Quote tidak ditemukan');
    }

    public function quoteDestroy($index)
    {
        $quotesJson = \App\Models\WebsiteSetting::get('quotes_list');
        $quotes = $quotesJson ? json_decode($quotesJson, true) : $this->getDefaultQuotes();

        if (isset($quotes[$index])) {
            unset($quotes[$index]);
            $quotes = array_values($quotes);

            \App\Models\WebsiteSetting::updateOrCreate(
                ['key' => 'quotes_list'],
                ['value' => json_encode($quotes)]
            );

            return redirect()->back()->with('success', 'Quote berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Quote tidak ditemukan');
    }
}
