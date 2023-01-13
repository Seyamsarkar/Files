<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('ProductController')->group(function () {
    Route::get('products', 'products')->name('products');
    // home pages
    Route::get('featured/products', 'products')->name('featured.products');
    Route::get('tag/products', 'products')->name('tag.products');
    Route::get('best/selling/products', 'products')->name('best.selling.products');
    Route::get('best/author/products', 'products')->name('best.author.products');
    // category pages
    Route::get('weekly/best/products', 'products')->name('weekly.best.products');

    Route::get('category/products/{id}/{slug}', 'categoryProducts')->name('category.products');
    Route::get('subcategory/products/{id}/{slug}', 'categoryProducts')->name('subcategory.products');
    Route::get('featured/products/{id}/{slug}', 'categoryProducts')->name('category.featured.products');
    Route::get('weekly/best/product/{id}/{slug}', 'categoryProducts')->name('category.weekly.best.products');
    Route::get('best/selling/{id}/{slug}', 'categoryProducts')->name('category.best.selling.products');

    Route::get('products/filter', 'productsFilter')->name('products.filter');

    Route::get('product/detail/{id}/{slug}', 'detail')->name('product.detail');
    Route::get('product/reviews/{slug}/{id}', 'reviews')->name('product.reviews');
    Route::get('product/comments/{slug}/{id}', 'comments')->name('product.comments');
    Route::get('product/support', 'productSupport')->name('product.support');
});

// Cart
Route::controller('CartController')->group(function () {
    Route::post('add-to-cart/{id}', 'addToCart')->name('add.to.cart');
    Route::post('remove/cart/{id}', 'removeCart')->name('remove.cart');
    Route::get('my-cart', 'myCart')->name('my.cart');
});

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::post('subscribe', 'subscribe')->name('subscribe.post');

    Route::get('author/profile/{username}', 'authorProfile')->name('author.profile');
    Route::get('author/products/{username}', 'authorProducts')->name('author.products');

    Route::get('all/category', 'categories')->name('all.category');
    Route::get('all/subcategory/{id}/{slug}', 'subcategories')->name('all.subcategory');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
