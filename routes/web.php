<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchUpdateController;
use App\Http\Controllers\MatchOddsController;
use App\Http\Controllers\LiveUpdateController;
use App\Http\Controllers\DrakonController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\K9CasinoController;
use App\Http\Controllers\K10CasinoController;

// Promosyon kodu route'u (en üstte) - admin middleware ile
Route::post('/api/promo/use', [HomeController::class, 'usePromoCode'])->middleware('auth:admin')->name('promo-code.use');

// Ana sayfa
Route::get('/', [HomeController::class, 'index'])->name('home');

// Maç güncelleme route'u
Route::get('/MatchUpdate', [MatchUpdateController::class, 'updateMatches'])->name('match-update');
use App\Http\Controllers\SportsApiController;

Route::post('/sports/login', [SportsApiController::class, 'validateCallback'])->name('sports.login');


// Canlı maç güncelleme route'u
Route::get('/LiveUpdates', [LiveUpdateController::class, 'updateLiveMatches'])->name('live-updates');

// Maç oranları route'u
Route::get('/MatchOdds/{eventid}', [MatchOddsController::class, 'getMatchOdds'])->name('match-odds');
Route::get('/match-odds/{id}', [MatchOddsController::class, 'showMatchOdds'])->name('match-odds-view');

// Canlı bahis oranları route'u
Route::get('/LiveOdds/{eventid}', [MatchOddsController::class, 'getLiveOdds'])->name('live-odds');

// API route for infinite scroll games
Route::get('/api/games', [HomeController::class, 'getGames'])->name('api.games');
Route::get('/api/casino-games', [HomeController::class, 'getCasinoGames'])->name('api.casino-games');
Route::get('/api/live-casino-games', [HomeController::class, 'getLiveCasinoGames'])->name('api.live-casino-games');

// Kupon işlemleri
// Route::post('/place-bet', [HomeController::class, 'placeBet'])->name('place-bet')->middleware('user'); // Removed live betting

// Casino sayfaları
Route::get('/casino', [HomeController::class, 'casino'])->name('casino');
Route::get('/slots', [HomeController::class, 'slots'])->name('slots');
Route::get('/live-casino', [HomeController::class, 'liveCasino'])->name('live-casino');
Route::get('/other-games', [HomeController::class, 'otherGames'])->name('other-games');
Route::get('/sports', [HomeController::class, 'sportsIframe'])->name('sports')->middleware('user');
// Route::get('/live-betting', [HomeController::class, 'liveBetting'])->name('live-betting'); // Removed live betting
Route::get('/bonus', [HomeController::class, 'bonus'])->name('bonus');
Route::post('/claim-bonus', [HomeController::class, 'claimBonus'])->name('claim-bonus');

// Static pages
Route::get('/help-center', [HomeController::class, 'helpCenter'])->name('help-center');
Route::get('/live-support', [HomeController::class, 'liveSupport'])->name('live-support');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/responsible-gaming', [HomeController::class, 'responsibleGaming'])->name('responsible-gaming');
Route::get('/aml-policy', [HomeController::class, 'amlPolicy'])->name('aml-policy');
Route::get('/license', [HomeController::class, 'license'])->name('license');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Forgot password (admin table)
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])->name('password.forgot.post');

// User account routes (protected by user middleware)
Route::middleware(['user'])->group(function () {
    Route::get('/hesabim', [HomeController::class, 'hesabim'])->name('hesabim');
    Route::get('/para-yatir', [PaymentController::class, 'paraYatir'])->name('para-yatir');
    Route::post('/payget', [PaymentController::class, 'payget'])->name('payget');
    Route::post('/payment-method', [PaymentController::class, 'getPaymentMethod'])->name('payment.method');
    Route::get('/para-cek', [HomeController::class, 'paraCek'])->name('para-cek');
    Route::post('/para-cek', [HomeController::class, 'paraCekPost'])->name('para-cek.post');
    Route::get('/hesap-hareketleri', [HomeController::class, 'hesapHareketleri'])->name('hesap-hareketleri');
    Route::get('/bahis-gecmisi', [HomeController::class, 'bahisGecmisi'])->name('bahis-gecmisi');
    Route::get('/casino-gecmisi', [HomeController::class, 'casinoGecmisi'])->name('casino-gecmisi');
    Route::get('/aktif-bonuslarim', [HomeController::class, 'aktifBonuslarim'])->name('aktif-bonuslarim');
    // Affiliate Panel
    Route::get('/affiliate', [HomeController::class, 'affiliatePanel'])->name('affiliate.panel');
});



