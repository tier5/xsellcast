<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Offer\OfferRepository;
use Auth;

class OfferController extends Controller
{
    
	protected $crud;

	protected $offer;

	protected $message;

	public function __construct(OfferRepository $offer)
	{
		$this->crud = new Crud();
		$this->offer = $offer;
	}

	public function index(Request $request)
	{
		$user = Auth::user();
    	$offers = $this->offer;
    	$status = $request->get('status');

    	if($user->hasRole('sales-rep'))
    	{
    		$offers->ofSalesRepOrAuthorType($user->salesRep);
    	}

    	if($status)
    	{
    		$offers->scopeQuery(function($q) use($status){

    			return $q->where('status', $status);
    		});
    	}

    	return response()->json($offers->paginate(20));
	}

}