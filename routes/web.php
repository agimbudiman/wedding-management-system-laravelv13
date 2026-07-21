<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagementSystem\AuthController;
use App\Http\Controllers\ManagementSystem\DashboardController;
use App\Http\Controllers\ManagementSystem\ProfileController;
use App\Http\Controllers\ManagementSystem\EventCategoryController;
use App\Http\Controllers\ManagementSystem\EventController;
use App\Http\Controllers\ManagementSystem\CrewController;
use App\Http\Controllers\ManagementSystem\VendorController;
use App\Http\Controllers\ManagementSystem\ClientAccessController;
use App\Http\Controllers\ManagementSystem\SystemSettingController;
use App\Http\Controllers\ManagementSystem\WebsiteSettingController;
use App\Http\Controllers\ManagementSystem\NotificationController;

use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prd', function () {
    return view('pre_requirement.index');
})->name('prd');

Route::get('/reservasi', [ReservationController::class, 'index'])->name('landing.reservasi');
Route::get('/reservasi/check-availability', [ReservationController::class, 'checkAvailability'])->name('landing.reservasi.check-availability');
Route::post('/reservasi/token', [ReservationController::class, 'getSnapToken'])->name('landing.reservasi.token');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('landing.reservasi.store');
Route::get('/reservasi/invoice/{invoice_no}', [ReservationController::class, 'invoice'])->name('landing.reservasi.invoice');
Route::get('/reservasi/invoice/{invoice_no}/download', [ReservationController::class, 'exportPdf'])->name('landing.reservasi.invoice.download');
Route::post('/midtrans/notification', [ReservationController::class, 'midtransNotification'])->name('midtrans.notification');

// Public QR Redirect Routes
Route::get('/qr/client/{token}', [ClientAccessController::class, 'clientQrRedirect'])->name('qr.client.redirect');
Route::get('/qr/guest/{token}', [ClientAccessController::class, 'guestQrRedirect'])->name('qr.guest.redirect');
Route::get('/undangan/{token}', [ClientAccessController::class, 'showInvitation'])->name('client.invitation');
Route::get('/rundown-undangan/{token}', [ClientAccessController::class, 'showRundown'])->name('client.rundown');
Route::get('/dokumentasi-undangan/{token}', [ClientAccessController::class, 'showDocumentation'])->name('client.documentation');
Route::get('/v/{slug}', [ClientAccessController::class, 'showInvitationBySlug'])->name('client.invitation.slug');
Route::get('/buku-tamu/{token}', [ClientAccessController::class, 'showGuestBook'])->name('client.guest_book');
Route::get('/isi-buku-tamu/{token}', [ClientAccessController::class, 'showGuestBookForm'])->name('client.guest_book.form');
Route::post('/buku-tamu/{token}', [ClientAccessController::class, 'storeGuestBook'])->name('client.guest_book.store');
Route::get('/testimoni/{token}', [ClientAccessController::class, 'showTestimonial'])->name('client.testimonial');
Route::post('/testimoni/{token}', [ClientAccessController::class, 'storeTestimonial'])->name('client.testimonial.store');
Route::post('/client-portal/{token}/personalize', [ClientAccessController::class, 'clientPersonalize'])->name('client.personalize');

