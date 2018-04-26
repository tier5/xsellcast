<!DOCTYPE html>
<html>

@include('admin.partials.header')

<body class="gray-bg">

    <div class="middle-box auth-box text-center @yield('authbox_class') animated fadeInDown">
        <div>
            <div>

                {{-- LOGO --}}

            </div>

            @if (isset($errors) && count($errors) > 0)
                <br/>
                <br/>
                <div class="alert alert-danger">

                    @if(is_string($errors))
                        <p>{!! $errors !!}</p>
                    @else
                        @foreach ($errors->all() as $error)
                            <p>{!! $error !!}</p>
                        @endforeach
                    @endif

                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')

        </div>
    </div>

    @include('admin.partials.scripts')

</body>

</html>
