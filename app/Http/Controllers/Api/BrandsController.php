<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Api\BrandDeleteRequest;
use App\Http\Requests\Api\BrandEditRequest;
use App\Http\Requests\Api\BrandEditStatusRequest;
use App\Http\Requests\Api\BrandsRequest;
use App\Http\Requests\Api\BrandsShowRequest;
use App\Http\Requests\Api\BrandStoreRequest;
use App\Storage\Brand\BrandRepository;
use App\Storage\LbtWp\WpConvetor;
use App\Storage\Media\MediaRepository;
use Illuminate\Http\Request;

/**
 * @resource Brand
 *
 * Brand resource.
 */
class BrandsController extends Controller {
    protected $brand;
    protected $media;

    public function __construct(BrandRepository $brand, MediaRepository $media) {
        $this->brand = $brand;
        $this->media = $media;
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
    public function index(BrandsRequest $request) {
        try {

            $per_page = $request->get('per_page') != '' ? $request->get('per_page') : 20;

            $brands = $this->brand->paginate($per_page);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
            ];
            $data = array_merge($data, $brands);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function show(BrandsShowRequest $request) {

        try {

            $wp_brand_id = $request->get('wp_brand_id');
            $wp          = new WpConvetor();
            $brand_id    = $wp->getId('brand', $wp_brand_id);
            $brand       = $this->brand->find($brand_id);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
            ];
            $data = array_merge($data, $brand);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function store(BrandStoreRequest $request) {

        try {

            $data     = $request->all();
            $media_id = '';
            // if (isset($data['logo'])) {
            //     $file     = $data['logo'];
            //     $type     = explode('/', $file->getClientMimeType());
            //     $ext      = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            //     $baseName = basename($file->getClientOriginalName(), '.' . $ext);
            //     $fileName = $this->media->setUploadPath()->generateFilename($baseName, $ext);

            //     try {
            //         $targetFile = $file->move($this->media->getUploadPath(), $fileName);
            //     } catch (\Exception $e) {

            //         $erroMsg = $this->media->errorMessage($file->getClientOriginalName());
            //         $error   = [
            //             'title' => $erroMsg[0],
            //             'body'  => $erroMsg[1],
            //         ];
            //         return response()->json([
            //             'status'  => false,
            //             'code'    => config('responses.bad_request.status_code'),
            //             'data'    => $error,
            //             'message' => $erroMsg,
            //         ], config('responses.bad_request.status_code'));
            //     }

            //     if ($type[0] == 'image') {
            //         $media = $this->media->skipPresenter()->uploadImg($targetFile->getPathname(), [[150, 100]], false);

            //         $media_id = $media->id;

            //     }
            // }
            //Convert wp_category_id to category_id
            $wp_category_id = $request->get('wp_category_id');
            $wp             = new WpConvetor();
            $category_id    = $wp->getId('category', $wp_category_id);

            $data = [
                'name'        => $request->get('name'),
                'slug'        => $request->get('slug'),
                'description' => $request->get('description'),
                'catalog_url' => $request->get('catalog_url'),
                'media_ids'   => $request->get('images'),
                'category'    => $category_id,
                'opid'        => $request->get('opid'),
                'wp_brand_id' => $request->get('wp_brand_id'),
                'image_url'   => $request->get('image_url'),
                'image_link'  => $request->get('image_link'),
                'image_text'  => $request->get('image_text'),

            ];
            // if ($media_id != '') {
            //     $data['media_logo_id'] = $media_id;
            // }

            $brand = $this->brand->createOne($data);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
                'data'    => $brand,
            ];

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
    /**
     * Edit
     *
     * Edit a brand details
     *
     *
     * @param      \App\Http\Requests\Api\BrandEditRequest  $request    The request
     *
     * @return     Response
     */
    public function edit(BrandEditRequest $request) {

        try {

            $data        = $request->all();
            $wp_brand_id = $request->get('wp_brand_id');
            $wp          = new WpConvetor();
            $brand_id    = $wp->getId('brand', $wp_brand_id);
            // $brand = $this->brand->find($brand_id);
            // $media_id = '';
            // if (isset($data['logo'])) {

            //     $file     = $data['logo'];
            //     $type     = explode('/', $file->getClientMimeType());
            //     $ext      = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            //     $baseName = basename($file->getClientOriginalName(), '.' . $ext);
            //     $fileName = $this->media->setUploadPath()->generateFilename($baseName, $ext);

            //     try {
            //         $targetFile = $file->move($this->media->getUploadPath(), $fileName);
            //     } catch (\Exception $e) {

            //         $erroMsg = $this->media->errorMessage($file->getClientOriginalName());
            //         $error   = [
            //             'title' => $erroMsg[0],
            //             'body'  => $erroMsg[1],
            //         ];
            //         return response()->json([
            //             'status'  => false,
            //             'code'    => config('responses.bad_request.status_code'),
            //             'data'    => $error,
            //             'message' => $erroMsg,
            //         ], config('responses.bad_request.status_code'));
            //     }

            //     if ($type[0] == 'image') {
            //         $media = $this->media->skipPresenter()->uploadImg($targetFile->getPathname(), [[150, 100]], false);

            //         $media_id = $media->id;

            //     }
            // }

            $update_arr = [];
            if (isset($data['name'])) {
                $update_arr['name'] = $data['name'];
            }
            if (isset($data['parent_id'])) {
                $update_arr['parent_id'] = $data['parent_id'];
            }
            // if ($media_id != '') {
            //     $update_arr['media_logo_id'] = $media_id;
            // }
            if (isset($data['description'])) {
                $update_arr['description'] = $data['description'];
            }
            if (isset($data['catalog_url'])) {
                $update_arr['catalog_url'] = $data['catalog_url'];
            }
            if (isset($data['media_ids'])) {
                $update_arr['media_ids'] = $data['media_ids'];
            }
            if (isset($data['slug'])) {
                $update_arr['slug'] = $data['slug'];
            }
            if (isset($data['image_url'])) {
                $update_arr['image_url'] = $data['image_url'];
            }
            if (isset($data['image_link'])) {
                $update_arr['image_link'] = $data['image_link'];
            }
            if (isset($data['image_text'])) {
                $update_arr['image_text'] = $data['image_text'];
            }

            if (isset($data['wp_category_id'])) {
                //Convert wp_category_id to category_id
                $wp_category_id         = $request->get('wp_category_id');
                $wp                     = new WpConvetor();
                $category_id            = $wp->getId('category', $wp_category_id);
                $update_arr['category'] = $category_id;
            }

            $this->brand->updateOne($data, $brand_id);

            $brand = $this->brand->find($brand_id);
            // dd($brand);
            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),

            ];
            $data = array_merge($data, $brand);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Delete
     *
     * Delete a brand details
     *
     *
     * @param      \App\Http\Requests\Api\BrandDeleteRequest  $request    The request
     *
     * @return     Response
     */

    public function destroy(BrandDeleteRequest $request) {

        try {

            $wp_brand_id = $request->get('wp_brand_id');
            $wp          = new WpConvetor();
            $brand_id    = $wp->getId('brand', $wp_brand_id);
            $brand       = $this->brand->skipPresenter()->find($brand_id);

            $brand->categories()->detach();
            $brand->save();
            // detach() and attach offer and dealer
            $uncategorized = $this->brand->uncategorized();
            $offers        = $brand->getOffers();
            $dealers       = $brand->getDealers();
            foreach ($offers as $offer) {

                $offer->brands()->detach();

                $offer->brands()->save($uncategorized);
            }

            foreach ($dealers as $dealer) {

                $dealer->brands()->detach();

                $dealer->brands()->save($uncategorized);
            }

            // // exit;
            $brand->delete();
            // $brand = $this->brand->skipPresenter()->delete($brand_id);
            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => "Brand has been deleted!", //config('responses.success.status_message'),
                'data'    => [],
            ];
            // $data=array_merge($data,$brand);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Edit
     *
     * Edit a brand status
     *
     *
     * @param      \App\Http\Requests\Api\BrandEditStatusRequest  $request    The request
     *
     * @return     Response
     */
    public function editStatus(BrandEditStatusRequest $request) {

        try {

            $data        = $request->all();
            $wp_brand_id = $request->get('wp_brand_id');
            $wp          = new WpConvetor();
            $brand_id    = $wp->getId('brand', $wp_brand_id);

            $update_arr = [];
            if (isset($data['status'])) {
                $update_arr['status'] = $data['status'];
            }

            $this->brand->updateOne($data, $brand_id);

            $brand = $this->brand->skipPresenter()->find($brand_id);

            if (isset($data['status'])) {
                $details = $brand->getDealers();
                //Dealer
                foreach ($details as $dealer) {
                    $dealer->status = $brand->status;
                    $dealer->save();
                }
                $offers = $brand->getOffers();
                //Offers
                foreach ($offers as $offer) {
                    // $status = $category->status ? 'publish' : 'draft';

                    $offer->is_active = $brand->status;
                    $offer->save();
                }
            }
            // dd($brand);
            $brand = $this->brand->find($brand_id);
            $data  = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
                'data'    => $brand,
            ];
            // $data = array_merge($data, $brand);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

}
