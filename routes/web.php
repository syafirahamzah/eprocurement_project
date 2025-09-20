<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderMonitorController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use Barryvdh\DomPDF\Facade\Pdf;





// Halaman awal//
Route::get('/', function () {
    return view('home');
});


// Route Autentikasi (dari Breeze)//
require __DIR__.'/auth.php';


Route::middleware(['auth', 'redirect.role'])->get('/home', function () {
    // tidak perlu isi apa-apa, akan diarahkan oleh middleware
});




// Login 
Route::get('/login/admin', [LoginController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/login/admin', [LoginController::class, 'loginAdmin']);
Route::get('/login/customer', [LoginController::class, 'showCustomerLogin'])->name('login.customer');
Route::post('/login/customer', [LoginController::class, 'loginCustomer']);
Route::get('/login/vendor', [LoginController::class, 'showVendorLogin'])->name('login.vendor');
Route::post('/login/vendor', [LoginController::class, 'loginVendor']);


// Register
Route::get('/register/customer', [RegisterController::class, 'showCustomerRegister'])->name('register.customer');
Route::post('/register/customer', [RegisterController::class, 'registerCustomer']);
Route::get('/register/vendor', [RegisterController::class, 'showVendorRegister'])->name('register.vendor');
Route::post('/register/vendor', [RegisterController::class, 'registerVendor']);



// Route untuk Lupa Password
Route::middleware('guest')->get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::middleware('guest')->post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');




//  dashboard customer
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CustomerController::class, 'showProfile'])->name('profile');
    Route::post('/upload-ktp', [CustomerController::class, 'uploadKtp'])->name('profile.uploadKtp');
    Route::patch('/profile', [CustomerController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [CustomerController::class, 'updatePassword'])->name('profile.updatePassword');
    // ini untuk pilih vendor dan produk katalog
    Route::get('/pemilihan-vendor', [CustomerController::class, 'showVendors'])->name('vendor.select');
    Route::post('/select-vendor', [CustomerController::class, 'selectVendor'])->name('vendor.selectVendor');
    Route::get('/eCatalog', [CustomerController::class, 'showECatalog'])->name('eCatalog');
    // produk detail dan order creation
    Route::get('/product/{id}', [CustomerController::class, 'show'])->name('product.show');
    Route::post('/order/{id}/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('/product/{id}/detail', [CustomerController::class, 'showProductDetail'])->name('product_detail');
      
//negosiasi yang benar
Route::get('/negotiation/{order}', [NegotiationController::class, 'showStatus'])->name('negotiation.status');
Route::get('/order/{order}/place', [OrderController::class, 'placeOrder'])->name('placeOrder');
Route::get('/product/{product_id}/negotiation/form', [NegotiationController::class, 'showNegotiationsForm'])->name('negotiation.form');
Route::post('/negotiation/respond/{negotiation}', [NegotiationController::class, 'respond'])->name('negotiation.respond');
Route::post('/negotiation/{negotiation}/send', [NegotiationController::class, 'sendMessage'])->name('negotiation.send');
Route::post('/negotiation/{id}/agree', [NegotiationController::class, 'agree'])->name('negotiation.agree');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

  Route::post('/product/{product_id}/negotiation/create', [NegotiationController::class, 'createNegotiation'])->name('negotiation.create');
    Route::get('/negotiation/{id}', [NegotiationController::class, 'viewNegotiation'])->name('negotiation.view');
    
    Route::get('/negotiation/message/{id}/edit', [NegotiationController::class, 'editMessage'])->name('negotiation.edit');
    Route::patch('/negotiation/message/{id}', [NegotiationController::class, 'updateMessage'])->name('negotiation.update');
    Route::delete('/negotiation/message/{id}', [NegotiationController::class, 'deleteMessage'])->name('negotiation.delete');


    // pesanan
Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
Route::post('/order/create/{negotiation}', [OrderController::class, 'store'])->name('order.create');
Route::get('/orders/akad/{orderId}/download', [CustomerController::class, 'downloadAkadPdf'])->name('orders.akad.download');
Route::get('/orders/{order}/akad', [CustomerController::class, 'printAkad'])->name('order.akad');
Route::post('/order/{order}/akad-upload', [CustomerController::class, 'uploadAkadDokumen'])->name('akad.upload');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::post('/order/direct/{product_id}', [OrderController::class, 'directOrder'])->name('order.direct');
Route::get('/order/{order}/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order/{order}/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/monitoring', [OrderController::class, 'monitoringCustomer'])->name('monitoring');
Route::put('/orders/{order}/confirm', [OrderController::class, 'confirmReceived'])->name('orders.confirm');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');





// notifikasi rute
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

//chat
Route::get('/chats', [NegotiationController::class, 'customerChatList'])->name('chat');
  
Route::get('/history', [OrderController::class, 'orderHistory'])->name('history');

});





// dashboard vendor
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
Route::get('/profile', [VendorController::class, 'edit'])->name('profile');
Route::patch('/profile', [VendorController::class, 'update'])->name('profile.update');
Route::patch('/profile/password', [VendorController::class, 'updatePassword'])->name('profile.updatePassword');

