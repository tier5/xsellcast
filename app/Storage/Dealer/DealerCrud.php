<?php namespace App\Storage\Dealer;

use HTML;
use App\Storage\Crud\Box;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\TableCollection;

class DealerCrud
{

    public static function createForm()
    {
        $fields       = new CrudForm('post');
        $fields->setRoute('admin.dealers.store');

        $fields->addField(array(
            'name'          => 'name',
            'label'         => 'Dealer/Store Name',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'logo',
            'label'         => 'Logo',
            'accepts'       => 'image/*|video/*',
            'is_single'     => true,
            'type'          => 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'       => 'Change Photo',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'desc',
            'label'         => 'Dealer Description',
            'type'          => 'textarea',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'brand',
            'label'         => 'Brand',
            'type'          => 'App\Storage\Crud\CustomFields@brandsList',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'website',
            'label'         => 'Dealer URL',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'phone',
            'label'         => 'Phone Number',
            'type'          => 'text',
            'field-attr' => ['data-mask' => '(999) 999-9999'],
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'fax',
            'label'         => 'Fax Number',
            'type'          => 'text',
            'field-attr' => ['data-mask' => '(999) 999-9999'],
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));        

        $fields->addField(array(
            'name'          => 'address_1',
            'label'         => 'Address 1',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'address_2',
            'label'         => 'Address 2',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'city',
            'label'         => 'City',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'state',
            'label'         => 'State',
            'type'          => 'App\Storage\Crud\CustomFields@statesList',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'zip',
            'label'         => 'Zip Code',
            'type'          => 'text',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));        

        $fields->addField(array(
            'name'          => 'hours_of_operation',
            'label'         => 'Hours of Operation',
            'type'          => 'App\Storage\Crud\CustomFields@hoursOperation',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));     

        $info = array(
            'box_title'     => 'Dealers - Create', 
            'column_size'   => 12,
            'column_class'  => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;   
    }

    public static function editForm($opts)
    {
        $dealer = $opts['dealer'];
        $brands = ($dealer->brands ? $dealer->brands : null);
        $brand  = $brands->first();
        $fields = new CrudForm('put');

        $fields->setRoute('admin.dealers.update');
        $fields->setModel($dealer);
        $fields->setModelId($dealer->id);    
        $fields->setSubmitText('Save');

        $fields->addField(array(
            'name'          => 'name',
            'label'         => 'Dealer/Store Name',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'logo',
            'label'         => 'Logo',
            'accepts'       => 'image/*|video/*',
            'is_single'     => true,
            'type'          => 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'       => 'Change Photo',
            'value'         => $dealer->logo_media_id,
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'description',
            'label'         => 'Dealer Description',
            'type'          => 'textarea',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'brand',
            'label'         => 'Brand',
            'type'          => 'App\Storage\Crud\CustomFields@brandsList',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'selected'      => ($brand ? $brand->id : null),
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'website',
            'label'         => 'Dealer URL',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'phone',
            'label'         => 'Phone Number',
            'type'          => 'text',
            'field-attr' => ['data-mask' => '(999) 999-9999'],
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'       => 'fax',
            'label'      => 'Fax Number',
            'type'       => 'text',
            'field-attr' => ['data-mask' => '(999) 999-9999'],
            'col-class'  => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'  => true));        

        $fields->addField(array(
            'name'          => 'address1',
            'label'         => 'Address 1',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'address2',
            'label'         => 'Address 2',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'city',
            'label'         => 'City',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'state',
            'label'         => 'State',
            'type'          => 'App\Storage\Crud\CustomFields@statesList',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'zip',
            'label'         => 'Zip Code',
            'type'          => 'text',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));        

        $fields->addField(array(
            'name'          => 'hours_of_operation',
            'label'         => 'Hours of Operation',
            'type'          => 'App\Storage\Crud\CustomFields@hoursOperation',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'value'         => $dealer->hours_of_operation,
            'clear_all'     => true));     

        $info = array(
            'box_title'     => 'Dealers - Edit', 
            'column_size'   => 12,
            'column_class'  => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;   
    }

    public static function table($model, $opt)
    {
        $table  = new TableCollection();
        $all    = ($model ? $model->all() : [] );
        $info   = array(  
          'box_title'     => 'Dealers',
          'box_body_class' => 'no-padding',
          'column_size'   => 12);

        $table = $table->make($all)
            ->columns(array(
                'name'     => 'Name',
             //   'category' => 'Category',
                'brand'    => 'Brand',
                'city'     => 'City',
                'state'    => 'State',
                'zip'      => 'Zip'
            ))
            ->modify('name', function($row){
                $span = Html::tag('span', 'Name', ['class' => 'responsive-tbl-head']);

                return $span . HTML::link(route('admin.dealers.edit', ['dealer_id' => $row->id]), $row->name, ['class' => 'text-default text-navy']);
            })
            ->modify('state', function($row){
                $states = states();
                $txt = (isset($states[$row->state]) ? $states[$row->state] : '');
                $span = Html::tag('span', 'State', ['class' => 'responsive-tbl-head']);

                return $span . HTML::link(route('admin.dealers.edit', ['dealer_id' => $row->id]), $txt, ['class' => 'text-default text-navy']);
            })
            ->modify('brand', function($row){

                $brand = $row->brands->first();
                $span = Html::tag('span', 'Brand', ['class' => 'responsive-tbl-head']);

                return $span . ($brand ? $brand->name : '' );
            })
            ->modify('category', function($row){

                $brand = $row->brands->first();
                $span = Html::tag('span', 'Category', ['class' => 'responsive-tbl-head']);

                if(!$brand)
                {
                    return $span . '';
                }

                $category = $brand->category;

                if(!$category){

                    return $span . '';
                }
                
                return $span . $category->name;
            })
            ->modify('city', function($row){
                $span = Html::tag('span', 'City', ['class' => 'responsive-tbl-head']);

                return $span . $row->city;
            })
            ->modify('zip', function($row){
                $span = Html::tag('span', 'Zip', ['class' => 'responsive-tbl-head']);

                return $span . $row->zip;
            })
            ->addAttribute('id', 'dealer-list-tbl')
            ->sortable(['name', 'brand', 'zip', 'city', 'state'])
            ->setDestroyMsg('Are you sure you would like to permanently delete this dealer?')
            ->setDestroyRoute('admin.dealers.delete')
            ->setEditRoute('admin.dealers.edit')
            ->toActionShow(true);

        $box = new Box($info);
        $box->setTable($table);    

        return $box;    

    }

}