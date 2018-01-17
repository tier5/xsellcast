<?php namespace App\Storage\SalesRep;

use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use App\Storage\LocalApiRequest\LocalApiRequest;
use HTML;

class BAInviteCrud
{

	public static function form($opts)
	{	
		$apiRequest   = new LocalApiRequest();
		$access_token = $apiRequest->getToken();			
		$fields       = new CrudForm('post');
		$fields->setRoute('admin.brand.associate.invite.send');

		$fields->addField(array(
			'name' 			=> 'avatar',
			'label' 		=> 'Change Photo',
			'accepts'		=> 'image/*|video/*',
			'is_single'		=> true,
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'col-class' 	=> 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
			'btn_txt'		=> 'Change Photo',
	
			'clear_all'		=> true));
		$fields->addField(array(
			'name'       => 'firstname',
			'label'      => 'First name',
			'type'       => 'text',
			'col-class'  => 'col-md-6 col-xs-12'));
		$fields->addField(array(
			'name'       => 'lastname',
			'label'      => 'Last name',
			'type'       => 'text',
			'col-class'  => 'col-md-6 col-xs-12'));
		$fields->addField(array(
			'name'      => 'jobtitle',
			'label'     => 'Job Title',
			'type'      => 'text',
			'col-class' => 'col-md-6 col-xs-12',
			'clear_all' => true));
		$fields->addField(array(
			'name'         => 'dealer',
			'label'        => 'Dealer',
			'type'         => 'App\Storage\Crud\CustomFields@selectDealerModal',
			'col-class'    => 'col-md-6 col-xs-12',
			'access_token' => $access_token,
			'clear_all'    => true));
		$fields->addField(array(
			'name'      => 'email',
			'label'     => 'Email',
			'type'      => 'email',
			'col-class' => 'col-md-6 col-xs-12',
			'clear_all' => true));
		$fields->addField(array(
			'name'       => 'cellphone',
			'label'      => 'Cell Phone',
			'type'       => 'text',
			'field-attr' => ['data-mask' => '(999) 999-9999'],
			'col-class'  => 'col-md-6 col-xs-12'));
		$fields->addField(array(
			'name'       => 'officephone',
			'label'      => 'Office Phone',
			'type'       => 'text',
			'field-attr' => ['data-mask' => '(999) 999-9999'],
			'col-class'  => 'col-md-6 col-xs-12'));
		$fields->addField(array(
			'label'      => 'Prospects should be shown:',
			'type'       => 'App\Storage\Crud\CustomFields@h2Field',
			'col-class'  => 'col-md-12 col-xs-12'));			
		$fields->addField(array(
			'name'      => 'show_fields',
			'type'      => 'App\Storage\User\ProfileCrud@showField',
			'col-class' => 'col-md-6 col-xs-12',
			'list'		=> ['show_cellphone' => 'Cell Phone', 'show_email' => 'Email', 'show_officephone' => 'Office Phone'],
			'clear_all' => true));	
		$fields->addField(array(
			'label'      => 'Social Accounts',
			'type'       => 'App\Storage\Crud\CustomFields@h2Field',
			'col-class'  => 'col-md-12 col-xs-12'));		
		$fields->addField(array(
			'name'       => 'facebook',
			'label'      => 'Facebook',
			'type'       => 'text',
			'col-class'  => 'col-md-4 col-xs-12',
			));
		$fields->addField(array(
			'name'       => 'twitter',
			'label'      => 'Twitter',
			'type'       => 'text',
			'col-class'  => 'col-md-4 col-xs-12'));
		$fields->addField(array(
			'name'       => 'linkedin',
			'label'      => 'LinkedIn',
			'type'       => 'text',
			'col-class'  => 'col-md-4 col-xs-12'));

		$fields->showDefaultSubmit(false)->addSubmitBtn('send', 'Send Invitation');

		$info = array(
			'box_title' 	=> 'Brand Associate Info', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;  		
	}
}