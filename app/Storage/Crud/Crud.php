<?php namespace App\Storage\Crud;

use App\Storage\Crud\LayoutColumn;
use App\Storage\Crud\CrudForm;
use App\Storage\Menu\Menu;
//use Menu;
use App\Storage\Menu\Builder;
use App\Storage\Crud\Box;
use \App\Storage\Page\Pages;

class Crud{

    protected $layout_title = null;

    protected $breadcrumb = null;

    protected $layout_columns = null;

    protected $extra = null;

    protected $menu;

    public function __construct()
    {
        $this->menu = new Menu();
      //  $this->breadcrumb = $this->menu->make('breadcrumb', function($menu){
      //      $menu->Add('Home', 'home');
      //  });

    }

    public function crudForm($method)
    {
        return new CrudForm($method);
    }

    public function layoutColumn()
    {
        return new LayoutColumn();
    }
   
    public function setLayoutColumns(LayoutColumn $layout_column)
    {
        $this->layout_columns = $layout_column;
    }

    public function getLayoutColumns()
    {
        return $this->layout_columns;
    }

    public function setExtra($key, $val, $is_arr = null)
    {
        if($is_arr){

            $this->extra[$key][] = $val;
        }else{

            $this->extra[$key] = $val;
        }
        
        return $this;
    }

    public function getExtra($key)
    {
        if(isset($this->extra[$key]))
        {

            return $this->extra[$key];
        }

        return false;
    }

    public function pageView($layoutColumns = null, $vars = null)
    {
        $this->pageSetup($vars);
        if($layoutColumns){
            $this->setLayoutColumns($layoutColumns);
        }

        $columns = $this->getLayoutColumns();
        $layout = array(
            'page_title' => $this->getLayoutTitle(),
            'breadcrumb' => $this->getBreadCrumb(),
            'extra' => $this->extra
        );

        return view('admin.crud.index', compact(
            'layout',
            'columns',
            'extra'
        ));        
    }

    protected function pageSetup($vars)
    {
        $page = Pages::init($vars);

        $currentPage = $page->getCurrent();

      // $config = $this->pageConfig();
        $layoutTitle      = ($currentPage ? $currentPage->getTitle() : '');
      //  $sidemenuActive   = null; //array_get($config, 'sidemenu_active');
        $this->breadcrumb = ($currentPage ? $currentPage->getBreadcrumb() : ''); //$currentPage->getBreadcrumb(); //array_get($config, 'breandcrumb');

        if($this->getLayoutTitle() == '' && $layoutTitle)
        {
            $this->setLayoutTitle($layoutTitle);
        }
    }

    public function getLayoutTitle()
    {
        return ($this->layout_title ? $this->layout_title : '');
    }

    public function setLayoutTitle($str)
    {
        $this->layout_title = $str;

        return $this;
    }

    public function addPageBtn($lbl, $url, $attr = array())
    {
        $this->setExtra('page_btn', array('label' => $lbl, 'url' => $url, 'attr' => $attr), true);

        return $this;
    }

    public function getBreadCrumb()
    {
        $breadcrumb = $this->breadcrumb;

        return ($this->breadcrumb ? $this->breadcrumb : null);
    }

    public function setBreadCrumb($arr)
    {
        $this->breadcrumb = $arr;

        return $this;
    }

    /**
     * @param $model Eloquent Model
     *
     * @return TableCollection
     */
    public function tableCollection($model)
    {
        $return = new TableCollection();

        return $return->make($model);
    }

    public function box($info)
    {
        return new Box($info);
    }
}