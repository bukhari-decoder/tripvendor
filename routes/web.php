<?php

use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\ChattingController;
use App\Http\Controllers\User\GuideController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PackageController as UserPackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\ManualRecaptchaController;
use App\Http\Controllers\khaltiPaymentController;
use App\Http\Controllers\User\PaymentGatewayController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\SocialiteController;
use App\Http\Controllers\User\SubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\User\KycVerificationController;
use App\Http\Controllers\TwoFaSecurityController;
use App\Http\Controllers\User\PayoutController;



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




$basicControl = basicControl();
Route::get('maintenance-mode', function () {
    if (!basicControl()->is_maintenance_mode) {
        return redirect(route('page'));
    }
    $data['maintenanceMode'] = \App\Models\MaintenanceMode::first();
    return view(template() . 'maintenance', $data);
})->name('maintenance');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('user.password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('current/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

Route::get('instruction/page', function () {
    return view('instruction-page');
})->name('instructionPage');

Route::get('switch', function () {
    $language = \App\Models\Language::where('default_status', 1)->first();
    app()->setLocale($language->short_name);
    session()->put('lang', $language->short_name);
    return redirect()->route('page');
})->name('switch.language');

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');
    });

    Route::group(['middleware' => ['auth'],
        'prefix' => 'user',
        'as' => 'user.'
    ], function () {

            Route::get('check', [VerificationController::class, 'check'])->name('check');
            Route::get('resend_code', [VerificationController::class, 'resendCode'])->name('resend.code');
            Route::post('mail-verify', [VerificationController::class, 'mailVerify'])->name('mail.verify');
            Route::post('sms-verify', [VerificationController::class, 'smsVerify'])->name('sms.verify');
            Route::post('twoFA-Verify', [VerificationController::class, 'twoFAverify'])->name('twoFA-Verify');

            Route::middleware('userCheck')->group(function () {

                Route::middleware('kyc')->group(function () {

                    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
                    Route::post('save-token', [HomeController::class, 'saveToken'])->name('save.token');
                    Route::get('add-fund', [HomeController::class, 'addFund'])->name('add.fund');

                    Route::get('transaction-list', [HomeController::class, 'transaction'])->name('transaction');
                    Route::get('transaction-search', [HomeController::class, 'transactionSearch'])->name('transaction.search');

                    Route::controller(TwoFaSecurityController::class)->group(function () {
                        Route::get('/twostep-security', 'twoStepSecurity')->name('twostep.security');
                        Route::post('twoStep-enable', 'twoStepEnable')->name('twoStepEnable');
                        Route::post('twoStep-disable', 'twoStepDisable')->name('twoStepDisable');
                        Route::post('twoStep/re-generate', 'twoStepRegenerate')->name('twoStepRegenerate');
                    });

                    Route::get('payout-list', [PayoutController::class, 'index'])->name('payout.index');
                    Route::get('payout-search', [PayoutController::class, 'search'])->name('payout.search');

                    Route::get('payout', [PayoutController::class, 'payout'])->name('payout');
                    Route::get('payout-supported-currency', [PayoutController::class, 'payoutSupportedCurrency'])->name('payout.supported.currency');
                    Route::get('payout-check-amount', [PayoutController::class, 'checkAmount'])->name('payout.checkAmount');
                    Route::post('request-payout', [PayoutController::class, 'payoutRequest'])->name('payout.request');

                    Route::match(['get', 'post'], 'confirm-payout/{trx_id}', [PayoutController::class, 'confirmPayout'])->name('payout.confirm');
                    Route::post('confirm-payout/flutterwave/{trx_id}', [PayoutController::class, 'flutterwavePayout'])->name('payout.flutterwave');
                    Route::post('confirm-payout/paystack/{trx_id}', [PayoutController::class, 'paystackPayout'])->name('payout.paystack');
                    Route::get('payout-check-limit', [PayoutController::class, 'checkLimit'])->name('payout.checkLimit');
                    Route::post('payout-bank-form', [PayoutController::class, 'getBankForm'])->name('payout.getBankForm');
                    Route::post('payout-bank-list', [PayoutController::class, 'getBankList'])->name('payout.getBankList');

                    Route::get('push-notification-show', [InAppNotificationController::class, 'show'])->name('push.notification.show');
                    Route::get('push-notification-readAll', [InAppNotificationController::class, 'readAll'])->name('push.notification.readAll');
                    Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

                    Route::get('messages/show', [ChattingController::class, 'show'])->name('message.show');
                    Route::get('messages/readAll', [ChattingController::class, 'readAll'])->name('message.readAll');
                    Route::get('messages/readAt/{id}', [ChattingController::class, 'readAt'])->name('message.readAt');

                    Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
                        Route::get('/', [SupportTicketController::class, 'index'])->name('list');
                        Route::get('/user-search', [SupportTicketController::class, 'search'])->name('user.search');
                        Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
                        Route::post('/create', [SupportTicketController::class, 'store'])->name('store');
                        Route::get('/view/{ticket}', [SupportTicketController::class, 'ticketView'])->name('view');
                        Route::put('/reply/{ticket}', [SupportTicketController::class, 'reply'])->name('reply');
                        Route::get('/download/{ticket}', [SupportTicketController::class, 'download'])->name('download');
                        Route::any('/close/{id}', [SupportTicketController::class, 'close'])->name('close');
                    });

                    Route::get('all-package', [UserPackageController::class, 'list'])->name('all.package');
                    Route::get('all-package/search', [UserPackageController::class, 'search'])->name('all.package.search');
                    Route::get('package/add', [UserPackageController::class, 'add'])->name('package.add');
                    Route::post('package/store', [UserPackageController::class, 'store'])->name('package.store');
                    Route::get('package/{id}/edit', [UserPackageController::class, 'edit'])->name('package.edit');
                    Route::any('package/{id}/update', [UserPackageController::class, 'update'])->name('package.update');
                    Route::any('package/{id}/delete', [UserPackageController::class, 'delete'])->name('package.delete');
                    Route::any('package/delete-multiple', [UserPackageController::class, 'deleteMultiple'])->name('package.delete.multiple');
                    Route::any('package/{id}/discount', [UserPackageController::class, 'discount'])->name('package.discount');
                    Route::get('package/seo/{id}', [UserPackageController::class, 'packageSEO'])->name('package.seo');
                    Route::post('package/seo/update/{id}', [UserPackageController::class, 'packageSeoUpdate'])->name('package.seo.update');

                    Route::get('notification-permission/list', [NotificationController::class, 'index'])->name('notification.permission.list');
                    Route::post('notification-perission/update', [NotificationController::class, 'notificationSettingsChanges'])->name('notification.permission');

                    Route::controller(ReviewController::class)->group(function () {
                        Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
                            Route::get('list', 'reviewList')->name('list');
                            Route::get('list/search', 'reviewSearch')->name('search');
                        });
                    });

                    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
                    Route::post('profile-update', [HomeController::class, 'profileUpdate'])->name('profile.update');
                    Route::post('profile-update/image', [HomeController::class, 'profileUpdateImage'])->name('profile.update.image');
                    Route::post('update/password', [HomeController::class, 'updatePassword'])->name('updatePassword');

                    Route::post('blog/Comment', [BlogController::class, 'updateComment'])->name('blog.comment');
                    Route::post('blog-comment/reply', [BlogController::class, 'updateCommentReply'])->name('comments.reply');

                    Route::post('become/vendor', [HomeController::class, 'becomeVendor'])->name('become.vendor');
                    Route::post('plan/renew', [PlanController::class, 'autoRenew'])->name('plan.auto.renew');

                    Route::get('booking', [BookingController::class, 'bookingList'])->name('booking.list');
                    Route::get('booking-search', [BookingController::class, 'bookingListSearch'])->name('booking.list.search');

                    Route::get('package-booking', [BookingController::class, 'vendorBookingList'])->name('vendor.booking.list');
                    Route::get('package-booking-search', [BookingController::class, 'vendorBookingListSearch'])->name('vendor.booking.list.search');
                    Route::get('accept-booking/{uid}', [BookingController::class, 'acceptBooking'])->name('accept.booking');
                    Route::get('refund-booking/{uid}', [BookingController::class, 'refundBooking'])->name('refund.booking');
                    Route::post('complete-booking/{uid}', [BookingController::class, 'completeBooking'])->name('complete.booking');
                    Route::get('view-booking/{uid}', [BookingController::class, 'viewBooking'])->name('view.booking');

                    Route::get('payment-logs', [HomeController::class, 'paymentLog'])->name('fund.index');
                    Route::get('payment-logs-search', [HomeController::class, 'paymentLogSearch'])->name('fund.index.search');

                    Route::post('states', [HomeController::class, 'fetchState'])->name('fetch.state');
                    Route::post('cities', [HomeController::class, 'fetchCity'])->name('fetch.city');

                    Route::any('package-checkout/{slug}/{uid?}', [CheckoutController::class, 'checkoutForm'])->name('checkout.form');
                    Route::any('package/checkout-form/travelers-details', [CheckoutController::class, 'checkoutTravelersDetails'])->name('checkout.form.travelers.details');

                    Route::get('package/checkout-form/travelers-details/{uid}', [CheckoutController::class, 'getTravel'])->name('checkout.get.travel');

                    Route::post('package/checkout-form/payment', [CheckoutController::class, 'checkoutTravelersPayment'])->name('checkout.form.travelers.payment');
                    Route::get('package/checkout/form/{uid}', [CheckoutController::class, 'checkoutPaymentForm'])->name('checkout.payment.form');

                    Route::any('package/make-payment', [CheckoutController::class, 'makePayment'])->name('make.payment');
                    Route::any('date/update', [CheckoutController::class, 'dateUpdate'])->name('date.update');
                    Route::get('coupon/check', [CheckoutController::class, 'checkCoupon'])->name('coupon.check');

                    Route::post('purchase-plan', [PlanController::class, 'planSelect'])->name('purchase.planSelect');
                    Route::get('make-payment/details', [PlanController::class, 'makePaymentDetails'])->name('make.payment.details');
                    Route::post('plan/make-payment', [PlanController::class, 'makePayment'])->name('plan.make.payment');

                    Route::get('package/make-featured', [UserPackageController::class, 'featuredRequest'])->name('package.featured.request');

                    Route::get('all-guides', [GuideController::class, 'list'])->name('all.guides');
                    Route::get('all-guides/search', [GuideController::class, 'search'])->name('all.guides.search');
                    Route::get('guide/add', [GuideController::class, 'add'])->name('guide.add');
                    Route::post('guide/store', [GuideController::class, 'store'])->name('guide.store');
                    Route::get('guide/{slug}/edit', [GuideController::class, 'edit'])->name('guide.edit');
                    Route::any('guide/update', [GuideController::class, 'update'])->name('guide.update');
                    Route::any('guide/{slug}/delete', [GuideController::class, 'delete'])->name('guide.delete');
                    Route::any('guide/{slug}/status', [GuideController::class, 'status'])->name('guide.status');

                    Route::post('add-review/store', [ReviewController::class, 'store'])->name('review.store');
                    Route::post('add-review/reply', [ReviewController::class, 'reply'])->name('review.reply.store');

                    Route::get('vendor-bookings', [HomeController::class, 'bookings'])->name('bookings');
                    Route::get('vendor-popular-packages', [HomeController::class, 'packages'])->name('popular.packages');
                    Route::get('vendor-booking-calender', [HomeController::class, 'bookingCalender'])->name('booking.calender');

                    Route::get('delete-account', [HomeController::class, 'deleteAccount'])->name('delete.account');

                    Route::any('chat/reply', [ChattingController::class, 'reply'])->name('chat.reply');

                    Route::get('chat/list', [ChattingController::class, 'view'])->name('chat.list');
                    Route::get('chat/search', [ChattingController::class, 'searchData'])->name('chat.search');
                    Route::delete('chat/{id}/delete', [ChattingController::class, 'delete'])->name('chat.delete');
                    Route::any('chat/{id}/nickname-set', [ChattingController::class, 'nickname'])->name('chat.nickname');
                    Route::any('chat/details', [ChattingController::class, 'chatDetails'])->name('chat.details');


                    Route::group(['prefix' => 'payment-gateway', 'as' => 'payment.gateway.'], function () {
                        Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
                        Route::any('/edit/{id}', [PaymentGatewayController::class, 'edit'])->name('edit');
                        Route::get('/manage', [PaymentGatewayController::class, 'manage'])->name('manage');
                        Route::post('/update-status', [PaymentGatewayController::class, 'updateStatus'])->name('update.status');
                    });
                });

                Route::get('verification/kyc', [KycVerificationController::class, 'kyc'])->name('verification.kyc');
                Route::get('verification/kyc-form/{id}', [KycVerificationController::class, 'kycForm'])->name('verification.kyc.form');
                Route::post('verification/kyc-form/submit', [KycVerificationController::class, 'verificationSubmit'])->name('kyc.verification.submit');
                Route::get('verification/kyc/history', [KycVerificationController::class, 'history'])->name('verification.kyc.history');
                Route::get('profile/kyc-settings', [HomeController::class, 'kycSettings'])->name('kyc.settings');
                Route::get('profile/kyc-details', [KycVerificationController::class, 'kycFormDetails'])->name('kycFrom.details');
                Route::get('verification/history', [KycVerificationController::class, 'verificationHistory'])->name('kyc.history');
                Route::get('profile/change-password', [HomeController::class, 'changePassword'])->name('change.password');


                Route::any('generate-with-ai', [UserPackageController::class, 'generate'])->name('ai.generate');
                Route::any('generate-with-ai/image', [UserPackageController::class, 'generateImage'])->name('ai.generate.image');

            });

    });

    Route::get('captcha', [ManualRecaptchaController::class, 'reCaptCha'])->name('captcha');
    Route::post('subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');

    Route::get('supported-currency', [DepositController::class, 'supportedCurrency'])->name('supported.currency');
    Route::post('payment-request', [DepositController::class, 'paymentRequest'])->name('payment.request');
    Route::get('deposit-check-amount', [DepositController::class, 'checkAmount'])->name('deposit.checkAmount');

    Route::get('payment-process/{trx_id}', [PaymentController::class, 'depositConfirm'])->name('payment.process');
    Route::post('addFundConfirm/{trx_id}', [PaymentController::class, 'fromSubmit'])->name('addFund.fromSubmit');
    Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');

    Route::post('khalti/payment/verify/{trx}', [khaltiPaymentController::class, 'verifyPayment'])->name('khalti.verifyPayment');
    Route::post('khalti/payment/store', [khaltiPaymentController::class, 'storePayment'])->name('khalti.storePayment');

    Route::get('news', [BlogController::class, 'blog'])->name('news');
    Route::get('news-details/{slug}', [BlogController::class, 'blogDetails'])->name('news.details');

    Route::get('destinations', [DestinationController::class, 'destinationList'])->name('destination');
    Route::get('destinations-details/{slug}', [DestinationController::class, 'destinationDetails'])->name('destination.details')->middleware('destinationVisitor');
    Route::any('destination/track-visitor', [FrontendController::class, 'trackVisitor'])->name('destination.track');

    Route::get('packages', [PackageController::class, 'packageList'])->name('package');
    Route::get('packages/author/{slug}', [PackageController::class, 'packageAuthor'])->name('package.author');
    Route::get('package/{slug}', [PackageController::class, 'packageDetails'])->name('package.details')->middleware('packageVisitor');
    Route::post('package-search', [PackageController::class, 'packageSearch'])->name('package.search');

    Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
    Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');

    Route::post('/contact/send', [FrontendController::class, 'contactSend'])->name('contact.send');
    Route::get('top-search', [FrontendController::class, 'topSearch'])->name('top.search');

    Route::get('/language/{code?}', [FrontendController::class, 'language'])->name('language');

    Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');
    Route::any('subscription/{code}/{utr?}', [SubscriptionController::class, 'subscriptionIpn'])->name('subscription.ipn');
    

    Auth::routes();
    /*= Frontend Manage Controller =*/
    Route::get("/{slug?}", [FrontendController::class, 'page'])->name('page');
});


