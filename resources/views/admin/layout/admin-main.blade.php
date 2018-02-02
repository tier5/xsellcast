<!DOCTYPE html>
<html>

@include('admin.partials.header')

<body class="admin {!! str_slug(\Request::route()->getName()) !!} {!! $salesrep_class !!}">

    <div id="wrapper">

        @yield('before_nav')
        @include('admin.partials.nav-vertical')

        <div id="page-wrapper" class="gray-bg dashbard-1">
            
            @include('admin.partials.nav-horizontal')

            @include('admin.partials.nav-title')

            <div class="wrapper wrapper-content" id="content-wrapper">
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>

            @include('admin.partials.footer')

        </div>
    </div>

    @yield('footer')

    @include('admin.partials.scripts')

    @if(Session::has('message'))
        <script type="text/javascript">
            $.gritter.add({
                title: 'Success!',
                text: "{{ Session::get('message') }}",
                time: 8000,
                class_name: 'gritter-success'
            });        
        </script>
    @endif

    @if(Session::has('warning'))
        <script type="text/javascript">
            $.gritter.add({
                title: 'WARNING!',
                text: "{{ Session::get('warning') }}",
                time: 8000,
                class_name: 'gritter-warning'
            });        
        </script>
    @endif

    @if($errors && $errors->count() > 0)
        <script type="text/javascript">
            @foreach($errors->messages() as $field)
                @foreach($field as $error)
                    $.gritter.add({
                        title: 'Error!',
                        text: '{!! $error !!}',
                        time: 8000,
                        class_name: 'gritter-danger'
                    });
                @endforeach
            @endforeach
        </script>
    @endif

</body>

</html>
