<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Media\MediaRepository;
use Symfony\Component\HttpFoundation\File\File;
use Response;

class MediaController extends Controller
{
    protected $media;

	public function __construct(MediaRepository $media)
    {
        $this->media = $media;
    }

    public function upload(Request $request)
    {
        $fields = $request->all();

        if(!isset($fields['files'])){

            /**
             * TODO: Return error here
             */

            exit();
        }

        $mediaIds = array();

        foreach($fields['files'] as $file)
        {

            $type = explode('/', $file->getClientMimeType());
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $baseName = basename($file->getClientOriginalName(), '.' . $ext);            
            $fileName = $this->media->setUploadPath()->generateFilename($baseName, $ext);

            try {
                $targetFile = $file->move($this->media->getUploadPath(), $fileName);
            }
            catch (\Exception $e) {

                /**
                 * TODO: create better error handling of upload.
                 *
                 * @var        <type>
                 */
                $erroMsg = $this->media->errorMessage($file->getClientOriginalName());
                $error = [
                    'title' => $erroMsg[0],
                    'body'  => $erroMsg[1]];
                return Response::json([$error], 422);

            }

            switch($type[0]){
                case 'image':
                    
                    try {
                        $media = $this->media->skipPresenter()->uploadImg($targetFile->getPathname(), 
                            [[150, 100]], false);
                    }
                    catch (\Exception $e) {
                        return Response::json([$e->getMessage() . ' ' . $e->getCode()], 422);
                    }
                    
                    break;
                case 'video':
                    $media = $this->media->skipPresenter()->saveVideo($targetFile, $fileName);
                    break;
                default:

                    /**
                     * TODO: Return error
                     */
                    exit();
                    break;  
            }
            
            $mediaIds[] = $media->id;
        }        

        $all = $this->media->skipPresenter(false)->findWhereIn('id', $mediaIds);

        return response()->json($all);        
    }

    public function show(Request $request, $media_id)
    {
        $ids = explode(',',$media_id);
        $medias = $this->media->findWhereIn('id', $ids);

        return response()->json($medias);
    }
}