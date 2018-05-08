<?php namespace App\Http\Middleware;

use Closure;
//use Menu;
use Auth;
use App\Storage\Messenger\Thread;
use App\Storage\Menu\Menu;
use App\Storage\Messenger\Message;
use App\Storage\SalesRep\SalesRepRepositoryEloquent;

class AdminPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * make sure it loaded only on admin page with GET method.
         */
        if($request->method() != 'GET' || $request->ajax())
        {

          return $next($request);
        }

        $user          = $request->user();
        $isBroyhillCs  = $user->hasRole('broyhill-cs');
        $isSalesRep    = $user->hasRole('sales-rep');
        $isCustomer    = $user->hasRole('customer');

        if($isCustomer)
        {
          Auth::logout();

          $request->session()->flash('errors', "<strong>Oops</strong>, we don't recognize your username and password combination. Please try again.");
          Auth::logout();
          return redirect()->guest(route('auth.login'));
        }

        /**
         * Force to redirect to registration page if not yet completed for BA that registered using public register or FB.
         */
        if($isSalesRep)
        {
          $salesrepRepo = new SalesRepRepositoryEloquent(app());
          $reglvl       = $salesrepRepo->maxLevelRegistration($user->salesRep);

          if($reglvl < 2){
            return redirect()->route('register');
          }
        }

      //  $prospectCount = null;

        /**
         * Count messaages
         * TODO: create better and cleaner way for couting messages.
         *
         * @var        array
         */
     //   $counts = array('all' => 0, 'all-unread' => 0);
     //   foreach(['message', 'appt', 'price', 'info'] as $k){

//          $c = Message::allMessages()->unReadForUser($user->id)->allMessagesForUser($user->id)->forType($k)->count();

  //        $counts[$k] = $c;
  //        $counts['all-unread'] += $c;

//          $allCount = Message::allMessages()->allMessagesForUser($user->id)->forType($k)->count();

