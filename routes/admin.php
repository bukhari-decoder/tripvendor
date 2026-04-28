<?php

use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BasicControlController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CookiePolicyController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\EmailConfigController;
use App\Http\Controllers\Admin\FirebaseConfigController;
use App\Http\Controllers\Admin\GoogleApiSettingController;
use App\Http\Controllers\Admin\HomeStylesController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\ManageMenuController;
use App\Http\Controllers\Admin\ManualGatewayController;
use App\Http\Controllers\Admin\PackageCategoryController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackageStylesController;
use App\Http\Controllers\Admin\PaymentCollectionController;
use App\Http\Controllers\Admin\PaymentLogController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PayoutLogController;
use App\Http\Controllers\Admin\PayoutMethodController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\PusherConfigController;
use App\Http\Controllers\Admin\PwaController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SmsConfigController;
use App\Http\Controllers\Admin\SocialiteController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\StorageController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\TransactionLogController;
use App\Http\Controllers\Admin\TranslateAPISettingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\InAppNotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProfileSettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MaintenanceModeController;
use App\Http\Controllers\Admin\NotificationTemplateController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/trans', function () {
    \App\Models\ContentDetails::latest()->get()->map(function ($item) {
        $updatedDescription = [];
        foreach ((array)$item->description as $key => $value) {
            $updatedDescription[$key] = str_replace('touriety.binaryvines.com', 'trip-vendor.bugfinder.app', $value);

        }

        // Save only if there was a change
        if ($updatedDescription !== (array)$item->description) {
            $item->description = $updatedDescription;
            $item->save();

            echo $item->id."<br>";
        }

    });
});



Route::get('clear', function () {
    Illuminate\Support\Facades\Artisan::call('optimize:clear');
    $previousUrl = url()->previous();
    $keywords = ['push-notification', 'ajax'];
    if (array_filter($keywords, fn($keyword) => str_contains($previousUrl, $keyword))) {
        return redirect('/')->with('success', 'Cache Cleared Successfully');
    }
    return redirect()->back(fallback: '/')->with('success', 'Cache Cleared Successfully');

})->name('clear');


Route::get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('schedule-run', function () {
    return Illuminate\Support\Facades\Artisan::call('schedule:run');
})->name('schedule:run');

