<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> 
                    <span>
                        @if($avatar_48)
                            <img alt="image" class="img-circle" src="{!! $avatar_48 !!}" width="48" height="48" />
                        @else
                            <img alt="image" class="img-circle" src="{!! asset('img/profile_small.jpg') !!}" />
                        @endif
                    </span>
                    
                    <a href="{!! route('admin.settings.profile') !!}">
                        <span class="clear"> 
                            <span class="block m-t-xs"> 
                            <strong class="font-bold">
                                @if(Auth::user()->firstname != '')
                                    {!! Auth::user()->firstname !!} {!! Auth::user()->lastname !!}
                                @else
                                    {!! Auth::user()->getMeta('name') !!}
                                @endif
                            </strong>
                        </span>
                        <span class="text-muted text-xs block">
                            @foreach(Auth::user()->roles()->get() as $role)
                                {!! config('lbt.user_roles.' . $role->name . '.nav_lbl') !!}
                            @endforeach
                            
                        </span> 
                    </a>

                </div>
                <div class="logo-element">
                    XSC
                </div>
            </li>

            @if(isset($layout['extra']['sidemenu_active']) && $layout['extra']['sidemenu_active'])
                <?php 
                    $sidemenu = $sidebarMenu->get($layout['extra']['sidemenu_active']);
                    if($sidemenu){
                        $sidemenu->active();
                    } ?>
            @endif

            @include('admin.partials.nav-items', array('items' => $sidebarMenu->roots()))

        </ul>
    </div>
</nav>