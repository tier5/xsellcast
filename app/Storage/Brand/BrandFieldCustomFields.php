<?php namespace App\Storage\Brand;

class BrandFieldCustomFields {

    public function editSelect($crud_field) {
        return view('admin.brand.fields.edit_select', compact('crud_field'));
    }

}