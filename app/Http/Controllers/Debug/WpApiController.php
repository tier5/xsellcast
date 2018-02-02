<?php

namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\LbtWp\LbtWp;
use App\Storage\Crud\TableCollection;

class WpApiController extends Controller
{
	protected $crud;

	protected $lbt_wp;

    public function __construct()
    {
    	$this->crud = new Crud();
    	$this->lbt_wp = new LbtWp();

    }

    public function index(Request $request)
    {
    	$posts = collect($this->lbt_wp->posts()->get());
    	$offers = collect($this->lbt_wp->offers()->get());
    	$postTable = $this->postTable($posts);
    	$offerTable = $this->offerTable($offers);
    	$layoutColumns = $this->crud->layoutColumn();

    	$layoutColumns->addItemForm($this->postForm());
    	$layoutColumns->addItemTable($postTable, null);
    	$layoutColumns->addItemForm($this->offerForm());
    	$layoutColumns->addItemTable($offerTable, null);

		$this->crud->setLayoutTitle('LBT - WP API');
		$this->crud->getBreadCrumb()->add('LBT - WP API');

		return $this->crud->pageView($layoutColumns);
    }

    protected function offerForm()
    {
      $fields = $this->crud->crudForm('post');
      $fields->setRoute('debug.wp.api.offer.store');
      $fields->addField(array(
        'name' => 'title',
        'label' => 'Title',
        'type' => 'text',
        'col-class' => 'col-md-9'));

      $fields->noRedirectField();

      $info = array(
        'box_title' => 'Create Offer', 
        'column_size' => 12,
        'column_class' => 'col-sm-12 col-xs-12');

      $box = $this->crud->box($info);
      $box->setForm($fields);

      return $box;    	
    }

    protected function postForm()
    {
      $fields = $this->crud->crudForm('post');
      $fields->setRoute('debug.wp.api.post.store');
      $fields->addField(array(
        'name' => 'title',
        'label' => 'Title',
        'type' => 'text',
        'col-class' => 'col-md-9'));

      $fields->addField(array(
        'name' => 'content',
        'label' => 'Content',
        'type' => 'textarea',
        'col-class' => 'col-md-12'));

      $fields->setSubmitText('Post to LBT');
      $fields->noRedirectField();

      $info = array(
        'box_title' => 'Create Post Blog', 
        'column_size' => 12,
        'column_class' => 'col-sm-12 col-xs-12');

      $box = $this->crud->box($info);
      $box->setForm($fields);

      return $box;
    }    

    public function postStore(Request $request)
    {
    	$arr = [
    		'title' => $request->get('title'),
    		'content' => $request->get('content'),
    		'status' => 'publish'];

    	$this->lbt_wp->posts()->save($arr);

		\Session::flash('message', 'Blog post has been added.');

		return redirect()->route('debug.wp.api');	    	
    }

    protected function postTable($collect)
    {
		$table = new TableCollection();
		$all = $collect;
		$info = array(  'box_title' => 'Blog Posts', 
			'column_size' => 12, 
			'column_class' => 'col-sm-12 col-xs-12',
			'box_float' => 'left');

		$table = $table->make($all)
			->columns(array(
				'title' => 'Title',
				'content' => 'Content'
			))
			->modify('title', function($r){

				return $r['title']['rendered'];
			})
			->modify('content', function($r){

				return $r['content']['rendered'];
			})
			->toActionShow(false)
			->sortable(array('title'));

		$box = $this->crud->box($info);
		$box->setTable($table);    

		return $box;     	
    }

    protected function offerTable($collect)
    {
		$table = new TableCollection();
		$all = $collect;
		$info = array(  'box_title' => 'Offers', 
			'column_size' => 12, 
			'column_class' => 'col-sm-12 col-xs-12',
			'box_float' => 'left');

		$table = $table->make($all)
			->columns(array(
				'title' => 'Title'
			))
			->modify('title', function($r){

				return $r['title']['rendered'];
			})
			->toActionShow(false)
			->sortable(array('title'));

		$box = $this->crud->box($info);
		$box->setTable($table);    

		return $box;     	
    }    
}