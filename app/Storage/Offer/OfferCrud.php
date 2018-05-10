<?php namespace App\Storage\Offer;

use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use Illuminate\Http\Request;
use App\Storage\Brand\Brand;
use HTML;

class OfferCrud
{

	public static function editForm($options)
	{
		$offer          = $options['model'];
		$fields         = new CrudForm('put');
		$saveBtnAttr    = ['label' => 'Save Changes', 'class' => 'btn-light-navy'];
		$brand          = $offer->brands->first();
		$disableEdit    = false;
		$user = \Auth::user();
		$isCsr  = $user->hasRole('csr');

		if($offer->author_type != 'custom' && $options['user']->hasRole('csr'))
		{
			$disableEdit = true;
		}

		if(!$disableEdit){
			$fields->addField(array(
				'name' 			=> 'status',
				'label' 		=> 'Status',
				'value'			=> $offer->status,
				'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@editSelect',
				'list'			=> ['publish' => 'Published', 'draft' => 'Unpublished'],
				'col-class' 	=> 'col-xs-8'));
		}

		$fields->addField(array(
			'name' 			=> 'delete',
			'label' 		=> 'Delete Offer',
			'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@yesNoLinkModal',
			'col-class' 	=> ($disableEdit ? 'col-xs-12' : 'col-xs-4') . ' text-right',
			'url' 			=> route('admin.offers.destroy', ['offer_id' => $offer->id]),
			'question'		=> 'Are you sure you would like to delete this offer?',
			'class'			=> 'btn-danger'));

		$fields->addField(array(
			'name'       => 'title',
			'label'      => 'Title',
			'type'       => 'text',
			'clear_all'  => true,
			'value'      => $offer->title,
			'col-class'  => 'col-md-6 col-xs-12',
			'field-attr' => ['placeholder' => 'add offer title here',
				'disabled' => $disableEdit,
				'help' => 'Help contents here please.']));

		$fields->addField(array(
			'name' 			=> 'thumbnail_id',
			'label' 		=> 'Thumbnail',
			'type' 			=> 'hidden',
			'value'			=> $offer->getMeta('thumbnail_id'),
			'field-attr'	=> ['id' => 'thumbnail_id', 'disabled' => $disableEdit]));

		if($isCsr)
		{

			$fields->addField(array(
				'name' 			=> 'brand',
				'label' 		=> 'Brand',
				'type' 			=> 'select',
				'col-class' 	=> 'col-md-6 col-xs-12',
				'list'			=>  [0 => 'Select brand...'] + Brand::all()->lists('name', 'id')->toArray(),
				'selected'		=> ($brand ? $brand->id : 0),
				'field-attr'	=> ['id' => 'brand', 'disabled' => $disableEdit]));
		}else{

			$fields->addField(array(
				'name' 			=> 'brand',
				'label' 		=> 'Brand',
				'type' 			=> 'hidden',
				'value'			=> $brand->id,
				'col-class' 	=> 'col-md-6'));
		}

		$fields->addField(array(
			'name' 			=> 'media',
			'label' 		=> 'Media',
			'accepts'		=> 'image/*|video/*',
			'value'			=> 	$offer->getMeta('media'),
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'modfootrth'	=> 'offerModalFooter',
			'col-class' 	=> 'col-md-12 col-xs-12',
			'field-attr'	=> ['disabled' => $disableEdit]));

		$fields->addField(array(
			'name' 			=> 'contents',
			'label' 		=> 'Body',
			'type' 			=> 'tinymce',
			'value'			=> $offer->contents,
			'col-class' 	=> 'col-md-8 col-xs-12',
			'field-attr' 	=> ['placeholder' => 'add body text here', 'disabled' => $disableEdit]));

		$fields->addField(array(
			'name' 			=> 'tags',
			'label' 		=> 'Tags',
			'type' 			=> 'text',
			'value'			=> implode(',', $offer->tags()->lists('tag')->toArray()),
			'col-class' 	=> 'col-md-8 col-xs-12',
			'field-attr' 	=> ['placeholder' => 'add hashtags here. 2 max, comma separated',
				'help' => 'Help contents here please.', 'disabled' => $disableEdit]));

		$fields->showDefaultSubmit(false);

		if($offer->status != 'publish'){
			$fields->addSubmitBtn('publish', 'Publish Offer');
		//	$saveBtnAttr['class'] = 'btn-link';
		}

		if(!$disableEdit){
			$fields->addSubmitBtn('save', $saveBtnAttr);
		}

		$fields->addSubmitLinkBtn('cancel', ['label' => 'Cancel', 'class' => 'btn-light-navy', 'url' => route('admin.offers')])
			->setModelId($offer->id)
			->setRoute('admin.offers.update');

		$info = array(
			'box_title' 	=> 'Edit an Offer',
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;
	}

	protected static function currentSalesRepBrand()
	{
		$user = \Auth::user();
		$isCsr  = $user->hasRole('csr');
		$brand = null;

		if(!$isCsr)
		{
			$brand = $user->salesRep->dealers->first()->brands->first();
		}

		return $brand;
	}

	public static function createForm($data)
	{
		$brand = OfferCrud::currentSalesRepBrand();
		$fields = new CrudForm('post');

		$fields->setRoute('admin.offers.store');
		$fields->addField(array(
			'name' 			=> 'title',
			'label' 		=> 'Title',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-6',
			'field-attr' 	=> ['placeholder' => 'add offer title here',
				'help' => 'Help contents here please.']));

		$fields->addField(array(
			'name' 			=> 'thumbnail_id',
			'label' 		=> 'Thumbnail',
			'type' 			=> 'hidden',
			'value'			=> '',
			'field-attr'	=> ['id' => 'thumbnail_id']));

		$fields->addField(array(
			'name' 			=> 'media',
			'label' 		=> 'Media',
			'accepts'		=> 'image/*|video/*',
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'modfootrth'	=> 'offerModalFooter',
			'col-class' 	=> 'col-md-12'));

		if(!$brand)
		{
			$fields->addField(array(
				'name' 			=> 'brand',
				'label' 		=> 'Brand',
				'type' 			=> 'select',
				'col-class' 	=> 'col-md-6',
				'list'			=> [0 => 'Select brand...'] + Brand::all()->lists('name', 'id')->toArray(),
				'field-attr'	=> ['id' => 'brand']));
		}else
		{
			$fields->addField(array(
				'name' 			=> 'brand',
				'label' 		=> 'Brand',
				'type' 			=> 'hidden',
				'value'			=> $brand->id,
				'col-class' 	=> 'col-md-6'));
		}

		$fields->addField(array(
			'name' 			=> 'contents',
			'label' 		=> 'Body',
			'type' 			=> 'tinymce',
			'col-class' 	=> 'col-md-8',
			'field-attr' 	=> ['placeholder' => 'add body text here']));

		$fields->addField(array(
			'name' 			=> 'tags',
			'label' 		=> 'Tags',
			'type' 			=> 'text',
			'col-class' 	=> 'col-md-8',
			'field-attr' 	=> ['placeholder' => 'add hashtags here. 2 max, comma separated',
				'help' => 'Help contents here please.']));

		$fields->showDefaultSubmit(false)
			->addSubmitBtn('publish', 'Publish Offer')
			->addSubmitBtn('draft', ['label' => 'Save as Draft', 'class' => 'btn-light-navy'])
			->addSubmitLinkBtn('cancel', ['label' => 'Cancel', 'class' => 'btn-light-navy', 'url' => route('admin.offers')]);

		$info = array(
			'box_title' 	=> 'Create an Offer',
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;
	}

	public static function table($model, $opt)
	{
		$user = $opt['user'];
		$isCsr = $user->is_csr;
		$table  = new TableCollection();
		$all    = ($model ? $model->all() : [] );
		$info   = array(
		  'box_title'     => (isset($opt['box_title']) ? $opt['box_title'] : 'My Offers' ),
		  'box_body_class' => 'no-padding',
		  'column_size'   => 12);

		$table = $table->make($all)
			->columns(array(
		    	'thumbnail' => 'Thumbnail',
		    	'info'		=> '',
		    	'updated'	=> 'Last Updated',
		    	'actions' 	=> 'Actions'
		  	))
		  	->modify('thumbnail', function($offer){

				$medias = $offer->medias();
				$thumbId = $offer->getMeta('thumbnail_id');
				$url='';
				if($medias){
					if($thumbId){
					$media = $medias->find($thumbId);
					}else{
						$media = $medias->first();
					}
					if($media){
					$url = $media->getSize(150, 100);
					}else{
						$url=$offer->media_link;
					}

				}else
				{
						$url=$offer->media_link;
					}

				if($url!=''){
					$img = ($url ? HTML::image($url) : '' );

			  		return $img;
				}else{
					return '';
				}

		  		//return HTML::link(route('admin.offers.edit', [$offer->id]), $img, [], null, false);
		  	})
		  	->modify('info', function($o){

		  		return view('admin.offer.table.col-info', ['offer' => $o]);
		  	})
		  	->modify('updated', function($o){
		  		$o->updated_at = carbonToLocal($o->updated_at);

		  		$now = \Carbon\Carbon::now();
		  		$length = $o->updated_at->diffInDays($now);

		  		if($length == 0){
		  			return $o->updated_at->format('\T\o\d\a\y \a\t h:i A');
		  		}elseif($length < 7)
		  		{
		  			return $o->updated_at->format('l \a\t h:i A');
		  		}else{

		  			return $o->updated_at->format('M d \a\t h:i A');
		  		}

		  	})
		  	->modify('actions', function($o) use($user, $isCsr){

		  		return view('admin.offer.table.col-actions', ['offer' => $o, 'user' => $user, 'is_csr' => $isCsr]);
		  	})
		  	->sortable(['updated'])
		  	->addAttribute('id', 'offer-list-tble')
		  	->toActionShow(false);

		$box = new Box($info);
		$box->setTable($table);

		return $box;
	}

	public static function showForm($options)
	{
		$offer          = $options['model'];
		$fields         = new CrudForm('put');
		$saveBtnAttr    = ['label' => 'Save Changes', 'class' => 'btn-light-navy'];
		$brand          = $offer->brands->first();
		$disableEdit    = true;
		$user = \Auth::user();
		$isCsr  = $user->hasRole('csr');

		if($offer->author_type != 'custom' && $options['user']->hasRole('csr'))
		{
			$disableEdit = true;
		}

		if(!$disableEdit){
			$fields->addField(array(
				'name' 			=> 'status',
				'label' 		=> 'Status',
				'value'			=> $offer->status,
				'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@editSelect',
				'list'			=> ['publish' => 'Published', 'draft' => 'Unpublished'],
				'col-class' 	=> 'col-xs-8' ,'disabled'));
		}

		// $fields->addField(array(
		// 	'name' 			=> 'delete',
		// 	'label' 		=> 'Delete Offer',
		// 	'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@yesNoLinkModal',
		// 	'col-class' 	=> ($disableEdit ? 'col-xs-12' : 'col-xs-4') . ' text-right',
		// 	'url' 			=> route('admin.offers.destroy', ['offer_id' => $offer->id]),
		// 	'question'		=> 'Are you sure you would like to delete this offer?',
		// 	'class'			=> 'btn-danger'));

		$fields->addField(array(
			'name'       => 'title',
			'label'      => 'Title',
			'type'       => 'text',
			'clear_all'  => true,
			'value'      => $offer->title,
			'col-class'  => 'col-md-12 col-xs-12',
			'field-attr' => ['placeholder' => 'add offer title here',
				'disabled' => $disableEdit,
				'help' => 'Help contents here please.']));

		$fields->addField(array(
			'name' 			=> 'thumbnail_id',
			'label' 		=> 'Thumbnail',
			'type' 			=> 'hidden',
			'value'			=> $offer->getMeta('thumbnail_id'),
			'field-attr'	=> ['id' => 'thumbnail_id', 'disabled' => $disableEdit]));

		if($isCsr)
		{

			$fields->addField(array(
				'name' 			=> 'brand',
				'label' 		=> 'Brand',
				'type' 			=> 'select',
				'col-class' 	=> 'col-md-12 col-xs-12',
				'list'			=>  [0 => 'Select brand...'] + Brand::all()->lists('name', 'id')->toArray(),
				'selected'		=> ($brand ? $brand->id : 0),
				'field-attr'	=> ['id' => 'brand', 'disabled' => $disableEdit]));
		}else{

			$fields->addField(array(
				'name' 			=> 'brand',
				'label' 		=> 'Brand',
				'type' 			=> 'hidden',
				'value'			=> $brand->id,
				'col-class' 	=> 'col-md-12'));
		}


// dd($offer->getMeta('media'));
			// $fields->addField(array(
			// 'name' 			=> 'media',
			// 'label' 		=> 'Media',
			// 'accepts'		=> 'image/*|video/*',
			// 'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			// 'modfootrth'	=> 'offerModalFooter',
			// 'col-class' 	=> 'col-md-12'));

			$fields->addField(array(
			'name' 			=> 'media_link',
			'label' 		=> 'Media',
			'value'			=> $offer->media_link,
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUrl',
			// 'modfootrth'	=> 'offerModalFooter',
			'col-class' 	=> 'col-md-2'));


		$fields->addField(array(
			'name' 			=> 'contents',
			'label' 		=> 'Body',
			'type' 			=> 'tinymce',
			'value'			=> $offer->contents,
			'col-class' 	=> 'col-md-12 col-xs-12',
			'field-attr' 	=> ['placeholder' => 'add body text here', 'disabled' => $disableEdit]));

		$fields->addField(array(
			'name' 			=> 'tags',
			'label' 		=> 'Tags',
			'type' 			=> 'text',
			'value'			=> implode(',', $offer->tags()->lists('tag')->toArray()),
			'col-class' 	=> 'col-md-12 col-xs-12',
			'field-attr' 	=> ['placeholder' => 'add hashtags here. 2 max, comma separated',
				'help' => 'Help contents here please.', 'disabled' => $disableEdit]));

		$fields->showDefaultSubmit(false);

		if($offer->status != 'publish'){
			$fields->addSubmitBtn('publish', 'Publish Offer');
		//	$saveBtnAttr['class'] = 'btn-link';
		}

		if(!$disableEdit){
			$fields->addSubmitBtn('save', $saveBtnAttr);
		}

		$fields->addSubmitLinkBtn('cancel', ['label' => 'Cancel', 'class' => 'btn-light-navy', 'url' => route('admin.offers')])
			->setModelId($offer->id)
			->setRoute('admin.offers.update');

		$info = array(
			'box_title' 	=> 'Show an Offer',
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;
	}
}