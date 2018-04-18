<?php namespace App\Http\Controllers\Admin\Brands;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Brand\BrandRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BrandStoreRequest;
use App\Http\Requests\Admin\BrandPutRequest;
use App\Storage\Category\Category;
use App\Storage\LbtWp\LbtWp;
class BrandsController extends Controller
{
    protected $brand;
    protected $lbt_wp;

	public function __construct(BrandRepository $brand)
    {
        $this->brand = $brand;
        $this->crud  = new Crud();
        $this->lbt_wp = new LbtWp();

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

         $brand=   $this->brand->createOne([
                'name'          => $request->get('name'),
                // 'media_logo_id' => $request->get('logo'),
                'description'   => $request->get('desc'),
                'catalog_url'   => $request->get('catalog_url'),
                // 'media_ids'     => $request->get('images'),
                'category'      => $request->get('category'),
                'opid'          => $request->get('opid'),
                'slug'          => $request->get('slug'),
                'image_url'     => $request->get('image_url'),
                'image_link'    => $request->get('image_link'),
                'image_text'    => $request->get('image_text'),


            ]);
        $wp_category_id='';
         foreach ($brand->categories as $category) {
            $wp_category_id=isset($category->wp_category_id)?$category->wp_category_id:"";
            break;
         }

         $meta=[];
         if($request->get('catalog_url')!=''){
            $meta['wpr_brand_catalog']     = $request->get('catalog_url');
         }
         if($request->get('image_url')!=''){
            $meta['wpr_brand_image_url']     = $request->get('image_url');
         }

         if($request->get('image_link')!=''){
            $meta['wpr_brand_image_link']     = $request->get('image_link');
         }

         if($request->get('image_text')!=''){
            $meta['wpr_brand_link_text']     = $request->get('image_text');
         }


            $arr = [
                'name'          => $brand->name,
                'slug'          => $brand->slug,
                'description'   => $brand->description,
                'parent_category' => $wp_category_id ,
                'meta'           => json_encode($meta)
            ];

                //insert wp site database
             $response= $this->lbt_wp->storeCategory($arr);//client()->categories()->save($arr);
             if($response['code']==200){

                    $this->brand->updateWpid($response['data']['wp_brand_id'],$brand->id);
                    $request->session()->flash('message', 'The new brand was successfully added!');
              }else{
                  $brand->delete();
                return redirect()->back()->withErrors($response['errors'])->withInput($request->input());

              }
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
            // $brand->save();

            $wp_category_id='';

            $wp_category_id=isset($category->wp_category_id)?$category->wp_category_id:"";

             $meta=[];
             if($brand->catalog_url!=''){
                $meta['wpr_brand_catalog']     = $brand->catalog_url;
             }
             if($brand->image_url!=''){
                $meta['wpr_brand_image_url']     = $brand->image_url;
             }

             if($brand->image_link!=''){
                $meta['wpr_brand_image_link']     = $brand->image_link;
             }

             if($brand->image_text!=''){
                $meta['wpr_brand_link_text']     = $brand->image_text;
             }


            $arr = [
                'name'          => $brand->name,
                'slug'          => $brand->slug,
                'description'   => $brand->description,
                'parent_category' => $wp_category_id ,
                'meta'           => json_encode($meta)
            ];

            if($brand->wp_brand_id!=''){
                //update wp site database
                $response= $this->lbt_wp->updateCategory($arr,$brand->wp_brand_id);
                if($response['code']==200){
                $brand->categories()->detach();
                $brand->save();
                if($category)
                    {
                        $brand->categories()->save($category);
                    }
                    $request->session()->flash('message', 'The brand was successfully updated!');
                }else{
                      return redirect()->back()->withErrors($response['errors'])->withInput($request->input());
                }
            }else{
                    // $request->session()->flash('message', 'The wp brand  id  not found !');
                     return redirect()->back()->withErrors(['wp_brand_id'=>'The wp brand  id  not found !'])->withInput($request->input());
            }
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

            if($brand->wp_brand_id != ''){
                $arr = [
                    'wp_id' =>$brand->wp_brand_id
                    ];
                $response= $this->lbt_wp->deleteCategory($arr);
                if($response['code']==200){
                    $brand->categories()->detach();
                    $brand->save();
                    $brand->delete();
                    $request->session()->flash('message', 'The brand was successfully deleted!');

                }else{
                     return redirect()->back()->withErrors($response['errors'])->withInput($request->input());
                }
            }else{
                    // $request->session()->flash('message', 'The wp brand  id  not found !');
                     return redirect()->back()->withErrors(['wp_brand_id'=>'The wp brand  id  not found !'])->withInput($request->input());
            }
            return redirect()->route('admin.brands');
         }
        catch (\Exception $e) {
        $request->session()->flash('message', $e->getMessage());
        return redirect()->back();
        }
    }
}