// Admin routes
Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
    Route::get('/users/{id}', [AdminController::class, 'userDetails'])->name('user.details');
    Route::post('/users/{id}/balance', [AdminController::class, 'updateUserBalance'])->name('users.balance');
    Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
    Route::post('/admins/{id}/update', [AdminController::class, 'updateUser'])->name('admins.update');
    Route::post('/admins/{id}/balance', [AdminController::class, 'updateUserBalance'])->name('admins.balance');
    Route::get('/yoneticiler', [AdminController::class, 'yoneticiler'])->name('yoneticiler');
    Route::post('/yoneticiler/{id}/toggle-status', [AdminController::class, 'toggleYoneticiStatus'])->name('yoneticiler.toggle-status');
    Route::post('/yoneticiler/{id}/update', [AdminController::class, 'updateYonetici'])->name('yoneticiler.update');
    Route::delete('/yoneticiler/{id}', [AdminController::class, 'deleteYonetici'])->name('yoneticiler.delete');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/deposits', [AdminController::class, 'deposits'])->name('deposits');
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/system-stats', [AdminController::class, 'systemStats'])->name('system-stats');
    
    // Yeni eklenen route'lar
    Route::get('/games', [AdminController::class, 'games'])->name('games');
    Route::get('/providers', [AdminController::class, 'providers'])->name('providers');
    Route::post('/providers/{id}/toggle-status', [AdminController::class, 'toggleProviderStatus'])->name('providers.toggle-status');
    Route::get('/tournaments', [AdminController::class, 'tournaments'])->name('tournaments');
    Route::get('/payment-methods', [AdminController::class, 'paymentMethods'])->name('payment-methods');
    Route::get('/promos', [AdminController::class, 'promos'])->name('promos');
    Route::get('/promo-codes', [AdminController::class, 'promoCodes'])->name('promo-codes');
    Route::post('/promo-codes/store', [AdminController::class, 'storePromoCode'])->name('promo-codes.store');
    Route::post('/promo-codes/{id}/update', [AdminController::class, 'updatePromoCode'])->name('promo-codes.update');
    Route::delete('/promo-codes/{id}', [AdminController::class, 'deletePromoCode'])->name('promo-codes.delete');
    Route::post('/promo-codes/{id}/toggle-status', [AdminController::class, 'togglePromoCodeStatus'])->name('promo-codes.toggle-status');
    Route::get('/bonuses', [AdminController::class, 'bonuses'])->name('bonuses');
    Route::post('/bonuses/store', [AdminController::class, 'storeBonus'])->name('bonuses.store');
    Route::post('/bonuses/{id}/update', [AdminController::class, 'updateBonus'])->name('bonuses.update');
    Route::delete('/bonuses/{id}', [AdminController::class, 'deleteBonus'])->name('bonuses.delete');
    Route::post('/bonuses/{id}/toggle-status', [AdminController::class, 'toggleBonusStatus'])->name('bonuses.toggle-status');
    
    // Affiliates
    Route::get('/affiliates', [AdminController::class, 'affiliates'])->name('affiliates');
    Route::get('/banners', [AdminController::class, 'banners'])->name('banners');
    Route::post('/banners/store', [AdminController::class, 'storeBanner'])->name('banners.store');
    Route::get('/duyurular', [AdminController::class, 'duyurular'])->name('duyurular');
    Route::post('/duyurular/store', [AdminController::class, 'storeDuyuru'])->name('duyurular.store');
    Route::post('/duyurular/{id}/update', [AdminController::class, 'updateDuyuru'])->name('duyurular.update');
    Route::delete('/duyurular/{id}', [AdminController::class, 'deleteDuyuru'])->name('duyurular.delete');
    Route::post('/duyurular/{id}/toggle-status', [AdminController::class, 'toggleDuyuruStatus'])->name('duyurular.toggle-status');
    Route::post('/banners/{id}/update', [AdminController::class, 'updateBanner'])->name('banners.update');
    Route::post('/banners/bottom', [AdminController::class, 'updateBottomBanner'])->name('banners.bottom.update');
    // Alt banner altı görseller
    Route::post('/banners/bottom-below/store', [AdminController::class, 'storeBottomImage'])->name('banners.bottom-below.store');
    Route::post('/banners/bottom-below/{id}/update', [AdminController::class, 'updateBottomImage'])->name('banners.bottom-below.update');
    Route::delete('/banners/bottom-below/{id}', [AdminController::class, 'deleteBottomImage'])->name('banners.bottom-below.delete');
    Route::post('/banners/bottom-below/{id}/toggle-status', [AdminController::class, 'toggleBottomImage'])->name('banners.bottom-below.toggle-status');
    Route::post('/banners/bottom-below/layout', [AdminController::class, 'updateBottomBelowLayout'])->name('banners.bottom-below.layout');
    Route::delete('/banners/{id}', [AdminController::class, 'deleteBanner'])->name('banners.delete');
    Route::post('/banners/{id}/toggle-status', [AdminController::class, 'toggleBannerStatus'])->name('banners.toggle-status');
    Route::get('/social-buttons', [AdminController::class, 'socialButtons'])->name('social-buttons');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::get('/backup', [AdminController::class, 'backup'])->name('backup');
    Route::get('/games-list', [AdminController::class, 'gamesList'])->name('games-list');
    Route::post('/games/{id}/update', [AdminController::class, 'updateGame'])->name('games.update');
    Route::post('/games/{id}/toggle-status', [AdminController::class, 'toggleGameStatus'])->name('games.toggle-status');
    
    // Visual Settings Routes
    Route::get('/visual-settings', [AdminController::class, 'visualSettings'])->name('visual-settings');
    Route::post('/visual-settings/update-order', [AdminController::class, 'updateVisualOrder'])->name('visual-settings.update-order');
    Route::delete('/visual-settings/{type}/{id}', [AdminController::class, 'deleteVisualItem'])->name('visual-settings.delete');
    Route::post('/visual-settings/upload', [AdminController::class, 'uploadVisualItem'])->name('visual-settings.upload');
    Route::post('/visual-settings/{type}/{id}/update', [AdminController::class, 'updateVisualItem'])->name('visual-settings.update');
    
    // Deposit/Withdrawal actions
    Route::post('/deposits/{id}/approve', [AdminController::class, 'approveDeposit'])->name('deposits.approve');
    Route::post('/deposits/{id}/reject', [AdminController::class, 'rejectDeposit'])->name('deposits.reject');
    Route::post('/withdrawals/{id}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    
    // User management routes
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('users.update');

    // Cache temizleme rotası
    Route::post('/clear-cache', [AdminController::class, 'clearCache'])->name('clear-cache');
    
    // Footer Payments Routes
    Route::get('/footer-payments', [AdminController::class, 'footerPayments'])->name('footer-payments');
    Route::post('/footer-payments/store', [AdminController::class, 'storeFooterPayment'])->name('footer-payments.store');
    Route::post('/footer-payments/{id}/update', [AdminController::class, 'updateFooterPayment'])->name('footer-payments.update');
    Route::delete('/footer-payments/{id}', [AdminController::class, 'deleteFooterPayment'])->name('footer-payments.delete');
    Route::post('/footer-payments/{id}/toggle-status', [AdminController::class, 'toggleFooterPaymentStatus'])->name('footer-payments.toggle-status');
    
    // Provider Photos Routes
    Route::get('/provider-photos', [AdminController::class, 'providerPhotos'])->name('provider-photos');
    Route::post('/provider-photos/store', [AdminController::class, 'storeProviderPhoto'])->name('provider-photos.store');
    Route::post('/provider-photos/{id}/update', [AdminController::class, 'updateProviderPhoto'])->name('provider-photos.update');
    Route::delete('/provider-photos/{id}', [AdminController::class, 'deleteProviderPhoto'])->name('provider-photos.delete');
    Route::post('/provider-photos/{id}/toggle-status', [AdminController::class, 'toggleProviderPhotoStatus'])->name('provider-photos.toggle-status');
    
    // Payment Settings Routes
    Route::get('/payment-settings', [PaymentController::class, 'paymentSettings'])->name('payment-settings');
    Route::post('/payment-settings', [PaymentController::class, 'updatePaymentSettings'])->name('payment-settings.update');
    
    // Payment Methods Routes
    Route::get('/payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/payment-methods/{paymentMethod}/toggle', [App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle');
    
    // Registration Settings Routes (API only - integrated in settings page)
    Route::post('/registration-settings/toggle/{fieldName}', [App\Http\Controllers\Admin\RegistrationSettingsController::class, 'toggleField'])->name('registration-settings.toggle');
    Route::post('/registration-settings/update-order', [App\Http\Controllers\Admin\RegistrationSettingsController::class, 'updateOrder'])->name('registration-settings.update-order');
    
    // SMS Routes
    Route::get('/sms-send', [App\Http\Controllers\Admin\SmsController::class, 'index'])->name('sms-send');
    Route::post('/sms-send', [App\Http\Controllers\Admin\SmsController::class, 'store'])->name('sms-send.store');
    
    // Home Sections routes
    Route::get('/home-sections', [AdminController::class, 'homeSections'])->name('home-sections');
    Route::post('/home-sections/order', [AdminController::class, 'updateHomeSectionOrder'])->name('home-sections.order');
    Route::post('/home-sections/{id}/toggle', [AdminController::class, 'toggleHomeSectionStatus'])->name('home-sections.toggle');
    Route::post('/home-sections/{id}/update', [AdminController::class, 'updateHomeSection'])->name('home-sections.update');
});

// Drakon Casino API routes
Route::prefix('drakon')->name('drakon.')->group(function () {
    // API routes for getting providers and games
    Route::get('/providers', [DrakonController::class, 'getProviders'])->name('providers');
    Route::get('/games', [DrakonController::class, 'getAllGames'])->name('games');
});

// K9 Casino API routes
Route::prefix('k9')->name('k9.')->group(function () {
    // API routes for getting providers and games
    Route::get('/providers', [K9CasinoController::class, 'getVendors'])->name('providers');
    Route::get('/games/{vendorCode}', [K9CasinoController::class, 'getVendorGames'])->name('games');
});

// K10 Casino API routes
Route::prefix('k10')->name('k10.')->group(function () {
    // API routes for getting providers and games
    Route::get('/providers', [K10CasinoController::class, 'getVendors'])->name('providers');
    Route::get('/games/{vendorCode}', [K10CasinoController::class, 'getVendorGames'])->name('games');
});

// Game launch routes (kısa URL'ler)
Route::get('/GameLaunch/{gameId}', [DrakonController::class, 'directGameLaunch'])->name('game-launch')->middleware('user');
Route::get('/api/GameLaunch/{gameId}', [DrakonController::class, 'gameLaunch'])->name('game-launch-api')->middleware('user');

// K9 Casino Game launch routes (kısa URL'ler)
Route::get('/K9GameLaunch/{vendorCode}/{gameCode?}', [K9CasinoController::class, 'directGameLaunch'])->name('k9-game-launch')->middleware('user');
Route::get('/api/K9GameLaunch/{vendorCode}/{gameCode?}', [K9CasinoController::class, 'gameLaunch'])->name('k9-game-launch-api')->middleware('user');

// K10 Casino Game launch routes (kısa URL'ler)
Route::get('/K10GameLaunch/{vendorCode}/{gameCode?}', [K10CasinoController::class, 'directGameLaunch'])->name('k10-game-launch')->middleware('user');
Route::get('/api/K10GameLaunch/{vendorCode}/{gameCode?}', [K10CasinoController::class, 'gameLaunch'])->name('k10-game-launch-api')->middleware('user');

// Webhook callback route (supports both GET and POST) - direkt root'ta
Route::match(['get', 'post'], '/drakon_api', [DrakonController::class, 'webhook'])->name('drakon.webhook');

// K9 Casino Webhook callback route (supports both GET and POST)
Route::match(['get', 'post'], '/k9_api', [K9CasinoController::class, 'webhook'])->name('k9.webhook');

// K10 Casino Webhook callback route (supports both GET and POST)
Route::match(['get', 'post'], '/k10_api', [K10CasinoController::class, 'webhook'])->name('k10.webhook');

// Payment routes
Route::post('/payment-method', [PaymentController::class, 'getPaymentMethod'])->name('payment.method');
Route::get('/api/banks', [PaymentController::class, 'getBankList'])->name('api.banks');
Route::post('/webhook/oleypayment', [PaymentController::class, 'oleyPaymentWebhook'])->name('webhook.oleypayment');

// 404 sayfası
Route::fallback(function () {
    return view('404');
});
