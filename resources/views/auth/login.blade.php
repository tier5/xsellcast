@extends('admin.layout.plane')

@section('htmlheader_title')
Log in
@endsection

@section('authbox_class')
loginscreen
@endsection
@section('head_script')
<script type="text/javascript">
 var oa = document.createElement('script');
 oa.type = 'text/javascript'; oa.async = true;
 oa.src = '//tier5.api.oneall.com/socialize/library.js'
 var s = document.getElementsByTagName('script')[0];
 s.parentNode.insertBefore(oa, s)
</script>
    {{-- <link href="{{ asset('/css/oneall.css') }}" rel="stylesheet" type="text/css" /> --}}

@endsection

@section('content')
    <h3>Log In</h3>
    <form class="m-t" role="form" method="post" action="{{ route('login.post') }}">
        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Username" required="required">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required="required">
        </div>
        <div class="form-group text-right">
            <a href="{{ route('forgotpassword') }}"><small>Forgot password?</small></a>
        </div>
        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        <p class="text-muted text-center"><small>Or log in with:</small></p>
        <div class="form-group text-center">
            <a href="{{ route('auth.social.fb') }}" class="btn btn-success btn-facebook btn-outline btn-block">
                <i class="fa fa-facebook"> </i> Sign in with Facebook
            </a>
        </div>
        <div class="form-group text-center">
            <a href="{{ route('auth.social','linkedin') }}" class="btn btn-success btn-linkedin btn-outline btn-block">
                <i class="fa fa-linkedin"> </i> Sign in with linkedin asdfdsaf
            </a>
        </div>
        <!-- The plugin will be embedded into this div //-->
        <div id="oa_social_login" class="form-group text-center"></div>
{{--
        <script type="text/javascript">
             var _oneall = _oneall || [];
             _oneall.push(['social_login', 'set_custom_css_uri', '{{ asset('/css/oneall.css') }}']);
             // _oneall.push(['social_login', 'set_callback_uri', window.location.href]);
             _oneall.push(['social_login', 'set_callback_uri',  '{{ route('register') }}']);
             _oneall.push(['social_login', 'set_providers', [ 'linkedin']]);

              // _oneall.push(['social_login', 'set_providers', ['amazon', 'battlenet', 'blogger', 'storage', 'discord', 'disqus', 'draugiem', 'dribbble', 'facebook', 'foursquare', 'github', 'google', 'instagram', 'line', 'linkedin', 'livejournal', 'mailru', 'meetup', 'odnoklassniki', 'openid', 'paypal', 'pinterest', 'pixelpin', 'reddit', 'skyrock', 'soundcloud', 'stackexchange', 'steam', 'tumblr', 'twitch', 'twitter', 'vimeo', 'vkontakte', 'weibo', 'windowslive', 'wordpress', 'xing', 'yahoo', 'youtube']]);
             _oneall.push(['social_login', 'do_render_ui', 'oa_social_login']);

        </script> --}}

         <script type="text/javascript">

            /* Replace #your_callback_uri# with the url to your own callback script */
            // var your_callback_script = '{{ route('register') }}';

            /* Embeds the buttons into the container oa_social_login_container */
            var _oneall = _oneall || [];
            _oneall.push(['social_login', 'set_providers', ['linkedin']]);
            _oneall.push(['social_login', 'set_callback_uri', 'http://xsellcast.test/auth/callback_uri']);
            _oneall.push(['social_login', 'do_render_ui', 'oa_social_login']);

        //      _oneall.api.plugins.social_login.build("oneall_social_login_providers", {
        //     "providers": ['linkedin','facebook', 'google', 'steam', 'twitter', 'youtube'],
        //     "callback_uri": 'http://elliteinformatica.esy.es/forum/index.php?action=oasl_callback;oasl_source=registration'
        // });
//       var _oneall = _oneall || [];
// _oneall.push(['social_login', 'set_providers', ['linkedin','google','instagram','twitter']]);
// _oneall.push(['social_login', 'set_callback_uri', (window.location.href + ((window.location.href.split('?')[1] ? '&': '?') + "oa_social_login_source=widget"))]);
// // _oneall.push(['social_login', 'set_custom_css_uri', 'http://public.oneallcdn.com/css/api/socialize/themes/wordpress/default.css']);
// _oneall.push(['social_login', 'do_render_ui', 'oa_social_login'])

      </script>

        <p class="text-muted text-center"><small>Don't have an account yet?</small></p>
        <a class="btn btn-white btn-block" href="{{ route('register') }}">Sign Up</a>
    </form>
@endsection
