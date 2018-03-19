<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * LBT website offer
 */
Route::get('lbt/offer/{wp_post_id}', array(
    'as' => 'lbt.offer',
    'uses' => function($wp_post_id){
        return redirect( config('lbt.wp_site') . '?p=' . $wp_post_id);
    }));

/**
 * Authentication
 */

Route::group(array('prefix' => 'auth'), function(){

    Route::get('login', array(
        'uses' => 'Auth\AuthController@showLoginForm',
        'as' => 'auth.login' ));

    Route::post('login', array(
        'uses' => 'Auth\AuthController@login',
        'as' => 'login.post' ));

    Route::get('password/reset/{token?}', array(
        'uses' => 'Auth\PasswordController@showResetForm',
        'as' => 'forgotpassword' ));

    Route::post('password/reset/', array(
        'uses' => 'Auth\PasswordController@postReset',
        'as' => 'forgotpassword.reset' ));

    Route::post('password/email', array(
        'uses' => 'Auth\PasswordController@sendResetLinkEmail',
        'as' => 'forgotpassword.post' ));

    Route::get('logout', array(
        'uses' => 'Auth\LogoutController@index',
        'as' => 'auth.logout' ));

    Route::get('register', array(
        'uses' => 'Auth\RegisterSalesRepController@showRegistrationForm',
        'as' => 'register' ));

    Route::get('register/cancel', array(
        'uses' => 'Auth\FbSocialController@cancelRegister',
        'as' => 'register.cancel' ));

    Route::post('register', array(
        'uses' => 'Auth\RegisterSalesRepController@storeAccount',
        'as' => 'register.salesrep.store_account' ));

    Route::post('register/brands-associates/store-dealer', array(
        'uses' => 'Auth\RegisterSalesRepController@storeDealer',
        'as' => 'register.salesrep.store_dealer' ));

    Route::post('register/brands-associates/store-social-profile', array(
        'uses' => 'Auth\RegisterSalesRepController@storeSocialProfile',
        'as' => 'register.salesrep.store_socialprofile' ));

    Route::get('register/confirm-account/{token}', array(
        'uses' => 'Auth\RegisterSalesRepController@confirmAccount',
        'as' => 'register.confirm.account' ));

    Route::get('fb', array(
        'uses' => 'Auth\FbSocialController@redirectToProvider',
        'as' => 'auth.social.fb' ));

    Route::get('fb/callback', array(
        'uses' => 'Auth\FbSocialController@handleProviderCallback',
        'as' => 'auth.social.fb.callback' ));

    /**
     * Cronofy
     */
    Route::get('cronofy/callback', array(
        'uses' => 'Auth\CronofyController@callback',
        'as' => 'auth.cronofy.callback' ));

    Route::get('cronofy/request-token', array(
        'uses' => 'Auth\CronofyController@callback',
        'as' => 'auth.cronofy.request.token' ));
});

/**
 * Public front end
 */
Route::group(array('namespace' => 'Frontend'), function(){

	Route::get('', array(
		'as' => 'front.home',
		'uses' => 'WelcomeController@home'));

});

/**
 * Debug
 */
