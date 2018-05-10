<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Brand\BrandRepository;
use App\Storage\LocalApiRequest\LocalApiRequest;
use App\Http\Requests\Admin\ProspectRequest;
use App\Http\Requests\Admin\ProspectUpdateRequest;
use App\Storage\MacroBuilder\MacroBuilder;
use App\Storage\User\UserRepository;
use App\Storage\Customer\CustomerCrud;
use App\Storage\ProspectNote\ProspectNote;
//use App\Storage\CustomerSalesrepInfo\CustomerSalesrepInfo;
use App\Storage\FullContact\FullContact;

class ProspectsController extends Controller
{
    use MacroBuilder;

	/**
	 * Crud
	 */
	protected $crud;

	/**
	 * SalesRepRepository
	 */
	protected $salesrep;

    /**
     * CustomerRepository
     */
    protected $customer;

    protected $macros = [
        'admin.prospect.show-activity-row'
    ];

    protected $prospect_note;

    protected $brand;

	/**
	 *
	 * @param      \App\Storage\SalesRep\SalesRepRepository  $salesrep  The salesrep
	 */
    public function __construct(SalesRepRepository $salesrep, CustomerRepository $customer, UserRepository $user, BrandRepository $brand)
    {
        $this->macroCall();

    	$this->crud = new Crud();
    	$this->salesrep = $salesrep;
        $this->customer = $customer;
        $this->user = $user;
        $this->prospect_note = new ProspectNote();
        $this->brand = $brand;
    }

    /**
     *
     * @param      ProspectRequest $request  The request
     *
     * @return     \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(ProspectRequest $request)
    {
        $sort       = $request->get('sort', 'asc');
        $search     = $request->get('s', null);
        $user       = $request->get('user');
        $salesRep   = $request->get('salesrep');
        $searchForm = \App\Storage\Customer\CustomerCrud::searchField($request);

        /**
         * Create layout
         */
		$layoutColumns = $this->crud->layoutColumn();

        /**
         * Create form
         */
        $layoutColumns->addItemForm($searchForm);

        /**
         * Create table
         */
        $query = ['sort' => $sort, 'field' => 'lastname', 'limit' => 50];

        if($salesRep){
            $query['id'] = $salesRep->id;
        }

        if($search){
            $query['s'] = $search;
        }

        if($salesRep)
        {

            $url = LocalApiRequest::requestUrl('api.v1.brands-associates.customers', $query);
        }else{

            $url = LocalApiRequest::requestUrl('api.v1.customers', $query);
        }

        $table = CustomerCrud::ajaxUserTable(null, $url);
        $layoutColumns->addItemTable($table, null, ['view_args' => ['url' => $url]]);

        /**
         * Create modal
         */
        $layoutColumns->addItem('admin.prospect.filter-modal', ['show_box' => false]);

		return $this->crud->pageView($layoutColumns);
    }

    /**
     * Show customer info.
     *
     * @param      ProspectRequest       $request      The request
     * @param      Integer 					 $customer_id  The customer identifier
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(ProspectRequest $request, $customer_id)
    {
        $fullContact           = new FullContact();
        $user                  = $request->get('user');
        $iscsr                 = $user->hasRole('csr');
        $customerData          = $this->customer->completePresenter()->find($customer_id);
        $customer              = $customerData['data'];
        $email                 = $customer['email'];
        $fc                    = $fullContact->personLookupByEmail($email);
        $brands                = $this->brand->skipPresenter()->customerBrands($customer_id)->all();
        $notes                 = $this->prospect_note->noteMessages($user->id, $customer['user_id']);
        $layoutColumns         = $this->crud->layoutColumn();
        $deleteUrl             = route('admin.prospects.delete', ['customer_id' => $customer_id]);
        $customer['firstname'] = ($request->get('firstname') ? $request->get('firstname') :  $customer['firstname']);
        $customer['lastname']  = ($request->get('lastname') ? $request->get('lastname') :  $customer['lastname']);
        $customer['email']     = ($request->get('email') ? $request->get('email') :  $customer['email']);
        $args                  = array(
            'box_title'   => 'Basic Info',
            'show_box'    => false,
            'column_size' => 12,
            'view_args'   => compact('customer', 'notes', 'iscsr', 'brands', 'fc', 'user'));
        $modalMsg = 'Are you sure you would like to permanently delete this prospect?';

        $layoutColumns->addItem('admin.prospect.parts.message-assign-now', ['show_box' => false]);
        // $layoutColumns->addItem('admin.partials.table-top-delete', ['view_args' => compact('deleteUrl', 'modalMsg'), 'show_box' => false, 'column_class' => 'm-b-md text-right']);
        $layoutColumns->addItem('admin.prospect.show', $args);
        $this->crud->setExtra('sidemenu_active', 'admin_prospects');

		return $this->crud->pageView($layoutColumns);
    }

    public function postNote(ProspectRequest $request, $customer_id)
    {
        $user       = $request->get('user');
        $salesRep   = $request->get('salesrep');
        $redirectTo = $request->get('redirect_to');

        if($user->hasRole('csr')){
            $customer   = $this->customer->skipPresenter()->find($customer_id);
        }else{
            $customer   = $salesRep->customers()->find($customer_id);
        }

        $this->prospect_note->postNote($user->id, $customer->user->id, $request->get('note'));

        \Session::flash('message', 'Note has been posted!');

        return redirect($redirectTo);
    }

    /**
     * Lookbook a prospect
     *
     * @param      \Illuminate\Http\Request  $request      The request
     * @param      Integer                   $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function offers(Request $request, $customer_id)
    {
        $customer      = $this->customer->skipPresenter()->find($customer_id);
        $salesRep      = $this->salesrep->currentUser();
        $layoutColumns = $this->crud->layoutColumn();
        $orderBy       = $request->get('field');
        $order         = $request->get('sort', 'desc');
        $model         = $customer->pivotOffers();

        $model->orderBy('updated_at', $order);

        $model = $model->paginate(20);
        $layoutColumns->addItem('admin.customer.lookbook_bottom', ['show_box' => false, 'view_args' => [ 'customer' => $customer ]]);
        $layoutColumns->addItemTable('App\Storage\Customer\CustomerCrud@lookbookTable', $model, ['box_title' => 'Lookbook Offers']);

        return $this->crud->pageView($layoutColumns, $customer);
    }

    /**
     * Update existing prospect.
     *
     * @param      \App\Http\Requests\Admin\ProspectUpdateRequest  $request      The request
     * @param      Integer                                          $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function update(ProspectUpdateRequest $request, $customer_id)
    {
        $customer = $this->customer->skipPresenter()->find($customer_id);

        $customer->user->email     = $request->get('email');
        $customer->user->lastname  = $request->get('lastname');
        $customer->user->firstname = $request->get('firstname');
        $customer->user->save();

        $customer->update($request->only([
            'zip', 'state', 'city', 'address2', 'address1', 'officephone', 'homephone', 'cellphone', 'geo_long', 'geo_lat'
        ]));

        $request->session()->flash('message', "Prospect information has been updated!");
        return redirect()->route('admin.prospects.show', ['customer_id' => $customer_id]);
    }

    /**
     * Delete prospect.
     *
     * @param      \Illuminate\Http\Request  $request      The request
     * @param      Integer                    $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function delete(Request $request, $customer_id)
    {
        $customer = $this->customer->skipPresenter()->find($customer_id);
        $user     = $customer->user;

        $customer->offers()->detach();
        $customer->salesReps()->detach();

        $customer->delete();
        $user->delete();

        $request->session()->flash('message', "Prospect has been deleted!");
        return redirect()->route('admin.prospects');
    }
}
