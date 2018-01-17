<?php
/**
 * Created by PhpStorm.
 * User: Alvin
 * Date: 8/29/2016
 * Time: 10:33 PM
 */

namespace App\Storage\Crud;


class LayoutColumn {

    protected $views;

    public function __construct()
    {

        return $this;
    }

    /**
     * @param $view
     * @param array $options
     * @return $this
     */
    public function addItem($view, $options = array())
    {

        if(view()->exists($view)){
            $args = (isset($options['view_args']) ? $options['view_args'] : array());
            $view = view($view, $args);
        }

        $this->views[] = LayoutColumnItem::init($view, $options); //->render();

        return $this;
    }

    /**
     * @param Box|String $box
     * @param array $options
     * @return $this
     */
    public function addItemTable($box, $model = null, $options = array())
    {
        if(is_string($box))
        {
            $callback = explode('@', $box);
            $box = call_user_func(array($callback[0], $callback[1]), $model, $options);
        }

        if($box->getTable())
        {
            $table = $box->getTable();
        }

        if($box->getInfo())
        {
            $options = $options + $box->getInfo();
        }

        $options['column_size'] = (isset($options['column_size']) ?  $options['column_size'] : '12' );
        $options['footer_view'] = ($model ? '<div class="ag-pagination">' . $model->render() . '</div>' : '' );
        $options['type'] = 'table';
        $this->addItem($table->render(), $options);

        return $this;
    }

    public function addItemForm($box, $options = array())
    {
        if(is_string($box))
        {
            $callback = explode('@', $box);
            $box = call_user_func(array($callback[0], $callback[1]), $options);
        }

        if($box->getForm())
        {
            $form = $box->getForm();
        }

        if($box->getInfo())
        {
            $options = $options + $box->getInfo();
        }

        $boxTitle = (isset($options['box_title']) ? $options['box_title'] : '');
        $options['column_size'] = (isset($options['column_size']) ?  $options['column_size'] : '12' );
        $options['footer_view'] = '';
        $options['type'] = 'form';
        $form->setBoxLabel($boxTitle);
        $this->addItem($form->render(), $options);
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }
}