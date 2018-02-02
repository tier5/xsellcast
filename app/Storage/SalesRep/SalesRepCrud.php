<?php namespace App\Storage\SalesRep;

use App\Storage\Crud\TableCollection;
use App\Storage\Crud\CrudForm;
use App\Storage\Crud\Box;
use Illuminate\Http\Request;
use HTML;
use App\Storage\LocalApiRequest\LocalApiRequest;

class SalesRepCrud
{

	public static function table($model, $opt)
	{
		$table  = new TableCollection();
		$all    = ($model ? $model->all() : [] );
		$info   = array(  
		  'box_title'     => (isset($opt['box_title']) ? $opt['box_title'] : 'Brand Associates' ),
		  'box_body_class' => 'no-padding',
		  'column_size'   => 12);

		$table = $table->make($all)
			->columns(array(
				'name'      => 'Name',
				'email'     => 'Email',
				'joined'    => 'Joined',
				'agreement' => 'Agreement Accepted'
		  	))
		  	->modify('name', function($salesrep){

		  		$html = $salesrep->user->firstname . ' ' . $salesrep->user->lastname;
				$span = Html::tag('span', 'Name', ['class' => 'responsive-tbl-head']);

		  		return $span . Html::link(route('admin.salesrep.show', ['salesrep_id' => $salesrep->id]), $html, ['class' => 'text-default text-navy']);
		  	})
		  	->modify('email', function($salesrep){

		  		$html = $salesrep->user->email;
		  		$span = Html::tag('span', 'Email', ['class' => 'responsive-tbl-head']);

		  		return $span . Html::link(route('admin.salesrep.show', ['salesrep_id' => $salesrep->id]), $html, ['class' => 'text-default text-navy']);
		  	})
		  	->modify('joined', function($salesrep){

		  		$html = $salesrep->local_created_at->format('d M Y \a\t h:i a');
		  		$span = Html::tag('span', 'Joined', ['class' => 'responsive-tbl-head']);

		  		return $span . Html::link(route('admin.salesrep.show', ['salesrep_id' => $salesrep->id]), $html, ['class' => 'text-default text-navy']);
		  	})
		  	->modify('agreement', function($salesrep){

		  		$agreedAt = $salesrep->local_agreed_at;
		  		$span = Html::tag('span', 'Agreement Accepted', ['class' => 'responsive-tbl-head']);

		  		if($agreedAt)
		  		{

		  			$html = $agreedAt->format('d/m/Y \a\t H:i a');
		  		}else{
		  			$html = '-';
		  		}

		  		return $span . ($agreedAt ? Html::link(route('admin.salesrep.show', ['salesrep_id' => $salesrep->id]), $html, ['class' => 'text-default text-navy']) : $html);
		  	})
		  	->sortable(array('name', 'email', 'joined', 'agreement'))
		  	->addAttribute('id', 'salesrep-tbl' )
		  	->toActionShow(false);

		$box = new Box($info);
		$box->setTable($table);    

		return $box;  		
	}

