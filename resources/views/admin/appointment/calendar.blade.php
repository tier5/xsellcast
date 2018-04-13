
<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Appointment</h5>
                    <div class="ibox-tools">
                        {{-- <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a> --}}
                        {{-- <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a> --}}
                        {{-- <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul> --}}
                        {{-- <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a> --}}
                    </div>
                </div>
                <div class="ibox-content">
                    <div id='external-events'>
                        <p>Drag a event and drop into calendar.</p>
                        @foreach($messages['data'] as $message)

                         @php
                            $sender_name=$message['last_message']['sender_name'];
                            $message_id=$message['last_message']['id'];
                            $message_body=$message['last_message']['body'];
                            $created=$message['last_message']['created_at_human'];
                            $offer_name=$message['offer']['title'];

                         @endphp

                            <div class='external-event navy-bg' data-message-id="{{$message_id}}">
                                {{-- <strong>Prospect Name:</strong>{{$sender_name}}<br>
                                <strong>Offer Name:</strong>{{$offer_name}}<br>
                                <strong>Message:</strong>{{$message_body}}<br>
                                <span class="pull-right">{{$created}}</span> --}}
                                 Prospect Name:{{$sender_name}},
                                Offer Name:{{$offer_name}},
                                Message:{{$message_body}},
                                {{$created}}

                            </div>
                        @endforeach
                        {{-- <div class='external-event navy-bg'>Go to shop and buy some products.</div>
                        <div class='external-event navy-bg'>Check the new CI from Corporation.</div>
                        <div class='external-event navy-bg'>Send documents to John.</div>
                        <div class='external-event navy-bg'>Phone to Sandra.</div>
                        <div class='external-event navy-bg'>Chat with Michael.</div> --}}
                       {{--  <p class="m-t">
                            <input type='checkbox' id='drop-remove' class="i-checks" checked /> <label for='drop-remove'>remove after drop</label>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">

            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Appointment Table </h5>
                    <div class="ibox-tools">
                        {{-- <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a> --}}
                   {{--      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul> --}}
                       {{--  <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a> --}}
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('after-script')

<script type="text/javascript">

        /* initialize the calendar
         -----------------------------------------------------------------*/
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        timezone=Intl.DateTimeFormat().resolvedOptions().timeZone;
        var calendar=function(data){
            console.log(data);
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                drop: function(date) {
                    // is the "remove after drop" checkbox checked?
                    // if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                        message_id=$(this).data('message-id');
                        text=$(this).html();

                        updateEvent(1,message_id,date.format(),null,null,text);
                         // alert("Dropped on " + date.format());
                    // }
                },
                events:  data
                    // {
                    //     title: 'All Day Event',
                    //     start: new Date(y, m, 1)
                    // },
                    // {
                    //     title: 'Long Event',
                    //     start: new Date(y, m, d-5),
                    //     end: new Date(y, m, d-2),
                    // },
                    // {
                    //     id: 999,
                    //     title: 'Repeating Event',
                    //     start: new Date(y, m, d-3, 16, 0),
                    //     allDay: false,
                    // },
                    // {
                    //     id: 999,
                    //     title: 'Repeating Event',
                    //     start: new Date(y, m, d+4, 16, 0),
                    //     allDay: false
                    // },
                    // {
                    //     title: 'Meeting',
                    //     start: new Date(y, m, d, 10, 30),
                    //     allDay: false
                    // },
                    // {
                    //     title: 'Lunch',
                    //     start: new Date(y, m, d, 12, 0),
                    //     end: new Date(y, m, d, 14, 0),
                    //     allDay: false
                    // },
                    // {
                    //     title: 'Birthday Party',
                    //     start: new Date(y, m, d+1, 19, 0),
                    //     end: new Date(y, m, d+1, 22, 30),
                    //     allDay: false
                    // },
                    // {
                    //     title: 'Click for Google',
                    //     start: new Date(y, m, 28),
                    //     end: new Date(y, m, 29),
                    //     url: 'http://google.com/'
                    // }
                 ,
                 // editable: true,
                  eventResize: function(event, delta, revertFunc) {

                    // alert(event.title + " end is now " + event.end.format());
                    // alert("start" + event.start.format() + " end " + event.end.format());
                    updateEvent(2,event.id,event.start.format(),event.end.format(),event,event.title);
                    // if (!confirm("is this okay?")) {
                      // revertFunc();
                    // }

                  },
                   eventDrop: function(event, delta, revertFunc) {

                    // alert(event.title + " was dropped on " + event.start.format());

                    // if (!confirm("Are you sure about this change?")) {
                    //   revertFunc();
                    // }
                    updateEvent(3,event.id,event.start.format(),event.end.format(),event,event.title);

                     }


            });
        }

        var getCalander= function(){

            var url="{{route('admin.appointment.calendar')}}";
            // timezone=Intl.DateTimeFormat().resolvedOptions().timeZone;
            frm_data={form:'2018-04-01',to:'2018-04-30',timezone:timezone};
            // simpleAjaxRq(url, frm_data, true, , ['data'], 'GET', []);
            method='GET';
            addBodyProcessing();

            $.ajax({
                url: url,
                data: frm_data,
                dataType: 'json',
                type: method,
                error: function(r) {
                    var html = '';
                    if(r.status == 422){

                        html = jsonErrorToHtml(r.responseJSON);
                    }else{

                        html = 'Uknown error found.';
                    }

                    $.gritter.add({
                        title: 'Form error!',
                        text: html,
                        time: 8000,
                        class_name: 'gritter-danger'
                    });
                },
                success: function(response) {

                    // if(successMsg){
                    //     $.gritter.add({
                    //         text: successMsg,
                    //         time: 3000
                    //     });
                    // }
                    calendar(response.data);

                }
            }).complete(function(){
                rmBodyProcessing();
            });

        };

        var updateEvent= function(req_type,message_id,start,end=null,cal_event=null,summary){

            var url="{{route('admin.appointment.calendar')}}";
            // var summary='';
            var event_id="";
            if(req_type==1){

            }else{
                // summary=cal_event.title;
                event_id=cal_event.event_id;
            }
            frm_data=   {
                    message_id:message_id,
                    type:req_type,
                    from:start,
                    to:end,
                    timezone:timezone,
                    summary:summary,
                    event_id:event_id,

                };
            // simpleAjaxRq(url, frm_data, true, , ['data'], 'GET', []);
            method='POST';
            addBodyProcessing();

            $.ajax({
                url: url,
                data: frm_data,
                dataType: 'json',
                type: method,
                error: function(r) {
                    var html = '';
                    if(r.status == 422){

                        html = jsonErrorToHtml(r.responseJSON);
                    }else{

                        html = 'Uknown error found.';
                    }

                    $.gritter.add({
                        title: 'Form error!',
                        text: html,
                        time: 8000,
                        class_name: 'gritter-danger'
                    });
                },
                success: function(response) {

                    // if(successMsg){
                    //     $.gritter.add({
                    //         text: successMsg,
                    //         time: 3000
                    //     });
                    // }
                    // calendar(response.data);
                    getCalander();

                }
            }).complete(function(){
                rmBodyProcessing();
            });

        };
    $(document).ready(function() {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

        /* initialize the external events
         -----------------------------------------------------------------*/


        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });

        getCalander();
    });

</script>
@endsection