$basicControl = basicControl();
Route::group(['prefix' => $basicControl->admin_prefix ?? 'admin', 'as' => 'admin.'], function () {
    Route::get('/themeMode/{themeType?}', function ($themeType = 'true') {
        session()->put('themeMode', $themeType);
        return $themeType;
    })->name('themeMode');

    /*== Authentication Routes ==*/
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest:admin');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')
        ->middleware('guest:admin');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset')->middleware('guest:admin');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.reset.update');

    Route::middleware(['auth:admin','demo'])->group(function () {
        Route::get('profile', [AdminProfileSettingController::class, 'profile'])->name('profile');
        Route::put('profile', [AdminProfileSettingController::class, 'profileUpdate'])->name('profile.update');
        Route::put('password', [AdminProfileSettingController::class, 'passwordUpdate'])->name('password.update');
        Route::post('notification-permission', [AdminProfileSettingController::class, 'notificationPermission'])->name('notification.permission');

        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('save-token', [DashboardController::class, 'saveToken'])->name('save.token');

        Route::get('dashboard/monthly-deposit-withdraw', [DashboardController::class, 'monthlyDepositWithdraw'])->name('monthly.deposit.withdraw');
        Route::get('dashboard/chartUserRecords', [DashboardController::class, 'chartUserRecords'])->name('chartUserRecords');
        Route::get('dashboard/chartTicketRecords', [DashboardController::class, 'chartTicketRecords'])->name('chartTicketRecords');
        Route::get('dashboard/chartKycRecords', [DashboardController::class, 'chartKycRecords'])->name('chartKycRecords');
        Route::get('dashboard/chartTransactionRecords', [DashboardController::class, 'chartTransactionRecords'])->name('chartTransactionRecords');
        Route::get('dashboard/chartLoginHistory', [DashboardController::class, 'chartLoginHistory'])->name('chartLoginHistory');

        /*== Control Panel ==*/
        Route::get('settings/{settings?}', [BasicControlController::class, 'index'])->name('settings');
        Route::get('basic-control', [BasicControlController::class, 'basicControl'])->name('basic.control');
        Route::post('basic-control-update', [BasicControlController::class, 'basicControlUpdate'])->name('basic.control.update');
        Route::post('basic-control-activity-update', [BasicControlController::class, 'basicControlActivityUpdate'])->name('basic.control.activity.update');
        Route::get('currency-exchange-api-config', [BasicControlController::class, 'currencyExchangeApiConfig'])->name('currency.exchange.api.config');
        Route::post('currency-exchange-api-config/update', [BasicControlController::class, 'currencyExchangeApiConfigUpdate'])->name('currency.exchange.api.config.update');

        Route::match(['get', 'post'], 'openai-settings', [BasicControlController::class, 'openAiUpdate'])->name('openAi.update');
        Route::match(['get', 'post'], 'gemini-settings', [BasicControlController::class, 'geminiUpdate'])->name('gemini.update');

        /* ===== STORAGE ===== */
        Route::get('storage', [StorageController::class, 'index'])->name('storage.index');
        Route::any('storage/edit/{id}', [StorageController::class, 'edit'])->name('storage.edit');
        Route::any('storage/update/{id}', [StorageController::class, 'update'])->name('storage.update');
        Route::post('storage/set-default/{id}', [StorageController::class, 'setDefault'])->name('storage.setDefault');

        /* ===== Maintenance Mode ===== */
        Route::get('maintenance-mode', [MaintenanceModeController::class, 'index'])->name('maintenance.index');
        Route::post('maintenance-mode/update', [MaintenanceModeController::class, 'maintenanceModeUpdate'])->name('maintenance.mode.update');

        /* ===== LOGO, FAVICON UPDATE ===== */
        Route::get('logo-setting', [LogoController::class, 'logoSetting'])->name('logo.settings');
        Route::post('logo-update', [LogoController::class, 'logoUpdate'])->name('logo.update');


        /* ===== FIREBASE CONFIG ===== */
        Route::get('firebase-config', [FirebaseConfigController::class, 'firebaseConfig'])->name('firebase.config');
        Route::post('firebase-config-update', [FirebaseConfigController::class, 'firebaseConfigUpdate'])->name('firebase.config.update');
        Route::get('firebase/config/file/download', [FirebaseConfigController::class, 'firebaseConfigFileDownload'])->name('firebase.config.file.download');
        Route::post('firebase/config/file/upload', [FirebaseConfigController::class, 'firebaseConfigFileUpload'])->name('firebase.config.file.upload');
        /* ===== PUSHER CONFIG ===== */
        Route::get('pusher-config', [PusherConfigController::class, 'pusherConfig'])->name('pusher.config');
        Route::post('pusher-config-update', [PusherConfigController::class, 'pusherConfigUpdate'])->name('pusher.config.update');

        /* ===== EMAIL CONFIG ===== */
        Route::get('email-controls', [EmailConfigController::class, 'emailControls'])->name('email.control');
        Route::get('email-controls', [EmailConfigController::class, 'emailControls'])->name('email.control');
        Route::get('email-config/edit/{method}', [EmailConfigController::class, 'emailConfigEdit'])->name('email.config.edit');
        Route::post('email-config/update/{method}', [EmailConfigController::class, 'emailConfigUpdate'])->name('email.config.update');
        Route::post('email-config/set-as-default/{method}', [EmailConfigController::class, 'emailSetAsDefault'])->name('email.set.default');
        Route::post('test.email', [EmailConfigController::class, 'testEmail'])->name('test.email');


        /* Notification Templates Routes */
        Route::match(['get', 'post'], 'default-template', [NotificationTemplateController::class, 'defaultTemplate'])->name('email.template.default');
        Route::get('email-templates', [NotificationTemplateController::class, 'emailTemplates'])->name('email.templates');
        Route::get('email-template/edit/{id}', [NotificationTemplateController::class, 'editEmailTemplate'])->name('email.template.edit');
        Route::put('email-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateEmailTemplate'])->name('email.template.update');

        Route::get('sms-templates', [NotificationTemplateController::class, 'smsTemplates'])->name('sms.templates');
        Route::get('sms-template/edit/{id}', [NotificationTemplateController::class, 'editSmsTemplate'])->name('sms.template.edit');
        Route::put('sms-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateSmsTemplate'])->name('sms.template.update');

        Route::get('in-app-notification-templates', [NotificationTemplateController::class, 'inAppNotificationTemplates'])
            ->name('in.app.notification.templates');
        Route::get('in-app-notification-template/edit/{id}', [NotificationTemplateController::class, 'editInAppNotificationTemplate'])
            ->name('in.app.notification.template.edit');
        Route::put('in-app-notification-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateInAppNotificationTemplate'])
            ->name('in.app.notification.template.update');
        Route::get('push-notification-templates', [NotificationTemplateController::class, 'pushNotificationTemplates'])->name('push.notification.templates');
        Route::get('push-notification-template/edit/{id}', [NotificationTemplateController::class, 'editPushNotificationTemplate'])->name('push.notification.template.edit');
        Route::put('push-notification-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updatePushNotificationTemplate'])->name('push.notification.template.update');


        /* ===== EMAIL CONFIG ===== */
        Route::get('sms-configuration', [SmsConfigController::class, 'index'])->name('sms.controls');
        Route::get('sms-config-edit/{method}', [SmsConfigController::class, 'smsConfigEdit'])->name('sms.config.edit');
        Route::post('sms-config-update/{method}', [SmsConfigController::class, 'smsConfigUpdate'])->name('sms.config.update');
        Route::post('sms-method-update/{method}', [SmsConfigController::class, 'manualSmsMethodUpdate'])->name('manual.sms.method.update');
        Route::post('sms-config/set-as-default/{method}', [SmsConfigController::class, 'smsSetAsDefault'])->name('sms.set.default');

        /* ===== PLUGIN CONFIG ===== */
        Route::get('plugin', [PluginController::class, 'pluginConfig'])->name('plugin.config');
        Route::get('plugin/tawk', [PluginController::class, 'tawkConfiguration'])->name('tawk.configuration');
        Route::post('plugin/tawk/Configuration/update', [PluginController::class, 'tawkConfigurationUpdate'])->name('tawk.configuration.update');
        Route::get('plugin/fb-messenger-configuration', [PluginController::class, 'fbMessengerConfiguration'])->name('fb.messenger.configuration');
        Route::post('plugin/fb-messenger-configuration/update', [PluginController::class, 'fbMessengerConfigurationUpdate'])->name('fb.messenger.configuration.update');
        Route::get('plugin/google-recaptcha', [PluginController::class, 'googleRecaptchaConfiguration'])->name('google.recaptcha.configuration');
        Route::post('plugin/google-recaptcha/update', [PluginController::class, 'googleRecaptchaConfigurationUpdate'])->name('google.recaptcha.Configuration.update');
        Route::get('plugin/google-analytics', [PluginController::class, 'googleAnalyticsConfiguration'])->name('google.analytics.configuration');
        Route::post('plugin/google-analytics', [PluginController::class, 'googleAnalyticsConfigurationUpdate'])->name('google.analytics.configuration.update');
        Route::get('plugin/manual-recaptcha', [PluginController::class, 'manualRecaptcha'])->name('manual.recaptcha');
        Route::post('plugin/manual-recaptcha/update', [PluginController::class, 'manualRecaptchaUpdate'])->name('manual.recaptcha.update');
        Route::post('plugin/active-recaptcha', [PluginController::class, 'activeRecaptcha'])->name('active.recaptcha');

        /* ===== ADMIN GOOGLE API SETTING ===== */
        Route::get('translate-api-setting', [TranslateAPISettingController::class, 'translateAPISetting'])->name('translate.api.setting');
        Route::get('translate-api-config/edit/{method}', [TranslateAPISettingController::class, 'translateAPISettingEdit'])->name('translate.api.config.edit');
        Route::post('translate-api-setting/update/{method}', [TranslateAPISettingController::class, 'translateAPISettingUpdate'])->name('translate.api.setting.update');
        Route::post('translate-api-setting/set-as-default/{method}', [TranslateAPISettingController::class, 'translateSetAsDefault'])->name('translate.set.default');


        /* ===== ADMIN LANGUAGE SETTINGS ===== */
        Route::get('languages', [LanguageController::class, 'index'])->name('language.index');
        Route::get('language/create', [LanguageController::class, 'create'])->name('language.create');
        Route::post('language/store', [LanguageController::class, 'store'])->name('language.store');
        Route::get('language/edit/{id}', [LanguageController::class, 'edit'])->name('language.edit');
        Route::put('language/update/{id}', [LanguageController::class, 'update'])->name('language.update');
        Route::delete('language-delete/{id}', [LanguageController::class, 'destroy'])->name('language.delete');
        Route::put('change-language-status/{id}', [LanguageController::class, 'changeStatus'])->name('change.language.status');


        Route::get('{short_name}/keywords', [LanguageController::class, 'keywords'])->name('language.keywords');
        Route::post('language-keyword/{short_name}', [LanguageController::class, 'addKeyword'])->name('add.language.keyword');
        Route::put('language-keyword/{short_name}/{key}', [LanguageController::class, 'updateKeyword'])->name('update.language.keyword');
        Route::delete('language-keyword/{short_name}/{key}', [LanguageController::class, 'deleteKeyword'])->name('delete.language.keyword');
        Route::post('language-import-json', [LanguageController::class, 'importJson'])->name('language.import.json');
        Route::put('update-key/{language}', [LanguageController::class, 'updateKey'])->name('language.update.key');
        Route::post('language/keyword/translate', [LanguageController::class, 'singleKeywordTranslate'])->name('single.keyword.translate');
        Route::post('language/all-keyword/translate/{shortName}', [LanguageController::class, 'allKeywordTranslate'])->name('all.keyword.translate');


        /* ===== ADMIN SUPPORT TICKET ===== */
        Route::get('tickets/{status?}', [SupportTicketController::class, 'tickets'])->name('ticket');
        Route::get('tickets-search/{status}', [SupportTicketController::class, 'ticketSearch'])->name('ticket.search');
        Route::get('tickets-view/{id}', [SupportTicketController::class, 'ticketView'])->name('ticket.view');
        Route::put('ticket-reply/{id}', [SupportTicketController::class, 'ticketReplySend'])->name('ticket.reply');
        Route::get('ticket-download/{ticket}', [SupportTicketController::class, 'ticketDownload'])->name('ticket.download');
        Route::post('ticket-closed/{id}', [SupportTicketController::class, 'ticketClosed'])->name('ticket.closed');
        Route::post('ticket-delete', [SupportTicketController::class, 'ticketDelete'])->name('ticket.delete');


        /* ===== InAppNotificationController SETTINGS ===== */
        Route::get('push-notification-show', [InAppNotificationController::class, 'showByAdmin'])->name('push.notification.show');
        Route::get('push-notification-readAll', [InAppNotificationController::class, 'readAllByAdmin'])->name('push.notification.readAll');
        Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

        /* PAYMENT METHOD MANAGE BY ADMIN*/
        Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment.methods');
        Route::get('edit-payment-methods/{id}', [PaymentMethodController::class, 'edit'])->name('edit.payment.methods');
        Route::put('update-payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('update.payment.methods');
        Route::post('sort-payment-methods', [PaymentMethodController::class, 'sortPaymentMethods'])->name('sort.payment.methods');
        Route::post('payment-methods/deactivate', [PaymentMethodController::class, 'deactivate'])->name('payment.methods.deactivate');
        Route::post('payment-methods/auto-update-currency/{id}', [PaymentMethodController::class, 'autoUpdateCurrency'])->name('payment.method.auto.update.currency');

        /*=* MANUAL METHOD MANAGE BY ADMIN *=*/
        Route::get('payment-methods/manual', [ManualGatewayController::class, 'index'])->name('deposit.manual.index');
        Route::get('payment-methods/manual/create', [ManualGatewayController::class, 'create'])->name('deposit.manual.create');
        Route::post('payment-methods/manual/store', [ManualGatewayController::class, 'store'])->name('deposit.manual.store');
        Route::get('payment-methods/manual/edit/{id}', [ManualGatewayController::class, 'edit'])->name('deposit.manual.edit');
        Route::put('payment-methods/manual/update/{id}', [ManualGatewayController::class, 'update'])->name('deposit.manual.update');

        /*= PAYOUT METHOD MANAGE BY ADMIN =*/
        Route::match(['get', 'post'], 'currency-exchange-api-config', [BasicControlController::class, 'currencyExchangeApiConfig'])->name('currency.exchange.api.config');

        /*= MANAGE KYC =*/
        Route::get('kyc-setting/list', [KycController::class, 'index'])->name('kyc.form.list');
        Route::get('kyc-setting/create', [KycController::class, 'create'])->name('kyc.create');
        Route::post('manage-kyc/store', [KycController::class, 'store'])->name('kyc.store');
        Route::get('manage-kyc/edit/{id}', [KycController::class, 'edit'])->name('kyc.edit');
        Route::post('manage-kyc/update/{id}', [KycController::class, 'update'])->name('kyc.update');
        Route::get('kyc/{status?}', [KycController::class, 'userKycList'])->name('kyc.list');
        Route::get('kyc-search/{status?}', [KycController::class, 'userKycSearch'])->name('kyc.search');
        Route::get('kyc/view/{id}', [KycController::class, 'view'])->name('kyc.view');
        Route::post('user/kyc/action/{id}', [KycController::class, 'action'])->name('kyc.action');

        Route::get('vendor/kyc-form', [KycController::class, 'vendorKyc'])->name('vendor.kyc.form');
        Route::post('vendor-kyc/store', [KycController::class, 'vendorKycStore'])->name('vendor.kyc.store');

        Route::get('vendor/kyc-requests', [KycController::class, 'vendorKycRequests'])->name('vendor.kyc.requests');
        Route::get('vendor/kyc-requests/search', [KycController::class, 'vendorKycRequestsSearch'])->name('vendor.kyc.requests.search');


        /*= PAYOUT METHOD MANAGE BY ADMIN =*/
        Route::get('withdraw-methods/list', [PayoutMethodController::class, 'index'])->name('payout.method.list');
        Route::get('withdraw-methods/create', [PayoutMethodController::class, 'create'])->name('payout.method.create');
        Route::post('withdraw-methods/store', [PayoutMethodController::class, 'store'])->name('payout.method.store');
        Route::get('withdraw-methods/manual/edit/{id}', [PayoutMethodController::class, 'manualEdit'])->name('payout.manual.method.edit');
        Route::get('withdraw-methods/edit/{id}', [PayoutMethodController::class, 'edit'])->name('payout.method.edit');
        Route::put('withdraw-methods/update/{id}', [PayoutMethodController::class, 'update'])->name('payout.method.update');
        Route::post('withdraw-methods/auto-update/{id}', [PayoutMethodController::class, 'autoUpdate'])->name('payout.method.auto.update');
        Route::post('withdraw-methods/active-deactivate', [PayoutMethodController::class, 'activeDeactivate'])->name('payout.active.deactivate');
        Route::get('withdraw-methods/days/setting', [PayoutMethodController::class, 'withdrawDays'])->name('payout.withdraw.days');
        Route::post('withdraw-methods/days/setting', [PayoutMethodController::class, 'withdrawDaysUpdate'])->name('withdrawal.days.update');
        Route::match(['get', 'post'], 'currency-exchange-api-config', [BasicControlController::class, 'currencyExchangeApiConfig'])->name('currency.exchange.api.config');

        /*= Frontend Manage =*/
        Route::get('frontend/pages/{theme}', [PageController::class, 'index'])->name('page.index');
        Route::get('frontend/create-page/{theme}', [PageController::class, 'create'])->name('create.page');
        Route::post('frontend/create-page/store/{theme}', [PageController::class, 'store'])->name('create.page.store');
        Route::get('frontend/edit-page/{id}/{theme}/{language?}', [PageController::class, 'edit'])->name('edit.page');
        Route::post('frontend/update-page/{id}/{theme}', [PageController::class, 'update'])->name('update.page');
        Route::post('frontend/page/update-slug', [PageController::class, 'updateSlug'])->name('update.slug');
        Route::delete('frontend/page/delete/{id}', [PageController::class, 'delete'])->name('page.delete');

        Route::get('frontend/edit-static-page/{id}/{theme}/{language?}', [PageController::class, 'editStaticPage'])->name('edit.static.page');
        Route::post('frontend/update-static-page/{id}/{theme}', [PageController::class, 'updateStaticPage'])->name('update.static.page');

        Route::get('frontend/page/seo/{id}', [PageController::class, 'pageSEO'])->name('page.seo');
        Route::post('frontend/page/seo/update/{id}', [PageController::class, 'pageSeoUpdate'])->name('page.seo.update');

        Route::get('frontend/manage-menu', [ManageMenuController::class, 'manageMenu'])->name('manage.menu');
        Route::post('frontend/header-menu-item/store', [ManageMenuController::class, 'headerMenuItemStore'])->name('header.menu.item.store');
        Route::post('frontend/footer-menu-item/store', [ManageMenuController::class, 'footerMenuItemStore'])->name('footer.menu.item.store');
        Route::post('frontend/manage-menu/add-custom-link', [ManageMenuController::class, 'addCustomLink'])->name('add.custom.link');
        Route::get('frontend/manage-menu/edit-custom-link/{pageId}', [ManageMenuController::class, 'editCustomLink'])->name('edit.custom.link');
        Route::post('frontend/manage-menu/update-custom-link/{pageId}', [ManageMenuController::class, 'updateCustomLink'])->name('update.custom.link');
        Route::delete('frontend/manage-menu/delete-custom-link/{pageId}', [ManageMenuController::class, 'deleteCustomLink'])->name('delete.custom.link');
        Route::get('frontend/manage-menu/get-custom-link-data', [ManageMenuController::class, 'getCustomLinkData'])->name('get.custom.link');

        Route::get('frontend/contents/{name}', [ContentController::class, 'index'])->name('manage.content');
        Route::post('frontend/contents/store/{name}/{language}', [ContentController::class, 'store'])->name('content.store');
        Route::get('frontend/contents/item/{name}', [ContentController::class, 'manageContentMultiple'])->name('manage.content.multiple');
        Route::post('frontend/contents/item/store/{name}/{language}', [ContentController::class, 'manageContentMultipleStore'])->name('content.multiple.store');
        Route::get('frontend/contents/item/edit/{name}/{id}', [ContentController::class, 'multipleContentItemEdit'])->name('content.item.edit');
        Route::post('frontend/contents/item/update/{name}/{id}/{language}', [ContentController::class, 'multipleContentItemUpdate'])->name('multiple.content.item.update');
        Route::delete('frontend/contents/delete/{id}', [ContentController::class, 'ContentDelete'])->name('content.item.delete');

        /*====Manage Users ====*/
        Route::get('login/as/user/{id}', [UsersController::class, 'loginAsUser'])->name('login.as.user');
        Route::post('block-profile/{id}', [UsersController::class, 'blockProfile'])->name('block.profile');
        Route::get('users', [UsersController::class, 'index'])->name('users');
        Route::get('user/edit/{id}', [UsersController::class, 'userEdit'])->name('user.edit');
        Route::get('users/search', [UsersController::class, 'search'])->name('users.search');

        Route::get('users/count-data', [UsersController::class, 'countData'])->name('users.search.countData');

        Route::post('users-delete-multiple', [UsersController::class, 'deleteMultiple'])->name('user.delete.multiple');
        Route::post('user/update/{id}', [UsersController::class, 'userUpdate'])->name('user.update');
        Route::post('user/email/{id}', [UsersController::class, 'EmailUpdate'])->name('user.email.update');
        Route::post('user/username/{id}', [UsersController::class, 'usernameUpdate'])->name('user.username.update');
        Route::post('user/update-balance/{id}', [UsersController::class, 'updateBalanceUpdate'])->name('user.update.balance');
        Route::post('user/password/{id}', [UsersController::class, 'passwordUpdate'])->name('user.password.update');
        Route::post('user/preferences/{id}', [UsersController::class, 'preferencesUpdate'])->name('user.preferences.update');
        Route::post('user/two-fa-security/{id}', [UsersController::class, 'userTwoFaUpdate'])->name('user.twoFa.update');
        Route::post('user/balance-update/{id}', [UsersController::class, 'userBalanceUpdate'])->name('user-balance-update');

        Route::get('user/send-email/{id}', [UsersController::class, 'sendEmail'])->name('send.email');
        Route::post('user/send-email/{id?}', [UsersController::class, 'sendMailUser'])->name('user.email.send');
        Route::get('mail-all-user', [UsersController::class, 'mailAllUser'])->name('mail.all.user');

        Route::get('user/kyc/{id}', [UsersController::class, 'userKyc'])->name('user.kyc.list');
        Route::get('user/kyc/search/{id}', [UsersController::class, 'KycSearch'])->name('user.kyc.search');

        Route::get('user/transaction/{id}', [UsersController::class, 'transaction'])->name('user.transaction');
        Route::get('user/transaction/search/{id}', [UsersController::class, 'userTransactionSearch'])->name('user.transaction.search');

        Route::get('user/payment/{id}', [UsersController::class, 'payment'])->name('user.payment');
        Route::get('user/payment/search/{id}', [UsersController::class, 'userPaymentSearch'])->name('user.payment.search');

        Route::get('user/withdraw/{id}', [UsersController::class, 'payout'])->name('user.payout');
        Route::get('user/withdraw/search/{id}', [UsersController::class, 'userPayoutSearch'])->name('user.payout.search');

        Route::get('user/booking/{id}', [UsersController::class, 'booking'])->name('user.booking');
        Route::get('user/booking/search/{id}', [UsersController::class, 'userBookingSearch'])->name('user.booking.search');

        Route::get('/email-send', [UsersController::class, 'emailToUsers'])->name('email-send');
        Route::post('/email-send', [UsersController::class, 'sendEmailToUsers'])->name('email-send.store');
        Route::delete('user/delete/{id}', [UsersController::class, 'userDelete'])->name('user.delete');

        Route::get('users/add', [UsersController::class, 'userAdd'])->name('users.add');
        Route::post('users/store', [UsersController::class, 'userStore'])->name('user.store');
        Route::get('users/added-successfully/{id}', [UsersController::class, 'userCreateSuccessMessage'])
            ->name('user.create.success.message');
        Route::get('user/view-profile/{id}', [UsersController::class, 'userViewProfile'])->name('user.view.profile');

        Route::post('users/set-payment-collection', [UsersController::class, 'setPaymentCollection'])->name('user.set.payment.collection');


        /* ====== Transaction Log =====*/
        Route::get('transaction', [TransactionLogController::class, 'transaction'])->name('transaction');
        Route::get('transaction/search', [TransactionLogController::class, 'transactionSearch'])->name('transaction.search');

        /* ====== Payment Log =====*/
        Route::get('payment/log', [PaymentLogController::class, 'index'])->name('payment.log');
        Route::get('payment/search', [PaymentLogController::class, 'search'])->name('payment.search');
        Route::get('payment/pending', [PaymentLogController::class, 'pending'])->name('payment.pending');
        Route::get('payment/pending/request', [PaymentLogController::class, 'paymentRequest'])->name('payment.request');
        Route::put('payment/action/{id}', [PaymentLogController::class, 'action'])->name('payment.action');

        /* ====== Payout Log =====*/
        Route::get('withdraw/log', [PayoutLogController::class, 'index'])->name('payout.log');
        Route::get('withdraw/search', [PayoutLogController::class, 'search'])->name('payout.search');
        Route::get('withdraw/pending', [PayoutLogController::class, 'pending'])->name('payout.pending');
        Route::put('withdraw/action/{id}', [PayoutLogController::class, 'action'])->name('payout.action');

        /* ===== ADMIN COOKIE POLICY SETTING ===== */
        Route::get('cookie-policy-setting', [CookiePolicyController::class, 'cookiePolicySetting'])->name('cookiePolicy.setting');
        Route::post('cookie-policy-update', [CookiePolicyController::class, 'cookiePolicyUpdate'])->name('cookiePolicy.update');

        /* ====== Blog Category Controller =====*/
        Route::resource('blog-category', BlogCategoryController::class);
        Route::resource('blogs', BlogController::class);
        Route::get('blogs/edit/{id}/{language}', [BlogController::class, 'blogEdit'])->name('blog.edit');
        Route::post('blogs/update/{id}/{language}', [BlogController::class, 'blogUpdate'])->name('blog.update');
        Route::post('blogs/slug/update', [BlogController::class, 'slugUpdate'])->name('slug.update');
        Route::get('blogs/seo-page/{id}', [BlogController::class, 'blogSeo'])->name('blog.seo');
        Route::post('blogs/seo-update/{id}', [BlogController::class, 'blogSeoUpdate'])->name('blog.seo.update');
        Route::get('blog-category/status-update/{id}', [BlogCategoryController::class, 'status'])->name('blog-category.status');
        Route::get('blog/status-update/{id}', [BlogController::class, 'status'])->name('blog.status');


        Route::controller(SubscriberController::class)->group(function () {
            Route::group(['prefix' => 'subscriber'], function () {
                Route::get('send-email-form/{id?}', 'sendEmailForm')->name('send.subscriber.email');
                Route::post('send-email/{id?}', 'sendMailUser')->name('subscriber.email.send');
                Route::get('list', 'list')->name('subscriber.list');
                Route::get('search-list', 'searchList')->name('subscriber.search.list');
                Route::post('delete-multiple', 'deleteMultiple')->name('subscriber.delete.multiple');
                Route::get('delete/{id}', 'delete')->name('subscriber.delete');
            });
        });

        Route::controller(CountryController::class)->group(function () {
            Route::group(['prefix' => 'country'], function () {
                Route::get('list', 'list')->name('all.country');
                Route::get('list-search', 'countryList')->name('country.list');
                Route::get('add', 'countryAdd')->name('country.add');
                Route::post('store', 'countryStore')->name('country.store');
                Route::get('edit/{id}', 'countryEdit')->name('country.edit');
                Route::post('update/{id}', 'countryUpdate')->name('country.update');
                Route::post('delete-multiple', 'deleteMultiple')->name('country.delete.multiple');
                Route::delete('delete/{id}', 'countryDelete')->name('country.delete');
                Route::get('status-change/{id}', 'status')->name('country.status');
                Route::any('status-multiple', 'inactiveMultiple')->name('country.inactiveMultiple');
            });
        });

        Route::controller(StateController::class)->group(function () {
            Route::get('country/{id}/all-states', 'statelist')->name('country.all.state');
            Route::get('country/{id}/state-list', 'countryStateList')->name('country.state.list');
            Route::get('country/{country}/state/{state}/edit', 'countryStateEdit')->name('country.state.edit');
            Route::post('country/{country}/state/{state}/update', 'countryStateUpdate')->name('country.state.update');
            Route::delete('country/{country}/state/{state}/delete', 'countryStateDelete')->name('country.state.delete');
            Route::post('country-state-delete-multiple', 'deleteMultipleState')->name('country.delete.multiple.state');
            Route::get('country/{country}/add-state', 'countryAddState')->name('country.add.state');
            Route::post('country/store-state', 'countryStateStore')->name('country.state.store');
            Route::get('state/{state}/status', 'status')->name('country.state.status');
            Route::any('state/inactive-multiple', 'inactiveMultiple')->name('state.inactiveMultiple');
        });


        Route::controller(CityController::class)->group(function () {
            Route::get('country/{country}/state/{state}/all-cities', 'citylist')->name('country.state.all.city');
            Route::get('country/{country}/state/{state}/city-list', 'countryStateCityList')->name('country.state.city.list');
            Route::get('country/{country}/state/{state}/city/{city}/edit', 'countryStateCityEdit')->name('country.state.city.edit');
            Route::post('country/{country}/state/{state}/city/{city}/update', 'countryStateCityUpdate')->name('country.state.city.update');
            Route::get('country/{country}/state/{state}/city/{city}/delete', 'countryStateCityDelete')->name('country.state.city.delete');
            Route::post('country-state-delete-city-multiple', 'deleteMultipleStateCity')->name('country.delete.multiple.state.city');
            Route::get('country/{country}/state/{state}/add-city', 'countryStateAddCity')->name('country.state.add.city');
            Route::post('country/state/store-city', 'countryStateStoreCity')->name('country.state.store.city');
            Route::get('city/{city}/status', 'status')->name('country.state.city.status');
            Route::any('city/inactive-multiple', 'inactiveMultiple')->name('city.inactiveMultiple');
        });

        Route::any('all-coupon', [CouponController::class, 'list'])->name('all.coupon');
        Route::get('all-coupon/search', [CouponController::class, 'search'])->name('coupon.search');
        Route::get('coupon/add', [CouponController::class, 'add'])->name('coupon.add');
        Route::post('coupon/store', [CouponController::class, 'store'])->name('coupon.store');
        Route::get('coupon/{id}/edit', [CouponController::class, 'edit'])->name('coupon.edit');
        Route::any('coupon/{id}/update', [CouponController::class, 'update'])->name('coupon.update');
        Route::any('coupon/{id}/delete', [CouponController::class, 'delete'])->name('coupon.delete');
        Route::any('coupon/delete-multiple', [CouponController::class, 'deleteMultiple'])->name('coupon.delete.multiple');

        Route::get('all-booking/{status?}', [BookingController::class, 'all_booking'])->name('all.booking');
        Route::get('all-booking-search', [BookingController::class, 'all_booking_search'])->name('all.booking.search');
        Route::post('booking/completed/{id}', [BookingController::class, 'complete'])->name('booking.action');
        Route::any('booking/approve/{id}', [BookingController::class, 'approve'])->name('booking.approve');
        Route::get('booking-edit/{id}', [BookingController::class, 'bookingEdit'])->name('booking.edit');
        Route::post('booking-edit/traveller-update', [BookingController::class, 'travellerUpdate'])->name('traveller.update');
        Route::get('booking-refund/{id}', [BookingController::class, 'bookingRefund'])->name('booking.refund');
        Route::post('booking-update', [BookingController::class, 'bookingUpdate'])->name('booking.update');
        Route::any('booking/refund-multiple', [BookingController::class, 'refundMultiple'])->name('booking.refund.multiple');
        Route::any('booking/completed-multiple', [BookingController::class, 'completedMultiple'])->name('booking.completed.multiple');

        Route::get('package-booking', [BookingController::class, 'totalBooking'])->name('package.booking.History');
        Route::get('vendor-activity', [BookingController::class, 'vendorActivity'])->name('vendorActivity');
        Route::get('/booking-overview', [BookingController::class, 'getBookingOverview'])->name('getBookingOverview');
        Route::get('destiantion/top-visited', [BookingController::class, 'topVisitedDestination'])->name('top.visited.destination');
        Route::get('top-destinations', [BookingController::class, 'topDestinations'])->name('topDestinations');


        Route::controller(ReviewController::class)->group(function () {
            Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
                Route::get('list', 'list')->name('list');
                Route::get('list/search', 'search')->name('search');
                Route::post('multiple-delete', 'multipleDelete')->name('multipleDelete');
                Route::post('multiple/status-change', 'multipleStatusChange')->name('multipleStatusChange');
                Route::get('status-change/{id}', 'statusChange')->name('statusChange');
                Route::get('package-delete/{id}', 'deleteReview')->name('delete');
            });
        });
        /* ===== ADMIN HOME STYLE SETTINGS ===== */
        Route::get('home-styles', [HomeStylesController::class, 'home'])->name('home.style');
        Route::any('select/home-style', [HomeStylesController::class, 'selectHome'])->name('select.home.style');

        /* ===== ADMIN PACKAGE STYLE SETTINGS ===== */
        Route::get('list-styles', [PackageStylesController::class, 'style'])->name('list.style');
        Route::any('select/list-style', [PackageStylesController::class, 'selectStyle'])->name('select.list.style');

        Route::controller(DestinationController::class)->group(function () {
            Route::group(['prefix' => 'destination'], function () {
                Route::get('list', 'list')->name('all.destination');
                Route::get('list-search', 'search')->name('all.destination.search');
                Route::get('create', 'add')->name('destination.add');
                Route::post('store', 'store')->name('destination.store');
                Route::get('edit/{id}', 'edit')->name('destination.edit');
                Route::post('update/{id}', 'update')->name('destination.update');
                Route::get('status/{id}', 'status')->name('destination.status');
                Route::delete('delete/{id}', 'delete')->name('destination.delete');
                Route::any('status-multiple', 'statusMultiple')->name('destination.statusMultiple');
                Route::any('delete-multiple', 'deleteMultiple')->name('destination.delete.multiple');
                Route::post('states', 'fetchState')->name('fetch.state');
                Route::post('cities', 'fetchCity')->name('fetch.city');
            });
        });

        /* ===== ADMIN PAYMENT COLLECTION SETTING ===== */
        Route::get('payment-collection', [PaymentCollectionController::class, 'index'])->name('payment.collection.index');
        Route::any('payment-collection/update', [PaymentCollectionController::class, 'update'])->name('payment.collection.update');

        /* ===== ADMIN GOOGLE MAP API SETTING ===== */
        Route::get('google-api-setting', [GoogleApiSettingController::class, 'googleMapAPISetting'])->name('google.api.setting');
        Route::any('google-map-api-add', [GoogleApiSettingController::class, 'googleMapAPIAdd'])->name('add.google.map.api');
        Route::any('google-map-api-edit/{id}', [GoogleApiSettingController::class, 'googleMapAPIEdit'])->name('edit.google.map.api');
        Route::any('google-map-api-edit-status/{id}', [GoogleApiSettingController::class, 'googleMapAPIEditStatus'])->name('editStatus.google.map.api');

        Route::get('all-package-category', [PackageCategoryController::class, 'list'])->name('all.package.category');
        Route::get('all-package-category/search', [PackageCategoryController::class, 'search'])->name('all.package.category.search');
        Route::get('package-category/add', [PackageCategoryController::class, 'add'])->name('package.category.add');
        Route::post('package-category/store', [PackageCategoryController::class, 'store'])->name('package.category.store');
        Route::get('package-category/{id}/edit', [PackageCategoryController::class, 'edit'])->name('package.category.edit');
        Route::any('package-category/{id}/update', [PackageCategoryController::class, 'update'])->name('package.category.update');
        Route::any('package-category/{id}/delete', [PackageCategoryController::class, 'delete'])->name('package.category.delete');
        Route::any('package-category/delete-multiple', [PackageCategoryController::class, 'deleteMultiple'])->name('package.category.delete.multiple');
        Route::any('package-category/{id}/status', [PackageCategoryController::class, 'status'])->name('packageCategory.status');
        Route::any('package-category/inactive-multiple', [PackageCategoryController::class, 'inactiveMultiple'])->name('packageCategory.inactiveMultiple');


        Route::get('all-package', [PackageController::class, 'list'])->name('all.package');
        Route::get('all-package/search', [PackageController::class, 'search'])->name('all.package.search');
        Route::post('package/action', [PackageController::class, 'action'])->name('package.action');
        Route::get('package/{id}/edit', [PackageController::class, 'edit'])->name('package.edit');
        Route::any('package/{id}/update', [PackageController::class, 'update'])->name('package.update');
        Route::any('package/{id}/delete', [PackageController::class, 'delete'])->name('package.delete');
        Route::any('package/delete-multiple', [PackageController::class, 'deleteMultiple'])->name('package.delete.multiple');
        Route::any('package/{id}/discount', [PackageController::class, 'discount'])->name('package.discount');
        Route::any('package/{id}/status', [PackageController::class, 'status'])->name('package.status');
        Route::any('package/inactive-multiple', [PackageController::class, 'inactiveMultiple'])->name('package.inactiveMultiple');
        Route::get('package/seo/{id}', [PackageController::class, 'packageSEO'])->name('package.seo');
        Route::post('package/seo/update/{id}', [PackageController::class, 'packageSeoUpdate'])->name('package.seo.update');
        Route::post('package-featured/action', [PackageController::class, 'featuredAction'])->name('package.featured.action');


        /* ===== SOCIALITE ===== */
        Route::get('socialite', [SocialiteController::class, 'index'])->name('socialite.index');
        Route::match(['get', 'post'], 'google-config', [SocialiteController::class, 'googleConfig'])->name('google.control');
        Route::match(['get', 'post'], 'facebook-config', [SocialiteController::class, 'facebookConfig'])->name('facebook.control');
        Route::match(['get', 'post'], 'github-config', [SocialiteController::class, 'githubConfig'])->name('github.control');

        Route::controller(AmenitiesController::class)->group(function () {
            Route::group(['prefix' => 'amenity'], function () {
                Route::get('list', 'list')->name('all.amenity');
                Route::get('list-search', 'listSearch')->name('all.amenity.search');
                Route::get('create', 'add')->name('amenity.create');
                Route::post('store', 'store')->name('amenity.store');
                Route::get('edit/{id}', 'edit')->name('amenity.edit');
                Route::post('update', 'update')->name('amenity.update');
                Route::post('status/{id}', 'status')->name('amenity.status');
                Route::delete('delete/{id}', 'delete')->name('amenity.delete');
                Route::any('status-multiple', 'statusMultiple')->name('amenity.statusMultiple');
                Route::any('delete-multiple', 'deleteMultiple')->name('amenity.deleteMultiple');
            });
        });

        Route::get('/plan/list', [PlanController::class, 'planList'])->name('plan.list');
        Route::get('/plan/list-search', [PlanController::class, 'planListSearch'])->name('plan.list.search');
        Route::get('/plan/create', [PlanController::class, 'planCreate'])->name('plan.create');
        Route::post('/plan/store', [PlanController::class, 'planStore'])->name('plan.store');
        Route::get('/plan/edit/{id?}', [PlanController::class, 'planEdit'])->name('plan.edit');
        Route::put('/plan/update{id?}', [PlanController::class, 'planUpdate'])->name('plan.update');
        Route::delete('/plan/delete{id?}', [PlanController::class, 'planDelete'])->name('plan.delete');
        Route::post('/plan/sort', [PlanController::class, 'sortPlan'])->name('sort.plan');


        Route::get('/plan-sold-list', [PlanController::class, 'planSoldList'])->name('plan.sold.list');
        Route::get('/plan-sold/search', [PlanController::class, 'searchPlanSoldList'])->name('search.plan.sold');

        Route::any('pwa',[PwaController::class,'create'])->name('pwa.create');
    });

});


