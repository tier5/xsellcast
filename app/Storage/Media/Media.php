<?php namespace App\Storage\Media;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Kodeine\Metable\Metable;

class Media extends Model implements Transformable
{
    use TransformableTrait, Metable;

    protected $fillable = ['title', 'path', 'name', 'extension', 'slug', 'type'];

    protected $table = 'media';

    protected $metaTable = 'media_meta';

    public function getOrigUrl(){

    	return url($this->path . '/' . $this->slug);

    }

    public function getSize($width, $height = null)
    {
    	$key = 'size_' . $width;
    	$key .= ($height ? 'x' . $height : '' );

    	$filename = $this->getMeta($key);

    	if($filename){
    		return url($filename);
    	}

    	return null;
    }
}
