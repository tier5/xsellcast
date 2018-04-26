@extends('admin.layout.plane')

@section('htmlheader_title')
Register
@endsection

@section('authbox_class')
registerscreen
@endsection

@section('content')

    <div class="stepwizard">

        <div class="stepwizard-row setup-panel m-b">
            <div class="stepwizard-step">
                <a href="#step-1" type="button" class="btn btn-primary btn-circle open-done">1</a>
                <p>Create Account</p>
            </div>
            <div class="stepwizard-step">
                <a href="#step-2" type="button" class="btn btn-default btn-circle @if(in_array(1, $reglvl)) open-done @endif" @if(!in_array(1, $reglvl)) disabled="disabled" @endif>2</a>
                <p>Company Info</p>
            </div>
            <div class="stepwizard-step">
                <a href="#step-3" type="button" class="btn btn-default btn-circle @if(in_array(2, $reglvl)) open-done @endif" @if(!in_array(2, $reglvl)) disabled="disabled" @endif>3</a>
                <p>Social Profiles</p>
            </div>
        </div>

        <div class="register-page setup-content" id="step-1">
            <form action="{{ route('register.salesrep.store_account') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <i class="fa fa-user-circle register-icon"></i>

                <h2 class="m-b">Create Account</h2>

                <div class="form-group text-center">
                    <a href="{{ route('auth.social.fb') }}" class="btn btn-success btn-facebook btn-outline btn-block">
                        <i class="fa fa-facebook"> </i> Continue with Facebook
                    </a>
                </div>
                <div class="form-group text-center">
                    <a href="{{ route('auth.social','linkedin') }}" class="btn btn-success btn-linkedin btn-outline btn-block">
                        <i class="fa fa-linkedin"> </i> Sign in with linkedin
                    </a>
                </div>
                <div class="form-group">
                     <p class="text-muted text-center breakline-through"><small>Or</small></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="First Name" name="firstname" value="{{ old('firstname', ($user ? $user->firstname : '')) }}"/>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Last Name" name="lastname" value="{{ old('lastname', ($user ? $user->lastname : '')) }}"/>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email', ($user ? $user->email : '')) }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Cell Phone" name="cellphone" value="{{ old('cellphone', ($sales_rep ? $sales_rep->cellphone : '')) }}"/>
                            <span class="fa fa-mobile-phone form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Office Phone" name="officephone" value="{{ old('officephone', ($sales_rep ? $sales_rep->officephone : '')) }}"/>
                            <span class="fa fa-phone form-control-feedback"></span>
                        </div>
                    </div>
                </div>
                <p class="text-muted text-center"><small>Prospect should be shown my:</small></p>
                <div class="form-group">
                    <div class="ibox-content ibox-field">
                        <label class="checkbox-inline"> <input type="checkbox" name="show_cellphone" value="1" class="i-checks" @if($sales_rep && $sales_rep->show_cellphone) checked="checked" @endif> Cell Phone </label>
                        <label class="checkbox-inline"> <input type="checkbox" name="show_officephone" value="1" class="i-checks" @if($sales_rep && $sales_rep->show_officephone) checked="checked" @endif> Office Phone </label>
                        <label class="checkbox-inline"> <input type="checkbox" name="show_email" value="1" class="i-checks" @if($sales_rep && $sales_rep->show_email) checked="checked" @endif> Email </label>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation"/>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-3 m-b-sm">
                        <button type="button" class="register-next next-submit btn btn-primary nextBtn btn-block btn-flat">Next</button>
                    </div><!-- /.col -->

                    <div class="col-xs-6 col-sm-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">
                        <a href="{!! route('register.cancel') !!}" class="btn btn-white btn-block btn-flat">Cancel</a>
                    </div><!-- /.col -->
                </div>
            </form>
        </div>

        <div class="register-page setup-content" id="step-2">
            <form action="{{ route('api.v1.dealers') }}" method="get" class="search-dealer">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="access_token" value="{{ $access_token }}" />

                <i class="fa fa-user-circle register-icon"></i>

                <h2 class="m-b">Company Info</h2>

                <p class="text-muted text-center"><small>Find my company: </small></p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Company Zipcode" name="zip" id="company_zip" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="select-box">
                               <select class="form-control" name="category">
                                    <option value="">Select category...</option>
                                    @foreach($categories as $category)
                                        <option value="{!! $category->id !!}">{!! $category->name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <button type="submit" class="register-next btn btn-white btn-block btn-flat"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>


            </form>

            <form method="post" class="form-horizontal" action="{{ route('register.salesrep.store_dealer') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="ibox-content m-b hidden" id="dealers-container">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Dealers</label>
                        <div class="col-sm-10">
                            <div id="register-dealer-list"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-3 m-b-md">
                        <button type="button" class="register-next btn btn-primary next-submit nextBtn btn-block btn-flat">Next</button>
                    </div><!-- /.col -->

                    <div class="col-xs-6 col-sm-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">
                        <a href="{!! route('register.cancel') !!}" class="btn btn-white btn-block btn-flat">Cancel</a>
                    </div><!-- /.col -->
                </div>
            </form>

        </div>

        <div class="register-page register-social setup-content" id="step-3">

            <h2 class="m-b">Link Social Profiles</h2>

            <form action="{{ route('register.salesrep.store_socialprofile') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-2 icon-box">
                            <span><i class="fa fa-facebook"></i></span>
                        </div>
                        <div class="col-xs-10">
                            <input type="text" class="form-control" placeholder="@username" name="facebook" id="facebook" value="{{ old('facebook', (isset($sales_rep->facebook) ? $sales_rep->facebook : '' ) ) }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-2 icon-box">
                            <span><i class="fa fa-twitter"></i></span>
                        </div>
                        <div class="col-xs-10">
                            <input type="text" class="form-control" placeholder="@username" name="twitter" id="twitter" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-2 icon-box">
                            <span><i class="fa fa-linkedin"></i></span>
                        </div>
                        <div class="col-xs-10">
                            <input type="text" class="form-control" placeholder="URL" name="linkedin" id="linkedin" value="{{ old('linkedin', (isset($sales_rep->linkedin) ? $sales_rep->linkedin : '' ) ) }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 m-b-md">
                        <button type="button" class="register-next btn btn-primary next-submit doneBtn nextBtn btn-block btn-flat">Done</button>
                    </div><!-- /.col -->

                    <div class="col-xs-6 col-sm-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">
                        <a href="{!! route('register.cancel') !!}" class="btn btn-white btn-block btn-flat">Cancel</a>
                    </div><!-- /.col -->
                </div>
            </form>
        </div>

        <div class="register-page setup-content" id="step-success">
            <h2>Registration Complete!</h2>
            <div class="m-b-md"></div>
            <h3>Welcome to Xsellcast! Log in now to begin exploring.</h3>
            <div class="m-b-lg"></div>
            <a class="btn btn-white" href="{{ route('auth.login') }}">Log In</a>
        </div>

    </div>

@endsection
