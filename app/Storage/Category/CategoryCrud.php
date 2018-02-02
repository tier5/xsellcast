<?php namespace App\Storage\Category;

use HTML;
use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;

class CategoryCrud
{
    public static function createForm()
    {

        $fields       = new CrudForm('post');
        $fields->setRoute('admin.categories.store');

        $fields->addField(array(
            'name'          => 'name',
            'label'         => 'Category Name',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'opid',
            'label'         => 'Ontraport Tag',
            'type'          => 'App\Storage\Crud\CustomFields@opTagSelect',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));        

        $info = array(
            'box_title'     => 'Create', 
            'column_size'   => 12,
            'column_class'  => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;    
    }

    public static function editForm($option)
    {
        $category = $option['category'];
        $fields   = new CrudForm('put');

        $fields->setRoute('admin.categories.update');
        $fields->setModel($category);
        $fields->setModelId($category->id);    
        $fields->setSubmitText('Save');
        $fields->addField(array(
            'name'          => 'name',
            'label'         => 'Category Name',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'field-attr'    => ['required' => 'required']));

        $fields->addField(array(
            'name'          => 'opid',
            'label'         => 'Ontraport Tag',
            'type'          => 'App\Storage\Crud\CustomFields@opTagSelect',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));      

        $info = array(
            'box_title'     => 'Edit', 
            'column_size'   => 12,
            'column_class'  => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;    
    }    

    public static function table($model)
    {
        $table  = new TableCollection();
        $all    = ($model ? $model->all() : [] );
        $info   = array(  
            'box_title'     => 'All Categories', 
            'column_size'   => 8, 
            'column_class'  => 'col-sm-12 col-xs-12',
            'box_float'     => 'left');

        $table = $table->make($all)
            ->columns(array(
                'name' => 'Name'
            ))
            ->modify('name', function($row){

                $deleteUrl = route('admin.categories.destroy', ['category_id' => $row->id]);
                $editUrl = route('admin.categories.edit', $row->id);

                return 
                    $row->name .
                    ' <div class="pull-right">' .
                    HTML::link($editUrl, '<i class="fa fa-pencil"></i> Edit', ['class' => 'btn btn-white btn-sm m-l-sm', 'onclick' => 'categoryUpdate(this); return false;', 'data-id' => $row->id], null, false) . ' ' .
                    HTML::link('#', '<i class="fa fa-trash"></i> Delete', ['class' => 'btn btn-white btn-sm', 'onclick' => 'categoryDestroyConfirm(this); return false;', 'data-id' => $row->id], null, false) . 
                    '</div>';
            })
            ->sortable(['name'])
            ->toActionShow(false);
            /*
            ->modify('name', function($user){

            $label = $user->firstname . ' ' . $user->lastname;

            if(isset($user->customer->id)){
              return HTML::linkRoute('admin.prospects.show', $label, $user->customer->id);  
            }else{
              return $label;
            }
            })
            */

        $box = new Box($info);
        $box->setTable($table);    

        return $box;          
    }
}