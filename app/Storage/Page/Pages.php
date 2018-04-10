<?php namespace App\Storage\Page;

use Closure;

class Pages
{

	public static function init($page_vars)
	{
        $page = new \App\Storage\Page\Page();
        $vars = $page_vars;
        $page->make(function($page) use($vars){
            $page->add('home', 'Dashboard')
                ->breadcrumb(function($menu){
                });

            $page->add('admin.prospects.leads', 'New Prospects')
                ->breadcrumb(function($menu){
                    $menu->add('Prospects', ['route' => 'admin.prospects']);
                    $menu->add('New Prospects')->active();
                });

            $page->add('admin.prospects', 'All Prospects')
                ->breadcrumb(function($menu){
                    $menu->add('Prospects', [ 'route' => 'admin.prospects' ]);
                    $menu->add('All Prospects')->active();
                });

            $page->add('admin.prospects.show', 'Prospects')
                ->breadcrumb(function($menu){
                    $menu->add('Prospects', [ 'route' => 'admin.prospects' ]);
                    $menu->add('Profile')->active();
                });

            $page->add('admin.messages')
                ->setTitle(function($item) use($vars){
                    $type = $vars['type'];
                    return ($type ? 'Inbox Messages: ' . config('lbt.message_types.' . $type . '.head_name') : 'Inbox Messages');
                })
                ->breadcrumb(function($menu) use($vars){
                    $menu->add('Messages', [ 'route' => ['admin.messages', $vars['type']] ]);
                    $menu->add('Show')->active();
                });

            $page->add('admin.messages.sent', 'Sent Messages')
                ->breadcrumb(function($menu){
                    $menu->add('Messages', ['route' => 'admin.messages']);
                    $menu->add('Sent')->active();
                });

            $page->add('admin.messages.draft', 'Draft Messages')
                ->breadcrumb(function($menu){
                    $menu->add('Messages', ['route' => 'admin.messages']);
                    $menu->add('Draft Messages')->active();
                });

            $page->add('admin.prospects.activity')
                ->setTitle(function($item) use($vars){
                    $customer = $vars['customer'];
                    return ($customer ? 'Prospect Activity of <strong>' . $customer->user->firstname . ' ' . $customer->user->lastname . '</strong>' : 'Prospect Activity');
                })
                ->breadcrumb(function($menu) use($vars){
                    if($vars['filter'] == 'request')
                    {
                        $name = 'Requests';
                    }elseif($vars['filter'] == 'lookbook')
                    {
                        $name = 'Lookbook Saves';
                    }else{
                        $name = 'All Activity';
                    }
                    $menu->add('Prospect Activity', ['route' => 'admin.prospects.activity']);
                    $menu->add($name)->active();
                });

            $page->add('admin.messages.create', 'Messages - New')
                ->breadcrumb(function($menu){
                    $menu->add('Messages', ['route' => 'admin.messages']);
                    $menu->add('Inbox', ['route' => 'admin.messages']);
                    $menu->add('New', ['route' => 'admin.messages.create'])->active();
                });

            $page->add('admin.messages.draft.continue', 'Messages - Draft')
                ->breadcrumb(function($menu){
                    $menu->add('Messages', ['route' => 'admin.messages']);
                    $menu->add('Inbox', ['route' => 'admin.messages']);
                    $menu->add('Draft')->active();
                });

            $page->add('admin.offers', 'All Offers')
                ->setTitle(function($item) use($vars){
                    if($vars['author_type'] == 'custom')
                    {
                        $name = ($vars['user']->hasRole('csr') ? 'BA Offers' : 'Custom Offers');
                    }elseif($vars['author_type'] == 'dealer')
                    {

                        $name = 'Dealer Offers';
                    }elseif($vars['author_type'] == 'brand')
                    {
                        $name = 'Brand Offers';
                    }else
                    {
                        $name = 'All Offers';
                    }

                    return $name;
                })
                ->breadcrumb(function($menu) use($vars){
                    if($vars['author_type'] == 'custom')
                    {
                        $name = ($vars['user']->hasRole('csr') ? 'BA Offers' : 'Custom Offers');
                    }elseif($vars['author_type'] == 'dealer')
                    {

                        $name = 'Dealer Offers';
                    }elseif($vars['author_type'] == 'brand')
                    {
                        $name = 'Brand Offers';
                    }else
                    {
                        $name = 'All Offers';
                    }

                    $menu->add('Offers', ['route' => 'admin.offers']);
                    $menu->add($name)->active();
                });

            $page->add('admin.settings.profile', 'Profile Settings')
                ->breadcrumb(function($menu){
                    $menu->add('My Settings');
                    $menu->add('My Profile')->active();
                });

            $page->add('admin.settings.notifications', 'Notifications')
                ->breadcrumb(function($menu){
                    $menu->add('My Settings');
                    $menu->add('Notifications')->active();
                });
            $page->add('admin.settings.salesrep.cronofysettings', 'Cronofy')
                ->breadcrumb(function($menu){
                    $menu->add('My Settings');
                    $menu->add('Cronofy Settings')->active();
                });

            $page->add('admin.settings.change.password', 'Password')
                ->breadcrumb(function($menu){
                    $menu->add('My Settings');
                    $menu->add('Change Password')->active();
                });

            $page->add('debug.customer.actions', 'Debug Tool')
                ->breadcrumb(function($menu){
                    $menu->add('Debug')->active();
                });

            $page->add('debug.customer.action.request', 'Debug Tool')
                ->breadcrumb(function($menu){
                    $menu->add('Debug')->active();
                });

            $page->add('admin.messages.show')
                ->setTitle(function() use($vars){

                    return ($vars['isFromMe'] ? 'Sent Messages' : 'Messages');
                })
                ->breadcrumb(function($menu){
                	$menu->add('Messages')->active();
                });

            $page->add('admin.offers.edit', 'Offers - Edit')
                ->breadcrumb(function($menu){
                	$menu->add('Offers', [ 'route' => 'admin.offers']);
                	$menu->add('Edit')->active();
                });

            $page->add('admin.offers.create', 'Offers - Create')
                ->breadcrumb(function($menu){
                	$menu->add('Offers', [ 'route' => 'admin.offers']);
                	$menu->add('Create')->active();
                });

            $page->add('admin.brand.associate.invite', 'Invite a New Brand Associate')
                ->breadcrumb(function($menu){
                    $menu->add('Brand Associates', ['route' => 'admin.salesrep']);
                    $menu->add('Invite New BA')->active();
                });

            $page->add('admin.welcome.salesrep', 'Welcome to Xsellcast!')
                ->breadcrumb(function($menu){
                    $menu->add('Welcome')->active();
                });

            $page->add('admin.salesrep', 'Brand Associates')
                ->breadcrumb(function($menu){
                    $menu->add('Brand Associates')->active();
                });

            $page->add('admin.salesrep.show', 'Brand Associates')
                ->breadcrumb(function($menu){
                    $menu->add('Brand Associates', ['route' => 'admin.salesrep']);
                    $menu->add('Profile')->active();
                });

            $page->add('admin.brands.create', 'Brands - Create')
                ->breadcrumb(function($menu){
                    $menu->add('Brands');
                    $menu->add('Create')->active();
                });

            $page->add('admin.brands.edit', 'Brands - Edit')
                ->breadcrumb(function($menu){
                    $menu->add('Brands');
                    $menu->add('Edit')->active();
                });

            $page->add('admin.dealers.create', 'Dealers - Create')
                ->breadcrumb(function($menu){
                    $menu->add('Dealers');
                    $menu->add('Create')->active();
                });

            $page->add('admin.dealers.edit', 'Dealers - Edit')
                ->breadcrumb(function($menu){
                    $menu->add('Dealers', ['route' => 'admin.dealers']);
                    $menu->add('Edit')->active();
                });

            $page->add('admin.dealers', 'Dealers')
                ->breadcrumb(function($menu){
                    $menu->add('Dealers')->active();
                });

            $page->add('admin.categories', 'Categories')
                ->breadcrumb(function($menu){
                    $menu->add('Categories')->active();
                });

            $page->add('admin.brands', 'Brands')
                ->breadcrumb(function($menu){
                    $menu->add('Brands')->active();
                });

            $page->add('admin.prospects.unmatched.lead', 'Unmatched Leads')
                ->breadcrumb(function($menu){
                    $menu->add('Prospects', ['route' => 'admin.prospects']);
                    $menu->add('Unmatched Leads')->active();
                });

            $page->add('admin.prospects.offers')
                ->setTitle(function($item) use($vars){

                    return 'Lookbook Offers for <strong>' . $vars->user->firstname . ' ' . $vars->user->lastname . '</strong>';
                })
                ->breadcrumb(function($menu) use($vars){
                    $menu->add('Prospects', ['route' => 'admin.prospects']);
                    $menu->add($vars->user->firstname . ' ' . $vars->user->lastname, ['url' => route('admin.prospects.show', ['customer_id' => $vars->id])]);
                    $menu->add('Lookbook')->active();
                });


            $page->add('admin.appointment', 'Appointment')
                ->breadcrumb(function($menu){
                    $menu->add('Appointment')->active();
                });

        });

        return $page;
	}

}