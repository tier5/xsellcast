<div class="row">
    <div class="col-md-6">
        <div class="widget-head-color-box navy-bg p-lg text-center">
            <div class="m-b-md">
            <h2 class="font-bold no-margins">
                {!! $salesrep->user->firstname !!} {!! $salesrep->user->lastname !!}
            </h2>
            </div>
            <img src="{{ $salesrep->user->avatar_url }}" width="140" height="140" class="img-circle circle-border m-b-md" alt="profile">

            @if($salesrep->local_agreed_at)
            <p>
                <small>Agreement Accepted: <strong>{!! $salesrep->local_agreed_at->format('m/d/Y \a\t h:i a'); !!}</strong></small>
            </p>
            @endif
        </div>

        <div class="widget-text-box">
            <div class="row">
            	<div class="col-md-6 col-sm-6">
            		<div class="form-group">
            			<label>Name</label>
            			<input value="" class="form-control" placeholder="First name" type="text" disabled="disabled" />
            		</div>
            	</div>
            	<div class="col-md-6 col-sm-6">
            		<div class="form-group">
                        <label>&nbsp;</label>
            			<input value="" class="form-control" placeholder="Last name" type="text" disabled="disabled" />
            		</div>
            	</div>

            	<div class="col-md-12">
            		<div class="form-group">
            			<label>Email</label>
            			<input class="form-control" type="text" value="{!! $salesrep->user->email !!}" disabled="disabled" />
            		</div>
            	</div>             

            </div>
        </div>

        @if($salesrep->email_work || $salesrep->email_personal || $salesrep->cellphone || $salesrep->officephone)
            <div class="ibox-content ibox-heading">
                <h3>Contact Info</h3>
            </div>  

            <div class="widget-text-box">
                <div class="row">
                    @if($salesrep->email_work && $salesrep->email_work != '')
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Work Email</label>
                            <input class="form-control" type="text" value="{!! $salesrep->email_work !!}" disabled="disabled" />
                        </div>
                    </div>
                    @endif

                    @if($salesrep->email_personal && $salesrep->email_personal != '')
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Personal Email</label>
                            <input class="form-control" type="text" value="{!! $salesrep->email_personal !!}" disabled="disabled" />
                        </div>
                    </div>   
                    @endif

                    @if($salesrep->cellphone && $salesrep->cellphone != '')
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Cell Phone</label>
                            <input class="form-control" type="text" value="{!! $salesrep->cellphone !!}" disabled="disabled" />
                        </div>
                    </div>    
                    @endif

                    @if($salesrep->officephone && $salesrep->officephone != '')
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Office Phone</label>
                            <input class="form-control" type="text" value="{!! $salesrep->officephone !!}" disabled="disabled" />
                        </div>
                    </div>  
                    @endif                                 
                </div>
            </div>    
        @endif          
    </div>

    <div class="col-md-6">
        @if($salesrep->has_social_profile)
            <div class="ibox-content ibox-heading">
                <h3>Social Accounts</h3>
            </div>

            <div class="widget-text-box">
                <div class="row">
                    @if($salesrep->facebook && $salesrep->facebook != '')
                    {!! HTML::form_group_paragaph('Facebook', $salesrep->facebook, ['class' => '']) !!}
                    @endif

                    @if($salesrep->twitter && $salesrep->twitter != '')
                    {!! HTML::form_group_paragaph('Twitter', $salesrep->twitter, ['class' => '']) !!}
                    @endif

                    @if($salesrep->linkedin && $salesrep->linkedin != '')
                    {!! HTML::form_group_paragaph('LinkedIn', $salesrep->linkedin, ['class' => '']) !!}
                    @endif 

                    @if($salesrep->pinterest && $salesrep->pinterest != '')
                    {!! HTML::form_group_paragaph('Pinterest', $salesrep->pinterest, ['class' => '']) !!}
                    @endif     

                    @if($salesrep->instagram && $salesrep->instagram != '')
                    {!! HTML::form_group_paragaph('Instagram', $salesrep->instagram, ['class' => '']) !!}
                    @endif       

                    @if($salesrep->youtube && $salesrep->youtube != '')
                    {!! HTML::form_group_paragaph('Youtube', $salesrep->youtube, ['class' => '']) !!}
                    @endif           
                </div>
            </div>

        @endif

        <div class="ibox-content ibox-heading">
            <h3>Dealers & Brands</h3>
        </div>

        <div class="widget-text-box">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Dealers</label>
                        <div class="row">
                            <div class="col-md-12">
                                @if($salesrep->dealers)
                                    @foreach($salesrep->dealers as $dealer)
                                        <li>{!! $dealer->name !!}</li>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<div class="m-b-lg"></div>