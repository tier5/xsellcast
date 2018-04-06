<?php

namespace App\Storage\Appointment;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;


class Appointment extends Model implements Transformable
{
    use TransformableTrait;


    protected $fillable = ['name', 'parent_id', 'media_logo_id', 'description', 'catalog_url', 'media_ids', 'opid','wp_brand_id','slug', 'image_url', 'image_link', 'image_text'];

    protected $table = 'brands';

    protected $category;

    protected $logo;

}