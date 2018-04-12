<?php namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;

use App\Storage\Crud\Crud;
use App\Storage\Appointment\AppointmentRepository;
use App\Storage\Messenger\MessageRepository;

use Illuminate\Http\Request;
use Auth;
use App\Storage\Cronofy\CronofyHttp;

class AppointmentController extends Controller
{
    protected $appointment;

    protected $message;

	public function __construct(AppointmentRepository $appointment,MessageRepository $message)
    {
        $this->appointment = $appointment;
        $this->message = $message;
        $this->crud  = new Crud();
    }
    public function index(Request $request)
    {
        // try{
            $user=Auth::user();

            $k     = 'appt';
            $messages = $this->message->listUnAppointed($user, $k);//->count();

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
             $timezone=$request->timezone!=''?$request->timezone:config('app.timezone');
            // $from=$request->from!=''?$request->from:date('Y-m-1');
            // $to=$request->to!=''?$request->to:date('Y-m-'.cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')));
            $from=date('Y-m-1');
            $to=date('Y-m-'.cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')));

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
                        'calendar_ids' =>[$user->salesrep->cronofy->calendar_id]
                    ];

                $cronofyobj=    $cronofy->read_events($params);
                 // echo '<pre>';print_r($cronofyobj->first_page['events']);
                $data=[];
                foreach ($cronofyobj->first_page['events'] as $event) {

                   $data[] =  [
                        'id'=> $event['event_uid'],
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
                $timezone=$request->timezone!=''?$request->timezone:config('app.timezone');
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
                $event_id='';
                $summary='';

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

                    $params['summary']=$message->body;
                    $params['description']='';

                }else{
                    //1 update cronofy calender
                    $event_id=$id;
                }
                if($end==''){
                // $end=date('Y-m-dTH:i:sZ', strtotime($start.'1 hour'));
                $end=strftime("%Y-%m-%dT%H:%M:%S",strtotime($start.'1 hour')).'Z';
                // dd($end);
                }
                $start=strftime("%Y-%m-%dT%H:%M:%S",strtotime($start)).'Z';
                $params['event_id']=$event_id;
                $params['start']=$start;
                $params['end']=$end;
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