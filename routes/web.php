<?php

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



Route::group(['middleware' => ['auth']], function() {

    Route::get          ('/',                            'NewApplicationController@index'                          )->name('dashboard');

    Route::get('/applications', function () {
        return view('backend.pages.applications');
    });


});


Route::get('/print', function () {
    return view('backend.partial.id_layout');
});

Route::group(['prefix' => 'topaz', 'middleware' => ['auth']], function () {
    Route::get('/diagnostics', function () {
        return view('backend.pages.tools.topaz_diagnostics');
    })->name('topaz-diagnostics');
});


// DASHBOARD
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'DashboardController@index'                          )->name('dashboard');
    Route::get          ('/record',                      'DashboardController@index_magic'                    )->name('dashboard');
    Route::post         ('/save',                        'DashboardController@store'                          )->name('dashboard_store');
    Route::get          ('/edit/{id}',                   'DashboardController@edit'                           )->name('dashboard_edit');
    Route::post         ('/update/{id}',                 'DashboardController@update'                         )->name('dashboard_update');
    Route::post         ('/filterRecord',                'DashboardController@filterRecord'                   )->name('dashboard_filter');
    Route::get          ('/destroy/{id}',                'DashboardController@destroy'                        )->name('dashboard_destroy');
    Route::get          ('/record/{get}/{date}',         'DashboardController@get_record'                     )->name('dashboard_get_record');
});

// Rates
Route::group(['prefix' => 'rate', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'RatesController@index'                          )->name('rate');
    Route::post         ('/save',                        'RatesController@store'                          )->name('rate_store');
    Route::get          ('/edit/{id}',                   'RatesController@edit'                           )->name('rate_edit');
    Route::post         ('/update/{id}',                 'RatesController@update'                         )->name('rate_update');
    Route::get          ('/destroy/{id}',                'RatesController@destroy'                        )->name('rate_destroy');
});

// MUNICIPALITY
Route::group(['prefix' => 'municipality', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'MunicipalityController@index'                          )->name('fee');
    Route::post         ('/save',                        'MunicipalityController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'MunicipalityController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'MunicipalityController@update'                         )->name('fee_update');
    Route::get          ('/destroy/{id}',                'MunicipalityController@destroy'                        )->name('fee_destroy');
});

// NATIONALITY
Route::group(['prefix' => 'nationality', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'NationalityController@index'                          )->name('fee');
    Route::post         ('/save',                        'NationalityController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'NationalityController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'NationalityController@update'                         )->name('fee_update');
    Route::get          ('/destroy/{id}',                'NationalityController@destroy'                        )->name('fee_destroy');
});

// RELIGION
Route::group(['prefix' => 'religion', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'ReligionController@index'                          )->name('fee');
    Route::post         ('/save',                        'ReligionController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'ReligionController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'ReligionController@update'                         )->name('fee_update');
    Route::get          ('/destroy/{id}',                'ReligionController@destroy'                        )->name('fee_destroy');
});

// PURPOSE
Route::group(['prefix' => 'purpose', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'PurposeController@index'                          )->name('fee');
    Route::post         ('/save',                        'PurposeController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'PurposeController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'PurposeController@update'                         )->name('fee_update');
    Route::get          ('/destroy/{id}',                'PurposeController@destroy'                        )->name('fee_destroy');
});

// NEW APPLICATION
Route::group(['prefix' => 'new_application', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'NewApplicationController@index'                          )->name('new-application');
    Route::get          ('/lookup',                     'NewApplicationController@lookup'                         )->name('new-application-lookup');
    Route::get          ('/lookup/{id}',               'NewApplicationController@lookupById'                     )->name('new-application-lookup-by-id');
    Route::get          ('/renewal/history',            'NewApplicationController@renewalHistory'                 )->name('new-application-renewal-history');
    Route::post         ('/renewal/save',              'NewApplicationController@renewalStore'                   )->name('new-application-renewal-save');
    Route::get          ('/complete_transaction',        'NewApplicationController@completeTransaction'            )->name('new-application-complete-transaction');
    Route::post         ('/picture',                     'NewApplicationController@picture'                        )->name('fee_store');
    Route::post         ('/signature/capture',           'NewApplicationController@captureSignature'               )->name('new-application-signature-capture');
    Route::get          ('/fingerprint_right',           'NewApplicationController@fingerprint_right_show'         )->name('new-application-fingerprint-right');
    Route::post         ('/fingerprint_right/save',      'NewApplicationController@fingerprint_right'              )->name('fee_store');
    Route::get          ('/fingerprint_left',            'NewApplicationController@fingerprint_left_show'          )->name('new-application-fingerprint-left');
    Route::post         ('/fingerprint_left/save',       'NewApplicationController@fingerprint_left'               )->name('fee_store');
    Route::get          ('/signature',                   'NewApplicationController@signature'                      )->name('new-application-signature');
    Route::post         ('/signature/{id}',              'NewApplicationController@signature_update'               )->name('new-application-signature');
    Route::get          ('/signature_show',              'NewApplicationController@signature_show'                 )->name('new-application-signature-show');
    Route::post         ('/save',                        'NewApplicationController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'NewApplicationController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'NewApplicationController@update'                         )->name('fee_update');
    Route::get          ('/destroy/{id}',                'NewApplicationController@destroy'                        )->name('fee_destroy');
});

Route::prefix('home-function')->group(function(){
    Route::post('{id}', 'NewApplicationController@picture')->name('home-functions');
});

