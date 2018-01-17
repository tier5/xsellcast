<?php
/**
 * Created by PhpStorm.
 * User: Alvin
 * Date: 8/25/2016
 * Time: 6:22 AM
 */

namespace App\Storage\Crud;

use Stevebauman\EloquentTable\TableCollection as BaseTableCollection;


class TableCollection extends BaseTableCollection{

    protected $table_label;

    protected $edit_route;

    protected $destroy_route;

    protected $actions = array();

    protected $model = null;

    protected $model_paginate = null;

    public $actionFieldModification = array();

    protected $show_action_column = true;

    protected $use_default_actions = true;

    protected $view = null;

    protected $extra = null;

    protected $attr = array();

    protected $show_header = true;

    protected $use_default_header_class = true;

    protected $destroy_msg = 'Are you sure to delete?';

    public function addAttribute($key, $val)
    {
        $this->attr[$key] = $val;

        return $this;
    }

    /**
     * Generates view for the table.
     *
     * @param string $view
     *
     * @return mixed
     */
    public function render($view = 'admin.crud.table.laravel-5-table')
    {
        if($this->getView()){
            $view = $this->getView();
        }

        $class = ($this->use_default_header_class ? "table table-striped table-bordered " : '');
        
        if(isset($this->attr['class'])){
            $this->attr['class'] .= " " . $class;
        }else{
            $this->attr['class'] = $class;
        }

        //Set table class
        $this->attributes($this->attr);

        //Show only if action is set enabled.
        if($this->isActionShow()){
            $this->eloquentTableColumns['actions'] = $this->getActionColumnLabel();
            $this->modify('actions', function($row){

                return $this->actionColumn($row);
            });
        }

        return parent::render($view);
    }

    public function getView()
    {
        return $this->view;
    }

    public function setDestroyMsg($msg)
    {
        $this->destroy_msg = $msg;

        return $this;
    }

    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    public function addExtra($key, $value, $is_array = false)
    {
        if($is_array)
        {
            $this->extra[$key][] = $value;
        }else{
            $this->extra[$key] = $value;
        }

        return $this;
    }

    public function getExtra($key)
    {
        if($this->extra && isset($this->extra[$key]))
        {
            return $this->extra[$key];
        }

        return null;
    }

    public function getActionColumnLabel()
    {
        return 'Actions';
    }

    public function actionColumn($row)
    {
        $id = $row->id;
        $this->actions = $this->moreActions($row); //array_merge($this->actions, $this->moreActions($row));
        $links = $this->actionLinks($row);

        return view('admin.crud.table.row-actions', compact(
            'id',
            'links'
        ));
    }

    private function moreActions($row)
    {
        $more_actions = array();
        if(count($this->actionFieldModification) > 0){

            foreach($this->actionFieldModification as $callback){

                $info = call_user_func_array($callback, array($row));
                $default = array(
                    'class' => 'btn-default'
                );

                if($info){
                    $more_actions[$info['key']] = array_merge($default, $info);
                }else{
                    $more_actions[$info['key']] = null;
                }
            }
        }

        return $more_actions;
    }

    private function actionLinks($row)
    {

        if($this->use_default_actions){
            $default = array();
            if($this->edit_route){
                $default['edit'] = array('label' => 'Edit', 
                    'url' => route($this->edit_route, array('id' => $row->id)));
            }

            if($this->destroy_route){
                $default['destroy'] = array(
                    'label'      => 'Delete', 
                    'delete_msg' => $this->destroy_msg,
                    'url'        => route($this->destroy_route, array('id' => $row->id)));
            }            
        }else{
            $default = array();
        }

        if($this->actions && count($this->actions) > 0){

            return array_merge($default, $this->actions);
        }else{

            return $default;
        }
    }

    public function useDefaultActions($use = true)
    {
        $this->use_default_actions = $use;

        return $this;
    }

    /**
     * This should return a URL for anchor tag.
     * @param $key Unique identifier of action
     * @param $label
     * @param $closure
     * @return $this
     */
    public function addAction($closure)
    {

     //   $this->actions[$key] = array('label' => $label);
        $this->actionFieldModification[] = $closure;

        return $this;
    }

    public function setDestroyRoute($str)
    {
        $this->destroy_route = $str;

        return $this;
    }

    public function setEditRoute($str)
    {
        $this->edit_route = $str;

        return $this;
    }

    public function isActionShow()
    {
        return $this->show_action_column;
    }

    /**
     * Set to show or hide action column.
     *
     * @param      Boolean  $boolean  The boolean
     *
     * @return     $this
     */
    public function toActionShow($boolean)
    {
        $this->show_action_column = $boolean;

        return $this;
    }

    /**
     * Set header to show or hide.
     *
     * @param      <type>  $bool   The bool
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function showHeader($bool)
    {
        $this->show_header = $bool;
        return $this;
    }

    /**
     * Check weather header is to show or not.
     *
     * @return     boolean  True if show header, False otherwise.
     */
    public function isShowHeader()
    {

        return $this->show_header;
    }

    public function useDefaultTblClass($bool)
    {

        $this->use_default_header_class = $bool;

        return $this;
    }
}