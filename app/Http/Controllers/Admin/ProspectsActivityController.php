<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Customer\Customer;
use App\Storage\UserAction\UserActionRepository;
use Illuminate\Http\Request;
use \Auth;

class ProspectsActivityController extends Controller {
    protected $crud;

    protected $user_action;

    public function __construct(UserActionRepository $user_action) {
        $this->crud        = new Crud();
        $this->user_action = $user_action;
    }

    public function index(Request $request, $filter = false) {

        $user          = Auth::user();
        $salesRep      = $user->salesRep;
        $layoutColumns = $this->crud->layoutColumn();
        $custId        = $request->get('c');
        $customer      = ($custId ? Customer::find($custId) : null);

        if ($salesRep) {
            if ($filter == 'lookbook') {
                $activities = $this->user_action->getSalesRepCustomerActivities($salesRep, true, false);
            } elseif ($filter == 'request') {
                $activities = $this->user_action->getSalesRepCustomerActivities($salesRep, false, true);
            } else {
                $activities = $this->user_action->getSalesRepCustomerActivities($salesRep);
            }
        } else {
            /**
             * CSR
             */
            if ($filter == 'lookbook') {
                $activities = $this->user_action->getActivities(true, false);
            } elseif ($filter == 'request') {
                $activities = $this->user_action->getActivities(false, true);
            } else {
                $activities = $this->user_action;
            }
        }

        if ($custId) {
            $activities = $activities->userActions($customer->user_id);

        }

        $activities = $activities->orderBy('user_actions.created_at', 'desc')->all();

        if ($customer) {
            $layoutColumns->addItem('admin.customer.lookbook_bottom', ['show_box' => false, 'view_args' => ['customer' => $customer]]);
        }

        $layoutColumns->addItem('admin.prospect.activity', ['view_args' => compact('activities')]);

        return $this->crud->pageView($layoutColumns, compact('filter', 'customer'));
    }
}