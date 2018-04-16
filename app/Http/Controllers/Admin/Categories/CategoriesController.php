<?php namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryPutRequest;
use App\Http\Requests\Admin\CategoryDestroyRequest;
use Illuminate\Http\Request;
use App\Storage\Category\CategoryRepository;
use App\Storage\LbtWp\LbtWp;


class CategoriesController extends Controller
{
    protected $category;
    protected $lbt_wp;

	public function __construct(CategoryRepository $category)
    {
    	$this->crud = new Crud();
        $this->category = $category;
        $this->lbt_wp = new LbtWp();
    }

    public function index(Request $request)
    {
        try
        {
            $sort          = $request->get('sort', 'desc');
            $sortBy        = $request->get('field', 'created_at');
            $layoutColumns = $this->crud->layoutColumn();
            $model         = $this->category->skipPresenter(); //->paginate(20);
            $category      = ($request->route('category_id') ? $this->category->skipPresenter()->find($request->route('category_id')) : null);

            $layoutColumns->addItem('admin.categories.top', ['show_box' => false]);
            $layoutColumns->addItemForm('App\Storage\Category\CategoryCrud@createForm', ['column_id' => 'category_create_box', 'column_class' => 'collapse']);

            if($category)
            {
                $layoutColumns->addItemForm('App\Storage\Category\CategoryCrud@editForm', compact('category'));
            }

            switch($sortBy)
            {
                case 'name':
                    $model = $model->orderBy('name', $sort);
                    break;
                default:
                    $model = $model->orderBy('created_at', $sort);
                    break;
            }

            $layoutColumns->addItemTable('App\Storage\Category\CategoryCrud@table', $model->paginate(20), ['column_size' => 12]);

            $this->crud->setExtra('sidemenu_active', 'admin_categories');

            return $this->crud->pageView($layoutColumns);

        }
        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function show(Request $request, $category_id)
    {
        try{
            $category       = $this->category->skipPresenter()->find($category_id);
            $form           = \App\Storage\Category\CategoryCrud::editForm(compact('category'))->getForm();
            $category->form = $form->render();

            return response()->json(['data' => $category]);
        }
        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        try{
            // $category=  $this->category->create([
            //     'name' => $request->get('name'),
            //     'opid' => $request->get('opid'),
            //     'slug' => $request->get('slug')
            // ]);
          // dd($category);
            // $arr = [
            //     'name' => $category->name,
            //     'slug' => $category->slug,
            // ];
            $arr = [
                'name' => $request->get('name'),
                'slug' => $request->get('slug')
            ];

           $data= $this->lbt_wp->storeCategory($arr);//client()->categories()->save($arr);
           dd($data);
            $request->session()->flash('message', 'The category was successfully added!');
            return redirect()->route('admin.categories');

        }
        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function update(CategoryPutRequest $request, $category_id)
    {
        try{
            $category = $this->category->skipPresenter()->find($category_id);
            $category->name = $request->get('name');
            $category->opid = $request->get('opid');
            $category->slug = $request->get('slug');
            $category->save();

            $request->session()->flash('message', 'The category was successfully updated!');
            return redirect()->route('admin.categories');
        }
        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(CategoryDestroyRequest $request, $category_id)
    {
        try{
            $category = $request->get('category', null);

            $category->delete();


            $request->session()->flash('message', 'The category was successfully deleted!');
            return redirect()->route('admin.categories');
         }
        catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function confirmDestroy(CategoryDestroyRequest $request, $category_id)
    {

        $category = $request->get('category', null);

        return response()
            ->json(['data' => ['id' => $category->id]]);
    }
}