<?php namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;

use App\Storage\Crud\Crud;
use App\Storage\Appointment\AppointmentRepository;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Offer\OfferRepository;

use Illuminate\Http\Request;
use Auth;
use App\Storage\Cronofy\CronofyHttp;

class AppointmentController extends Controller
{
    protected $appointment;

    protected $message;

	public function __construct(AppointmentRepository $appointment,MessageRepository $message,OfferRepository $offer)
    {
        $this->appointment = $appointment;
        $this->message = $message;
        $this->offer=$offer;
        $this->crud  = new Crud();
    }
    public function index(Request $request)
    {
        // try{
            $user=Auth::user();

            $k     = 'appt';
            $messages = $this->message->listUnAppointed($user, $k)->get();//->count();
            // echo '<pre>'; print_r($messages);
             // $this->message->baseGetAll($user, $search, $type)->get();
foreach ($messages['data'] as $key => $message) {
   $offer_id= $this->message->skipPresenter()->find($message['id'])->thread->getMeta('offer_id');
$messages['data'][$key]['offer']=$this->offer->skipPresenter()->find($offer_id);
// dd($messages['data'][$key]['offer']);
}
            // echo '<pre>'; print_r($messages);

            if($user->salesrep->cronofy==null){
                $request->session()->flash('message', 'The cronofy details Not find. Please configure cronofy settings !');
                return redirect()->back();
            }
            $layoutColumns = $this->crud->layoutColumn();
            $layoutColumns->addItem('admin.appointment.calendar',
            ['show_box' => false, 'view_args' => compact('messages'), 'column_size' => 12]);
            return $this->crud->pageView($layoutColumns);
        // }
        // catch (\Exception $e) {
        // $request->session()->flash('message', $e->getMessage());
        // return redirect()->back();
        // }
    }

    public function calendar(Request $request){
     // try{
            $user=Auth::user();
             // $timezone=$request->timezone!=''?$request->timezone:config('app.timezone');
               $timezone=config('app.timezone');
            // $from=$request->from!=''?$request->from:date('Y-m-1');
            // $to=$request->to!=''?$request->to:date('Y-m-'.cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')));
            $from=date('Y-m-d', strtotime("-1 month"));
            // $to=date('Y-m-').cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
            $to=date('Y-m-d',strtotime("+1 month"));

            if($user->salesrep->cronofy==null){
                $request->session()->flash('message', 'The cronofy details Not find. Please configure cronofy settings !');
                return redirect()->back();
            }
            // dd($to);
            try{
                $cronofy=new CronofyHttp();
                $cronofy->client_id=$user->salesrep->cronofy->client_id;
                $cronofy->client_secret=$user->salesrep->cronofy->client_secret;
                $cronofy->access_token=$user->salesrep->cronofy->token;

                $params=[
                        'tzid'  =>$timezone,
                        'from'  => $from,
                        'to'    => $to,
                        'include_managed' =>1,
                        // 'localized_times'=>1,
                        'calendar_ids' =>[$user->salesrep->cronofy->calendar_id]
                    ];


                $cronofyobj=    $cronofy->read_events($params);
                 // echo '<pre>';print_r($cronofyobj->first_page['events']);
                $data=[];
                foreach ($cronofyobj->first_page['events'] as $event) {
                    // dd($event);
                   $data[] =  [
                        'id'=> $event['event_uid'],
                        'event_id'=> $event['event_id'],
                        'title'=> $event['summary'],
                        'start'=>  $event['start'],
                        'end'=> $event['end'],
                        'allDay'=> false
                        ];
                }

                $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    'data'=> $data
                    ];


                return response()->json($data, config('responses.success.status_code'));

                // }catch (\Exception $e) {
                //     // $request->session()->flash('message', 'The cronofy details is invalid!');
                //     return response()->json([
                //         'status'=>false,
                //         'code'=>config('responses.bad_request.status_code'),
                //         'data'=>null,
                //         'message'=>'The cronofy details is invalid!'
                //         ],
                //         config('responses.bad_request.status_code')
                //     );
                // }


        }
        catch (\Exception $e) {

        return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }


    public function store(Request $request){
         try{
                $user=Auth::user();
                // $timezone=$request->timezone!=''?$request->timezone:config('app.timezone');
                // $request->timezone!=''?$request->timezone:
                $timezone=config('app.timezone');
                // $from=$request->from!=''?$request->from:date('Y-m-1');
                // $to=$request->to!=''?$request->to:date('Y-m-'.cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')));
                // $start=date('Y-m-1');
                // $end=date('Y-m-'.cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')));
                $type=$request->type;
                $start=$request->from;//2018-04-12
                // $start=date('Y-m-d%TH:i:s%Z', strtotime($start)); //2018-04-12T00:00:00Z
                // $start=strftime("%Y-%m-%dT%H:%M:%S",strtotime($start)).'Z';

                // dd($start);
                $end=$request->to;
                $timezone=$request->timezone;
                $id=$request->message_id;
                $summary=$request->summary;
                $event_id=$request->event_id;

                if($user->salesrep->cronofy==null){
                    $request->session()->flash('message', 'The cronofy details Not find. Please configure cronofy settings !');
                    return redirect()->back();
                }
            // dd($to);
            // try{
                $cronofy=new CronofyHttp();
                $cronofy->client_id=$user->salesrep->cronofy->client_id;
                $cronofy->client_secret=$user->salesrep->cronofy->client_secret;
                $cronofy->access_token=$user->salesrep->cronofy->token;
                $params=[
                        'tzid'  =>$timezone,
                        'calendar_id' =>$user->salesrep->cronofy->calendar_id,
                        ];
                if($type==1){
                    //1 add appointment created table
                    $message=$this->message->skipPresenter()->find($id);
                    $message->markAsAppointed($user->id);

                    $appointment=$message->messageAppointment()->first();

                    $event_id=$appointment->id;

                    $params['summary']= $summary;
                    $params['description']='';

                }else{
                    //1 update cronofy calender
                    // $event_id=$event_id;
                    $params['summary']=$summary;
                    $params['description']='';
                }
                if($end==''){
                // $end=date('Y-m-dTH:i:sZ', strtotime($start.'1 hour'));
                $end=strftime("%Y-%m-%dT%H:%M:%S",strtotime($start.'1 hour')).'Z';
                // dd($end);
                }else {
                    $end=strftime("%Y-%m-%dT%H:%M:%S",strtotime($end)).'Z';
                    // $end=date('Y-m-d H:i:s',strtotime($end));
                }
                $start=strftime("%Y-%m-%dT%H:%M:%S",strtotime($start)).'Z';
                // $start=strftime('Y-m-d H:i:s',strtotime($start));
                $params['event_id']=$event_id;
                $params['start']=['time'=>$start,'tzid'  =>$timezone];
                $params['end']=['time'=>$end,'tzid'  =>$timezone];
                // dd($params);
                //2 add in cronofy calender
                $cronofyobj=    $cronofy->upsert_event($params);
                 // echo '<pre>';print_r($cronofyobj->first_page['events']);

                $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    'data'=> ['success']
                    ];


                return response()->json($data, config('responses.success.status_code'));

                // }catch (\Exception $e) {
                //     // $request->session()->flash('message', 'The cronofy details is invalid!');
                //     return response()->json([
                //         'status'=>false,
                //         'code'=>config('responses.bad_request.status_code'),
                //         'data'=>null,
                //         'message'=>'The cronofy details is invalid!'
                //         ],
                //         config('responses.bad_request.status_code')
                //     );
                // }


        }
        catch (\Exception $e) {

        return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
}