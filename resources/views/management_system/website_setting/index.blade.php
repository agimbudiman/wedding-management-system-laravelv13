@extends('layouts.management')

@section('title', 'Website Settings')

@section('content')
<style>
    /* Premium Modern Navigation Tabs */
    #settingsTabs {
        border-radius: 14px;
        background: #ffffff;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    #settingsTabs .list-group-item {
        border: none !important;
        border-radius: 12px !important;
        padding: 14px 20px !important;
        font-weight: 500;
        font-size: 14px;
        color: #4a5568 !important;
        background: transparent !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
    }

    #settingsTabs .list-group-item i {
        font-size: 1.15rem;
        color: #a0aec0;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Hover State with soft shift and color */
    #settingsTabs .list-group-item:hover {
        background-color: rgba(124, 163, 97, 0.08) !important;
        color: #1a202c !important;
        transform: translateX(6px);
    }

    #settingsTabs .list-group-item:hover i {
        color: var(--brilliant-green, #7ca361);
        transform: scale(1.1);
    }

    /* Premium Active State */
    #settingsTabs .list-group-item.active {
        background: linear-gradient(135deg, var(--brilliant-green, #7ca361), var(--brilliant-green-dark, #6b8e52)) !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 8px 20px rgba(124, 163, 97, 0.25) !important;
        transform: translateX(6px) scale(1.01);
    }

    #settingsTabs .list-group-item.active i {
        color: #ffffff !important;
        transform: scale(1.1);
    }

    /* Subtle sleek left border for active tab */
    #settingsTabs .list-group-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 25%;
        height: 50%;
        width: 4px;
        background-color: #ffffff;
        border-radius: 0 4px 4px 0;
    }

    /* Left Sidebar Card upgrade */
    .col-xl-3.col-lg-4 .card {
        border: none !important;
        border-radius: 16px !important;
        background: #ffffff;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.03) !important;
        overflow: hidden;
    }

    .col-xl-3.col-lg-4 .card-body {
        padding: 8px !important;
    }

    /* Right side tab content card upgrade */
    .col-xl-9.col-lg-8 .card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.03) !important;
    }

    .col-xl-9.col-lg-8 .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
        background-color: #ffffff !important;
        padding: 20px 24px !important;
    }

    .col-xl-9.col-lg-8 .card-body {
        padding: 24px !important;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Website Settings</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="settingsTabs" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="hero-tab" data-bs-toggle="list" href="#hero-settings" role="tab">
                            <i class="bi bi-star me-2"></i> Hero Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="about-tab" data-bs-toggle="list" href="#about-settings" role="tab">
                            <i class="bi bi-info-circle me-2"></i> About Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="packages-tab" data-bs-toggle="list" href="#packages-settings" role="tab">
                            <i class="bi bi-box me-2"></i> Packages Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="gallery-tab" data-bs-toggle="list" href="#gallery-settings" role="tab">
                            <i class="bi bi-images me-2"></i> Gallery Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="testimonials-tab" data-bs-toggle="list" href="#testimonials-settings" role="tab">
                            <i class="bi bi-chat-quote me-2"></i> Testimonials Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="vendors-tab" data-bs-toggle="list" href="#vendors-settings" role="tab">
                            <i class="bi bi-shop me-2"></i> Vendors Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="faq-tab" data-bs-toggle="list" href="#faq-settings" role="tab">
                            <i class="bi bi-question-circle me-2"></i> FAQ Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="contact-tab" data-bs-toggle="list" href="#contact-settings" role="tab">
                            <i class="bi bi-telephone me-2"></i> Contact Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="footer-tab" data-bs-toggle="list" href="#footer-settings" role="tab">
                            <i class="bi bi-layout-text-window-reverse me-2"></i> Footer Section
                        </a>
                        <a class="list-group-item list-group-item-action" id="reservation-tab" data-bs-toggle="list" href="#reservation-settings" role="tab">
                            <i class="bi bi-calendar-check me-2"></i> Reservation Section
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="tab-content" id="settingsTabContent">
                <!-- Hero Settings -->
                <div class="tab-pane fade show active" id="hero-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hero Section Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.hero.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Hero Subtitle</label>
                                        <input type="text" name="hero_subtitle" class="form-control" value="{{ \App\Models\WebsiteSetting::get('hero_subtitle', 'Keanggunan dalam setiap detail') }}">
                                        <small class="text-muted">Teks kecil di atas judul utama.</small>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Hero Title (Utama)</label>
                                        <input type="text" name="hero_title" class="form-control" value="{{ \App\Models\WebsiteSetting::get('hero_title', 'Wujudkan Pernikahan Impian Anda') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Hero Description</label>
                                        <textarea name="hero_description" class="form-control" rows="3">{{ \App\Models\WebsiteSetting::get('hero_description', 'Kami menangani detailnya, Anda merayakan cintanya. Perencanaan pernikahan profesional untuk momen tak terlupakan.') }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label">Background Image</label>
                                        <div class="mb-3">
                                            @php
                                                $bg = \App\Models\WebsiteSetting::get('hero_background');
                                            @endphp
                                            @if($bg)
                                                <img src="{{ asset('storage/' . $bg) }}" alt="Hero BG" id="current_hero_bg" class="img-thumbnail mb-2" style="max-height: 200px;">
                                            @else
                                                <img src="{{ asset('assets/hero_wedding.png') }}" alt="Default Hero BG" id="current_hero_bg" class="img-thumbnail mb-2" style="max-height: 200px;">
                                            @endif
                                        </div>
                                        <input type="file" id="hero_bg_input" class="form-control" accept="image/*">
                                        <input type="hidden" name="hero_background_cropped" id="hero_background_cropped">
                                        <small class="text-muted">Gambar akan dipotong otomatis menjadi landscape (16:9). Rekomendasi: 1920x1080px.</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan Hero</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- About Settings -->
                <div class="tab-pane fade" id="about-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">About Section Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.about.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">About Subtitle</label>
                                        <input type="text" name="about_subtitle" class="form-control" value="{{ \App\Models\WebsiteSetting::get('about_subtitle', 'Dedikasi Kami') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">About Title</label>
                                        <input type="text" name="about_title" class="form-control" value="{{ \App\Models\WebsiteSetting::get('about_title', 'Kami Menciptakan Kenangan yang Abadi') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">About Description</label>
                                        <textarea name="about_description" class="form-control" rows="5">{{ \App\Models\WebsiteSetting::get('about_description', 'Dengan pengalaman lebih dari 10 tahun di industri pernikahan, Brilliant Event & Wedding Organizer telah membantu ratusan pasangan mewujudkan pernikahan impian mereka.') }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label">About Image</label>
                                        <div class="mb-3">
                                            @php
                                                $aboutImg = \App\Models\WebsiteSetting::get('about_image');
                                            @endphp
                                            @if($aboutImg)
                                                <img src="{{ asset('storage/' . $aboutImg) }}" alt="About Image" id="current_about_img" class="img-thumbnail mb-2" style="max-height: 200px;">
                                            @else
                                                <img src="{{ asset('assets/about_wedding.png') }}" alt="Default About Image" id="current_about_img" class="img-thumbnail mb-2" style="max-height: 200px;">
                                            @endif
                                        </div>
                                        <input type="file" id="about_img_input" class="form-control" accept="image/*">
                                        <input type="hidden" name="about_image_cropped" id="about_image_cropped">
                                        <small class="text-muted">Gambar akan dipotong otomatis menjadi rasio 4:3. Rekomendasi: 800x600px.</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan About</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Packages Settings -->
                <div class="tab-pane fade" id="packages-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page Packages Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.packages.update') }}" method="POST">
                                @csrf
                                <div class="alert alert-info small">
                                    <i class="bi bi-info-circle me-2"></i> Pilih 3 paket yang ingin Anda tampilkan secara khusus di halaman utama. Pastikan paket tersebut sudah memiliki detail harga di menu Paket.
                                </div>
                                <div class="row">
                                    @for($i = 1; $i <= 3; $i++)
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Paket Unggulan {{ $i }}</label>
                                        <select name="landing_package_{{$i}}" class="form-select">
                                            <option value="">-- Pilih Paket --</option>
                                            @foreach($packages as $package)
                                                <option value="{{ $package->id }}" {{ \App\Models\WebsiteSetting::get('landing_package_'.$i) == $package->id ? 'selected' : '' }}>
                                                    {{ $package->name }} (Final: IDR {{ number_format($package->final_price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endfor
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan Paket</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Gallery Settings -->
                <div class="tab-pane fade" id="gallery-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page Gallery Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.gallery.update') }}" method="POST">
                                @csrf
                                <div class="alert alert-info small">
                                    <i class="bi bi-info-circle me-2"></i> Unggah hingga 6 foto terbaik untuk ditampilkan di galeri. Gambar akan dipotong menjadi bentuk persegi (1:1).
                                </div>
                                <div class="row">
                                    @for($i = 1; $i <= 6; $i++)
                                    <div class="col-md-4 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">Foto {{ $i }}</label>
                                            @php
                                                $galImg = \App\Models\WebsiteSetting::get("gallery_image_{$i}");
                                            @endphp
                                            @if($galImg)
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-0" onclick="confirmDeleteGallery({{ $i }})" title="Hapus Foto">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            @endif
                                        </div>
                                        <div class="mb-2 text-center position-relative">
                                            @if($galImg)
                                                <img src="{{ asset('storage/' . $galImg) }}" alt="Gallery {{ $i }}" id="current_gallery_img_{{ $i }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                            @else
                                                <div id="current_gallery_img_{{ $i }}" class="bg-light border d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                                                    <span class="text-muted small">Kosong</span>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" class="form-control form-control-sm gallery-input" data-index="{{ $i }}" accept="image/*">
                                        <input type="hidden" name="gallery_image_{{ $i }}_cropped" id="gallery_image_{{ $i }}_cropped">
                                    </div>
                                    @endfor
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" id="btn-save-gallery" class="btn btn-primary px-4">Simpan Galeri</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Settings -->
                <div class="tab-pane fade" id="testimonials-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page Testimonials Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.testimonials.update') }}" method="POST">
                                @csrf
                                <div class="alert alert-info small">
                                    <i class="bi bi-info-circle me-2"></i> Pilih 3 testimoni terbaik dari klien Anda untuk ditampilkan di landing page.
                                </div>
                                <div class="row">
                                    @for($i = 1; $i <= 3; $i++)
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Testimoni Pilihan {{ $i }}</label>
                                        <select name="landing_testimonial_{{$i}}" class="form-select" required>
                                            <option value="">-- Pilih Testimoni --</option>
                                            @foreach($testimonials as $testimonial)
                                                <option value="{{ $testimonial->id }}" {{ \App\Models\WebsiteSetting::get('landing_testimonial_'.$i) == $testimonial->id ? 'selected' : '' }}>
                                                    {{ $testimonial->event->groom_name }} & {{ $testimonial->event->bride_name }} - {{ $testimonial->rating }}/5 Stars
                                                </option>
                                            @endforeach
                                        </select>
                                        @if(\App\Models\WebsiteSetting::get('landing_testimonial_'.$i))
                                            @php
                                                $selectedId = \App\Models\WebsiteSetting::get('landing_testimonial_'.$i);
                                                $selected = $testimonials->where('id', $selectedId)->first();
                                            @endphp
                                            @if($selected)
                                                <div class="mt-2 p-2 bg-light rounded small italic">
                                                    "{{ Str::limit($selected->testimony, 150) }}"
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    @endfor
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Testimoni</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Vendors Settings -->
                <div class="tab-pane fade" id="vendors-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page Vendors Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.vendors.update') }}" method="POST">
                                @csrf
                                <div class="alert alert-info small">
                                    <i class="bi bi-info-circle me-2"></i> Pilih hingga 6 vendor terbaik untuk ditampilkan di bagian "Mitra Kami" pada landing page.
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label d-block mb-3">Pilih Vendor (Maksimal 6)</label>
                                        @php
                                            $selectedVendors = json_decode(\App\Models\WebsiteSetting::get('landing_vendors', '[]'), true);
                                            if(!is_array($selectedVendors)) $selectedVendors = [];
                                            $groupedVendors = $vendors->groupBy('category');
                                        @endphp
                                        
                                        <div class="vendor-selection-grid" style="max-height: 400px; overflow-y: auto; padding: 10px; border: 1px solid #e3e6f0; border-radius: 5px; background: #f8f9fc;">
                                            @foreach($groupedVendors as $category => $categoryVendors)
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold border-bottom pb-1 mb-3">{{ $category }}</h6>
                                                    <div class="row">
                                                        @foreach($categoryVendors as $vendor)
                                                            <div class="col-md-4 mb-2">
                                                                <div class="form-check custom-checkbox">
                                                                    <input class="form-check-input vendor-checkbox" type="checkbox" name="landing_vendors[]" 
                                                                           value="{{ $vendor->id }}" id="vendor_{{ $vendor->id }}"
                                                                           {{ in_array($vendor->id, $selectedVendors) ? 'checked' : '' }}>
                                                                    <label class="form-check-label small" for="vendor_{{ $vendor->id }}">
                                                                        {{ $vendor->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Pilih minimal 1 dan maksimal 6 vendor.</small>
                                            <span class="badge bg-primary" id="vendor-count">Terpilih: 0/6</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Vendor</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- FAQ Settings -->
                <div class="tab-pane fade" id="faq-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page FAQ Configuration</h6>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addFaq()">
                                <i class="bi bi-plus-circle me-1"></i> Tambah FAQ
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered small">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">Urutan</th>
                                            <th>Pertanyaan</th>
                                            <th>Jawaban</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($faqs as $faq)
                                            <tr>
                                                <td class="text-center">{{ $faq->order }}</td>
                                                <td class="fw-bold">{{ $faq->question }}</td>
                                                <td>{{ Str::limit($faq->answer, 100) }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editFaq({{ $faq }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('management.website-setting.faq.delete', $faq->id) }}" method="POST" class="d-inline delete-faq-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-faq">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada FAQ.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Settings -->
                <div class="tab-pane fade" id="contact-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page Contact Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.contact.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">WhatsApp Number</label>
                                        <input type="text" name="contact_whatsapp" class="form-control" 
                                               value="{{ \App\Models\WebsiteSetting::get('contact_whatsapp', '6281234567890') }}" required>
                                        <small class="text-muted">Gunakan kode negara (contoh: 62812...). Jangan gunakan spasi atau tanda +.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" name="contact_email" class="form-control" 
                                               value="{{ \App\Models\WebsiteSetting::get('contact_email', 'hello@brilliantevent.com') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number (Display)</label>
                                        <input type="text" name="contact_phone" class="form-control" 
                                               value="{{ \App\Models\WebsiteSetting::get('contact_phone', '+62 812 3456 7890') }}" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="contact_address" class="form-control" rows="3" required>{{ \App\Models\WebsiteSetting::get('contact_address', 'Brilliant Tower Lt. 12, Jakarta Selatan') }}</textarea>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Kontak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="tab-pane fade" id="footer-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Landing Page Footer Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.footer.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label font-weight-bold">Nama Brand / Judul Footer</label>
                                        <input type="text" name="footer_brand" class="form-control" 
                                               value="{{ \App\Models\WebsiteSetting::get('footer_brand', 'Brilliant Event') }}" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label font-weight-bold">Deskripsi / Kata-kata Footer</label>
                                        <textarea name="footer_description" class="form-control" rows="3" required>{{ \App\Models\WebsiteSetting::get('footer_description', 'Menjadikan setiap pernikahan sebuah mahakarya cinta dan koordinasi.') }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label font-weight-bold">Copyright Teks</label>
                                        <input type="text" name="footer_copyright" class="form-control" 
                                               value="{{ \App\Models\WebsiteSetting::get('footer_copyright', 'Brilliant Event & Wedding Organizer. Seluruh hak cipta dilindungi.') }}" required>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Footer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reservation Settings -->
                <div class="tab-pane fade" id="reservation-settings" role="tabpanel">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Reservation Section Configuration</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('management.website-setting.reservation.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label font-weight-bold">Nominal Down Payment (DP) *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="reservation_dp_nominal" class="form-control" 
                                                   value="{{ \App\Models\WebsiteSetting::get('reservation_dp_nominal', 5000000) }}" required min="0">
                                        </div>
                                        <small class="text-muted">Tentukan nominal Down Payment (DP) dalam Rupiah untuk reservasi online klien.</small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label font-weight-bold">Maksimal Acara Per Hari *</label>
                                        <div class="input-group">
                                            <input type="number" name="max_events_per_day" class="form-control" 
                                                   value="{{ \App\Models\WebsiteSetting::get('max_events_per_day', 3) }}" required min="1">
                                            <span class="input-group-text">Acara</span>
                                        </div>
                                        <small class="text-muted">Tentukan batas maksimal acara yang bisa dipesan klien dalam satu hari yang sama.</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Pengaturan Reservasi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Potong Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="img-container" style="height: 400px; max-height: 400px; width: 100%; background-color: #f7f7f7; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img id="image-to-crop" src="" style="max-width: 100%; max-height: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="crop-button">Potong & Siapkan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let cropper;
    let currentTarget = ''; // 'hero' or 'about'
    
    const heroInput = document.getElementById('hero_bg_input');
    const aboutInput = document.getElementById('about_img_input');
    const galleryInputs = document.querySelectorAll('.gallery-input');
    
    const imageToCrop = document.getElementById('image-to-crop');
    const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
    const cropButton = document.getElementById('crop-button');
    
    let galleryIndex = null;

    function initCropper(input, target) {
        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                currentTarget = target;
                if (target === 'gallery') {
                    galleryIndex = e.target.getAttribute('data-index');
                }
                
                const reader = new FileReader();
                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropperModal.show();
                };
                reader.readAsDataURL(files[0]);
            }
        });
    }

    if (heroInput) initCropper(heroInput, 'hero');
    if (aboutInput) initCropper(aboutInput, 'about');
    galleryInputs.forEach(input => initCropper(input, 'gallery'));

    document.getElementById('cropperModal').addEventListener('shown.bs.modal', function () {
        let ratio = 16 / 9;
        if (currentTarget === 'about') ratio = 4 / 3;
        if (currentTarget === 'gallery') ratio = 1 / 1;
        
        cropper = new Cropper(imageToCrop, {
            aspectRatio: ratio,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            restore: false,
        });
    });

    document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (heroInput) heroInput.value = '';
        if (aboutInput) aboutInput.value = '';
        galleryInputs.forEach(input => input.value = '');
    });

    cropButton.addEventListener('click', function () {
        if (!cropper) return;

        let width = 1920, height = 1080;
        if (currentTarget === 'about') { width = 800; height = 600; }
        if (currentTarget === 'gallery') { width = 600; height = 600; }

        const canvas = cropper.getCroppedCanvas({
            width: width,
            height: height,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function (blob) {
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function () {
                const base64data = reader.result;
                if (currentTarget === 'hero') {
                    document.getElementById('hero_background_cropped').value = base64data;
                    document.getElementById('current_hero_bg').src = base64data;
                } else if (currentTarget === 'about') {
                    document.getElementById('about_image_cropped').value = base64data;
                    document.getElementById('current_about_img').src = base64data;
                } else if (currentTarget === 'gallery') {
                    document.getElementById(`gallery_image_${galleryIndex}_cropped`).value = base64data;
                    const preview = document.getElementById(`current_gallery_img_${galleryIndex}`);
                    if (preview.tagName === 'IMG') {
                        preview.src = base64data;
                    } else {
                        // Replace placeholder div with img
                        const img = document.createElement('img');
                        img.id = `current_gallery_img_${galleryIndex}`;
                        img.src = base64data;
                        img.className = 'img-thumbnail';
                        img.style = 'width: 150px; height: 150px; object-fit: cover;';
                        preview.parentNode.replaceChild(img, preview);
                    }
                }
                cropperModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Gambar siap!',
                    text: 'Silakan klik Simpan Galeri untuk mengunggah.',
                    timer: 2000,
                    showConfirmButton: false
                });
            };
        }, 'image/jpeg', 0.9);
    });

    function confirmDeleteGallery(index) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Foto akan dihapus permanen dari galeri. Minimal harus ada 4 foto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-gallery-form');
                let url = "{{ route('management.website-setting.gallery.remove', ['index' => ':index']) }}";
                form.action = url.replace(':index', index);
                form.submit();
            }
        });
    }

    // Add loading state to save button
    const galleryForm = document.querySelector('#gallery-settings form');
    const saveBtn = document.getElementById('btn-save-gallery');
    if (galleryForm && saveBtn) {
        galleryForm.addEventListener('submit', function() {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...';
        });
    }
    // Vendor Selection Logic
    const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
    const vendorCountSpan = document.getElementById('vendor-count');
    
    function updateVendorCount() {
        const checkedCount = document.querySelectorAll('.vendor-checkbox:checked').length;
        vendorCountSpan.textContent = `Terpilih: ${checkedCount}/6`;
        
        if (checkedCount >= 6) {
            vendorCheckboxes.forEach(cb => {
                if (!cb.checked) cb.disabled = true;
            });
        } else {
            vendorCheckboxes.forEach(cb => cb.disabled = false);
        }
    }

    vendorCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateVendorCount);
    });

    // Initialize count on load
    updateVendorCount();
    // FAQ Logic
    function addFaq() {
        document.getElementById('faq_id').value = '';
        document.getElementById('faq_question').value = '';
        document.getElementById('faq_answer').value = '';
        document.getElementById('faq_order').value = '0';
        document.getElementById('faqModalLabel').textContent = 'Tambah FAQ';
        new bootstrap.Modal(document.getElementById('faqModal')).show();
    }

    function editFaq(faq) {
        document.getElementById('faq_id').value = faq.id;
        document.getElementById('faq_question').value = faq.question;
        document.getElementById('faq_answer').value = faq.answer;
        document.getElementById('faq_order').value = faq.order;
        document.getElementById('faqModalLabel').textContent = 'Edit FAQ';
        new bootstrap.Modal(document.getElementById('faqModal')).show();
    }

    // Delete FAQ Confirmation
    document.querySelectorAll('.btn-delete-faq').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus FAQ ini?')) {
                this.closest('form').submit();
            }
        });
    });
</script>

<!-- FAQ Modal -->
<div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('management.website-setting.faq.store') }}" method="POST">
                @csrf
                <input type="hidden" name="faq_id" id="faq_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="faqModalLabel">FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" name="question" id="faq_question" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jawaban</label>
                        <textarea name="answer" id="faq_answer" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" id="faq_order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="delete-gallery-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection
