(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://xsellcast.local',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"lbt\/offer\/{wp_post_id}","name":"lbt.offer","action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/login","name":"auth.login","action":"App\Http\Controllers\Auth\AuthController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"auth\/login","name":"login.post","action":"App\Http\Controllers\Auth\AuthController@login"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/password\/reset\/{token?}","name":"forgotpassword","action":"App\Http\Controllers\Auth\PasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"auth\/password\/reset","name":"forgotpassword.reset","action":"App\Http\Controllers\Auth\PasswordController@postReset"},{"host":null,"methods":["POST"],"uri":"auth\/password\/email","name":"forgotpassword.post","action":"App\Http\Controllers\Auth\PasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/logout","name":"auth.logout","action":"App\Http\Controllers\Auth\LogoutController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/register","name":"register","action":"App\Http\Controllers\Auth\RegisterSalesRepController@showRegistrationForm"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/register\/cancel","name":"register.cancel","action":"App\Http\Controllers\Auth\FbSocialController@cancelRegister"},{"host":null,"methods":["POST"],"uri":"auth\/register","name":"register.salesrep.store_account","action":"App\Http\Controllers\Auth\RegisterSalesRepController@storeAccount"},{"host":null,"methods":["POST"],"uri":"auth\/register\/brands-associates\/store-dealer","name":"register.salesrep.store_dealer","action":"App\Http\Controllers\Auth\RegisterSalesRepController@storeDealer"},{"host":null,"methods":["POST"],"uri":"auth\/register\/brands-associates\/store-social-profile","name":"register.salesrep.store_socialprofile","action":"App\Http\Controllers\Auth\RegisterSalesRepController@storeSocialProfile"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/register\/confirm-account\/{token}","name":"register.confirm.account","action":"App\Http\Controllers\Auth\RegisterSalesRepController@confirmAccount"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/fb","name":"auth.social.fb","action":"App\Http\Controllers\Auth\FbSocialController@redirectToProvider"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/fb\/callback","name":"auth.social.fb.callback","action":"App\Http\Controllers\Auth\FbSocialController@handleProviderCallback"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/cronofy\/callback","name":"auth.cronofy.callback","action":"App\Http\Controllers\Auth\CronofyController@callback"},{"host":null,"methods":["GET","HEAD"],"uri":"auth\/cronofy\/request-token","name":"auth.cronofy.request.token","action":"App\Http\Controllers\Auth\CronofyController@callback"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"front.home","action":"App\Http\Controllers\Frontend\WelcomeController@home"},{"host":null,"methods":["GET","HEAD"],"uri":"debug","name":null,"action":"App\Http\Controllers\Debug\DebugController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"debug\/debug-callback","name":"debug.callback","action":"App\Http\Controllers\Debug\DebugController@callback"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/customers\/actions","name":"debug.customer.actions","action":"App\Http\Controllers\Debug\CustomerController@actions"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/customers\/actions\/request\/{type}\/{customer_id}","name":"debug.customer.action.request","action":"App\Http\Controllers\Debug\CustomerController@request"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/customers\/actions\/request","name":"debug.customer.action.request.send","action":"App\Http\Controllers\Debug\CustomerController@requestSend"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/fullcontact","name":"debug.fullcontact","action":"App\Http\Controllers\Debug\FullContactController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/wp-api","name":"debug.wp.api","action":"App\Http\Controllers\Debug\WpApiController@index"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/wp-api\/post","name":"debug.wp.api.post.store","action":"App\Http\Controllers\Debug\WpApiController@postStore"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/wp-api\/post\/offer","name":"debug.wp.api.offer.store","action":"App\Http\Controllers\Debug\WpApiController@offerStore"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/users","name":"debug.admin.users","action":"App\Http\Controllers\Debug\UsersController@index"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/users\/store\/customer","name":"debug.admin.users.customer.store","action":"App\Http\Controllers\Debug\UsersController@customerStore"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/users\/store\/salesrep","name":"debug.admin.users.salesrep.store","action":"App\Http\Controllers\Debug\UsersController@salesrepStore"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/users\/store\/csr","name":"debug.admin.users.csr.store","action":"App\Http\Controllers\Debug\UsersController@csrStore"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/api-tool","name":"debug.admin.api.tool","action":"App\Http\Controllers\Debug\ApiTestToolController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/debug\/ontraport","name":"debug.admin.ontraport","action":"App\Http\Controllers\Debug\OntraportController@index"},{"host":null,"methods":["POST"],"uri":"admin\/debug\/ontraport\/salesrep\/update","name":"debug.admin.ontraport.salesrep.update","action":"App\Http\Controllers\Debug\OntraportController@salesrepUpdate"},{"host":null,"methods":["GET","HEAD"],"uri":"admin","name":"home","action":"App\Http\Controllers\Admin\HomeController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/welcome\/brand-associate","name":"admin.welcome.salesrep","action":"App\Http\Controllers\Admin\WelcomeSalesRepController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/unmatched-lead","name":"admin.prospects.unmatched.lead","action":"App\Http\Controllers\Admin\UnmatchedLeadController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/activity\/{f?}","name":"admin.prospects.activity","action":"App\Http\Controllers\Admin\ProspectsActivityController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/leads","name":"admin.prospects.leads","action":"App\Http\Controllers\Admin\ProspectsLeadsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects","name":"admin.prospects","action":"App\Http\Controllers\Admin\ProspectsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/{id}\/edit","name":"admin.prospects.edit","action":"App\Http\Controllers\Admin\ProspectsController@edit"},{"host":null,"methods":["DELETE"],"uri":"admin\/prospects\/{id}\/destroy","name":"admin.prospects.destroy","action":"App\Http\Controllers\Admin\ProspectsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/{customer_id}","name":"admin.prospects.show","action":"App\Http\Controllers\Admin\ProspectsController@show"},{"host":null,"methods":["PUT"],"uri":"admin\/prospects\/{customer_id}\/update","name":"admin.prospects.update","action":"App\Http\Controllers\Admin\ProspectsController@update"},{"host":null,"methods":["POST"],"uri":"admin\/prospects\/{customer_id}\/post-post","name":"admin.prospects.post.note","action":"App\Http\Controllers\Admin\ProspectsController@postNote"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/{customer_id}\/offers","name":"admin.prospects.offers","action":"App\Http\Controllers\Admin\ProspectsController@offers"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/prospects\/{customer_id}\/delete","name":"admin.prospects.delete","action":"App\Http\Controllers\Admin\ProspectsController@delete"},{"host":null,"methods":["POST"],"uri":"admin\/media\/upload","name":"admin.upload.submit","action":"App\Http\Controllers\Admin\MediaController@upload"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/media\/show\/{media_id}","name":"admin.media.show","action":"App\Http\Controllers\Admin\MediaController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/offers\/create","name":"admin.offers.create","action":"App\Http\Controllers\Admin\OfferController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/offers\/{author_type?}","name":"admin.offers","action":"App\Http\Controllers\Admin\OfferController@index"},{"host":null,"methods":["POST"],"uri":"admin\/offers\/store","name":"admin.offers.store","action":"App\Http\Controllers\Admin\OfferController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/offers\/edit\/{offer_id}","name":"admin.offers.edit","action":"App\Http\Controllers\Admin\OfferController@edit"},{"host":null,"methods":["PUT"],"uri":"admin\/offers\/edit\/{offer_id}","name":"admin.offers.update","action":"App\Http\Controllers\Admin\OfferController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/offers\/delete\/{offer_id}","name":"admin.offers.destroy","action":"App\Http\Controllers\Admin\OfferController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/create","name":"admin.messages.create","action":"App\Http\Controllers\Admin\MessageNewController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/draft\/{thread_id}\/{message_id}","name":"admin.messages.draft.continue","action":"App\Http\Controllers\Admin\MessageNewController@continueDraft"},{"host":null,"methods":["PUT"],"uri":"admin\/messages\/draft\/{message_id}","name":"admin.messages.direct.continue.send","action":"App\Http\Controllers\Admin\MessageNewController@continueDraftSend"},{"host":null,"methods":["POST"],"uri":"admin\/messages\/create","name":"admin.messages.direct.send","action":"App\Http\Controllers\Admin\MessageNewController@send"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/sent","name":"admin.messages.sent","action":"App\Http\Controllers\Admin\MessageController@sent"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/draft","name":"admin.messages.draft","action":"App\Http\Controllers\Admin\MessageController@draft"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/{type?}","name":"admin.messages","action":"App\Http\Controllers\Admin\MessageController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/{thread_id}\/show\/{message_id?}","name":"admin.messages.show","action":"App\Http\Controllers\Admin\MessageController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/print\/{message_id?}","name":"admin.messages.show.print","action":"App\Http\Controllers\Admin\MessageController@printEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/{thread_id}\/delete","name":"admin.messages.delete","action":"App\Http\Controllers\Admin\MessageController@delete"},{"host":null,"methods":["POST"],"uri":"admin\/messages\/{thread_id}\/reply","name":"admin.messages.reply","action":"App\Http\Controllers\Admin\MessageController@reply"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/messages\/{thread_id}\/delete-multi","name":"admin.messages.delete.multi","action":"App\Http\Controllers\Admin\MessageController@deleteMulti"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/settings\/profile","name":"admin.settings.profile","action":"App\Http\Controllers\Admin\Settings\ProfileController@index"},{"host":null,"methods":["POST"],"uri":"admin\/settings\/profile\/brand-associate","name":"admin.settings.profile.save.salesrep","action":"App\Http\Controllers\Admin\Settings\ProfileController@save"},{"host":null,"methods":["POST"],"uri":"admin\/settings\/profile\/csr","name":"admin.settings.profile.save.csr","action":"App\Http\Controllers\Admin\Settings\ProfileController@csrSave"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/settings\/ba-agree-terms","name":"admin.settings.salesrep.agreement","action":"App\Http\Controllers\Admin\Settings\SalesRepProfileController@acceptAgreement"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/settings\/change-password","name":"admin.settings.change.password","action":"App\Http\Controllers\Admin\Settings\ChangePasswordController@index"},{"host":null,"methods":["POST"],"uri":"admin\/settings\/save-password","name":"admin.settings.save.password","action":"App\Http\Controllers\Admin\Settings\ChangePasswordController@save"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/settings\/notifications","name":"admin.settings.notifications","action":"App\Http\Controllers\Admin\Settings\NotificationController@index"},{"host":null,"methods":["POST"],"uri":"admin\/settings\/notifications","name":"admin.settings.notifications.save","action":"App\Http\Controllers\Admin\Settings\NotificationController@save"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/contacts","name":"admin.api.contacts","action":"App\Http\Controllers\Admin\Api\ContactsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/messages\/sent","name":"admin.api.messages.sent","action":"App\Http\Controllers\Admin\Api\MessageController@sent"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/messages\/draft","name":"admin.api.messages.draft","action":"App\Http\Controllers\Admin\Api\MessageController@draft"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/messages\/new-leads","name":"admin.api.messages.new_leads","action":"App\Http\Controllers\Admin\Api\MessageController@newLeads"},{"host":null,"methods":["POST"],"uri":"admin\/api\/messages\/accept-new-lead","name":"admin.api.accept.lead","action":"App\Http\Controllers\Admin\Api\ProspectController@acceptLead"},{"host":null,"methods":["POST"],"uri":"admin\/api\/messages\/reject-lead","name":"admin.api.reject.lead","action":"App\Http\Controllers\Admin\Api\ProspectController@rejectLead"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/messages\/{type?}","name":"admin.api.messages","action":"App\Http\Controllers\Admin\Api\MessageController@index"},{"host":null,"methods":["POST"],"uri":"admin\/api\/messages\/create\/validation","name":"admin.api.messages.create.validation","action":"App\Http\Controllers\Admin\Api\MesssageCreateValidationController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/offers","name":"admin.api.offers","action":"App\Http\Controllers\Admin\Api\OfferController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/prospects\/name-email","name":"admin.api.prospect.name_email","action":"App\Http\Controllers\Admin\Api\ProspectController@nameEmail"},{"host":null,"methods":["POST"],"uri":"admin\/api\/prospects\/change-ba","name":"admin.api.prospect.change_ba","action":"App\Http\Controllers\Admin\Api\ProspectController@setProspectToSalesrep"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/prospects\/activities","name":"admin.api.prospect.activities","action":"App\Http\Controllers\Admin\Api\CustomerActivityController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/prospects\/ba-assignment\/{customer_id}","name":"admin.api.prospect.ba.assignment","action":"App\Http\Controllers\Admin\Api\CustomerBaAssignmentController@index"},{"host":null,"methods":["POST"],"uri":"admin\/api\/option\/set-tz","name":"admin.api.option.set.tz","action":"App\Http\Controllers\Admin\Api\OptionController@setTz"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/dealers-category","name":"admin.api.dealers.categories","action":"App\Http\Controllers\Admin\Api\DealersCategoryController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/all-over-messages","name":"admin.api.sidebar.counter.messages.all-over","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsageAllOver"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/all-messages","name":"admin.api.sidebar.counter.messages.all","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsageAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/appt","name":"admin.api.sidebar.counter.messages.appt","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsageAppt"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/direct","name":"admin.api.sidebar.counter.messages.direct","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsageDirect"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/price","name":"admin.api.sidebar.counter.messages.price","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsagePrice"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/info","name":"admin.api.sidebar.counter.messages.info","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsageInfo"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/contact_me","name":"admin.api.sidebar.counter.messages.contact_me","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@messsageContactMe"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/prospect","name":"admin.api.sidebar.counter.prospect","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@prospect"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/api\/sidebar\/counter\/prospect\/new-leads","name":"admin.api.sidebar.counter.new.prospect","action":"App\Http\Controllers\Admin\Api\SidebarItemCountController@newProspect"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brand-associate\/invite-new","name":"admin.brand.associate.invite","action":"App\Http\Controllers\Admin\InviteBaController@index"},{"host":null,"methods":["POST"],"uri":"admin\/brand-associate\/invite-new","name":"admin.brand.associate.invite.send","action":"App\Http\Controllers\Admin\InviteBaController@send"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brand-associate","name":"admin.salesrep","action":"App\Http\Controllers\Admin\SalesRep\SalesRepController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brand-associate\/{salesrep_id}\/profile","name":"admin.salesrep.show","action":"App\Http\Controllers\Admin\SalesRep\SalesRepController@show"},{"host":null,"methods":["PUT"],"uri":"admin\/brand-associate\/{salesrep_id}\/profile","name":"admin.salesrep.update","action":"App\Http\Controllers\Admin\SalesRep\SalesRepController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brands\/create","name":"admin.brands.create","action":"App\Http\Controllers\Admin\Brands\BrandsController@create"},{"host":null,"methods":["POST"],"uri":"admin\/brands\/store","name":"admin.brands.store","action":"App\Http\Controllers\Admin\Brands\BrandsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brands","name":"admin.brands","action":"App\Http\Controllers\Admin\Brands\BrandsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brands\/{id}\/edit","name":"admin.brands.edit","action":"App\Http\Controllers\Admin\Brands\BrandsController@edit"},{"host":null,"methods":["PUT"],"uri":"admin\/brands\/{id}\/update","name":"admin.brands.update","action":"App\Http\Controllers\Admin\Brands\BrandsController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/brands\/{id}\/destroy","name":"admin.brands.delete","action":"App\Http\Controllers\Admin\Brands\BrandsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/dealers\/create","name":"admin.dealers.create","action":"App\Http\Controllers\Admin\Dealers\DealersController@create"},{"host":null,"methods":["POST"],"uri":"admin\/dealers\/store","name":"admin.dealers.store","action":"App\Http\Controllers\Admin\Dealers\DealersController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/dealers","name":"admin.dealers","action":"App\Http\Controllers\Admin\Dealers\DealersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/dealers\/{dealer_id}\/edit","name":"admin.dealers.edit","action":"App\Http\Controllers\Admin\Dealers\DealersController@edit"},{"host":null,"methods":["PUT"],"uri":"admin\/dealers\/{dealer_id}\/update","name":"admin.dealers.update","action":"App\Http\Controllers\Admin\Dealers\DealersController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/dealers\/{id}\/destroy","name":"admin.dealers.delete","action":"App\Http\Controllers\Admin\Dealers\DealersController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/categories","name":"admin.categories","action":"App\Http\Controllers\Admin\Categories\CategoriesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/categories\/{category_id}\/edit","name":"admin.categories.edit","action":"App\Http\Controllers\Admin\Categories\CategoriesController@index"},{"host":null,"methods":["PUT"],"uri":"admin\/categories\/{category_id}\/update","name":"admin.categories.update","action":"App\Http\Controllers\Admin\Categories\CategoriesController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/categories\/{category_id}\/show","name":"admin.categories.show","action":"App\Http\Controllers\Admin\Categories\CategoriesController@show"},{"host":null,"methods":["POST"],"uri":"admin\/categories","name":"admin.categories.store","action":"App\Http\Controllers\Admin\Categories\CategoriesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/categories\/{category_id}\/delete\/confirm","name":"admin.categories.destroy.confirm","action":"App\Http\Controllers\Admin\Categories\CategoriesController@confirmDestroy"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/categories\/{category_id}\/delete","name":"admin.categories.destroy","action":"App\Http\Controllers\Admin\Categories\CategoriesController@destroy"},{"host":null,"methods":["POST"],"uri":"api\/oauth\/request-token","name":"oauth.request-token","action":"App\Http\Controllers\Api\OAuthController@getClientCredentialsToken"},{"host":null,"methods":["GET","HEAD","POST","PUT","PATCH","DELETE"],"uri":"api\/error","name":"api.errors","action":"App\Http\Controllers\Api\ErrorsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/users","name":"api.v1.users","action":"App\Http\Controllers\Api\UsersController@index"},{"host":null,"methods":["POST"],"uri":"api\/v1\/users\/store","name":"api.v1.users.store","action":"App\Http\Controllers\Api\UsersController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/dealers","name":"api.v1.dealers","action":"App\Http\Controllers\Api\DealersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/dealer\/{id}","name":"api.v1.dealers.show","action":"App\Http\Controllers\Api\DealersController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/dealer\/{id}\/brands","name":"api.v1.dealers.show.brands","action":"App\Http\Controllers\Api\DealersController@brands"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/dealer\/{id}\/brand-associates","name":"api.v1.dealers.show.brands-associates","action":"App\Http\Controllers\Api\DealersController@salesReps"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/brands","name":"api.v1.brands","action":"App\Http\Controllers\Api\BrandsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/brand\/{id}","name":"api.v1.brands.show","action":"App\Http\Controllers\Api\BrandsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/offers","name":"api.v1.offers","action":"App\Http\Controllers\Api\OffersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/offer\/{id}","name":"api.v1.offers.show","action":"App\Http\Controllers\Api\OffersController@show"},{"host":null,"methods":["POST"],"uri":"api\/v1\/offer","name":"api.v1.offers.store","action":"App\Http\Controllers\Api\OffersController@store"},{"host":null,"methods":["DELETE"],"uri":"api\/v1\/offer","name":"api.v1.offers.delete","action":"App\Http\Controllers\Api\OffersController@destroy"},{"host":null,"methods":["PUT"],"uri":"api\/v1\/offer","name":"api.v1.offers.update","action":"App\Http\Controllers\Api\OffersController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/customers","name":"api.v1.customers","action":"App\Http\Controllers\Api\CustomerController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/customer\/{id}","name":"api.v1.customers.show","action":"App\Http\Controllers\Api\CustomerController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/customer\/{id}\/brand-associates","name":"api.v1.customers.brands-associates","action":"App\Http\Controllers\Api\CustomerController@salesReps"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/customer\/{id}\/offers","name":"api.v1.customers.offers","action":"App\Http\Controllers\Api\CustomerController@offers"},{"host":null,"methods":["POST"],"uri":"api\/v1\/customer\/offer","name":"api.v1.customers.offer.add","action":"App\Http\Controllers\Api\CustomerController@addOffer"},{"host":null,"methods":["DELETE"],"uri":"api\/v1\/customer\/offer","name":"api.v1.customers.offer.delete","action":"App\Http\Controllers\Api\CustomerController@deleteOffer"},{"host":null,"methods":["POST"],"uri":"api\/v1\/customer","name":"api.v1.customers.store","action":"App\Http\Controllers\Api\CustomerController@store"},{"host":null,"methods":["PUT"],"uri":"api\/v1\/customer","name":"api.v1.customers.update","action":"App\Http\Controllers\Api\CustomerController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/v1\/customer","name":"api.v1.customers.destroy","action":"App\Http\Controllers\Api\CustomerController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/brand-associates","name":"api.v1.brands-associates","action":"App\Http\Controllers\Api\SalesRepController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/brand-associate\/{id}","name":"api.v1.brands-associates.show","action":"App\Http\Controllers\Api\SalesRepController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/brand-associate\/{id}\/customers","name":"api.v1.brands-associates.customers","action":"App\Http\Controllers\Api\SalesRepController@customers"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/direct-message","name":"admin.api.messages.direct.sent","action":"App\Http\Controllers\Api\DirectMessageController@sent"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/direct-message\/show","name":"admin.api.messages.direct.show","action":"App\Http\Controllers\Api\DirectMessageController@show"},{"host":null,"methods":["POST"],"uri":"api\/v1\/messages\/direct-message\/mark-as-read","name":"admin.api.messages.direct.mark.read","action":"App\Http\Controllers\Api\DirectMessageController@markAsRead"},{"host":null,"methods":["POST"],"uri":"api\/v1\/messages\/direct-message\/store","name":"admin.api.messages.direct.store","action":"App\Http\Controllers\Api\DirectMessageController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/price","name":"admin.api.messages.request.price","action":"App\Http\Controllers\Api\RequestPriceController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/price\/{id}","name":"admin.api.messages.request.price.show","action":"App\Http\Controllers\Api\RequestPriceController@show"},{"host":null,"methods":["POST"],"uri":"api\/v1\/messages\/request\/price\/store","name":"admin.api.messages.request.price.store","action":"App\Http\Controllers\Api\RequestPriceController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/info","name":"admin.api.messages.request.info","action":"App\Http\Controllers\Api\RequestInfoController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/info\/{id}","name":"admin.api.messages.request.info.show","action":"App\Http\Controllers\Api\RequestInfoController@show"},{"host":null,"methods":["POST"],"uri":"api\/v1\/messages\/request\/info\/store","name":"admin.api.messages.request.info.store","action":"App\Http\Controllers\Api\RequestInfoController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/appt","name":"admin.api.messages.request.appt","action":"App\Http\Controllers\Api\RequestApptController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/appt\/{id}","name":"admin.api.messages.request.appt.show","action":"App\Http\Controllers\Api\RequestApptController@show"},{"host":null,"methods":["POST"],"uri":"api\/v1\/messages\/request\/appt\/store","name":"admin.api.messages.request.appt.store","action":"App\Http\Controllers\Api\RequestApptController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/contact_me","name":"admin.api.messages.request.contact_me","action":"App\Http\Controllers\Api\RequestContactController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/messages\/request\/contact_me\/{id}","name":"admin.api.messages.request.contact_me.show","action":"App\Http\Controllers\Api\RequestContactController@show"},{"host":null,"methods":["POST"],"uri":"api\/v1\/messages\/request\/contact_me\/store","name":"admin.api.messages.request.contact_me.store","action":"App\Http\Controllers\Api\RequestContactController@store"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