//mengelola produk
Route::get('/products', [VendorProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [VendorProductController::class, 'create'])->name('products.create');
Route::post('/products', [VendorProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [VendorProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [VendorProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [VendorController::class, 'destroy'])->name('products.destroy');


//permintaan cari customer
Route::get('/requests', [VendorController::class, 'requests'])->name('requests');


//negosiasi
Route::get('/negotiation', [NegotiationController::class, 'index'])->name('negotiation.index');
Route::get('/negotiation/{product_id}/chat', [NegotiationController::class, 'vendorForm'])->name('negotiation.form');
Route::post('/negotiation/{negotiation}/respond', [NegotiationController::class, 'vendorRespond'])->name('negotiation.respond');
Route::post('/negotiation/{negotiation_id}/offer', [NegotiationController::class, 'vendorOfferPrice'])->name('negotiation.offer');
Route::post('/negotiation/{id}/approve', [NegotiationController::class, 'approve'])->name('negotiation.approve');
Route::post('/negotiation/{id}/reject', [NegotiationController::class, 'reject'])->name('negotiation.reject');


//Route::post('/negotiation/{id}/reply', [NegotiationController::class, 'sendMessage'])->name('negotiation.reply');
Route::get('/negonation/{negotiation}/chat', [NegotiationController::class, 'chat'])->name('negonation.chat');


//mengelola pesanan
Route::get('/orders', [VendorController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [VendorController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/shipping', [VendorController::class, 'showShippingForm'])->name('orders.shippingForm');
Route::put('/orders/{order}/shipping', [VendorController::class, 'updateShipping'])->name('orders.updateShipping');
Route::put('//orders/{order}/send', [VendorController::class, 'markAsShipped'])->name('orders.send');
Route::get('orders/{order}/print-agreement', [VendorController::class, 'printAgreement'])->name('orders.printAgreement');
Route::post('/orders/{order}/process', [VendorController::class, 'process'])->name('orders.process');
Route::get('/orders/{order}/invoice', [VendorController::class, 'invoice'])->name('orders.akad');
Route::get('/orders/{order}/akad', [VendorController::class, 'viewAkad'])->name('orders.akad');
Route::get('/orders/{order}/invoice', [VendorController::class, 'invoiceStruk'])->name('orders.invoice');
Route::get('/orders/{order}/invoice-agreement', [VendorController::class, 'showAgreementDocument'])->name('invoice.agreement');
Route::get('/orders/{order}/invoiceFull', [VendorController::class, 'showFullInvoice'])->name('invoice.full');
Route::post('orders/{id}/upload-akad', [VendorController::class, 'uploadAkad'])->name('orders.uploadAkad');
Route::get('/orders/{id}/akad-dokumen', [VendorController::class, 'viewAkadDokumen'])->name('orders.viewAkadDokumen');
Route::post('orders/{id}/validate-dokumen', [VendorController::class, 'validateDokumen'])->name('orders.validateDokumen');
Route::post('/orders/{order}/mark-paid', [VendorController::class, 'markPaid'])->name('orders.markPaid');
Route::patch('/orders/{order}/approve-akad', [VendorController::class, 'approveAkad'])->name('orders.approveAkad');
Route::get('/orders/{order}/akad', [VendorController::class, 'viewAkad'])->name('orders.akad');
Route::post('/orders/{order}/accept', [VendorController::class, 'accept'])->name('orders.accept');





Route::get('/monitoring', [OrderController::class, 'monitoring'])->name('monitoring');

//notifikasi
Route::get('/notifications', [NotificationController::class, 'vendorIndex'])->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'vendorMarkAsRead'])->name('notifications.read');
Route::post('/send-notification/{customerId}', [NotificationController::class, 'vendorSendNotification'])->name('notifications.send');

Route::get('/requests/history', [VendorController::class, 'requestHistory'])->name('requests.history');

});






// dashboard admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [OrderMonitorController::class, 'index'])->name('monitoring'); 
    Route::get('/customers', [AdminController::class, 'listCustomers'])->name('customers.index');
    Route::get('/customers/{id}/edit', [AdminController::class, 'editCustomer'])->name('customers.edit');
    Route::put('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('customers.update');
    Route::delete('/customers/{id}', [AdminController::class, 'destroyCustomer'])->name('customers.destroy');
    Route::get('/vendors', [AdminController::class, 'listVendors'])->name('vendors.index');
    Route::get('/vendors/{id}/edit', [AdminController::class, 'editVendor'])->name('vendors.edit');
    Route::put('/vendors/{id}', [AdminController::class, 'updateVendor'])->name('vendors.update');
    Route::delete('/vendors/{id}', [AdminController::class, 'destroyVendor'])->name('vendors.destroy');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::get('/orders', [AdminOrderController::class, 'list'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
});




// Route profil user biasa untuk (edit/update/destroy) //
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Testing / Dummy //
Route::get('/test', function () {
    return view('test');
});

Route::get('/login', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('customer')) {
            return redirect()->route('customer.dashboard');
        }
        return redirect()->route('login.customer');  // Default redirect untuk customer
    }
    return redirect()->route('login.customer');  // Jika belum login
})->name('login');

