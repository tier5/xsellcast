<?php namespace App\Http\Controllers\Admin\Appointment;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Appointment\AppointmentRepository;
use Illuminate\Http\Request;

use App\Storage\Category\Category;

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

            $layoutColumns = $this->crud->layoutColumn();
            $layoutColumns->addItem('admin.appointment.calendar');
            return $this->crud->pageView($layoutColumns);
        }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }
}