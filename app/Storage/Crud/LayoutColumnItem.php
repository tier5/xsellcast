<?php
/**
 * Created by PhpStorm.
 * User: Alvin
 * Date: 8/29/2016
 * Time: 10:37 PM
 */

namespace App\Storage\Crud;


class LayoutColumnItem {

    protected $options = array(
        'column_size'    => '12',
        'view'           => null,
        'column_class'   => null,
        'box_title'      => '',
        'box_body_class' => '',
        'footer_view'    => '',
        'type'           => 'custom',
        'show_box'       => true,
        'column_id'      => null
    );

    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public static function init($view, $options)
    {
        $options['view'] = $view;
        $layoutColumnItem = new LayoutColumnItem($options);

        return $layoutColumnItem;
    }

    public function getColumnClass()
    {
        $class = '';
       // if(!$this->options['column_class']){
            $class .= 'col-md-' . $this->options['column_size'];
       // }

        if(isset($this->options['column_class'])){
            
            $class .= ' '. $this->options['column_class'];
        }

        if(isset($this->options['box_float'])){
            if($this->options['box_float'] == 'right') {

                $class .= ' pull-right';
            }else{

                $class .= ' pull-left';
            }
        }

        return  $class;
    }

    public function getView()
    {
        return $this->options['view'];
    }

    public function getBoxTitle()
    {
        return $this->options['box_title'];
    }

    public function render()
    {
        $view = $this->getView();
        $class = $this->getColumnClass();
        $box_title = $this->getBoxTitle();
        $options = $this->options;
        
        unset($options['view']);

        return view('admin.crud.column-box', compact(
            'view',
            'class',
            'box_title',
            'options'
        ));
    }
}