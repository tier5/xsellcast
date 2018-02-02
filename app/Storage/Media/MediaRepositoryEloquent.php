<?php

namespace App\Storage\Media;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Media\MediaRepository;
use App\Storage\Media\Media;
use App\Storage\Media\MediaValidator;
use App\Storage\Media\MediaPresenter;
use Intervention\Image\Size;
use App\Storage\Media\MediaTransformer;
use \File;
use \Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MediaRepositoryEloquent
 * @package namespace App\Storage\Media;
 */
class MediaRepositoryEloquent extends BaseRepository implements MediaRepository
{

    protected $upload_dir;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Media::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return MediaValidator::class;
    }

    public function presenter()
    {
        
        return MediaPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function setUploadPath()
    {
        $this->upload_dir = public_path() . '/uploads';

        return $this;
    }

    public function getUploadPath()
    {
        $this->setUploadPath();

        return $this->upload_dir;
    }

    public function createMedia($dir, $ext, $filename, $mime_type)
    {
        $mime = explode('/', $mime_type);

        if(count($mime) > 1){
            $type = $mime[0];
        }else{
            $type = $mime_type;
        }

        $data = array(
            'path'      => '/' . $dir,
            'extension' => $ext,
            'slug'  => $filename,
            'title' => str_replace('-', ' ', $filename),
            'type'  => $type);

        return $this->model->create($data);
    }

    public function saveVideo($file, $filename)
    {
        $media = $this->createMedia('uploads', $file->guessExtension(), $filename, $file->getMimeType());

        $media->setMeta('size_150x100', '/img/play_thumbnail.png');
        $media->save();

        return $media;
    }

    /**
     * Uploads an image.
     *
     * @param  >  $tmp_file  The temporary file
     */
    public function uploadImg($tmp_file = null, $sizes = array(), $rename = true)
    {
        $this->setUploadPath();

        $file = $tmp_file;
        /**
         * Save orignal image without resize.
         *
         * @var Image
         */
        $imgOrig    = Image::make($file);
        $filename   = ($rename ? $this->generateFilename($imgOrig->filename, $imgOrig->extension) : $imgOrig->filename . '.' . $imgOrig->extension);
        $newImg     = $imgOrig->save($this->upload_dir . '/' . $filename);
        $origMedia  = $this->createMedia('uploads', $newImg->extension, $filename, 'image');

        /**
         * Loop on other sizes.
         */
        foreach($sizes as $size){
            $imgSize = Image::make($file);
            if(!isset($size[1])){

                /**
                 * Proportion to width resize.
                 *
                 * @var Size
                 */
                $s = $imgSize->widen($size[0]);
            }else{

                /**
                 * Stretch resize
                 *
                 * @var Size
                 */
                $s = $imgSize->resize($size[0], $size[1]);
            }

            $filename   = $this->generateImgSizename($imgSize->filename, $imgSize->extension, $s->getWidth(), $s->getHeight());
            $newImg     = $imgSize->save($this->upload_dir . '/' . $filename);
            $metaKey = 'size_' . $s->getWidth();

            if(isset($size[1])){
                $metaKey .= 'x' . $s->getHeight();   
            }

            $origMedia->setMeta($metaKey, '/uploads/' . $filename);
        }   

        $origMedia->save();

        return $origMedia; 
    }

    /**
     * Generate image file name width size.
     *
     * @param      string          $filename  The filename
     * @param      string          $ext       The extent
     * @param      interger        $width     The width
     * @param      interger        $height    The height
     * @param      integer|string  $count     The count
     *
     * @return     string
     */
    protected function generateImgSizename($filename, $ext, $width, $height)
    {
        $filenameSize   = $filename . '-' . $width . 'x' . $height . '.' . $ext;
  
        return $this->uniqueFilename($this->upload_dir, $filenameSize, $ext);
    }

    /**
     * Generate image file name.
     *
     * @param      string          $filename  The filename
     * @param      string          $ext       The extent
     * @param      integer|string  $count     The count
     *
     * @return     string          ( description_of_the_return_value )
     */
    public function generateFilename($filename, $ext)
    {
        $filename .= '.' . $ext;
        return $this->uniqueFilename($this->upload_dir, $filename, $ext);      
    }

    function uniqueFilename($path, $name, $ext) {
        
        $output = $name;
        $basename = basename($name, '.' . $ext);
        $i = 2;

        while(File::exists($path . '/' . $output)) {
            $output = $basename . '-' . $i . '.' . $ext;
            $i ++;
        }
        
        return $output;
    }  

    public function uploadImgFrmFb($url)
    {
        $baseName = basename($url);
        $img = $this->getUploadPath() . '/' . str_random(10) . time() . '.jpg';
        file_put_contents($img, file_get_contents($url));
        $uploadInfo = $this->skipPresenter()->uploadImg($img, [[48, 48], [140, 140], [150, 100]]);
        unlink($img);
        return $uploadInfo;
    }  

    public function errorMessage($filename)
    {
        $maxSize = UploadedFile::getMaxFilesize() / 1024;
        $msgTitle = "FILE TOO LARGE TO UPLOAD";
        $msg = 'Oops! Your file "' . $filename . '" is too large to upload. Please try again with a file that is ' . $maxSize . 'KB or less.';

        return [$msgTitle, $msg];
    }
}