// FINGERPRINT
Route::group(['prefix' => 'fingerprint', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'FingerPrintController@index'                          )->name('fee');
    Route::post         ('/save',                        'FingerPrintController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'FingerPrintController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'FingerPrintController@update'                         )->name('fee_update');
    Route::get          ('/destroy/{id}',                'FingerPrintController@destroy'                        )->name('fee_destroy');
});

// APPLICATION
Route::group(['prefix' => 'application', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'ApplicationController@index'                          )->name('fee');
    Route::get          ('/printable-form',             'ApplicationController@printableForm'                  )->name('application-printable-form');
    Route::get          ('/completed',                   'ApplicationController@completed'                      )->name('fee_store');
    Route::get          ('/completed/record',            'ApplicationController@completed_record'               )->name('fee_store');
    Route::get          ('/completed_edit/{id}',         'ApplicationController@completed_edit'                 )->name('fee_store');
    Route::post         ('/completed_update/{id}',       'ApplicationController@completed_update'               )->name('fee_store');
    Route::get          ('/dashboard_api',               'ApplicationController@dashboard_api'                  )->name('fee_store');
    Route::get          ('/detail',                      'ApplicationController@detail'                         )->name('fee_store');
    Route::get          ('/list_applicant',              'ApplicationController@list_applicant'                 )->name('fee_store');
    Route::post          ('/list_applicant_filter',      'ApplicationController@list_applicant_filter'          )->name('fee_store');
    Route::get          ('/masterlist_applicant',        'ApplicationController@masterlist_applicant'           )->name('fee_store');
    Route::get          ('/masterlist_applicant_record', 'ApplicationController@masterlist_applicant_magic'     )->name('fee_store');
    Route::post          ('/masterlist_applicant_filter', 'ApplicationController@masterlist_applicant_filter'    )->name('fee_store');
    Route::get          ('/picture/{id}',                'ApplicationController@picture'                        )->name('fee_store');
    Route::get          ('/right_thumb/{id}',            'ApplicationController@right_thumb'                    )->name('fee_store');
    Route::get          ('/left_thumb/{id}',             'ApplicationController@left_thumb'                     )->name('fee_store');
    Route::get          ('/signature/{id}',              'ApplicationController@signature'                      )->name('fee_store');
    Route::post         ('/save',                        'ApplicationController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'ApplicationController@edit'                           )->name('fee_edit');
    Route::get          ('/certificate/{id}',            'ApplicationController@certificate'                    )->name('fee_edit');
    Route::post         ('/update/{id}',                 'ApplicationController@update'                         )->name('fee_update');
    Route::get          ('/get_application/{id}',        'ApplicationController@getApplication'                 )->name('fee_update');
    Route::post          ('/renew',                      'ApplicationController@renewApplication'                 )->name('fee_update');
    Route::get          ('/destroy/{id}',                'ApplicationController@destroy'                        )->name('fee_destroy');
});

// USER
Route::group(['prefix' => 'user', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'UserController@index'                          )->name('fee');
    Route::post         ('/save',                        'UserController@store'                          )->name('fee_store');
    Route::get          ('/edit/{id}',                   'UserController@edit'                           )->name('fee_edit');
    Route::post         ('/update/{id}',                 'UserController@update'                         )->name('fee_update');
    Route::post         ('/update_status_active/{id}',   'UserController@update_status_active'           )->name('fee_update');
    Route::get          ('/destroy/{id}',                'UserController@destroy'                        )->name('fee_destroy');
    Route::post         ('/update_picture',              'UserController@updatePicture'                  )->name('update_picture');
    Route::post         ('/change_password',             'UserController@changePassword'                 )->name('change_password');

});

// NEW APPLICATION
Route::group(['prefix' => 'new-application', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'NewApplicationController@index2'                         )->name('newapplication');
    Route::post         ('/save',                        'NewApplicationController@store'                          )->name('newapplication_store');
    Route::get          ('/edit/{id}',                   'NewApplicationController@edit'                           )->name('newapplication_edit');
    Route::post         ('/update/{id}',                 'NewApplicationController@update'                         )->name('newapplication_update');
    Route::get          ('/destroy/{id}',                'NewApplicationController@destroy'                        )->name('newapplication_destroy');

});

// Payments
Route::group(['prefix' => 'payment', 'middleware' => ['auth']], function (){
    Route::get          ('/',                            'PaymentController@index'                                  )->name('payment');
    Route::get          ('/process',                     'PaymentController@process'                                  )->name('payment');
    Route::post         ('/save',                        'PaymentController@store'                          )->name('newapplication_store');
    Route::get          ('/edit/{id}',                   'PaymentController@edit'                           )->name('newapplication_edit');
    Route::post         ('/update/{id}',                 'PaymentController@update'                         )->name('newapplication_update');
    Route::get          ('/destroy/{id}',                'PaymentController@destroy'                        )->name('newapplication_destroy');

});

// Payments
Route::group(['prefix' => 'hit-verification', 'middleware' => ['auth']], function (){
    Route::get          ('/',                               'HitVerificationController@index'                       )->name('hitverification');

    // Route::post         ('/save',                        'NewApplicationController@store'                          )->name('newapplication_store');
    // Route::get          ('/edit/{id}',                   'NewApplicationController@edit'                           )->name('newapplication_edit');
    // Route::post         ('/update/{id}',                 'NewApplicationController@update'                         )->name('newapplication_update');
    // Route::get          ('/destroy/{id}',                'NewApplicationController@destroy'                        )->name('newapplication_destroy');

});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
