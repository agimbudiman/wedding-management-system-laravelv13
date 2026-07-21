<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Models\Package;
use App\Models\EventTestimonial;
use App\Models\Vendor;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::all()->groupBy('group');
        $packages = Package::with('items')->get();
        $testimonials = EventTestimonial::with('event')->latest()->get();
        $vendors = Vendor::orderBy('name')->get();
        $faqs = Faq::orderBy('order')->get();
        return view('management_system.website_setting.index', compact('settings', 'packages', 'testimonials', 'vendors', 'faqs'));
    }

    public function updateHero(Request $request)
    {
        $request->validate([
            'hero_subtitle' => 'nullable|string',
            'hero_title' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'hero_background_cropped' => 'nullable|string',
        ]);

        $this->updateSetting('hero_subtitle', $request->hero_subtitle, 'hero');
        $this->updateSetting('hero_title', $request->hero_title, 'hero');
        $this->updateSetting('hero_description', $request->hero_description, 'hero');

        if ($request->hero_background_cropped) {
            $imageData = $request->hero_background_cropped;
            
            // Remove the "data:image/jpeg;base64," part
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc

                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    return redirect()->back()->with('error', 'Gagal memproses gambar');
                }
            } else {
                return redirect()->back()->with('error', 'Format gambar tidak valid');
            }

            // Delete old background if exists
            $oldPath = WebsiteSetting::get('hero_background');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $fileName = 'hero_bg_' . time() . '.' . $type;
            $path = 'landing/' . $fileName;

            Storage::disk('public')->put($path, $imageData);
            $this->updateSetting('hero_background', $path, 'hero', 'image');
        }

        return redirect()->back()->with('success', 'Pengaturan Hero berhasil diperbarui');
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_subtitle' => 'nullable|string',
            'about_title' => 'nullable|string',
            'about_description' => 'nullable|string',
            'about_image_cropped' => 'nullable|string',
        ]);

        $this->updateSetting('about_subtitle', $request->about_subtitle, 'about');
        $this->updateSetting('about_title', $request->about_title, 'about');
        $this->updateSetting('about_description', $request->about_description, 'about');

        if ($request->about_image_cropped) {
            $imageData = $request->about_image_cropped;
            
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    return redirect()->back()->with('error', 'Gagal memproses gambar');
                }
            } else {
                return redirect()->back()->with('error', 'Format gambar tidak valid');
            }

            // Delete old image if exists
            $oldPath = WebsiteSetting::get('about_image');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $fileName = 'about_img_' . time() . '.' . $type;
            $path = 'landing/' . $fileName;

            Storage::disk('public')->put($path, $imageData);
            $this->updateSetting('about_image', $path, 'about', 'image');
        }

        return redirect()->back()->with('success', 'Pengaturan About berhasil diperbarui');
    }

    public function updatePackages(Request $request)
    {
        $request->validate([
            'landing_package_1' => 'nullable|exists:packages,id',
            'landing_package_2' => 'nullable|exists:packages,id',
            'landing_package_3' => 'nullable|exists:packages,id',
        ]);

        $this->updateSetting('landing_package_1', $request->landing_package_1, 'packages');
        $this->updateSetting('landing_package_2', $request->landing_package_2, 'packages');
        $this->updateSetting('landing_package_3', $request->landing_package_3, 'packages');

        return redirect()->back()->with('success', 'Pengaturan Paket berhasil diperbarui');
    }

    public function updateGallery(Request $request)
    {
        // Count how many images will exist after this update
        $existingCount = 0;
        for ($i = 1; $i <= 6; $i++) {
            if (WebsiteSetting::get("gallery_image_{$i}") || ($request->has("gallery_image_{$i}_cropped") && $request->get("gallery_image_{$i}_cropped"))) {
                $existingCount++;
            }
        }

        if ($existingCount < 4) {
            return redirect()->back()->with('error', 'Galeri harus memiliki minimal 4 foto.');
        }

        // We handle up to 6 gallery images
        for ($i = 1; $i <= 6; $i++) {
            $fieldName = "gallery_image_{$i}_cropped";
            if ($request->has($fieldName) && $request->$fieldName) {
                $imageData = $request->$fieldName;
                
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    $imageData = base64_decode($imageData);

                    if ($imageData === false) continue;
                } else {
                    continue;
                }

                // Delete old image
                $oldPath = WebsiteSetting::get("gallery_image_{$i}");
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                $fileName = "gallery_img_{$i}_" . time() . '.' . $type;
                $path = 'landing/' . $fileName;

                Storage::disk('public')->put($path, $imageData);
                $this->updateSetting("gallery_image_{$i}", $path, 'gallery', 'image');
            }
        }

        return redirect()->route('management.website-setting.index')->with('success', 'Galeri berhasil diperbarui');
    }

    public function removeGalleryImage($index)
    {
        // Check current count
        $count = 0;
        for ($i = 1; $i <= 6; $i++) {
            if (WebsiteSetting::get("gallery_image_{$i}")) {
                $count++;
            }
        }

        if ($count <= 4) {
            return redirect()->route('management.website-setting.index')->with('error', 'Gagal menghapus. Galeri harus memiliki minimal 4 foto.');
        }

        $path = WebsiteSetting::get("gallery_image_{$index}");
        if ($path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            WebsiteSetting::where('key', "gallery_image_{$index}")->delete();
        }

        return redirect()->route('management.website-setting.index')->with('success', 'Foto galeri berhasil dihapus');
    }

    public function updateTestimonials(Request $request)
    {
        $request->validate([
            'landing_testimonial_1' => 'required|exists:event_testimonials,id',
            'landing_testimonial_2' => 'required|exists:event_testimonials,id',
            'landing_testimonial_3' => 'required|exists:event_testimonials,id',
        ]);

        $this->updateSetting('landing_testimonial_1', $request->landing_testimonial_1, 'testimonials');
        $this->updateSetting('landing_testimonial_2', $request->landing_testimonial_2, 'testimonials');
        $this->updateSetting('landing_testimonial_3', $request->landing_testimonial_3, 'testimonials');

        return redirect()->route('management.website-setting.index')->with('success', 'Pengaturan Testimoni berhasil diperbarui');
    }

    public function updateVendors(Request $request)
    {
        $request->validate([
            'landing_vendors' => 'required|array|min:1|max:6',
            'landing_vendors.*' => 'exists:vendors,id',
        ]);

        $this->updateSetting('landing_vendors', json_encode($request->landing_vendors), 'vendors');

        return redirect()->route('management.website-setting.index')->with('success', 'Pengaturan Vendor berhasil diperbarui');
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        Faq::updateOrCreate(
            ['id' => $request->faq_id],
            [
                'question' => $request->question,
                'answer' => $request->answer,
                'order' => $request->order ?? 0,
            ]
        );

        return redirect()->route('management.website-setting.index')->with('success', 'FAQ berhasil disimpan');
    }

    public function deleteFaq($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->route('management.website-setting.index')->with('success', 'FAQ berhasil dihapus');
    }

    public function updateContact(Request $request)
    {
        $request->validate([
            'contact_whatsapp' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'contact_address' => 'required|string',
        ]);

        $this->updateSetting('contact_whatsapp', $request->contact_whatsapp, 'contact');
        $this->updateSetting('contact_email', $request->contact_email, 'contact');
        $this->updateSetting('contact_phone', $request->contact_phone, 'contact');
        $this->updateSetting('contact_address', $request->contact_address, 'contact');

        return redirect()->route('management.website-setting.index')->with('success', 'Pengaturan Kontak berhasil diperbarui');
    }

    public function updateFooter(Request $request)
    {
        $request->validate([
            'footer_brand' => 'required|string|max:255',
            'footer_description' => 'required|string',
            'footer_copyright' => 'required|string|max:255',
        ]);

        $this->updateSetting('footer_brand', $request->footer_brand, 'footer');
        $this->updateSetting('footer_description', $request->footer_description, 'footer');
        $this->updateSetting('footer_copyright', $request->footer_copyright, 'footer');

        return redirect()->route('management.website-setting.index')->with('success', 'Pengaturan Footer berhasil diperbarui');
    }

    public function updateReservation(Request $request)
    {
        $request->validate([
            'reservation_dp_nominal' => 'required|numeric|min:0',
            'max_events_per_day' => 'required|numeric|min:1',
        ]);

        $this->updateSetting('reservation_dp_nominal', $request->reservation_dp_nominal, 'reservation');
        $this->updateSetting('max_events_per_day', $request->max_events_per_day, 'reservation');

        return redirect()->route('management.website-setting.index')->with('success', 'Pengaturan Reservasi berhasil diperbarui');
    }

    private function updateSetting($key, $value, $group, $type = 'text')
    {
        WebsiteSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }
}
