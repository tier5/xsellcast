<?php namespace App\Storage\Messenger;

use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use Illuminate\Http\Request;
use App\Storage\Brand\Brand;
use HTML;
use Auth;

class MessageNewCrud
{

	public static function formForModal($options)
	{
		$user         = Auth::user();
		$isCsrRole    = $user->hasRole('csr');
		$fields       = new CrudForm('post');
		$preId        = $options['pre_id'];
		$contactRoute = route('admin.api.contacts');

		$fields->setRoute('admin.messages.direct.send')->setValidationRoute('admin.api.messages.create.validation');

		$fields->addField(array(
			'name' 			=> 'to',
			'label' 		=> 'To',
			'type' 			=> 'App\Storage\Crud\CustomFields@textAutoComplete',
			'col-class' 	=> 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
			'data-url'		=> $contactRoute,
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'subject',
			'label' 		=> 'Subject',
			'type' 			=> 'text',
			'value'			=> $options['subject'],
			'col-class' 	=> 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'offer',
			'label' 		=> 'Offer',
			'type'			=> 'hidden',
			'value'			=> $options['pre_id'],
		//	'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@offerSelect',
			'col-class' 	=> 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
			'select_event'	=> 'offerSelectItem',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'media',
			'label' 		=> 'Media',
			'accepts'		=> 'image/*|video/*',
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'col-class' 	=> 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'body',
			'label' 		=> '&nbsp;',
			'type' 			=> 'tinymce',
			'col-class' 	=> 'col-lg-12 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->showDefaultSubmit(false)
			->showDefaultHead(false)
	//		->addHeadView('admin.messages.create-head')
			->addSubmitBtn('send', ['label' => 'Send', 'class' => 'btn-primary', 'icon_class' => 'fa fa-reply'])
			->addSubmitLinkBtn('discard', ['label' => 'Discard', 'class' => 'btn-white', 'url' => route('admin.offers')]);
	//		->addSubmitBtn('discard', ['label' => 'Discard', 'class' => 'btn-white', 'icon_class' => 'fa fa-close']);
	//		->addSubmitBtn('draft', ['label' => 'Draft', 'class' => 'btn-white', 'icon_class' => 'fa fa-pencil']);		
		$info = array(
			'box_title' 	=> 'Compose new message', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;
	}

	public static function form($options)
	{
		$user         = Auth::user();
		$isCsrRole    = $user->hasRole('csr');
		$fields       = new CrudForm('post');
		$contactRoute = route('admin.api.contacts');

		$fields->setRoute('admin.messages.direct.send');

		$fields->addField(array(
			'name' 			=> 'to',
			'label' 		=> 'To',
			'type' 			=> 'App\Storage\Crud\CustomFields@textAutoComplete',
			'col-class' 	=> 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
			'data-url'		=> $contactRoute,
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'subject',
			'label' 		=> 'Subject',
			'type' 			=> 'text',
			'col-class' 	=> 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'offer',
			'label' 		=> 'Offer',
			'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@offerSelect',
			'col-class' 	=> 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
			'select_event'	=> 'offerSelectItem',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'media',
			'label' 		=> 'Media',
			'accepts'		=> 'image/*|video/*',
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'col-class' 	=> 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'body',
			'label' 		=> '&nbsp;',
			'type' 			=> 'tinymce',
			'col-class' 	=> 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->showDefaultSubmit(false)
			->showDefaultHead(false)
			->addHeadView('admin.messages.create-head')
			->addSubmitBtn('send', ['label' => 'Send', 'class' => 'btn-primary', 'icon_class' => 'fa fa-reply'])
			->addSubmitBtn('discard', ['label' => 'Discard', 'class' => 'btn-white', 'icon_class' => 'fa fa-close'])
			->addSubmitBtn('draft', ['label' => 'Draft', 'class' => 'btn-white', 'icon_class' => 'fa fa-pencil']);		
		$info = array(
			'box_title' 	=> 'Compose new message', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;
	}

	public static function formDraft($options)
	{
		$message   = $options['view_args']['message'];
		$user      = $options['view_args']['user'];
		$thread    = $message->threadWithDrafts;
		$offerId   = $thread->getMeta('offer_id');
		$recepient = $thread->participants()->where('user_id', '!=', $user->id)->first()->user;
		$fields    = new CrudForm('put');

		$fields->setRoute('admin.messages.direct.continue.send')->setModel($message)->setModelId($message->id);

		$fields->addField(array(
			'name' 			=> 'to',
			'label' 		=> 'To',
			'type' 			=> 'App\Storage\Crud\CustomFields@textAutoComplete',
			'col-class' 	=> 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
			'data-url'		=> route('admin.api.contacts'),
			'value'			=> $recepient->email,
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'subject',
			'label' 		=> 'Subject',
			'type' 			=> 'text',
			'value'			=> $thread->subject,
			'col-class' 	=> 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'offer',
			'label' 		=> 'Offer',
			'type' 			=> 'App\Storage\Offer\OfferFieldCustomFields@offerSelect',
			'col-class' 	=> 'col-lg-6 col-md-12 col-sm-12 col-xs-12',
			'select_event'	=> 'offerSelectItem',
			'value'			=> $offerId,
			'offer'			=> $thread->offer(),
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'media',
			'label' 		=> 'Media',
			'accepts'		=> 'image/*|video/*',
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'col-class' 	=> 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
			'value'			=> $message->media_ids,
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'body',
			'label' 		=> '&nbsp;',
			'type' 			=> 'tinymce',
			'value'			=> $message->body,
			'col-class' 	=> 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
			'clear_all'		=> true));

		$fields->showDefaultSubmit(false)
			->showDefaultHead(false)
			->addHeadView('admin.messages.create-head')
			->addSubmitBtn('send', ['label' => 'Send', 'class' => 'btn-primary', 'icon_class' => 'fa fa-reply'])
			->addSubmitBtn('discard', ['label' => 'Discard', 'class' => 'btn-white', 'icon_class' => 'fa fa-close'])
			->addSubmitBtn('draft', ['label' => 'Draft', 'class' => 'btn-white', 'icon_class' => 'fa fa-pencil']);		
		$info = array(
			'box_title' 	=> 'Compose draft message', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;
	}
}