Route::group(array('namespace' => 'Debug'), function(){

    /**
     * For development purpose
     */
    Route::get('debug', array(
        'uses' => 'DebugController@index'));

    Route::get('debug/debug-callback', array(
        'as' => 'debug.callback',
        'uses' => 'DebugController@callback'));

    Route::group(['middleware' => ['auth', 'admin-page'], 'prefix' => 'admin/debug'], function(){
        Route::get('customers/actions', array(
            'as' => 'debug.customer.actions',
            'uses' => 'CustomerController@actions'));

        Route::get('customers/actions/request/{type}/{customer_id}', array(
            'as' => 'debug.customer.action.request',
            'uses' => 'CustomerController@request'));

        Route::post('customers/actions/request/', array(
            'as' => 'debug.customer.action.request.send',
            'uses' => 'CustomerController@requestSend'));

        Route::get('fullcontact', array(
            'as' => 'debug.fullcontact',
            'uses' => 'FullContactController@index'));

        Route::get('wp-api', array(
            'as' => 'debug.wp.api',
            'uses' => 'WpApiController@index'));

        Route::post('wp-api/post', array(
            'as' => 'debug.wp.api.post.store',
            'uses' => 'WpApiController@postStore'));

        Route::post('wp-api/post/offer', array(
            'as' => 'debug.wp.api.offer.store',
            'uses' => 'WpApiController@offerStore'));

        Route::get('users', array(
            'as' => 'debug.admin.users',
            'uses' => 'UsersController@index'));

        Route::post('users/store/customer', array(
            'as' => 'debug.admin.users.customer.store',
            'uses' => 'UsersController@customerStore'));

        Route::post('users/store/salesrep', array(
            'as' => 'debug.admin.users.salesrep.store',
            'uses' => 'UsersController@salesrepStore'));

        Route::post('users/store/csr', array(
            'as' => 'debug.admin.users.csr.store',
            'uses' => 'UsersController@csrStore'));

        Route::get('api-tool', array(
            'as' => 'debug.admin.api.tool',
            'uses' => 'ApiTestToolController@index'));

        Route::get('ontraport', array(
            'as' => 'debug.admin.ontraport',
            'uses' => 'OntraportController@index'));

        Route::post('ontraport/salesrep/update', array(
            'as' => 'debug.admin.ontraport.salesrep.update',
            'uses' => 'OntraportController@salesrepUpdate'));
    });
});

/**
 * Web Admin pages
 */
