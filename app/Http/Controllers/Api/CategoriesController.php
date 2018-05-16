<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Api\CategoriesRequest;
use App\Http\Requests\Api\CategoryEditRequest;
// use App\Http\Requests\Api\categoryDeleteRequest;
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
                // 'data'    => $categories,
            ];
            $data = array_merge($data, (array) $categories);

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

            $category->save();

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
     * @param      \App\Http\Requests\Api\categoryEditRequest  $request    The request
     *
     * @return     Response
     */

    // public function destroy(categoryDeleteRequest $request) {

    //     try {

    //         $wp_category_id = $request->get('wp_category_id');
    //         $wp          = new WpConvetor();
    //         $category_id    = $wp->getId('category', $wp_category_id);
    //         $category       = $this->category->skipPresenter()->delete($category_id);
    //         $data        = [
    //             'status'  => true,
    //             'code'    => config('responses.success.status_code'),
    //             'message' => "category has been deleted!", //config('responses.success.status_message'),
    //             'data'    => [],
    //         ];
    //         // $data=array_merge($data,$category);

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
}
