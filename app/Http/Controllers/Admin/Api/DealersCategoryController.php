<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use Auth;
use App\Storage\DealersCategory\DealersCategoryRepository;
use App\Storage\Category\CategoryRepository;

class DealersCategoryController extends Controller
{
    protected $category;

	//protected $dealers_category;

	public function __construct(DealersCategoryRepository $dealers_category, CategoryRepository $category)
	{
		$this->category = $category;
	//	$this->dealers_category = $dealers_category;
	}

	public function index(Request $request)
	{
		$limit = $request->get('limit', 20);
			
		$categories = $this->category->withDealers();

		if($limit < 0){
			$cat = $categories->all();
		}else{
			$cat = $categories->paginate($limit);
		}

		return response()->json($cat);
	}
}