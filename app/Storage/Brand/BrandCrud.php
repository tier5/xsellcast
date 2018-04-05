<?php namespace App\Storage\Brand;

use App\Storage\Crud\Box;
use HTML;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\TableCollection;

class BrandCrud
{

    public static function table($model, $opt)
    {
        $table  = new TableCollection();
        $all    = ($model ? $model->all() : [] );
        $info   = array(
          'box_title'     => 'Brands',
          'box_body_class' => 'no-padding',
          'column_size'   => 12);

        $table = $table->make($all)
            ->columns(array(
                'name'      => 'Name',
                'category'  => 'Category'
            ))
            ->modify('category', function($row){

                if($row->category)
                {
                    return $row->category->name;
                }else{

                    return '';
                }
            })
            ->sortable(['name', 'category'])
            ->setDestroyRoute('admin.brands.delete')
            ->setEditRoute('admin.brands.edit')
            ->toActionShow(true);

        $box = new Box($info);
        $box->setTable($table);

        return $box;

    }

    public static function editForm($opts)
    {
        $brand = $opts['brand'];

        $fields       = new CrudForm('put');
        $fields->setRoute('admin.brands.update');
        $fields->setModel($brand);
        $fields->setModelId($brand->id);

        $fields->addField(array(
            'name'          => 'name',
            'label'         => 'Brand Name',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'slug',
            'label'         => 'Brand Slug',
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
            'btn_txt'       => 'Upload Logo',
            'value'         => $brand->media_logo_id,
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'desc',
            'label'         => 'Brand Description',
            'type'          => 'textarea',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'value'         => $brand->description,
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'catalog_url',
            'label'         => 'Brand Catalog URL',
            'type'          => 'text',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'images',
            'label'         => 'Default Images',
            'accepts'       => 'image/*|video/*',
            'is_single'     => false,
            'type'          => 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'       => 'Add Media',
            'value'         => explode(',', $brand->media_ids),
            'clear_all'     => true));

        $fields->addField(array(
            'name'      => 'category',
            'label'     => 'Category',
            'type'      => 'App\Storage\Crud\CustomFields@brandCategoryList',
            'col-class' => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'selected'  => ($brand->category ? $brand->category->id : null ),
            'clear_all' => true));

        $fields->addField(array(
            'name'      => 'opid',
            'label'     => 'Ontraport Tag',
            'type'      => 'App\Storage\Crud\CustomFields@opTagSelect',
            'col-class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12',
            'clear_all' => true,
            'value'     => $brand->opid));
        $fields->addField(array(
            'name'          => 'image_url',
            'label'         => 'Brand Image URL',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'image_link',
            'label'         => 'Brand Image Link',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'image_text',
            'label'         => 'Brand Image Text',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $info = array(
            'box_title'     => 'Brands - Create',
            'column_size'   => 12,
            'column_class'  => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

    public static function createForm()
    {
        $fields       = new CrudForm('post');
        $fields->setRoute('admin.brands.store');

        $fields->addField(array(
            'name'          => 'name',
            'label'         => 'Brand Name',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));
        $fields->addField(array(
            'name'          => 'slug',
            'label'         => 'Brand Slug',
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
            'btn_txt'       => 'Upload Logo',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'desc',
            'label'         => 'Brand Description',
            'type'          => 'textarea',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'catalog_url',
            'label'         => 'Brand Catalog URL',
            'type'          => 'text',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'images',
            'label'         => 'Default Images',
            'accepts'       => 'image/*|video/*',
            'is_single'     => false,
            'type'          => 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
            'col-class'     => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'       => 'Add Media',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'category',
            'label'         => 'Category',
            'type'          => 'App\Storage\Crud\CustomFields@brandCategoryList',
            'col-class'     => 'col-lg-4 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'opid',
            'label'         => 'Ontraport Tag',
            'type'          => 'App\Storage\Crud\CustomFields@opTagSelect',
            'col-class'     => 'col-lg-4 col-md-4 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'image_url',
            'label'         => 'Brand Image URL',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'image_link',
            'label'         => 'Brand Image Link',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));

        $fields->addField(array(
            'name'          => 'image_text',
            'label'         => 'Brand Image Text',
            'type'          => 'text',
            'col-class'     => 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
            'clear_all'     => true));



        $info = array(
            'box_title'     => 'Brands - Create',
            'column_size'   => 12,
            'column_class'  => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

}