//          $counts['all'] += $allCount;
//        }

      //  if($isSalesRep){
        //  $prospectCount = $user->salesRep()->first()->customers()->count();
      //  }

        $menu = new Menu();
        $menu->make('sidebarMenu', function($menu) use($user, $isSalesRep, $isBroyhillCs){

        //  $allMsgCount = ($counts['all-unread'] > 0 ? $counts['all-unread'] . '/' . $counts['all'] : 0);

          /**
            * Dashboard
            */
          $menu->add('DASHBOARD',  ['route' => 'home'])->data('icon_class', 'fa fa-home');

          /**
           * Messages
           */
           // $menu->add('MESSAGES', [])
           //  ->data('icon_class', 'fa fa-envelope-o')
           //  ->data('labelcolor', 'warning')
           //  ->restricSalesRep()
           //  ->setCountRoute('admin.api.sidebar.counter.messages.all-over')
           //  ->nickname('admin_messages');

           //  $menu->admin_messages->add('All Messages', ['route' => 'admin.messages'])
           //    ->data('icon_class', 'fa fa-envelope-o')
           //    ->setCountRoute('admin.api.sidebar.counter.messages.all')
           //    ->forRoleOnly('sales-rep');

           //  $menu->admin_messages->add('Appt Requests', ['url' => route('admin.messages', ['type' => 'appt'])])
           //    ->data('icon_class', 'fa fa-clock-o')
           //    ->setCountRoute('admin.api.sidebar.counter.messages.appt')
           //    ->forRoleOnly('sales-rep');

           //  $menu->admin_messages->add('Price Requests', ['url' => route('admin.messages', ['type' => 'price'])])
           //    ->data('icon_class', 'fa fa-dollar')
           //    ->setCountRoute('admin.api.sidebar.counter.messages.price')
           //    ->forRoleOnly('sales-rep');

           //  $menu->admin_messages->add('Info Requests', ['url' => route('admin.messages', ['type' => 'info'])])
           //    ->data('icon_class', 'fa fa-info-circle')
           //    ->setCountRoute('admin.api.sidebar.counter.messages.info')
           //    ->forRoleOnly('sales-rep');

           //  $menu->admin_messages->add('Contact Requests', ['url' => route('admin.messages', ['type' => 'contact_me'])])
           //    ->data('icon_class', 'fa fa-phone')
           //    ->setCountRoute('admin.api.sidebar.counter.messages.contact_me')
           //    ->forRoleOnly('sales-rep');

           //  $menu->admin_messages->add('Direct Messages', ['url' => route('admin.messages', ['type' => 'message'])])
           //    ->data('icon_class', 'fa fa-comment-o')
           //    ->setCountRoute('admin.api.sidebar.counter.messages.direct');

           //  $menu->admin_messages->add('Sent Messages', ['route' => 'admin.messages.sent'])
           //    ->data('icon_class', 'fa fa-mail-reply');
           //  $menu->admin_messages->add('Drafts', ['route' => 'admin.messages.draft'])
           //    ->data('icon_class', 'fa fa-eraser');

          // End messages

          /**
           * BA
           */
          // $menu->add('BRAND ASSOCIATES', [])
          //   ->data('icon_class', 'fa fa-barcode')
          //   ->nickname('admin_ba')
          //   ->forRoleOnly('csr')
          //   ->restricSalesRep();
          //  // ->setCountRoute('admin.api.sidebar.counter.prospect');
          //   //->data('count', $prospectCount);

          //   $menu->admin_ba->add('Invite New BA', ['route' => 'admin.brand.associate.invite']);
          //   $menu->admin_ba->add('All BAs', ['route' => 'admin.salesrep']);
          //End BA

          /**
           * Prospects
           */
          $menu->add('PROSPECTS', ['route' => 'admin.prospects'])
            ->data('icon_class', 'fa fa-bullseye')
            ->nickname('admin_prospects')
            ->restricSalesRep()
            ->setCountRoute('admin.api.sidebar.counter.prospect');

            $menu->admin_prospects->add('New Prospects', ['route' => 'admin.prospects.leads'])
              ->forRoleOnly('sales-rep')
              ->setCountRoute('admin.api.sidebar.counter.new.prospect');
            $menu->admin_prospects->add('Unmatched Leads', ['route' => 'admin.prospects.unmatched.lead'])
              ->forRoleOnly('csr');
            $menu->admin_prospects->add('All Prospects', ['route' => 'admin.prospects']);
          //End Prospect

            $menu->add('CTA Requests', ['route' => 'admin.cta'])
            ->data('icon_class', 'fa fa-bars')
            ->forRoleOnly('csr')
            ->nickname('admin_cta');

          /**
           * Prospect Activity
           */
          // $menu->add('PROSPECT ACTIVITY', ['route' => 'admin.prospects.activity'])
          //   ->data('icon_class', 'fa fa-eye')
          //   ->data('count', 7)
          //   ->forRoleOnly('sales-rep')
          //   ->restricSalesRep()
          //   ->nickname('admin_prospect_activity');

          //   $menu->admin_prospect_activity->add('All Activity', ['url' => route('admin.prospects.activity')]);
          //   $menu->admin_prospect_activity->add('Requests', ['url' => route('admin.prospects.activity', ['f' => 'request'])]);
          //   $menu->admin_prospect_activity->add('Lookbook Saves', ['url' => route('admin.prospects.activity', ['f' => 'lookbook'])]);
          // End Prospect activity

          /**
           * Deprecated
           */
          if($isBroyhillCs){
            $menu->add('Brand Associates')
              ->data('icon_class', 'fa fa-tags')
              ->nickname('admin.salesreps');
          }

          /**
           * Deprecated
           */
          if($isBroyhillCs){
            $menu->add('Dealers')
              ->data('icon_class', 'fa fa-handshake-o')
              ->nickname('admin.dealers');
          }

          /**
           * Offers
           */
          $menu->add('OFFERS')
            ->data('icon_class', 'fa fa-tag')
            ->restricSalesRep()
            ->nickname('admin_offers');

            $menu->admin_offers->add('All Offers', ['route' => 'admin.offers']);
            $menu->admin_offers->add( ($user->hasRole('csr') ? 'BA Offers' : 'Custom Offers'), ['url' => route('admin.offers', ['author_type' => 'custom'])]);

            if(config('lbt.offer.enable_dealer_offers'))
            {
              $menu->admin_offers->add('Dealer Offers', ['url' => route('admin.offers', ['author_type' => 'dealer'])]);
            }

            $menu->admin_offers->add('Brand Offers', ['url' => route('admin.offers', ['author_type' => 'brand'])]);
          // End offer

          /**
           * Dealers
           */
          $menu->add('DEALERS', [])
            ->data('icon_class', 'fa fa-beer')
            ->forRoleOnly('csr')
            ->nickname('admin_dealers');

            $menu->admin_dealers->add('Add New Dealer', ['route' => 'admin.dealers.create']);
            $menu->admin_dealers->add('All Dealers', ['route' => 'admin.dealers']);
          //End Dealers

          /**
           * Categories
           */
          $menu->add('CATEGORIES', ['route' => 'admin.categories'])
            ->data('icon_class', 'fa fa-bars')
            ->forRoleOnly('csr')
            ->nickname('admin_categories');

           // $menu->admin_categories->add('Add New Category', []);
            $menu->admin_categories->add('All Categories', ['route' => 'admin.categories']);

          //End Dealers
          /**
           * Appointment
           */
          $menu->add('Appointment', ['route' => 'admin.appointment'])
            ->data('icon_class', 'fa fa-calendar')
            ->forRoleOnly('sales-rep')
            ->nickname('admin_appointment');

          /**
           * Brand
           */
          $menu->add('BRAND', [])
            ->data('icon_class', 'fa fa-institution')
            ->forRoleOnly('csr')
            ->nickname('admin_brand');

            $menu->admin_brand->add('Add New Brand', ['route' => 'admin.brands.create']);
            $menu->admin_brand->add('All Brands', ['route' => 'admin.brands']);
          //End Brand

          /**
           * My Settings
           */
          $menu->add('MY SETTINGS')
            ->data('icon_class', 'fa fa-user-circle')
            ->nickname('admin_settings');
          $menu->admin_settings->add('My Profile', ['route' => 'admin.settings.profile']);
          // $menu->admin_settings->add('Notifications', ['route' => 'admin.settings.notifications'])->restricSalesRep();
          $menu->admin_settings->add('Cronofy Settings', ['route' => 'admin.settings.salesrep.cronofysettings'])->restricSalesRep();
          $menu->admin_settings->add('Change Password', ['route' => 'admin.settings.change.password']);
          // End settings

          /**
           * Logout
           */
          $menu->add('LOG OUT',  ['route' => 'auth.logout'])
            ->data('icon_class', 'fa fa-sign-out');


          /**
           * Linkedin Demo
           */
          $menu->add('Linkedin Data', ['route' => 'auth.social.demo'])
            ->data('icon_class', 'fa fa-in')
            ->forRoleOnly('sales-rep')
            ->nickname('admin_linkedin');

        });

        return $next($request);
    }
}
