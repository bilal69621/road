<?php

use App\Jobs;
use App\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CarImport;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
//Umer's Routes

Route::get('/test', 'service\ServiceController@testmail');
Route::get('/teststatus/{id}', 'service\ServiceController@teststatus');
Route::post('excel_import', 'service\ServiceController@excel_import')->name('step_5');
Route::get('upload', 'service\ServiceController@upload');

Route::get('create_blog_slugs','AdminController@create_blog_slugs');

Route::get('/help', function () {
    $title = 'home';
    $description = 'Request emergency roadside assistance right away. Log in as a member or continue as a guest to get started.';
    $user = Auth::user();
    if ($user == Null) {
        return view('main.index')->with(['title' => $title, 'user' => $user]);
    } else {
        return redirect('/rescue');
    }
})->name('help');
Route::get('/dashboard_user', function () {
    return view('dashboard.dashboard');
});

Route::get('sendmessage', 'service\ServiceController@senMessageToUser')->name('sendmessage');

Route::get('rescue', 'service\ServiceController@step1')->name('rescue');
Route::get('rescue2', 'service\ServiceController@step12')->name('rescue2');
Route::get('service/{id}', 'service\ServiceController@directService')->name('service');
Route::group(['middleware' => ['auth']], function () {
    Route::get('step-2', 'service\ServiceController@step2')->name('step_2');
    Route::post('step-3', 'service\ServiceController@step3')->name('step_3');
    Route::post('step-4', 'service\ServiceController@step4')->name('step_4');
    Route::post('step-5', 'service\ServiceController@step5')->name('step_5');

    Route::get('jobdetail/{job_id}', 'service\ServiceController@jobdetail')->name('jobdetail');
    Route::post('cancelJob', 'service\ServiceController@canceljob')->name('cancelJob');
});
//end Umer's route
Route::get('/admin/ss_!458fDEf_frEE45333DDE12~!4874__ddd45', function () {
    if (Auth::guard('admin')->check()) {
        return redirect('dashboard');
    }
    return view('admin.login');
});

Route::get('forget-password', 'Auth\PasswordResetController@forget_password_view');
Route::post('send-password-reset-link', 'Auth\PasswordResetController@create');

Route::get('/login', function () {
    if (Auth::guard('admin')->check()) {
        return redirect('dashboard');
    }
    return view('admin.login');
});
Route::get('/user_login', function (Illuminate\Http\Request $request) {
    if (Auth::user()) {

        return redirect('userdashboard');
    }
    $data['ref11'] = ($request->has('ref')) ? '?ref=' . $request->get('ref') : '';
    return view('users.login')->with($data);

})->name('user.login');

Route::get('/notificationcron', 'NotificationsController@cron');
Route::get('/cron_extra', 'NotificationsController@cron_extra');
Route::get('/cron_extra_canceled', 'NotificationsController@cron_extra_canceled');
Route::get('/pay_on_completion_check', 'NotificationsController@pay_on_completion_check');
Route::get('/expire_job_cron', 'NotificationsController@expireJobCron');

Route::get('/send_reminders', 'NotificationsController@sendReminders');
Route::get('/reset', 'Controller@reset');
Route::get('/success', function () {
    return view('reset_success');
});


