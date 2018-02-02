<div class="row">
    <div class="col-md-6">
        <div class="widget-head-color-box navy-bg p-lg text-center">
            <div class="m-b-md">
            <h2 class="font-bold no-margins">
                @if(empty($customer['firstname']) && empty($customer['lastname']))
                    {!! $customer['name'] !!}
                @else
                    {!! $customer['firstname'] !!} {!! $customer['lastname'] !!}
                @endif
            </h2>
            </div>
            @if(isset($customer['avatar_orig_url']) && $customer['avatar_orig_url'])
                <img src="{!! $customer['avatar_orig_url'] !!}" width="140" height="140" class="img-circle circle-border m-b-md" alt="profile">
            @else
                <img src="{{ asset('img/blank-avatar.jpg') }}" width="140" height="140" class="img-circle circle-border m-b-md" alt="profile">
            @endif
        </div>

        <div class="widget-text-box">

            @if($iscsr)
                @include('admin.prospect.parts.info-csr')
            @else
                @include('admin.prospect.parts.info-ba')
            @endif

        </div>

        @if($fc->hasInfo())
            <div class="ibox-content ibox-heading">
                <h3>Personal Details Found via Social Search</h3>
            </div>

            <div class="no-padding">
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-12">* Note that these details are gathered in an authomated way and may not be as accurate as data shown above.</div>
                    </div>
                </div>

                @if($fc->viewLocation())
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-4">Location: </div>
                        <div class="col-md-8">{!! $fc->viewLocation() !!}</div>
                    </div>
                </div>   
                @endif

                @if($fc->getGender())
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-4">Gender: </div>
                        <div class="col-md-8">{!! $fc->getGender() !!}</div>
                    </div>
                </div>
                @endif           

                @if($fc->viewOrgs())
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-4">Organizations: </div>
                        <div class="col-md-8">{!! $fc->viewOrgs() !!}</div>
                    </div>
                </div>
                @endif     

                @if($fc->viewWebsites())
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-4">Websites: </div>
                        <div class="col-md-8">{!! $fc->viewWebsites() !!}</div>
                    </div>
                </div>
                @endif     
                       
                @if($fc->viewPhotos())
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-4">Social Media Profile Photos: </div>
                        <div class="col-md-8">{!! $fc->viewPhotos() !!}</div>
                    </div>
                </div>
                @endif   

            </div>            
        @endif

        @if(isset($customer['has_social_profile']) && $customer['has_social_profile'])
            <div class="ibox-content ibox-heading">
                <h3>Social Accounts</h3>
            </div>

            <div class="widget-text-box">
                <div class="row">
                    @if($customer['fb_id'] && $customer['fb_id'] != '')
                    {!! HTML::form_group_paragaph('Facebook', 'https://facebook.com/' . $customer['fb_id']) !!}
                    @endif

                    @if(isset($customer['twitter']) && $customer['twitter'] && $customer['twitter'] != '')
                    {!! HTML::form_group_paragaph('Twitter', $customer['twitter']) !!}
                    @endif

                    @if(isset($customer['linkedin']) && $customer['linkedin'] && $customer['linkedin'] != '')
                    {!! HTML::form_group_paragaph('LinkedIn', $customer['linkedin']) !!}
                    @endif

                    @if(isset($customer['pinterest']) && $customer['pinterest'] && $customer['pinterest'] != '')
                    {!! HTML::form_group_paragaph('Pinterest', $customer['pinterest']) !!}
                    @endif

                    @if(isset($customer['instagram']) && $customer['instagram'] && $customer['instagram'] != '')
                    {!! HTML::form_group_paragaph('Instagram', $customer['instagram']) !!}
                    @endif

                    @if(isset($customer['youtube']) && $customer['youtube'] && $customer['youtube'] != '')
                    {!! HTML::form_group_paragaph('Youtube', $customer['youtube']) !!}
                    @endif
                </div>
            </div>
        @endif

        @include('admin.prospect.parts.activity')

    </div>

    <div class="col-md-6">

        @if($iscsr)
            @include('admin.prospect.parts.ba-assignment')
        @endif

        <div class="ibox-content ibox-heading">
            <h3>Notes</h3>
        </div>

        <div class="widget-text-box">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.prospects.post.note', $customer['id']]]) !!}
                    <div class="input-group">
                        <input class="form-control" type="text" name="note" placeholder="Add notes here" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary"> Submit</button>
                        </span>
                    </div>
                    <input type="hidden" name="redirect_to" value="{!! url()->full() !!}" />
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        @if(isset($notes) && $notes)
        <div class="ibox-content">
            <div class="feed-activity-list">
                @foreach($notes as $note)
                    <div class="feed-element">
                        <div class="media-body">
                            <small class="text-muted">{!! $note->created_at->format('m/d/Y') !!} by {!! $note->user->firstname !!} {!! $note->user->lastname !!}</small><br/>
                            {!! $note->body !!}
                        </div>
                    </div> 
                @endforeach              
            </div>
        </div>
        @endif
    </div>
</div>
<div class="m-b-lg"></div>