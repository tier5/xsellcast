<?php
namespace App\Storage\DealerCsv;

use App\Storage\Crud\Box;
use App\Storage\Crud\CrudForm;

class DealerCsvCrud {

    public static function createForm() {

        $fields = new CrudForm('post');
        $fields->setRoute('admin.dealers.store');

        $fields->addField(array(
            'name'      => 'csv',
            'label'     => 'Upload Dealer CSV',
            'accepts'   => 'csv/*',
            'is_single' => true,
            'type'      => 'App\Storage\Media\MediaFieldCustomFields@mediaCsvUpload',
            'col-class' => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'   => 'Upload  CSV',
            'clear_all' => true));

        $info = array(
            'box_title'    => 'Dealers - CSV Upload',
            'column_size'  => 12,
            'column_class' => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

}