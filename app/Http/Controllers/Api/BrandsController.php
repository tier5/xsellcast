<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Brand\BrandRepository;
use App\Http\Requests\Api\BrandsRequest;
use App\Http\Requests\Api\BrandsShowRequest;

/**
 * @resource Brand
 *
 * Brand resource.
 */
class BrandsController extends Controller
{
	protected $brand;

	public function __construct(BrandRepository $brand)
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
    	$brands = $this->brand->paginate(20);

		return response()
			->json($brands);
    }

    /**
     * Single
     *
     * Get a brand by ID.
     * Return 404 if dealer doesn't exist.
     *
     * @param      \App\Http\Requests\Api\BrandsShowRequest  $request    The request
     * @param      Integer                                  $dealer_id  The dealer identifier
     *
     * @return     Response
     */
    public function show(BrandsShowRequest $request, $brand_id)
    {
    	$brand = $this->brand->find($brand_id);

		return response()
			->json($brand);
    }


}