Route::post('postlogin', 'AdminController@postLogin');
Route::post('userpostlogin', 'UserController@postLogin');
Route::group(['middleware' => ['checkUser']], function () {
    //user
    Route::get('userdashboard', 'UserController@userDashboard')->name('user.dashboard');
    Route::get('usersubscription', 'UserController@getSubscription');
    Route::get('cancel_subscription', 'UserController@cancelSubscription');

    Route::get('add_subscription_view', 'UserController@addSubscriptionView')->name('user.addSubscription');
    Route::get('create_subscription/{plan}', 'UserController@createSubscription');
    Route::post('new_subscription', 'UserController@newSubscription');
    Route::post('create_subscription_coupon_new', 'UserController@newSubscriptionCoupn')->name('create_subscription_coupon_new');
    Route::get('upgrade_subscription/{plan}', 'UserController@upgradeSubscription');
    Route::post('valid_coupon', 'UserController@checkValidCoupon')->name('valid_coupon');


    Route::get('logout_user', 'UserController@logout')->name('user.logout');
    Route::get('edit_user_profile', 'UserController@editUserProfile')->name('user.edit_profile');
    Route::post('update_user', 'UserController@updateUserProfile')->name('user.update_profile');
    Route::post('change_user_password', 'UserController@changeUserPassword')->name('user.change_password');

    //services
    Route::get('get_services', 'UserController@getServices')->name('user.show_services');

    //payment methods
    Route::get('get_payment_method', 'UserController@get_payment_method')->name('user.get_payment_method');
    Route::post('update_payment_method', 'UserController@update_payment_method')->name('user.update_payment_method');

    //family members
    Route::get('user_family_members', 'UserController@user_family_members')->name('user.user_family_members');
    Route::post('register_new_member', 'UserController@register_new_member')->name('user.register_new_member');
    Route::get('remove_member/{id}', 'UserController@remove_member');

}
);
Route::group(['middleware' => ['admin']], function () {

    Route::get('dashboard', 'AdminController@dashboard');
    Route::post('create_subscription', 'AdminController@createSubscription');
    Route::get('get_subscription_plan', 'AdminController@getSubscriptionPlan');
    Route::get('all_subscriptions', 'AdminController@allSubscription');
    Route::get('cancel_subscription/{subscription}', 'AdminController@cancelSubscription');
    Route::get('cancel_reasons', 'AdminController@cancelReasons');
    Route::post('creat_coupon', 'AdminController@createCoupon');
    Route::get('coupon_system', 'AdminController@coupon_system');

    Route::get('blog_system', 'AdminController@blog_system');
    Route::post('creat_blog', 'AdminController@creat_blog');
    Route::get('delete_blog/{id}', 'AdminController@delete_blog')->name('delete-post');
    Route::post('get_blog', 'AdminController@get_blog')->name('get_blog');
    Route::post('update_blog', 'AdminController@update_blog')->name('update_blog');

    Route::get('categories', 'AdminController@categories');
    Route::post('creat_category', 'AdminController@creat_category');
    Route::get('delete_category/{id}', 'AdminController@delete_category')->name('delete-category');
    Route::post('get_category', 'AdminController@get_category')->name('get_category');
    Route::post('update_category', 'AdminController@update_category')->name('update_category');


    Route::get('test_card_token', 'AdminController@testCreateCardToken');
    Route::get('users', 'AdminController@getusers');
//    Get User Details
    Route::get('user_detail/{id}', 'API\UserController@userDetail');
//    Get User Services Detail
    Route::get('used_services/{id}', 'API\UserController@usedServices');
    Route::get('subscriptions', 'AdminController@getSubscriptions');
//    Get active Jobs all
    Route::get('active_jobs', 'AdminController@getactiveJobs')->name('active_jobs');

    Route::get('logout', 'AdminController@logout');
//   Cancel Subscription Plan from admin panel
    Route::post('cancel_sub', 'API\UserController@adminCancelSub');

    //    Admin Edit Profile View
    Route::get('edit_admin_profile_view', 'AdminController@editProfileView');
//    Save Admin Edit Profile Data
    Route::post('save_edit_profile_data', 'AdminController@editProfileData');
//    Change Password
    Route::post('change_password', 'AdminController@changePassword');

//  Test date compare subscription
    Route::get('test_date_compare', 'AdminController@testDateCompare');
});
//Cron Job Route
Route::get('status_cron', 'AuthController@statusCron');

Route::get('register_profile', 'AdminController@registerProfile')->name('register_p');
Route::post('/register_membership', 'AdminController@register');

Route::get('verify', 'AdminController@verifyUser');
Route::get('/t', function () {
    return view('users.login');
});
Route::group(['namespace' => 'Auth', 'middleware' => 'web', 'prefix' => 'password'], function () {
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('update-password', 'PasswordResetController@updatePassword');
});