Route::group(array('prefix' => 'admin', 'middleware' => array('auth', 'admin-page', 'role-only:csr|sales-rep'), 'namespace' => 'Admin'), function()
{

    Route::get('', array(
    	'as' => 'home',
    	'uses' => 'HomeController@index',
        'middleware' => 'salesrep-agreed'));//->middleware(['role-only:admin|sales-rep']);

    Route::get('welcome/brand-associate', array(
        'as' => 'admin.welcome.salesrep',
        'uses' => 'WelcomeSalesRepController@index'))
        ->middleware(['role-only:sales-rep', 'salesrep-agreed']);

    Route::group(array('prefix' => 'prospects', 'middleware' => 'salesrep-agreed'), function()
    {
        Route::get('unmatched-lead', array(
            'uses'       => 'UnmatchedLeadController@index',
            'as'         => 'admin.prospects.unmatched.lead',
            'middleware' => ['role-only:csr']));

        Route::get('/activity/{f?}', array(
            'uses' => 'ProspectsActivityController@index',
            'as' => 'admin.prospects.activity'));

        Route::get('leads', array(
            'uses' => 'ProspectsLeadsController@index',
            'as' => 'admin.prospects.leads' ));

        Route::get('', array(
            'uses' => 'ProspectsController@index',
            'as' => 'admin.prospects' ));

        Route::get('/{id}/edit', array(
            'uses' => 'ProspectsController@edit',
            'as' => 'admin.prospects.edit' ));

        Route::delete('/{id}/destroy', array(
            'uses' => 'ProspectsController@destroy',
            'as' => 'admin.prospects.destroy' ));

        Route::get('/{customer_id}', array(
            'uses' => 'ProspectsController@show',
            'as' => 'admin.prospects.show' ));

        Route::put('/{customer_id}/update', array(
            'uses' => 'ProspectsController@update',
            'as' => 'admin.prospects.update' ));

        Route::post('/{customer_id}/post-post', array(
            'uses' => 'ProspectsController@postNote',
            'as' => 'admin.prospects.post.note' ));

        Route::get('/{customer_id}/offers', array(
            'uses' => 'ProspectsController@offers',
            'as' => 'admin.prospects.offers' ));

        Route::get('/{customer_id}/delete', array(
            'uses' => 'ProspectsController@delete',
            'as' => 'admin.prospects.delete' ));
    });

    Route::post('media/upload', array(
        'uses' => 'MediaController@upload',
        'as' => 'admin.upload.submit' ));

    Route::get('media/show/{media_id}', array(
        'uses' => 'MediaController@show',
        'as' => 'admin.media.show' ));

    Route::group(array('prefix' => 'offers', 'middleware' => 'salesrep-agreed'), function()
    {
        Route::get('/create', array(
            'uses' => 'OfferController@create',
            'as' => 'admin.offers.create' ));

        Route::get('/{author_type?}', array(
            'uses' => 'OfferController@index',
            'as' => 'admin.offers' ));

        Route::post('/store', array(
            'uses' => 'OfferController@store',
            'as' => 'admin.offers.store' ));

        Route::get('/edit/{offer_id}', array(
            'uses' => 'OfferController@edit',
            'as' => 'admin.offers.edit' ));

        Route::put('/edit/{offer_id}', array(
            'uses' => 'OfferController@update',
            'as' => 'admin.offers.update' ));

        Route::get('/delete/{offer_id}', array(
            'uses' => 'OfferController@destroy',
            'as' => 'admin.offers.destroy' ));
    });

    Route::group(array('prefix' => 'messages', 'middleware' => 'salesrep-agreed'), function()
    {
        Route::get('/create', array(
            'uses' => 'MessageNewController@index',
            'as' => 'admin.messages.create' ));

        Route::get('/draft/{thread_id}/{message_id}', array(
            'uses' => 'MessageNewController@continueDraft',
            'as' => 'admin.messages.draft.continue' ));

        Route::put('/draft/{message_id}', array(
            'uses' => 'MessageNewController@continueDraftSend',
            'as' => 'admin.messages.direct.continue.send' ));

        Route::post('/create', array(
            'uses' => 'MessageNewController@send',
            'as' => 'admin.messages.direct.send' ));

        Route::get('/sent', array(
            'uses' => 'MessageController@sent',
            'as' => 'admin.messages.sent' ));

        Route::get('/draft', array(
            'uses' => 'MessageController@draft',
            'as' => 'admin.messages.draft' ));

        Route::get('/{type?}', array(
            'uses' => 'MessageController@index',
            'as' => 'admin.messages' ));

        Route::get('/{thread_id}/show/{message_id?}', array(
            'uses' => 'MessageController@show',
            'as' => 'admin.messages.show' ));

        Route::get('/print/{message_id?}', array(
            'uses' => 'MessageController@printEmail',
            'as' => 'admin.messages.show.print' ));

        Route::get('/{thread_id}/delete', array(
            'uses' => 'MessageController@delete',
            'as' => 'admin.messages.delete' ));

        Route::post('/{thread_id}/reply/', array(
            'uses' => 'MessageController@reply',
            'as' => 'admin.messages.reply' ));

        Route::get('/{thread_id}/delete-multi', array(
            'uses' => 'MessageController@deleteMulti',
            'as' => 'admin.messages.delete.multi' ));

    });

    Route::group(array('prefix' => 'settings', 'namespace' => 'Settings'), function()
    {
        Route::get('profile', array(
            'uses' => 'ProfileController@index',
            'middleware' => 'salesrep-agreed',
            'as' => 'admin.settings.profile' ));

        Route::post('profile/brand-associate', array(
            'uses' => 'ProfileController@save',
            'as' => 'admin.settings.profile.save.salesrep' ));

        Route::post('profile/csr', array(
            'uses' => 'ProfileController@csrSave',
            'as' => 'admin.settings.profile.save.csr' ));

        Route::get('ba-agree-terms', array(
            'uses' => 'SalesRepProfileController@acceptAgreement',
            'as' => 'admin.settings.salesrep.agreement',
            'middleware' => ['role-only:sales-rep']));

        Route::get('change-password', array(
            'uses' => 'ChangePasswordController@index',
            'as' => 'admin.settings.change.password' ));

        Route::post('save-password', array(
            'uses' => 'ChangePasswordController@save',
            'as' => 'admin.settings.save.password' ));

        Route::get('notifications', array(
            'uses' => 'NotificationController@index',
            'as' => 'admin.settings.notifications' ));

        Route::post('notifications', array(
            'uses' => 'NotificationController@save',
            'as' => 'admin.settings.notifications.save' ));

    });

    Route::group(array('prefix' => 'api', 'namespace' => 'Api'), function()
    {
        Route::get('contacts', [
            'as' => 'admin.api.contacts',
            'uses' => 'ContactsController@index']);

        Route::group(['prefix' => 'messages'], function()
        {
            Route::get('/sent', array(
                'uses' => 'MessageController@sent',
                'as' => 'admin.api.messages.sent' ));

            Route::get('/draft', array(
                'uses' => 'MessageController@draft',
                'as' => 'admin.api.messages.draft' ));

            Route::get('/new-leads', array(
                'uses' => 'MessageController@newLeads',
                'as' => 'admin.api.messages.new_leads' ));

            Route::post('/accept-new-lead', array(
                'uses' => 'ProspectController@acceptLead',
                'as' => 'admin.api.accept.lead' ));

            Route::post('/reject-lead', array(
                'uses' => 'ProspectController@rejectLead',
                'as' => 'admin.api.reject.lead' ));

            Route::get('/{type?}', array(
                'uses' => 'MessageController@index',
                'as' => 'admin.api.messages' ));

            Route::post('/create/validation', array(
                'uses' => 'MesssageCreateValidationController@index',
                'as' => 'admin.api.messages.create.validation' ));

        });

        Route::group(['prefix' => 'offers'], function()
        {
            Route::get('/', array(
                'uses' => 'OfferController@index',
                'as' => 'admin.api.offers' ));
        });

        /**
         * Prospect related
         */
        Route::group(['prefix' => 'prospects'], function()
        {
            Route::get('/name-email', array(
                'uses' => 'ProspectController@nameEmail',
                'as' => 'admin.api.prospect.name_email' ));

            Route::post('/change-ba', array(
                'uses' => 'ProspectController@setProspectToSalesrep',
                'as' => 'admin.api.prospect.change_ba' ));

            Route::get('/activities', array(
                'uses' => 'CustomerActivityController@index',
                'as' => 'admin.api.prospect.activities' ));

            Route::get('/ba-assignment/{customer_id}', array(
                'uses' => 'CustomerBaAssignmentController@index',
                'as' => 'admin.api.prospect.ba.assignment' ));

        });

        // End Prospect related

        Route::post('/option/set-tz', array(
            'uses' => 'OptionController@setTz',
            'as' => 'admin.api.option.set.tz' ));

        Route::get('dealers-category', array(
            'uses' => 'DealersCategoryController@index',
            'as' => 'admin.api.dealers.categories'));

        Route::group(['prefix' => 'sidebar/counter'], function()
        {
            Route::get('all-over-messages', array(
                'uses' => 'SidebarItemCountController@messsageAllOver',
                'as' => 'admin.api.sidebar.counter.messages.all-over' ));

            Route::get('all-messages', array(
                'uses' => 'SidebarItemCountController@messsageAll',
                'as' => 'admin.api.sidebar.counter.messages.all' ));

            Route::get('appt', array(
                'uses' => 'SidebarItemCountController@messsageAppt',
                'as' => 'admin.api.sidebar.counter.messages.appt' ));

            Route::get('direct', array(
                'uses' => 'SidebarItemCountController@messsageDirect',
                'as' => 'admin.api.sidebar.counter.messages.direct' ));

            Route::get('price', array(
                'uses' => 'SidebarItemCountController@messsagePrice',
                'as' => 'admin.api.sidebar.counter.messages.price' ));

            Route::get('info', array(
                'uses' => 'SidebarItemCountController@messsageInfo',
                'as' => 'admin.api.sidebar.counter.messages.info' ));

            Route::get('contact_me', array(
                'uses' => 'SidebarItemCountController@messsageContactMe',
                'as' => 'admin.api.sidebar.counter.messages.contact_me' ));

            Route::get('prospect', array(
                'uses' => 'SidebarItemCountController@prospect',
                'as' => 'admin.api.sidebar.counter.prospect' ));

            Route::get('prospect/new-leads', array(
                'uses' => 'SidebarItemCountController@newProspect',
                'as' => 'admin.api.sidebar.counter.new.prospect' ));

        });
    });

    Route::group(array('prefix' => 'brand-associate', 'middleware' => ['role-only:csr']), function()
    {
        Route::get('invite-new', array(
            'uses' => 'InviteBaController@index',
            'as' => 'admin.brand.associate.invite'));

        Route::post('invite-new', array(
            'uses' => 'InviteBaController@send',
            'as' => 'admin.brand.associate.invite.send'));

        Route::group(array('prefix' => '', 'namespace' => 'SalesRep'), function()
        {
            Route::get('', array(
                'as' => 'admin.salesrep',
                'uses' => 'SalesRepController@index'));

            Route::get('{salesrep_id}/profile', array(
                'as' => 'admin.salesrep.show',
                'uses' => 'SalesRepController@show'));

            Route::put('{salesrep_id}/profile', array(
                'as' => 'admin.salesrep.update',
                'uses' => 'SalesRepController@update'));
        });

    });

    Route::group(array('prefix' => 'brands', 'namespace' => 'Brands', 'middleware' => ['role-only:csr']), function()
    {
        Route::get('create', array(
            'as' => 'admin.brands.create',
            'uses' => 'BrandsController@create'));

        Route::post('store', array(
            'as' => 'admin.brands.store',
            'uses' => 'BrandsController@store'));

        Route::get('', array(
            'as' => 'admin.brands',
            'uses' => 'BrandsController@index'));

        Route::get('{id}/edit', array(
            'as' => 'admin.brands.edit',
            'uses' => 'BrandsController@edit'));

        Route::put('{id}/update', array(
            'as' => 'admin.brands.update',
            'uses' => 'BrandsController@update'));

        Route::get('{id}/destroy', array(
            'as' => 'admin.brands.delete',
            'uses' => 'BrandsController@destroy'));
    });

    Route::group(array('prefix' => 'dealers', 'namespace' => 'Dealers', 'middleware' => ['role-only:csr']), function()
    {
        Route::get('create', array(
            'as' => 'admin.dealers.create',
            'uses' => 'DealersController@create'));

        Route::post('store', array(
            'as' => 'admin.dealers.store',
            'uses' => 'DealersController@store'));

        Route::get('', array(
            'as' => 'admin.dealers',
            'uses' => 'DealersController@index'));

        Route::get('{dealer_id}/edit', array(
            'as' => 'admin.dealers.edit',
            'uses' => 'DealersController@edit'));

        Route::put('{dealer_id}/update', array(
            'as' => 'admin.dealers.update',
            'uses' => 'DealersController@update'));

        Route::get('{id}/destroy', array(
            'as' => 'admin.dealers.delete',
            'uses' => 'DealersController@destroy'));
    });

    Route::group(array('prefix' => 'categories', 'namespace' => 'Categories', 'middleware' => ['role-only:csr']), function()
    {
        Route::get('', array(
            'as' => 'admin.categories',
            'uses' => 'CategoriesController@index'));

        Route::get('{category_id}/edit', array(
            'as' => 'admin.categories.edit',
            'uses' => 'CategoriesController@index'));

        Route::put('{category_id}/update', array(
            'as' => 'admin.categories.update',
            'uses' => 'CategoriesController@update'));

        Route::get('{category_id}/show', array(
            'as' => 'admin.categories.show',
            'uses' => 'CategoriesController@show'));

        Route::post('', array(
            'as' => 'admin.categories.store',
            'uses' => 'CategoriesController@store'));

        Route::get('{category_id}/delete/confirm', array(
            'as' => 'admin.categories.destroy.confirm',
            'uses' => 'CategoriesController@confirmDestroy'));

        Route::get('{category_id}/delete', array(
            'as' => 'admin.categories.destroy',
            'uses' => 'CategoriesController@destroy'));
    });

});

