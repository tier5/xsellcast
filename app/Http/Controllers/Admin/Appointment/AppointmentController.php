<?php namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Appointment\AppointmentRepository;
use Illuminate\Http\Request;
use Auth;
use App\Storage\Cronofy\CronofyHttp;

class AppointmentController extends Controller
{
    protected $appointment;

	public function __construct(AppointmentRepository $appointment)
    {
        $this->appointment = $appointment;
        $this->crud  = new Crud();
    }
    public function index(Request $request)
    {
        try{
            $user=Auth::user();

            if($user->salesrep->cronofy==null){
                $request->session()->flash('message', 'The cronofy details Not find. Please configure cronofy settings !');
                return redirect()->back();
            }
            $layoutColumns = $this->crud->layoutColumn();
            $layoutColumns->addItem('admin.appointment.calendar');
            return $this->crud->pageView($layoutColumns);
        }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }

    public function calendar(Request $request){
     // try{
            $user=Auth::user();
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
                        'tzid'  =>config('app.timezone'),
                        'from'  => $from,
                        'to'    => $to,
                        'include_managed' =>1,
                        'calendar_ids' =>[$user->salesrep->cronofy->calendar_id]
                    ];

                $cronofyobj=    $cronofy->read_events($params);
                $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    'data'=> $cronofyobj
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