Route::get('change/{token}', 'User\LoginController@change');

Route::get('/{any?}', ['as' => 'home', 'uses' => 'PagesController@viewHome'])->where('any', 'home');
Route::get('/about-us', ['as' => 'about', 'uses' => 'PagesController@viewAbout']);
Route::get('/roadsideassistanceplans', ['as' => 'membership', 'uses' => 'PagesController@viewMembership']);
Route::get('/partnership', ['as' => 'partnership', 'uses' => 'PagesController@viewPartnership']);
Route::get('/careers', ['as' => 'careers', 'uses' => 'PagesController@viewCareers']);
Route::get('/privacy–policy', ['as' => 'privacy', 'uses' => 'PagesController@viewPrivacy']);
Route::get('/news/{id}', ['as' => 'news', 'uses' => 'PagesController@news']);
Route::get('blog/{id}', 'PagesController@viewblogSingle');
//=======
//Route::get('/emergency-roadside-assistance-news', ['as' => 'news', 'uses' => 'PagesController@news']);
//Route::get('blog/{id}','PagesController@viewblogSingle');
//>>>>>>> 906802c5c2b826074895e4d54d31e37960459cc9
Route::get('/blog2', ['as' => 'blogSingle', 'uses' => 'PagesController@viewblogSingle2']);
Route::get('/blog1 ', ['as' => 'blogSingle', 'uses' => 'PagesController@viewblogSingle1']);
Route::get('/blog5 ', ['as' => 'blogSingle', 'uses' => 'PagesController@viewblogSingle5']);
Route::get('/blog', ['as' => 'blogSingle', 'uses' => 'PagesController@viewblogSingle']);
Route::get('/blog3', ['as' => 'blogSingle', 'uses' => 'PagesController@viewblogSingle3']);
Route::get('/blog4', ['as' => 'blogSingle', 'uses' => 'PagesController@viewblogSingle4']);
Route::get('/terms-of-use', ['as' => 'terms', 'uses' => 'PagesController@viewTerms']);

Route::post('/contact_us', 'UserController@contact_us');

Route::get('/contactus', 'PagesController@viewAbout')->name('contactus');
//Route::get('contactus',function (){
//    return view('about');
//})->name('contactus');

Route::get('userlogin', 'User\LoginController@LoginUser')->name('userlogin');
Route::post('/uservarify', 'User\LoginController@varifieuser')->name('uservarify');