Route::group(array('prefix' => 'api', 'namespace' => 'Api'), function()
{
    /**
     * OAuth related
     */
    Route::group(array('prefix' => 'oauth'), function()
    {
        Route::post('request-token', array(
            'as' => 'oauth.request-token',
            'uses' => 'OAuthController@getClientCredentialsToken'
        ));
    });

    Route::any('error', array(
        'as' => 'api.errors',
        'uses' => 'ErrorsController@index'
    ));

    /**
     * Secure API V1
     */
    Route::group(array('prefix' => 'v1', 'middleware' => ['oauth']), function()
    {

        Route::get('users', array(
            'as'           => 'api.v1.users',
            'uses'         => 'UsersController@index'));

        Route::post('users/store', array(
            'as'           => 'api.v1.users.store',
            'uses'         => 'UsersController@store'));

        /**
         * Dealer
         */
        Route::get('dealers', array(
            'as'           => 'api.v1.dealers',
            'uses'         => 'DealersController@index'));

        Route::get('dealer/{id}', array(
            'as'           => 'api.v1.dealers.show',
            'uses'         => 'DealersController@show'));


        Route::post('dealer-location', array(
            'as'           => 'api.v1.dealers.show',
            'uses'         => 'DealersController@dealerLocation'));


        Route::get('dealer/{id}/brands', array(
            'as'           => 'api.v1.dealers.show.brands',
            'uses'         => 'DealersController@brands'));

        Route::get('dealer/{id}/brand-associates', array(
            'as'           => 'api.v1.dealers.show.brands-associates',
            'uses'         => 'DealersController@salesReps'));

        //End Dealer

        Route::get('brands', array(
            'as'           => 'api.v1.brands',
            'uses'         => 'BrandsController@index'));

        Route::get('brand/{id}', array(
            'as'           => 'api.v1.brands.show',
            'uses'         => 'BrandsController@show'));

        /**
         * Offer routes
         */
        Route::get('offers', array(
            'as'           => 'api.v1.offers',
            'uses'         => 'OffersController@index'));

        Route::get('offer/{id}', array(
            'as'           => 'api.v1.offers.show',
            'uses'         => 'OffersController@show'));

        Route::post('individual-offer', array(
            'as'           => 'api.v1.offers.showOffer',
            'uses'         => 'OffersController@showOffer'));

        Route::post('offer', array(
            'as'           => 'api.v1.offers.store',
            'uses'         => 'OffersController@store'));

        Route::delete('offer', array(
            'as'           => 'api.v1.offers.delete',
            'uses'         => 'OffersController@destroy'));

        Route::put('offer', array(
            'as'           => 'api.v1.offers.update',
            'uses'         => 'OffersController@update'));
        //End Offer

        /**
         * Customer routes
         */
        Route::get('customers', array(
            'as'           => 'api.v1.customers',
            'uses'         => 'CustomerController@index'));

        Route::get('customer/{id}', array(
            'as'           => 'api.v1.customers.show',
            'uses'         => 'CustomerController@show'));

        Route::get('customer/{id}/brand-associates', array(
            'as'           => 'api.v1.customers.brands-associates',
            'uses'         => 'CustomerController@salesReps'));

         Route::post('customer/brand-associates', array(
            'as'           => 'api.v1.customers.brand-associates-list',
            'uses'         => 'CustomerController@viewSalesReps'));

        Route::get('customer/lookbook/offers', array(
            'as'           => 'api.v1.customers.lookbook.offers',
            'uses'         => 'CustomerController@offers'));

        Route::post('customer/lookbook/offer', array(
            'as'           => 'api.v1.customers.offer.lookbook.add',
            'uses'         => 'CustomerController@addOffer'));

        Route::delete('customer/lookbook/offer', array(
            'as'           => 'api.v1.customers.offer.delete',
            'uses'         => 'CustomerController@deleteOffer'));

        Route::post('customer', array(
            'as'           => 'api.v1.customers.store',
            'uses'         => 'CustomerController@store'));
        Route::post('customer/social-registration', array(
            'as'           => 'api.v1.customers.social-registration',
            'uses'         => 'CustomerController@storeSocialRegistration'));

        Route::put('customer', array(
            'as'           => 'api.v1.customers.update',
            'uses'         => 'CustomerController@update'));

        Route::delete('customer', array(
            'as'           => 'api.v1.customers.destroy',
            'uses'         => 'CustomerController@destroy'));

        Route::post('customer/login', array(
            'as'           => 'api.v1.customers.login',
            'uses'         => 'CustomerController@doCustomerLogin'));

        Route::post('customer/social-login', array(
            'as'           => 'api.v1.customers.social-login',
            'uses'         => 'CustomerController@doCustomerSocialLogin'));

        Route::post('customer/logout', array(
            'as'           => 'api.v1.customers.logout',
            'uses'         => 'CustomerController@doCustomerLogout'));

        Route::post('customer/forgot-password', array(
            'as'           => 'api.v1.customers.forgot-password',
            'uses'         => 'CustomerController@forgotPassword'));
        Route::post('customer/new-password', array(
            'as'           => 'api.v1.customers.new-password',
            'uses'         => 'CustomerController@newPassword'));
        Route::post('customer/change-password', array(
            'as'           => 'api.v1.customers.change-password',
            'uses'         => 'CustomerController@changePassword'));

        Route::post('customer/upload-avatar', array(
            'as'           => 'api.v1.customers.upload-avatar',
            'uses'         => 'CustomerController@uploadAvatar'));
        Route::post('customer/avatars', array(
            'as'           => 'api.v1.customers.avatars',
            'uses'         => 'CustomerController@avatars'));
        Route::post('customer/change-avatar', array(
            'as'           => 'api.v1.customers.change-avatar',
            'uses'         => 'CustomerController@changeAvatar'));

        Route::post('customer/share-offer', array(
            'as'           => 'api.v1.customers.share-offer',
            'uses'         => 'CustomerController@shareOffer'));

        Route::post('customer/my-offers', array(
            'as'           => 'api.v1.customers.my-offer',
            'uses'         => 'CustomerController@myOffers'));
        Route::post('customer/my-dealers', array(
            'as'           => 'api.v1.customers.my-offer',
            'uses'         => 'CustomerController@myDealers'));



        Route::group(array('prefix' => 'customer/notification'), function(){

            Route::post('global', array(
                'as'           => 'api.v1.customers.notification.global',
                'uses'         => 'CustomerNotificationSettingController@createGlobal'));


            Route::post('national', array(
                'as'           => 'api.v1.customers.notification.brand',
                'uses'         => 'CustomerNotificationSettingController@createBrand'));

            // Route::get('brands', array(
            //     'as'           => 'api.v1.customers.notification.brands',
            //     'uses'         => 'CustomerNotificationSettingController@indexBrand'));

            Route::post('ba', array(
                'as'           => 'api.v1.customers.notification.brand-associate',
                'uses'         => 'CustomerNotificationSettingController@createBrandAssociate'));

            // Route::post('brand-associates', array(
            //     'as'           => 'api.v1.customers.notification.brand-associates',
            //     'uses'         => 'CustomerNotificationSettingController@indexBrandAssociate'));
            });

        /**
         * Brand associates (Sales Rep)
         */
        Route::get('brand-associates/', array(
            'as'           => 'api.v1.brands-associates',
            'uses'         => 'SalesRepController@index'));

        Route::get('brand-associate/{id}', array(
            'as'           => 'api.v1.brands-associates.show',
            'uses'         => 'SalesRepController@show'));

        Route::get('brand-associate/{id}/customers', array(
            'as'           => 'api.v1.brands-associates.customers',
            'uses'         => 'SalesRepController@customers'));

        Route::get('brand-associate/{id}/customers', array(
            'as'           => 'api.v1.brands-associates.customers',
            'uses'         => 'SalesRepController@customers'));

        /**
         * Message
         */

        Route::group(array('prefix' => 'messages'), function()
        {
            Route::group(array('prefix' => 'direct-message'), function()
            {
                Route::get('', array(
                    'uses'  => 'DirectMessageController@index',
                    'as'    => 'admin.api.messages.direct' ));

                Route::get('', array(
                    'uses'  => 'DirectMessageController@sent',
                    'as'    => 'admin.api.messages.direct.sent' ));

                Route::get('show', array(
                    'uses'  => 'DirectMessageController@show',
                    'as'    => 'admin.api.messages.direct.show' ));

                Route::post('mark-as-read', array(
                    'uses'  => 'DirectMessageController@markAsRead',
                    'as'    => 'admin.api.messages.direct.mark.read' ));

                Route::post('store', array(
                    'uses'  => 'DirectMessageController@store',
                    'as'    => 'admin.api.messages.direct.store' ));
            });

            Route::group(array('prefix' => 'request/price'), function()
            {
                Route::get('', array(
                    'uses'  => 'RequestPriceController@index',
                    'as'    => 'admin.api.messages.request.price' ));

                Route::get('{id}', array(
                    'uses'  => 'RequestPriceController@show',
                    'as'    => 'admin.api.messages.request.price.show' ));

                Route::post('store', array(
                    'uses'  => 'RequestPriceController@store',
                    'as'    => 'admin.api.messages.request.price.store' ));
            });

            Route::group(array('prefix' => 'request/info'), function()
            {
                Route::get('', array(
                    'uses'  => 'RequestInfoController@index',
                    'as'    => 'admin.api.messages.request.info' ));

                Route::get('{id}', array(
                    'uses'  => 'RequestInfoController@show',
                    'as'    => 'admin.api.messages.request.info.show' ));

                // Route::get('view', array(
                //     'uses'  => 'RequestInfoController@view',
                //     'as'    => 'admin.api.messages.request.info.view' ));

                Route::post('store', array(
                    'uses'  => 'RequestInfoController@store',
                    'as'    => 'admin.api.messages.request.info.store' ));
            });

            Route::group(array('prefix' => 'request/appt'), function()
            {
                Route::get('', array(
                    'uses'  => 'RequestApptController@index',
                    'as'    => 'admin.api.messages.request.appt' ));

                Route::get('{id}', array(
                    'uses'  => 'RequestApptController@show',
                    'as'    => 'admin.api.messages.request.appt.show' ));

                Route::post('store', array(
                    'uses'  => 'RequestApptController@store',
                    'as'    => 'admin.api.messages.request.appt.store' ));
            });

            Route::group(array('prefix' => 'request/contact_me'), function()
            {
                Route::get('', array(
                    'uses'  => 'RequestContactController@index',
                    'as'    => 'admin.api.messages.request.contact_me' ));

                Route::get('{id}', array(
                    'uses'  => 'RequestContactController@show',
                    'as'    => 'admin.api.messages.request.contact_me.show' ));

                Route::post('store', array(
                    'uses'  => 'RequestContactController@store',
                    'as'    => 'admin.api.messages.request.contact_me.store' ));
            });
        });
    });
});
