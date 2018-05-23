<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Api\CategoriesRequest;
use App\Http\Requests\Api\CategoryDeleteRequest;
use App\Http\Requests\Api\CategoryEditRequest;
use App\Http\Requests\Api\CategoryEditStatusRequest;
use App\Http\Requests\Api\CategoryStoreRequest;
// use App\Http\Requests\Api\categorysShowRequest;
use App\Storage\Category\CategoryRepository;
use App\Storage\LbtWp\WpConvetor;
use Illuminate\Http\Request;

/**
 * @resource Categories
 *
 * Categories resource.
 */
class CategoriesController extends Controller {
    protected $category;
    protected $media;

    public function __construct(CategoryRepository $category) {
        $this->category = $category;

    }

    /**
     * All
     *
     * Get a list of categories.
     *
     * @param      \App\Http\Requests\Api\CategoriesRequest  $request  The request
     *
     * @return     Response
     */
    public function index(CategoriesRequest $request) {
        try {

            $per_page = $request->get('per_page') != '' ? $request->get('per_page') : 20;

            $categories = $this->category->paginate($per_page);

            // dd($categories);
            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
                'data'    => $categories,
            ];
            // $data = array_merge($data, (array) $categories);

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

    // /**
    //  * Single
    //  *
    //  * Get a category by ID.
    //  *
    //  *
    //  * @param      \App\Http\Requests\Api\categorysShowRequest  $request    The request
    //  * @param      Integer                                  $category_id  The category identifier
    //  *
    //  * @return     Response
    //  */
    // public function show(categorysShowRequest $request) {

    //     try {

    //         $wp_category_id = $request->get('wp_category_id');
    //         $wp          = new WpConvetor();
    //         $category_id    = $wp->getId('category', $wp_category_id);
    //         $category       = $this->category->find($category_id);

    //         $data = [
    //             'status'  => true,
    //             'code'    => config('responses.success.status_code'),
    //             'message' => config('responses.success.status_message'),
    //         ];
    //         $data = array_merge($data, $category);

    //         return response()->json($data, config('responses.success.status_code'));

    //     } catch (\Exception $e) {
    //         // dd($e->getMessage());
    //         return response()->json([
    //             'status'  => false,
    //             'code'    => config('responses.bad_request.status_code'),
    //             'data'    => null,
    //             'message' => $e->getMessage(),
    //         ],
    //             config('responses.bad_request.status_code')
    //         );
    //     }
    // }

    /**
     * Store
     *
     * Get a category by ID.
     *
     *
     * @param      \App\Http\Requests\Api\categorysStoreRequest  $request    The request
     *
     * @return     Response
     */
    public function store(CategoryStoreRequest $request) {

        try {

            $data = $request->all();

            $data = [
                'name'           => $request->get('name'),
                'slug'           => $request->get('slug'),
                'wp_category_id' => $request->get('wp_category_id'),

            ];

            $category = $this->category->create($data);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
                'data'    => $this->category->find($category->id),
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
     * Edit a category details
     *
     *
     * @param      \App\Http\Requests\Api\CategoryEditRequest  $request    The request
     *
     * @return     Response
     */
    public function edit(CategoryEditRequest $request) {

        try {

            $data           = $request->all();
            $wp_category_id = $request->get('wp_category_id');
            $wp             = new WpConvetor();
            $category_id    = $wp->getId('category', $wp_category_id);
            $category       = $this->category->find($category_id);

            // $update_arr = [];
            if (isset($data['name'])) {
                // $update_arr['name'] = $data['name'];
                $category->name = $data['name'];
            }

            if (isset($data['slug'])) {
                // $update_arr['slug'] = $data['slug'];
                $category->slug = $data['slug'];
            }
            if (isset($data['status'])) {
                // $update_arr['name'] = $data['name'];
                $category->status = $data['status'];
            }
            $category->save();

            if (isset($data['status'])) {
                $brands = $category->getBrands();

                foreach ($brands as $brand) {
                    $brand->status = $category->status;
                    $brand->save();
                    //Dealer
                    foreach ($brand->getDealers() as $dealer) {
                        $dealer->status = $category->status;
                        $dealer->save();
                    }
                    // dd($brand->getDealers());
                    //Offers
                    foreach ($brand->getOffers() as $offer) {
                        // $status = $category->status ? 'publish' : 'draft';

                        $offer->is_active = $category->status;
                        $offer->save();
                    }
                }
            }
            $category = $this->category->skipPresenter()->find($category_id);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
                'data'    => $category,
            ];
            // $data = array_merge($data, $category);

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
     * Delete a category details
     *
     *
     * @param      \App\Http\Requests\Api\CategoryDeleteRequest  $request    The request
     *
     * @return     Response
     */

    public function destroy(CategoryDeleteRequest $request) {

        try {

            $wp_category_id = $request->get('wp_category_id');
            $wp             = new WpConvetor();
            $category_id    = $wp->getId('category', $wp_category_id);
            $category       = $this->category->skipPresenter()->find($category_id);
            //Move all attached brand to uncategorized brand
            $brands = $category->getBrands();
            // dd(count($brands));
            $uncategorized = $this->category->uncategorized();

            foreach ($brands as $brand) {
                // dd($brand);
                $brand->categories()->detach();
                // exit;
                $brand->categories()->save($uncategorized);
            }

            $category->delete($category_id);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => "Category has been deleted!", //config('responses.success.status_message'),
                'data'    => [],
            ];
            // $data=array_merge($data,$category);

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
     * Edit a category  Status
     *
     *
     * @param      \App\Http\Requests\Api\CategoryEditStatusRequest  $request    The request
     *
     * @return     Response
     */
    public function editStatus(CategoryEditStatusRequest $request) {

        try {

            $data           = $request->all();
            $wp_category_id = $request->get('wp_category_id');
            $wp             = new WpConvetor();
            $category_id    = $wp->getId('category', $wp_category_id);
            $category       = $this->category->find($category_id);

            // $update_arr = [];
            if (isset($data['status'])) {
                // $update_arr['name'] = $data['name'];
                $category->status = $data['status'];
            }
            $category->save();

            // if new status is 1 or 0 then brand, dealer and offer

            $brands = $category->getBrands();

            foreach ($brands as $brand) {
                $brand->status = $category->status;
                $brand->save();
                //Dealer
                foreach ($brand->getDealers() as $dealer) {
                    $dealer->status = $category->status;
                    $dealer->save();
                }
                // dd($brand->getDealers());
                //Offers
                foreach ($brand->getOffers() as $offer) {
                    // $status = $category->status ? 'publish' : 'draft';

                    $offer->is_active = $category->status;
                    $offer->save();
                }
            }

            $category = $this->category->skipPresenter()->find($category_id);

            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
                'data'    => $category,
            ];
            // $data = array_merge($data, $category);

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