	public static function editForm($opts)
	{
		$apiRequest   = new LocalApiRequest();
		$access_token = $apiRequest->getToken();		
		$user         = $opts['view_args']['user'];
		$salesrep     = $user->salesRep;
		$fields       = new CrudForm('put');
		$dealer       = $salesrep->dealers->first();
		$showModal    = \Request::session()->get('show_salesrep_agree_modal', false);

		$fields->setRoute('admin.salesrep.update');
		$fields->setModel($salesrep);
		$fields->setModelId($salesrep->id);

		$fields->addField(array(
			'name' 			=> 'salesrep_agreement',
			'type' 			=> 'App\Storage\Crud\CustomFields@salesrepAgreement',
			'value'			=> (string)$salesrep->is_agreement,
			'show_modal'	=> $showModal,
			'clear_all'		=> true));

		$fields->addField(array(
			'name' 			=> 'avatar',
			'label' 		=> 'Change Photo',
			'accepts'		=> 'image/*|video/*',
			'is_single'		=> true,
			'type' 			=> 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
			'col-class' 	=> 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
			'btn_txt'		=> 'Change Photo',
			'value'			=> [$user->avatarId()],
			'clear_all'		=> true));

		$fields->addField(array(
			'name'       => 'local_agreed_at_date',
			'type'       => 'text',
			'label'		 => 'Agreement Accepted',
			'value'      => ($salesrep->local_agreed_at ? $salesrep->local_agreed_at->format('m/d/Y \a\t h:i a') : ''),
			'field-attr' => [ 'disabled' => 'disabled' ],
			'col-class'  => 'col-md-6 col-xs-12',
			'clear_all'  => true));	

		$fields->addField(array(
			'name'       => 'firstname',
			'label'      => 'First name',
			'type'       => 'text',
			'value'		 => $user->firstname,
			'col-class'  => 'col-md-6'));
		$fields->addField(array(
			'name'       => 'lastname',
			'label'      => 'Last name',
			'type'       => 'text',
			'value'		 => $user->lastname,
			'col-class'  => 'col-md-6'));
		$fields->addField(array(
			'name'      => 'jobtitle',
			'label'     => 'Job Title',
			'type'      => 'text',
			'col-class' => 'col-md-6',
			'value'		=> $salesrep->job_title,
			'clear_all' => true));
		$fields->addField(array(
			'name'         => 'dealer',
			'label'        => 'Dealer',
			'type'         => 'App\Storage\Crud\CustomFields@selectDealerModal',
			'col-class'    => 'col-md-6',
			'value'        =>  (isset($dealer->id) ? [$dealer->id, $dealer->name] : null),
			'access_token' => $access_token,
			'clear_all'    => true));

		$fields->addField(array(
			'name'      => 'email',
			'label'     => 'Personal Email',
			'type'      => 'email',
			'col-class' => 'col-md-6',
			'value'		=> $user->email));
		$fields->addField(array(
			'name'      => 'work_email',
			'label'     => 'Work Email',
			'type'      => 'email',
			'value'     => $salesrep->email_work,
			'col-class' => 'col-md-6'));

		$fields->addField(array(
			'name'       => 'cellphone',
			'label'      => 'Cell Phone',
			'type'       => 'text',
			'value'		 => $salesrep->cellphone,
			'field-attr' => ['data-mask' => '(999) 999-9999'],
			'col-class'  => 'col-md-6'));
		$fields->addField(array(
			'name'       => 'officephone',
			'label'      => 'Office Phone',
			'type'       => 'text',
			'field-attr' => ['data-mask' => '(999) 999-9999'],
			'value'		 => $salesrep->officephone,
			'col-class'  => 'col-md-6'));
		$fields->addField(array(
			'label'      => 'Prospects should be shown:',
			'type'       => 'App\Storage\Crud\CustomFields@h2Field',
			'col-class'  => 'col-md-12'));			
		$fields->addField(array(
			'name'      => 'show_fields',
			'type'      => 'App\Storage\User\ProfileCrud@showField',
			'col-class' => 'col-md-6',
			'list'		=> ['show_cellphone' => 'Cell Phone', 'show_officephone' => 'Office Phone', 'show_email' => 'Email'],
			'value'		=> ['show_cellphone' => $salesrep->show_cellphone, 'show_officephone' => $salesrep->show_officephone, 'show_email' => $salesrep->show_email],
			'clear_all' => true));	
		$fields->addField(array(
			'label'      => 'Social Accounts',
			'type'       => 'App\Storage\Crud\CustomFields@h2Field',
			'col-class'  => 'col-md-12'));		
		$fields->addField(array(
			'name'       => 'facebook',
			'label'      => 'Facebook',
			'type'       => 'text',
			'value'		 => $salesrep->facebook,
			'col-class'  => 'col-md-4',
			));
		$fields->addField(array(
			'name'       => 'twitter',
			'label'      => 'Twitter',
			'type'       => 'text',
			'value'		 => $salesrep->twitter,
			'col-class'  => 'col-md-4'));
		$fields->addField(array(
			'name'       => 'linkedin',
			'label'      => 'LinkedIn',
			'type'       => 'text',
			'value'		 => $salesrep->linkedin,
			'col-class'  => 'col-md-4'));
		$fields->addField(array(
			'name'       => 'pinterest',
			'label'      => 'Pinterest',
			'type'       => 'text',
			'value'		 => $salesrep->pinterest,
			'col-class'  => 'col-md-4'));
		$fields->addField(array(
			'name'       => 'instagram',
			'label'      => 'Instagram',
			'type'       => 'text',
			'value'		 => $salesrep->instagram,
			'col-class'  => 'col-md-4'));
		$fields->addField(array(
			'name'       => 'youtube',
			'label'      => 'Youtube',
			'type'       => 'text',
			'value'		 => $salesrep->youtube,
			'col-class'  => 'col-md-4'));

		$fields->showDefaultSubmit(false)->addSubmitBtn('update', 'Update Profile');

		$info = array(
			'box_title' 	=> 'Profile', 
			'column_size' 	=> 12,
			'column_class' 	=> 'col-sm-12 col-xs-12');

		$box = new Box($info);
		$box->setForm($fields);

		return $box;  		
	}

}
