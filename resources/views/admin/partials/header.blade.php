<head>
    <meta charset="UTF-8">
    <title> LBT XSellCast - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('/js/gritter/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/plugins/dropzone/basic.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/plugins/dropzone/dropzone.css') }}" rel="stylesheet" />
    <link href="{{ asset('/js/tinymce/skin.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/video-js.css') }}" rel="stylesheet" />
    <!-- Theme style -->
    <link href="{{ asset('/css/admin-style.css') }}" rel="stylesheet" type="text/css" />
</head>
