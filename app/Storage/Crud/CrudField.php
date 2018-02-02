<?php
/**
 * Created by PhpStorm.
 * User: Alvin
 * Date: 8/25/2016
 * Time: 7:17 AM
 */

namespace App\Storage\Crud;

use \Form;
use \Html;

class CrudField {

    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getOption($key, $default = null)
    {
        return (isset($this->options[$key]) ? $this->options[$key] : $default);
    }

    public function render()
    {
        $field = null;
        $fieldMethod = 'field' . ucfirst(strtolower($this->options['type']));        
        $classMethod = explode('@', $this->options['type']);

        if(count($classMethod) == 2 && method_exists($classMethod[0], $classMethod[1])){

            $obj = new $classMethod[0]();
            return $obj->{$classMethod[1]}($this);
        }
        elseif(method_exists($this, $fieldMethod)){
            $field = $this->{$fieldMethod}();
        }
        else{
            $field = "No field type or field type don't exist";
        }

        return $field;
    }

    public function fieldTinymce()
    {
        return Form::admin_tinymce($this->getOption('name'), $this->getOption('label'), $this->getOption('value'), $this->getOption('field-attr'));   
    }

    public function fieldText()
    {
        return Form::admin_text($this->getOption('name'), $this->getOption('label'), $this->getOption('value'), $this->getOption('field-attr'));
    }

    public function fieldEmail()
    {
        return Form::admin_email($this->getOption('name'), $this->getOption('label'), $this->getOption('value'), $this->getOption('field-attr'));
    }    

    public function fieldPassword()
    {
        return Form::admin_password($this->getOption('name'), $this->getOption('label'), $this->getOption('field-attr'));
    }      

    public function fieldTextarea()
    {
        return Form::admin_textarea($this->getOption('name'), $this->getOption('label'), $this->getOption('value'), $this->getOption('field-attr'));   
    }

    public function fieldSelect()
    {
        if(isset($this->options['selected'])){
            $selected = $this->getOption('selected');
        }else{
            $selected = '';
        }

        return Form::admin_select($this->getOption('name'), $this->getOption('label'), $this->getOption('list'), $selected, $this->getOption('field-attr'));        
    }

    public function fieldHidden()
    {
        return Form::hidden($this->getOption('name'), $this->getOption('value'), $this->getOption('field-attr'));
    }

    public function fieldBtnLink()
    {

        return Form::admin_link($this->getOption('label'), $this->getOption('url'), $this->options);
    }

    public function fieldRadio()
    {
        return Form::admin_radio($this->getOption('name'), $this->getOption('label'), $this->getOption('list'), $this->getOption('value'), $this->getOption('field-attr'));
    }
}