Route::prefix('management-system')->group(function () {
    Route::get('/', function () {
        return redirect()->route('management.dashboard');
    });

    Route::get('/login', [AuthController::class, 'index'])->name('management.login');
    Route::post('/login', [AuthController::class, 'login'])->name('management.login.post');
    Route::get('/forgot-password', function () {
        return view('management_system.login.forgot_password');
    })->name('management.forgot_password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('management.logout');

    Route::middleware(['auth:management'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('management.dashboard')->middleware('permission:dashboard-view');
        Route::get('/dashboard/calendar-events', [DashboardController::class, 'getCalendarEvents'])->name('management.dashboard.calendar-events');
        Route::get('/dashboard/yearly-overview-data', [DashboardController::class, 'getYearlyOverviewData'])->name('management.dashboard.yearly-overview-data');
        
        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'index'])->name('management.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('management.profile.update');
        Route::post('/profile/status', [ProfileController::class, 'updateStatus'])->name('management.profile.status.update');

        // Event Routes
        Route::get('/event', [EventCategoryController::class, 'index'])->name('management.event')->middleware('permission:event-view');
        Route::post('/event/category', [EventCategoryController::class, 'store'])->name('management.event.category.store')->middleware('permission:system-setting');
        Route::put('/event/category/{eventCategory}', [EventCategoryController::class, 'update'])->name('management.event.category.update')->middleware('permission:system-setting');
        Route::delete('/event/category/{eventCategory}', [EventCategoryController::class, 'destroy'])->name('management.event.category.destroy')->middleware('permission:system-setting');

        // Feedback Routes
        Route::get('/event/feedback', [\App\Http\Controllers\ManagementSystem\FeedbackController::class, 'index'])->name('management.event.feedback')->middleware('permission:event-view');

        // New Event Management Routes
        Route::get('/event/{category_slug}', [EventController::class, 'index'])->name('management.event.list')->middleware('permission:event-view');
        Route::post('/event', [EventController::class, 'store'])->name('management.event.store')->middleware('permission:event-create');
        Route::get('/event/detail/{id}', [EventController::class, 'show'])->name('management.event.show')->middleware('permission:event-view');
        Route::get('/event/{id}/export-pdf', [EventController::class, 'exportPdf'])->name('management.event.export-pdf')->middleware('permission:event-view');
        Route::put('/event/{id}', [EventController::class, 'update'])->name('management.event.update')->middleware('permission:event-edit');
        Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('management.event.destroy')->middleware('permission:event-delete');

        // Event Detail Actions
        Route::post('/event/{id}/crew', [EventController::class, 'addCrew'])->name('management.event.crew.add')->middleware('permission:event-edit');
        Route::delete('/event/{id}/crew/{crewId}', [EventController::class, 'removeCrew'])->name('management.event.crew.remove')->middleware('permission:event-edit');
        Route::post('/event/{id}/vendor', [EventController::class, 'addVendor'])->name('management.event.vendor.add')->middleware('permission:event-edit');
        Route::delete('/event/{id}/vendor/{vendorId}', [EventController::class, 'removeVendor'])->name('management.event.vendor.remove')->middleware('permission:event-edit');
        Route::post('/event/{id}/todo', [EventController::class, 'addTodo'])->name('management.event.todo.add')->middleware('permission:event-edit');
        Route::put('/event/todo/{todo}', [EventController::class, 'updateTodo'])->name('management.event.todo.update')->middleware('permission:event-edit');
        Route::delete('/event/todo/{todo}', [EventController::class, 'deleteTodo'])->name('management.event.todo.destroy')->middleware('permission:event-edit');
        Route::post('/event/todo/{todo}/toggle', [EventController::class, 'toggleTodo'])->name('management.event.todo.toggle');
        Route::post('/event/{id}/start', [EventController::class, 'startEvent'])->name('management.event.start')->middleware('permission:event-edit');
        Route::post('/event/{id}/end', [EventController::class, 'endEvent'])->name('management.event.end')->middleware('permission:event-edit');
        Route::post('/event/{id}/rundown', [EventController::class, 'addRundown'])->name('management.event.rundown.add')->middleware('permission:event-edit');
        Route::put('/event/rundown/{rundown}', [EventController::class, 'updateRundown'])->name('management.event.rundown.update')->middleware('permission:event-edit');
        Route::delete('/event/rundown/{rundown}', [EventController::class, 'deleteRundown'])->name('management.event.rundown.destroy')->middleware('permission:event-edit');
        Route::post('/event/{id}/notes', [EventController::class, 'updateNotes'])->name('management.event.notes.update');

        // Crew Routes
        Route::get('/crew', [CrewController::class, 'index'])->name('management.crew.index')->middleware('permission:crew-manage');
        Route::post('/crew', [CrewController::class, 'store'])->name('management.crew.store')->middleware('permission:crew-manage');
        Route::get('/crew/{id}', [CrewController::class, 'show'])->name('management.crew.show')->middleware('permission:crew-manage');
        Route::put('/crew/{id}', [CrewController::class, 'update'])->name('management.crew.update')->middleware('permission:crew-manage');
        Route::delete('/crew/{id}', [CrewController::class, 'destroy'])->name('management.crew.destroy')->middleware('permission:crew-manage');

        // Vendor Routes
        Route::get('/vendor', [VendorController::class, 'index'])->name('management.vendor.index')->middleware('permission:vendor-manage');
        Route::post('/vendor', [VendorController::class, 'store'])->name('management.vendor.store')->middleware('permission:vendor-manage');
        Route::put('/vendor/{vendor}', [VendorController::class, 'update'])->name('management.vendor.update')->middleware('permission:vendor-manage');
        Route::delete('/vendor/{vendor}', [VendorController::class, 'destroy'])->name('management.vendor.destroy')->middleware('permission:vendor-manage');
        
        // Package Routes
        Route::get('/package', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'index'])->name('management.package.index')->middleware('permission:package-manage');
        Route::post('/package', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'store'])->name('management.package.store')->middleware('permission:package-manage');
        Route::get('/package/{id}', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'show'])->name('management.package.show')->middleware('permission:package-manage');
        Route::put('/package/{id}', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'update'])->name('management.package.update')->middleware('permission:package-manage');
        Route::delete('/package/{id}', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'destroy'])->name('management.package.destroy')->middleware('permission:package-manage');
        Route::post('/package/{id}/item', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'addItem'])->name('management.package.item.add')->middleware('permission:package-manage');
        Route::delete('/package/{id}/item/{itemId}', [\App\Http\Controllers\ManagementSystem\PackageController::class, 'removeItem'])->name('management.package.item.remove')->middleware('permission:package-manage');
        
        // Payment Routes
        Route::get('/payment', [\App\Http\Controllers\ManagementSystem\PaymentController::class, 'index'])->name('management.payment.index')->middleware('permission:payment-manage');
        Route::get('/payment/create', [\App\Http\Controllers\ManagementSystem\PaymentController::class, 'create'])->name('management.payment.create')->middleware('permission:payment-manage');
        Route::get('/payment/export-pdf/{invoice_no}', [\App\Http\Controllers\ManagementSystem\PaymentController::class, 'exportPdf'])->name('management.payment.export-pdf')->middleware('permission:payment-manage');
        Route::post('/payment', [\App\Http\Controllers\ManagementSystem\PaymentController::class, 'store'])->name('management.payment.store')->middleware('permission:payment-manage');
        Route::get('/payment/{id}', [\App\Http\Controllers\ManagementSystem\PaymentController::class, 'show'])->name('management.payment.show')->middleware('permission:payment-manage');
        
        // Financial Routes
        Route::get('/financials', [\App\Http\Controllers\ManagementSystem\FinancialController::class, 'index'])->name('management.financial.index')->middleware('permission:financial-view');

        // Client Access Routes
        Route::get('/client-access', [ClientAccessController::class, 'index'])->name('management.client-access.index')->middleware('permission:client-access-manage');
        Route::get('/client-access/{id}', [ClientAccessController::class, 'show'])->name('management.client-access.show')->middleware('permission:client-access-manage');
        Route::post('/client-access/{id}/regenerate', [ClientAccessController::class, 'regenerate'])->name('management.client-access.regenerate')->middleware('permission:client-access-manage');
        Route::post('/client-access/{id}/toggle', [ClientAccessController::class, 'toggle'])->name('management.client-access.toggle')->middleware('permission:client-access-manage');
        Route::post('/client-access/{id}/personalize', [ClientAccessController::class, 'personalize'])->name('management.client-access.personalize')->middleware('permission:client-access-manage');
        Route::get('/client-access/{id}/preview', [ClientAccessController::class, 'previewQR'])->name('management.client-access.preview')->middleware('permission:client-access-manage');


        // System Setting Routes
        Route::get('/system-setting', [SystemSettingController::class, 'index'])->name('management.system-setting.index')->middleware('permission:system-setting');
        Route::post('/system-setting/role', [SystemSettingController::class, 'roleStore'])->name('management.system-setting.role.store')->middleware('permission:system-setting');
        Route::put('/system-setting/role/{role}', [SystemSettingController::class, 'roleUpdate'])->name('management.system-setting.role.update')->middleware('permission:system-setting');
        Route::delete('/system-setting/role/{role}', [SystemSettingController::class, 'roleDestroy'])->name('management.system-setting.role.destroy')->middleware('permission:system-setting');
        Route::put('/system-setting/user/{user}/role', [SystemSettingController::class, 'userUpdateRole'])->name('management.system-setting.user.role.update')->middleware('permission:system-setting');
        Route::get('/system-setting/quotes', [SystemSettingController::class, 'quotesIndex'])->name('management.system-setting.quotes.index')->middleware('permission:system-setting');
        Route::get('/system-setting/event-category', [SystemSettingController::class, 'eventCategoryIndex'])->name('management.system-setting.event-category.index')->middleware('permission:system-setting');
        Route::post('/system-setting/quotes/config', [SystemSettingController::class, 'updateQuotesConfig'])->name('management.system-setting.quotes.config.update')->middleware('permission:system-setting');
        Route::post('/system-setting/quote', [SystemSettingController::class, 'quoteStore'])->name('management.system-setting.quote.store')->middleware('permission:system-setting');
        Route::put('/system-setting/quote/{index}', [SystemSettingController::class, 'quoteUpdate'])->name('management.system-setting.quote.update')->middleware('permission:system-setting');
        Route::put('/system-setting/quote/{index}/toggle', [SystemSettingController::class, 'quoteToggleActive'])->name('management.system-setting.quote.toggle')->middleware('permission:system-setting');
        Route::delete('/system-setting/quote/{index}', [SystemSettingController::class, 'quoteDestroy'])->name('management.system-setting.quote.destroy')->middleware('permission:system-setting');

        // Website Setting Routes
        Route::get('/website-setting', [WebsiteSettingController::class, 'index'])->name('management.website-setting.index')->middleware('permission:system-setting');
        Route::post('/website-setting/hero', [WebsiteSettingController::class, 'updateHero'])->name('management.website-setting.hero.update')->middleware('permission:system-setting');
        Route::post('/website-setting/about', [WebsiteSettingController::class, 'updateAbout'])->name('management.website-setting.about.update')->middleware('permission:system-setting');
        Route::post('/website-setting/packages', [WebsiteSettingController::class, 'updatePackages'])->name('management.website-setting.packages.update')->middleware('permission:system-setting');
        Route::post('/website-setting/gallery', [WebsiteSettingController::class, 'updateGallery'])->name('management.website-setting.gallery.update')->middleware('permission:system-setting');
        Route::delete('/website-setting/gallery/{index}', [WebsiteSettingController::class, 'removeGalleryImage'])->name('management.website-setting.gallery.remove')->middleware('permission:system-setting');
        Route::post('/website-setting/testimonials', [WebsiteSettingController::class, 'updateTestimonials'])->name('management.website-setting.testimonials.update')->middleware('permission:system-setting');
        Route::post('/website-setting/vendors', [WebsiteSettingController::class, 'updateVendors'])->name('management.website-setting.vendors.update')->middleware('permission:system-setting');
        Route::post('/website-setting/faqs', [WebsiteSettingController::class, 'storeFaq'])->name('management.website-setting.faq.store')->middleware('permission:system-setting');
        Route::delete('/website-setting/faqs/{id}', [WebsiteSettingController::class, 'deleteFaq'])->name('management.website-setting.faq.delete')->middleware('permission:system-setting');
        Route::post('/website-setting/contact', [WebsiteSettingController::class, 'updateContact'])->name('management.website-setting.contact.update')->middleware('permission:system-setting');
        Route::post('/website-setting/footer', [WebsiteSettingController::class, 'updateFooter'])->name('management.website-setting.footer.update')->middleware('permission:system-setting');
        Route::post('/website-setting/reservation', [WebsiteSettingController::class, 'updateReservation'])->name('management.website-setting.reservation.update')->middleware('permission:system-setting');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('management.notification.index');
        Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('management.notification.recent');
        Route::post('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('management.notification.mark-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('management.notification.mark-all-read');
        Route::delete('/notifications/delete/{id}', [NotificationController::class, 'destroy'])->name('management.notification.destroy');
        Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('management.notification.clear-all');
    });
});