Route::get('/getyears', 'User\LoginController@getyears')->name('getyears');
Route::get('/getmake', 'User\LoginController@getmake')->name('getmake');
Route::get('/getmodal', 'User\LoginController@getmodal')->name('getmodal');
// Routes for user side /////   bilal
Route::group(['middleware' => ['guestuser']], function () {


    Route::get('/udashboard', 'User\LoginController@dashboard')->name('udashboard')->middleware('auth');

    Route::post('/addusercar', 'User\LoginController@carcreate')->name('addusercar')->middleware('auth');
    Route::post('/deletecar', 'User\LoginController@deletecar')->name('deletecar')->middleware('auth');
    Route::get('/editprofile/{id}', 'User\LoginController@editprofile')->name('editprofile')->middleware('auth');
    Route::post('/updateProfile', 'User\LoginController@updateProfileUser')->name('updateUserProfile')->middleware('auth');
    Route::post('/createcard', 'User\cardController@add_card')->name('createcard');
    Route::post('/deletecard', 'User\cardController@deleteCard')->name('deletecard')->middleware('auth');
    Route::get('/gasprices/{lat}/{lng}', 'User\cardController@getGassPrices');
    Route::get('/subsecription', 'User\cardController@subsecriptions')->name('subsecriptions');
    Route::get('/changepassword', 'User\LoginController@changePassword')->name('changepassword')->middleware('auth');
    Route::get('/checkpassword', 'User\LoginController@checkPassword')->name('checkpassword')->middleware('auth');
    Route::get('/updatepassword', 'User\LoginController@updatepassword')->name('updatepassword')->middleware('auth');
    Route::post('/updateprofilepic', 'User\LoginController@updateProfielPic')->name('updateprofilepic')->middleware('auth');
    Route::get('/paymentinfo', 'User\LoginController@paymentInfo')->name('paymentinfo')->middleware('auth');

    Route::get('/userSubsecriptions', 'UserController@getUserSubscription')->name('userSubsecriptions')->middleware('auth');
    Route::get('/create_subscription_u/{planToken}', 'UserController@createSubUser');
    Route::post('create_subscription_coupon', 'UserController@createSubUserwithcoupon')->name('create_subscription_coupon');
    Route::post('create_subscription_coupon1', 'UserController@createSubUserwithcoupon1')->name('create_subscription_coupon');
    Route::post('upgrade_subscription_coupon', 'UserController@updateSubsecriptionfromcoupon')->name('upgrade_subscription_coupon');
    Route::post('upgrade_subscription_coupon1', 'UserController@updateSubsecriptionfromcoupon1')->name('upgrade_subscription_coupon');

    Route::get('upgrade_subscription_u/{planToken}subsecription', 'UserController@upgradeSubscriptionUser');

    Route::post('cancel_subscription_u', 'UserController@cancelSubscriptionUser');
    Route::post('upgradeusersubsecription', 'UserController@updateSubsecriptionfrom')->name('upgradeusersubsecription');

});
Route::post('checkCouponUser', 'UserController@checkCouponUser')->name('checkCouponUser');
Route::post('checkemail', 'UserController@checkemail')->name('checkemail');

Route::get('/userlogout', 'User\LoginController@logout')->name('userlogout');

route::get('getPhoneNumber', 'service\ServiceController@getPhoneNumber')->name('getphoneNumber');
Route::get('uberride', 'service\ServiceController@UberRide')->name('uberride');
Route::get('return_uber', 'service\ServiceController@testin');
Route::get('checkusersub', 'service\ServiceController@check_before_create_job')->name('checkusersub');
Route::post('payuseronetime', 'service\ServiceController@payOneTime')->name('payuseronetime');
Route::post('chargepaymentguest', 'service\ServiceController@payguestuser')->name('chargepaymentguest');

// for getting swoop job status
Route::get('statusjobswoop', 'service\ServiceController@getJobStatus')->name('statusjobswoop');

Route::get('payflow-thankyou', 'service\ServiceController@payflow_thankyou')->name('status-jobswoop');
//
Route::post('create_hubspot', 'service\ServiceController@submitHubspot')->name('create_hubspot');

Route::post('pay_via_payflow', 'service\ServiceController@pay_via_payflow')->name('pay_via_payflow');

//Route for reset Password
Route::get('foget_password', 'User\LoginController@reSetLink')->name('foget_password');
Route::post('send_rest_link', 'User\LoginController@createLink')->name('send_rest_link');
Route::post('change_password', 'User\LoginController@resetPass')->name('change_password');

Route::get('atlanta', function () {
    return view('citypages.atlanta');
});
Route::get('rescue/roadside-assistance-newyork', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-chicago', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-dallas', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-houston', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-los_angles', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-orlando', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-atlanta', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-charlotte', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-philadelphia', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-miami', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-denvor', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-boston', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-tampa', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-washington', 'service\ServiceController@cityPages');

Route::get('rescue/roadside-assistance-los_vegas', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-san_francisco', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-nashville', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-detroit', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-memphis', 'service\ServiceController@cityPages');
Route::get('rescue/roadside-assistance-phoenix', 'service\ServiceController@cityPages');

Route::get('testhub', 'service\ServiceController@submitHubspottest')->name('testhub');

Route::get('teststatus/{id}', 'service\ServiceController@teststatus')->name('testhub');

Route::get('send_test', 'service\ServiceController@senMessageToUsertest');
