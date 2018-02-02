<?php namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryDestroyRequest;
use Illuminate\Http\Request;
use App\Storage\Category\CategoryRepository;

class CategoriesController extends Controller
{
    protected $category;

	public function __construct(CategoryRepository $category)
    {
    	$this->crud = new Crud();
        $this->category = $category;
    }

    public function index(Request $request)
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

    public function show(Request $request, $category_id)
    {
        $category       = $this->category->skipPresenter()->find($category_id);
        $form           = \App\Storage\Category\CategoryCrud::editForm(compact('category'))->getForm();
        $category->form = $form->render();
        
        return response()->json(['data' => $category]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $this->category->create(['name' => $request->get('name'), 
            'opid' => $request->get('opid')]);

        $request->session()->flash('message', 'The category was successfully added!');
        return redirect()->route('admin.categories');            
    }

    public function update(CategoryStoreRequest $request, $category_id)
    {
        $category = $this->category->skipPresenter()->find($category_id);
        $category->name = $request->get('name');
        $category->opid = $request->get('opid');
        $category->save();

        $request->session()->flash('message', 'The category was successfully updated!');
        return redirect()->route('admin.categories');          
    }

    public function destroy(CategoryDestroyRequest $request, $category_id)
    {
        $category = $request->get('category', null);

        $category->delete();

        $request->session()->flash('message', 'The category was successfully deleted!');
        return redirect()->route('admin.categories');    
    }

    public function confirmDestroy(CategoryDestroyRequest $request, $category_id)
    {
        $category = $request->get('category', null);

        return response()
            ->json(['data' => ['id' => $category->id]]);    
    }
}