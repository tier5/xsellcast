<?php
/**
 * Created by PhpStorm.
 * User: Alvin
 * Date: 8/25/2016
 * Time: 6:22 AM
 */

namespace App\Storage\Crud;

use App\Storage\Crud\CrudField;
use Illuminate\Routing\Route;

class CrudForm {

    protected $fields;

    protected $form_method;

    protected $model;

    protected $model_id;

    protected $action_url;

    protected $submit_btn_text;

    protected $box_label;

    protected $have_redirect;

    protected $submit_btns;

    protected $show_default_submit;

    protected $header_views;

    protected $show_default_head;

    /**
     * Use for ajax request.
     */
    protected $validation_route;

    public function __construct($method = null)
    {
        $this->fields = array();
        $this->form_method = ($method ? $method : 'get');
        $this->model = null;
        $this->model_id = null;
        $this->route = null;
        $this->submit_btn_text = null;
        $this->box_label = null;
        $this->have_redirect = true;
        $this->submit_btns = [];
        $this->show_default_submit = true;
        $this->header_views = [];
        $this->show_default_head = true;
        $this->validation_route = null;
    }

    public function setRoute($str)
    {
        $this->route = $str;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setValidationRoute($route)
    {
        $this->validation_route = $route;

        return $this;
    }

    public function getValidationRoute()
    {
        return $this->validation_route;
    }    

    public function getValidationUrl()
    {
        return ($this->getValidationRoute() ? route($this->getValidationRoute()) : null );
    }

    public function setModel($str_class)
    {

        $this->model = $str_class;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModelId($str)
    {
        $this->model_id = $str;

        return $this;
    }

    public function getModelId()
    {
        return $this->model_id;
    }

    public function setMethod($method)
    {
        $methods = array('put', 'post', 'get', 'delete');
        $method = strtolower($method);

        if(!in_array($method, $methods)){
            return 'get';
        }

        return $method;
    }

    public function getMethod()
    {
        return $this->form_method;
    }

    public function addField($options)
    {
        $default = array(
            'col-class' => 'col-md-12',
            'name' => null,
            'id' => null,
            'label' => null,
            'field_style' => null,
            'value' => null,
            'field-attr' => null
        );

        if(isset($options['name']) && $options['name'] != ''){

            $this->fields[$options['name']] = new CrudField($this->parseAttr($options, $default));
        }else{
            
            $this->fields[] = new CrudField($this->parseAttr($options, $default));
        }     
        
        return $this;
    }

    public function getFields()
    {

        if($this->have_redirect){

            $this->addField(array(
                'name' => 'redirect_to',
                'value' => \Request::fullUrl(),
                'type' => 'hidden'));
        }

        return $this->fields;
    }

    public function noRedirectField()
    {
        $this->have_redirect = false;

        return $this;
    }

    /**
     * parseAttr()
     *
     * @param array $arr
     * @param array $default
     * @return array
     */
    public function parseAttr(Array $arr, Array $default)
    {
        return array_merge($default, $arr);
    }

    public function showDefaultSubmit($boolean)
    {
        $this->show_default_submit = $boolean;

        return $this;
    }

    public function isShowDefaultSubmit()
    {
        return $this->show_default_submit;
    }

    public function getSubmitBtns()
    {
        if($this->isShowDefaultSubmit()){
            $this->submit_btns['default'] = [ 'class' => 'btn-primary' , 'label' => $this->getSubmitText() ];
        }

        return $this->submit_btns;
    }

    public function addHeadView($view)
    {
        $this->header_views[] = view($view);
        return $this;
    }

    public function getHeaderViews()
    {
        return $this->header_views;   
    }

    public function addSubmitBtn($key, $lbl)
    {
        $arr = $lbl;
        if(!is_array($lbl)){
            $arr = [ 'class' => 'btn-primary', 'label' =>  $lbl];
        }
        
        $arr['is_link'] = false;

        $this->submit_btns[$key] = $arr;

        return $this;
    }

    public function addSubmitLinkBtn($key, $args)
    {
        $args['is_link'] = true;

        $this->submit_btns[$key] = $args;

        return $this;
    }

    public function setSubmitText($str)
    {
        $this->submit_btn_text = $str;
        return $this;
    }

    public function getSubmitText()
    {
        if($this->submit_btn_text){
            return $this->submit_btn_text;
        }

        return "Submit";
    }

    public function setBoxLabel($str)
    {
        $this->box_label = $str;

        return $this;
    }

    public function getBoxLabel()
    {
        if(!$this->box_label){
            return '';
        }
        
        return $this->box_label;
    }

    public function render($view = 'admin.crud.form-main')
    {
        $crud_form = $this;
        $view = view($view, compact(
            'crud_form'
        ));

        return $view->render();
    }

    public function showDefaultHead($bool)
    {
        $this->show_default_head = $bool;    

        return $this;  
    }

    public function isShowDefaultHead()
    {
        return $this->show_default_head;
    }
}