<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Offer\OfferRepository;
use App\Storage\SalesRep\SalesRepRepository;
use App\Http\Requests\Admin\OfferPostRequest;
use App\Storage\OfferTag\OfferTagRepository;
use App\Storage\Brand\BrandRepository;
use Auth;

class OfferController extends Controller
{
	protected $crud;

	protected $offer;

	protected $salesrep;

    protected $offertag;

	public function __construct(OfferRepository $offer, SalesRepRepository $salesrep, OfferTagRepository $offertag, BrandRepository $brand)
	{
		$this->crud = new Crud();
		$this->offer = $offer;
		$this->salesrep = $salesrep;
        $this->offertag = $offertag;
        $this->brand = $brand;
	}

	public function index(Request $request, $author_type = null)
	{
        $layoutColumns = $this->crud->layoutColumn();
        $user          = $request->user();
        $orderBy       = $request->get('field', 'created_at');
        $orderBy       = ($orderBy == 'updated' ? 'updated_at' : 'created_at' );
        $order         = $request->get('sort', 'desc');

        if($user->hasRole('csr'))
        {
            $model = $this->offer->ofAuthorType($author_type);
        }else{
            /**
             * BA(SalesRep) go here.
             *
             * @var SalesRep
             */
            $salesRep = $user->salesRep;
            $model    = $this->offer->ofSalesRepOrAuthorType($salesRep, $author_type);
        }
        $model->orderBy($orderBy, $order);
        $model = $model->skipPresenter()->paginate(20);

        $layoutColumns->addItem('admin.offer.listing_bottom', ['show_box' => false, 'column_class' => 'm-b-sm']);
    	$layoutColumns->addItemTable('App\Storage\Offer\OfferCrud@table', $model, compact('user'));

		return $this->crud->pageView($layoutColumns, compact('author_type', 'user'));
	}

    public function create(Request $request)
    {
        $user = $request->user();
		$layoutColumns = $this->crud->layoutColumn();
    	$layoutColumns->addItemForm('App\Storage\Offer\OfferCrud@createForm', ['user' => $user]);

		return $this->crud->pageView($layoutColumns);
    }

    public function store(OfferPostRequest $request)
    {
    	$draft = $request->get('draft');
    	$publish = $request->get('publish');
    	$salesRep = $this->salesrep->currentUser();
    	$status = ($draft ? 'draft' : 'publish');
        $brand = $this->brand->skipPresenter()->find($request->get('brand'));

        if(!$salesRep)
        {
            //CSR saving offer.
            $msg = 'Offer added.';
        }elseif($draft){
    		$msg = 'Offer saved to draft.';
    	}else{
    		$msg = 'We are now checking your offer for typos and for conformance with our community guidelines. Your offer will be posted to LuxuryBuysToday.com within 5 minutes.';
    	}

    	$param = [
    		'media' => $request->get('media'),
    		'contents' => $request->get('contents'),
    		'title' => $request->get('title'),
            'thumbnail_id' => $request->get('thumbnail_id'),
    		'status' => $status];

        if($salesRep)
        {
            $offer = $this->offer->createForSalesRep($param, $salesRep->id);
        }else{
            $offer = $this->offer->createForCsr($param);
        }

        /**
         * Save brand
         */
        $offer->brands()->detach();
        $offer->brands()->attach($brand);

        $this->offertag->createUpdateToOffer($offer, $request->get('tags'));

        $request->session()->flash('message', $msg);
		return redirect()->route('admin.offers');
    }

    public function edit(Request $request, $offer_id)
    {
        $offer         = $this->offer->skipPresenter()->find($offer_id);
        $user          = Auth::user();
        $layoutColumns = $this->crud->layoutColumn();

        $layoutColumns->addItemForm('App\Storage\Offer\OfferCrud@editForm', ['model' => $offer, 'user' => $user]);

        return $this->crud->pageView($layoutColumns);
    }

    public function update(OfferPostRequest $request, $offer_id)
    {
        $save = $request->get('save');
        $publish = $request->get('publish');
        $status = $request->get('status');
        $brand = $this->brand->skipPresenter()->find($request->get('brand'));

        if($publish){
            $status = 'publish';
        }

        $args = [
            'status' => $status,
            'title' => $request->get('title'),
            'contents' => $request->get('contents')];

        $offer = $this->offer->skipPresenter()->update($args, $offer_id);

        /**
         * Save brand
         */
        $offer->brands()->detach();
        $offer->brands()->attach($brand);

        $msg = "Offer has been updated!";

        $offer->setMeta('media', $request->get('media'));
        $offer->setMeta('thumbnail_id', $request->get('thumbnail_id'));
        $offer->save();

        $this->offertag->createUpdateToOffer($offer, $request->get('tags'));

        $request->session()->flash('message', $msg);

        return redirect()->route('admin.offers');
    }

    public function destroy(Request $request, $offer_id)
    {
        $offer = $this->offer->skipPresenter()->delete($offer_id);
        $msg = "Offer has been deleted!";
        $request->session()->flash('message', $msg);
        return redirect()->route('admin.offers');
    }

    public function show(Request $request, $offer_id)
    {
        $offer         = $this->offer->skipPresenter()->find($offer_id);
        $user          = Auth::user();
        $layoutColumns = $this->crud->layoutColumn();

        $layoutColumns->addItemForm('App\Storage\Offer\OfferCrud@showForm', ['model' => $offer, 'user' => $user]);

        return $this->crud->pageView($layoutColumns);
    }
}