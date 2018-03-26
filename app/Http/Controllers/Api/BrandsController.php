<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Brand\BrandRepository;
use App\Storage\Media\MediaRepository;

use App\Http\Requests\Api\BrandsRequest;
use App\Http\Requests\Api\BrandsShowRequest;
use App\Http\Requests\Api\BrandStoreRequest;

/**
 * @resource Brand
 *
 * Brand resource.
 */
class BrandsController extends Controller
{
	protected $brand;
    protected $media;

	public function __construct(BrandRepository $brand,MediaRepository $media)
	{
		$this->brand = $brand;
	}

	/**
	 * All
	 *
	 * Get a list of brands.
	 *
	 * @param      \App\Http\Requests\Api\BrandsRequest  $request  The request
	 *
	 * @return     Response
	 */
    public function index(BrandsRequest $request)
    {
        try{

            $per_page=$request->get('per_page') !='' ?$request->get('per_page'):20;

            $brands = $this->brand->paginate($per_page);

            $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    ];
                $data=array_merge($data,$brands);

            return response()->json($data, config('responses.success.status_code'));

        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Single
     *
     * Get a brand by ID.
     *
     *
     * @param      \App\Http\Requests\Api\BrandsShowRequest  $request    The request
     * @param      Integer                                  $brand_id  The brand identifier
     *
     * @return     Response
     */
    public function show(BrandsShowRequest $request)
    {

         try{

            $wp_brand_id=$request->get('wp_brand_id');
            $brand_id=$this->brand->getId($wp_brand_id);
            $brand = $this->brand->find($brand_id);

            $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    ];
                $data=array_merge($data,$brand);

            return response()->json($data, config('responses.success.status_code'));

        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Store
     *
     * Get a brand by ID.
     *
     *
     * @param      \App\Http\Requests\Api\BrandsStoreRequest  $request    The request
     *
     * @return     Response
     */
    public function store(BrandStoreRequest $request)
    {

         try{

            $data     = $request->all();

            $media_id='';
            $file=$data['logo'];
            $type = explode('/', $file->getClientMimeType());
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $baseName = basename($file->getClientOriginalName(), '.' . $ext);
            $fileName = $this->media->setUploadPath()->generateFilename($baseName, $ext);

                try {
                    $targetFile = $file->move($this->media->getUploadPath(), $fileName);
                }
                catch (\Exception $e) {

                    $erroMsg = $this->media->errorMessage($file->getClientOriginalName());
                    $error = [
                        'title' => $erroMsg[0],
                        'body'  => $erroMsg[1]
                    ];
                    return response()->json([
                        'status'=>false,
                        'code'=>config('responses.bad_request.status_code'),
                        'data'=>$error,
                        'message'=> $erroMsg ,
                        ], config('responses.bad_request.status_code'));
                }

            if($type[0]== 'image')
            {
                $media = $this->media->skipPresenter()->uploadImg($targetFile->getPathname(),[[150, 100]], false);

                $media_id=$media->id;


            }
        $brand=    $this->brand->createOne([
                'name'          => $request->get('name'),
                'media_logo_id' => $media_id,
                'description'   => $request->get('desc'),
                'catalog_url'   => $request->get('catalog_url'),
                'media_ids'     => $request->get('images'),
                'category'      => $request->get('category_id'),
                'opid'          => $request->get('opid')
            ]);

            $data=[
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'message'=>config('responses.success.status_message'),
                    'data'=>$brand
                    ];


            return response()->json($data, config('responses.success.status_code'));

        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

}
