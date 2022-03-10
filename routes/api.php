<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\UserController@login');
//Get Location
Route::get('get_location/{job_id}', 'NotificationsController@getLocation');
Route::get('get_status/{job_id}', 'NotificationsController@getStatus');


//Api's
Route::get('/get_all_users', 'API\UserController@get_all_users');
Route::get('/get_user_detail', 'API\UserController@get_user_detail');
Route::post('/update_user_details', 'API\UserController@update_user_details');
Route::get('/get_used_services', 'API\UserController@get_used_services');
Route::get('getCancelSubscriptions', 'API\UserController@get_cancel_subscriptions');
Route::post('cancelSubscription', 'API\UserController@cancel_subscription');

Route::post('register', 'API\UserController@register');

Route::post('register_guest', 'API\UserController@register_guest');
Route::group(['middleware' => ['auth:api', 'checkSession']], function() {
    Route::post('details', 'API\UserController@details');
    Route::post('details', 'API\UserController@details');
    Route::post('update_user_profile', 'API\UserController@updateUserProfile');
    Route::post('update_user_profile_guest', 'API\UserController@updateUserProfileForGuest');
    Route::post('update_avatar', 'API\UserController@updateAvatar');
    Route::get('logout', 'API\UserController@logout');
    Route::post('get_make', 'API\CarsController@getMake');
    Route::post('get_model', 'API\CarsController@getModel');
    Route::post('change_password', 'API\UserController@changePassword');
    Route::post('createSubscription', 'API\UserController@create_subscription');
    Route::post('createGuestService', 'API\UserController@create_guest_service');
    Route::post('sendAlertNotification', 'API\UserController@send_alert_notification');
    Route::post('retrieveJobId', 'API\UserController@retrieve_job_id');
    Route::post('memberPayPerUse', 'API\UserController@pay_per_use_member');
    Route::post('retrieveMilesForService', 'API\UserController@retrieve_miles_for_service');
    Route::post('retrieveMilesForMembers', 'API\UserController@retrieve_miles_for_members');
    Route::get('getSubscriptionPlan', 'API\UserController@get_subscription_plan');


    Route::post('charge', 'API\UserController@create_charge');
    Route::get('get_user_subscription_details', 'API\UserController@get_user_subscription_details');
    Route::get('get_members', 'API\UserController@get_members');
    Route::get('remove_member', 'API\UserController@remove_member');
    Route::post('register_new_member', 'API\UserController@register_new_member');
    Route::get('get_member_details', 'API\UserController@get_member_details');
    Route::post('add_update_payment_method', 'API\UserController@addCard');
    Route::post('on_off_tracking', 'API\UserController@on_off_tracking');
    Route::post('update_user_location', 'API\UserController@update_user_location');
    Route::post('get_updated_location', 'API\UserController@get_updated_location');

    Route::post('subscriptions','AdminController@createSubscription');
    Route::post('cancel_subscription','AdminController@cancelSubscription');

    Route::post('test','AdminController@test');
    Route::post('get_swoop_token','API\UserController@getSwoopToken');

    // Chat Routes
//    Route::get('/group_users_list', 'API\ChatController@group_users_list');
//    Route::get('/users_list', 'API\ChatController@users_to_chat_with');
//    Route::post('/create_chat', 'API\ChatController@create_chat');
//    Route::post('/send_message_mobile', 'API\ChatController@send_message_mobile');
//    Route::post('/delete_message', 'API\ChatController@delete_message');
//    Route::post('/delete_chat', 'API\ChatController@delete_chat');
//    Route::get('/get_all_chats', 'API\ChatController@get_all_chats');
//    Route::get('/update_unread_count', 'API\ChatController@update_unread_count');
    Route::post('/send_message', 'API\ChatController@send_message');
    Route::post('/upload_media', 'API\ChatController@upload_media');
    Route::get('/get_chat', 'API\ChatController@get_chat');
    Route::get('/read_chat', 'API\ChatController@read_chat');


});

Route::group(['namespace' => 'Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
    Route::post('create', 'PasswordResetController@create');
//    Route::get('find/{token}', 'PasswordResetController@find');
//    Route::post('reset', 'PasswordResetController@reset');
});

Route::group(['middleware' => ['auth:api', 'checkSession'], 'prefix' => 'car'], function () {
    Route::get('all_cars', 'API\CarsController@all_cars');
    Route::post('create', 'API\CarsController@create');
    Route::get('edit/{id}', 'API\CarsController@edit');
    Route::post('update', 'API\CarsController@update');
    Route::get('show/{id}', 'API\CarsController@show');
    Route::get('delete/{id}', 'API\CarsController@destroy');
});

Route::group(['middleware' => ['auth:api', 'checkSession'], 'prefix' => 'job'], function () {
    Route::get('all_jobs', 'API\JobsController@all_jobs');
    Route::post('create', 'API\JobsController@create');
    Route::post('cancel_job', 'API\JobsController@cancel_job');
    Route::get('edit/{id}', 'API\JobsController@edit');
    Route::post('update', 'API\JobsController@update');
    Route::get('show/{id}', 'API\JobsController@show');
    Route::get('delete/{id}', 'API\JobsController@destroy');
    Route::get('check_before_create_job', 'API\JobsController@check_before_create_job');
});

Route::group(['middleware' => ['auth:api', 'checkSession'], 'prefix' => 'reminder'], function () {
    Route::get('all_reminders', 'API\RemindersController@index');
    Route::post('add_reminder', 'API\RemindersController@addReminder');
    Route::post('show_reminder', 'API\RemindersController@showReminder');
    Route::post('update_reminder', 'API\RemindersController@updateReminder');
    Route::get('delete_reminder/{id}', 'API\RemindersController@deleteReminder');
});




