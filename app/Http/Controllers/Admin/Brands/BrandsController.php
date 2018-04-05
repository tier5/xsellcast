<?php namespace App\Http\Controllers\Admin\Brands;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Brand\BrandRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BrandStoreRequest;
use App\Http\Requests\Admin\BrandPutRequest;
use App\Storage\Category\Category;

class BrandsController extends Controller
{
    protected $brand;

	public function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
        $this->crud  = new Crud();
    }

    public function index(Request $request)
    {
        try{

            $layoutColumns = $this->crud->layoutColumn();
            $model         = $this->brand->skipPresenter();
            $table         = 'App\Storage\Brand\BrandCrud@table';
            $order         = $request->get('sort', 'desc');
            $orderBy       = $request->get('field', 'created_at');

            switch ($orderBy) {
                case 'name':
                    $model->orderBy('name', $order);
                    break;
                case 'category':
                    $model->orderByCategoryName($order);
                default:
                    $model->orderBy('created_at', $order);
                    break;
            }

            $layoutColumns->addItemTable($table, $model->paginate(20));

            return $this->crud->pageView($layoutColumns);
        }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }

    public function create()
    {
        try{

        	$layoutColumns = $this->crud->layoutColumn();

            $layoutColumns->addItemForm('App\Storage\Brand\BrandCrud@createForm');
            $this->crud->setExtra('sidemenu_active', 'admin_brand');

    		return $this->crud->pageView($layoutColumns);
        }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }

    public function store(BrandStoreRequest $request)
    {
        try{

            $this->brand->createOne([
                'name'          => $request->get('name'),
                'media_logo_id' => $request->get('logo'),
                'description'   => $request->get('desc'),
                'catalog_url'   => $request->get('catalog_url'),
                'media_ids'     => $request->get('images'),
                'category'      => $request->get('category'),
                'opid'          => $request->get('opid'),
                'slug'          => $request->get('slug'),
                'image_url'     => $request->get('image_url'),
                'image_link'    => $request->get('image_link'),
                'image_text'    => $request->get('image_text'),


            ]);

            $request->session()->flash('message', 'The new brand was successfully added!');
            return redirect()->route('admin.brands');
         }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }

    }

    public function edit(Request $request, $brand_id)
    {
        try{
            $brand = $this->brand->skipPresenter()->find($brand_id);

            if(!$brand)
            {
                abort(402, "Brand don't exist.");
            }

            $layoutColumns = $this->crud->layoutColumn();
            $deleteUrl = route('admin.brands.delete', ['brand_id' => $brand->id]);
            $modalMsg = 'Are you sure you would like to permanently delete this brand?';

            $layoutColumns->addItem('admin.partials.table-top-delete',
                [
                    'view_args' => compact('deleteUrl', 'modalMsg'),
                    'show_box' => false,
                    'column_class' => 'm-b-md text-right']);
            $layoutColumns->addItemForm('App\Storage\Brand\BrandCrud@editForm', compact('brand'));
            $this->crud->setExtra('sidemenu_active', 'admin_brand');

            return $this->crud->pageView($layoutColumns);
         }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }

    public function update(BrandPutRequest $request, $brand_id)
    {
        try{
            $brand = $this->brand->skipPresenter()->find($brand_id);

            if(!$brand)
            {
                abort(402, "Brand don't exist.");
            }

            $category = Category::find($request->get('category'));

            $brand->name = $request->get('name');
            $brand->media_logo_id = $request->get('logo');
            $brand->description = $request->get('desc');
            $brand->catalog_url = $request->get('catalog_url');
            $brand->media_ids    = ($request->get('images') ? implode(',', $request->get('images')) : '' );
            $brand->opid = $request->get('opid');
            $brand->slug = $request->get('slug');
            $brand->image_url = $request->get('image_url');
            $brand->image_link = $request->get('image_link');
            $brand->image_text = $request->get('image_text');

            $brand->categories()->detach();
            $brand->save();

            if($category)
            {
                $brand->categories()->save($category);
            }

            $request->session()->flash('message', 'The brand was successfully updated!');
            return redirect()->route('admin.brands');

        }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }

    public function destroy(Request $request, $brand_id)
    {   try{
            $brand = $this->brand->skipPresenter()->find($brand_id);

            if(!$brand)
            {
                abort(402, "Brand don't exist.");
            }

            $brand->categories()->detach();
            $brand->save();
            $brand->delete();

            $request->session()->flash('message', 'The brand was successfully deleted!');
            return redirect()->route('admin.brands');
         }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }
}