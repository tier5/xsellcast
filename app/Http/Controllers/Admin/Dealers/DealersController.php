<?php namespace App\Http\Controllers\Admin\Dealers;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Illuminate\Http\Request;
use App\Storage\Dealer\DealerRepository;
use App\Http\Requests\Admin\DealerStoreRequest;
use App\Http\Requests\Admin\DealerUpdateRequest;

class DealersController extends Controller
{
    protected $dealer;

	public function __construct(DealerRepository $dealer)
    {
        $this->crud   = new Crud();
        $this->dealer = $dealer;
    }

    public function index(Request $request)
    {
        $layoutColumns = $this->crud->layoutColumn();        
        $model         = $this->dealer->skipPresenter();
        $sort          = $request->get('sort', 'asc');
        $sortBy        = $request->get('field', 'name');
        
        switch($sortBy)
        {   
            case 'name':
                $model = $model->orderByName($sort);
                break;
            case 'brand':
                $model = $model->orderByBrand($sort);
                break;
            case 'city':
                $model = $model->orderByCity($sort);
                break;
            case 'state':
                $model = $model->orderByState($sort);
                break;
            case 'zip':
                $model = $model->orderByZip($sort);
                break;
            default:
                $model = $model->orderBy('created_at', $sort);
                break;
        }

        $layoutColumns->addItemTable('App\Storage\Dealer\DealerCrud@table', $model->paginate(20), ['column_size' => 12]);  
        $this->crud->setExtra('sidemenu_active', 'admin_dealer');

        return $this->crud->pageView($layoutColumns);
    }

    public function create()
    {
    	$layoutColumns = $this->crud->layoutColumn();

        $layoutColumns->addItemForm('App\Storage\Dealer\DealerCrud@createForm');
        $this->crud->setExtra('sidemenu_active', 'admin_dealer');

		return $this->crud->pageView($layoutColumns);
    }

    public function store(DealerStoreRequest $request)
    {        
        $logo               = $request->get('logo');
        $name               = $request->get('name');
        $desc               = $request->get('desc');
        $website            = $request->get('website');
        $phone              = $request->get('phone');
        $fax                = $request->get('fax');
        $address1           = $request->get('address_1');
        $address2           = $request->get('address_2');
        $city               = $request->get('city');
        $state              = $request->get('state');
        $zip                = $request->get('zip');
        $hours_of_operation = $request->get('hours_of_operation');
        $brand              = $request->get('brand');

        $this->dealer->createOne([
            'description' => $desc, 'logo_media_id' => $logo,'address2' => $address2, 'hours_of_operation' => $hours_of_operation, 'website' => $website, 'fax' => $fax, 'phone' => $phone, 'state' => $state, 'zip' => $zip, 'city' => $city, 'address1' => $address1, 'name' => $name, 'brand' => $brand]);

        $request->session()->flash('message', "The new dealer was successfully added!");
        return redirect()->route('admin.dealers');             
    }

    public function edit(Request $request, $dealer_id)
    {
        $dealer        = $this->dealer->skipPresenter()->find($dealer_id);
        $layoutColumns = $this->crud->layoutColumn();

        if(!$dealer)
        {
            abort(422, 'Invalid dealer ID.');
        }

        $deleteUrl = route('admin.dealers.delete', ['dealer_id' => $dealer->id]);
        $modalMsg = 'Are you sure you would like to permanently delete this dealer?';

        $layoutColumns->addItem('admin.partials.table-top-delete', ['view_args' => compact('deleteUrl', 'modalMsg'), 'show_box' => false, 'column_class' => 'm-b-md text-right']);
        $layoutColumns->addItemForm('App\Storage\Dealer\DealerCrud@editForm', compact('dealer'));
        $this->crud->setExtra('sidemenu_active', 'admin_dealers');

        return $this->crud->pageView($layoutColumns);

    }

    public function update(DealerUpdateRequest $request, $dealer_id)
    {
        $dealer = $request->get('dealer');

        if(!$dealer)
        {
            abort(422, 'Invalid dealer ID.');
        }        

        $logo               = $request->get('logo');
        $name               = $request->get('name');
        $desc               = $request->get('description');
        $website            = $request->get('website');
        $phone              = $request->get('phone');
        $fax                = $request->get('fax');
        $address1           = $request->get('address1');
        $address2           = $request->get('address2');
        $city               = $request->get('city');
        $state              = $request->get('state');
        $zip                = $request->get('zip');
        $hours_of_operation = $request->get('hours_of_operation');
        $brand              = $request->get('brand');

        $this->dealer->updateOne($dealer, [
            'description' => $desc, 'logo_media_id' => $logo,'address2' => $address2, 'hours_of_operation' => $hours_of_operation, 'website' => $website, 'fax' => $fax, 'phone' => $phone, 'state' => $state, 'zip' => $zip, 'city' => $city, 'address1' => $address1, 'name' => $name, 'brand' => $brand]);

        $request->session()->flash('message', "The new dealer was successfully updated!");
        return redirect()->route('admin.dealers');
    }

    public function destroy(Request $request, $dealer_id)
    {
        $dealer = $this->dealer->skipPresenter()->find($dealer_id);

        if(!$dealer)
        {
            abort(402, "Dealer don't exist.");
        }
        
        $dealer->brands()->detach();
        $dealer->save();
        $dealer->delete();

        $request->session()->flash('message', 'The dealer was successfully deleted!');
        return redirect()->route('admin.dealers');                    
    }    
}