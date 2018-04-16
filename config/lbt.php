<?php

return [

	/**
	 * XSellCast client ID
	 */
	'api_client_id' => 'f3d259ddd3ed8ff3843839b',

	/**
	 * XSellCast secret ID
	 */
	'api_secret_id' => '4c7f6f8fa93d59c45502c0ae8c4a95b',
	/**
	 * XSellCast client ID
	 */
	'wp_api_client_id' => 'rRXeQSMR8a65IA7a0uMdwwAqMjL5SAgNsoUcK3Va',

	/**
	 * XSellCast secret ID
	 */
	'wp_api_secret_id' => '18AfMk8StGPoKNzDo1TylfeTmVMINsjy1wZadtaj',

	/**
	 * Ontraport API key
	 */
	'ontraport_key' => env('ONTRAPORT_API_KEY', '5lCXC9WtdfsyV3a'),

	/**
	 * Ontraport API ID
	 */
	'ontraport_id' => env('ONTRAPORT_API_ID', '2_93458_CwnlDwj7F'),

	/**
	 * Ontraport BA object
	 */
	'ontraport_ba_object_id' => env('ONTRAPORT_BA_OBJECT_ID', 0),

	/**
	 * Ontraport Customer object
	 */
	'ontraport_cust_object_id' => env('ONTRAPORT_CUSTOMER_OBJECT_ID', 0),

	/**
	 * Ontraport contact id key
	 */
	'ontraport_contact_id_key' => env('ONTRAPORT_CONTACT_ID_KEY', 'ID'),

	/**
	 * Ontraport tags ID
	 */
	'ontraport_tags' => [
		'ba'             => env('ONTRAPORT_TAG_BA', 63),
		'lc'             => env('ONTRAPORT_TAG_LC', 64),
		'req_price'      => env('ONTRAPORT_TAG_REQ_PRICE', 62),
		'req_message'    => env('ONTRAPORT_TAG_REQ_MESSAGE', 88),
		'req_info'       => env('ONTRAPORT_TAG_REQ_INFO', 86),
		'req_call'       => env('ONTRAPORT_TAG_REQ_CALL', 87),
		'req_appt'       => env('ONTRAPORT_TAG_REQ_APPT', 85),
		'req_contact_me' => env('ONTRAPORT_TAG_REQ_APPT', 18) #TODO: change to proper TAG id
	],

	/**
	 * Mandrill Key
	 */
	'mandrill_key' => env('MANDRILL_API_KEY', 'M4oqwzIEFMarwyGxwzKosA'),

	/**
	 * FullContact API
	 */
	'fullcontact_key' => env('FULLCONTACT_API_KEY', '925b4c2a5309873a'),

	/**
	 * LBT WP Site credentials
	 */
	// 'wp_site' => env('LBT_URL', 'https://luxurybuystoday.com/'),
	'wp_site' => env('LBT_URL', 'https://v2.luxurybuystoday.com/'),

	'wp_api_site' => env('LBT_API_URL', 'https://v2.luxurybuystoday.com/wp-json/wpr-datahub-api/v1/'),

	'wp_user' => env('LBT_USERNAME'),

	'wp_pass' => env('LBT_PASSWORD'),

	/**
	 * User Roles
	 */
	'user_roles'	  => [
		'sales-rep' => [
			'nav_lbl'	=> 'Brand Associate'
		],
		'csr' => [
			'nav_lbl'	=> 'Customer Service Rep'
		]
	],

	'user_action_types' => [
		'offer_request_appt'  => [
			'key'    => 'offer_request_appt',
			'icon'   => 'fa-calendar bg-warning',
			'op_key' => 'REQ_APPT'],
		'offer_request_price' => [
			'key'    => 'offer_request_price',
			'icon'   => 'fa-dollar bg-danger',
			'op_key' => 'REQ_PRICE'],
		'offer_request_info'  => [
			'key'    => 'offer_request_info',
			'icon'   => 'fa-info bg-success',
			'op_key' => 'REQ_INFO'],
		'offer_request_contact_me'  => [
			'key'    => 'offer_request_contact_me',
			'icon'   => 'fa-phone bg-navy',
			'op_key' => 'REQ_CALL'],
		'direct_message' => [
			'key'    => 'direct_message',
			'icon'   => 'fa-comment bg-navy',
			'op_key' => 'REQ_MESSAGE'],
		'added_offer' => [
			'key'  => 'added_offer',
			'icon' => 'fa-circle'],
	],

	'message_stat' => [
		'publish' => ['label' => 'Publish', 'key' => 'publish'],
		'draft'   => ['label' => 'Draft', 'key' => 'draft']
	],

	'message_types' => [
		'message' 	=> [
			'simple'             => 'Direct',
			'name'               => 'Direct Message',
			'head_name'          => 'Direct Messages',
			'head_name_singular' => 'Direct Message',
			'badge'              => 'navy'],
		'note' 		=> [
			'name'  => 'Note',
			'badge' => 'blank'],
		'appt' 		=> [
			'simple'             => 'Appointment',
			'name'               => 'Appt Request',
			'head_name'          => 'Appointment Requests',
			'head_name_singular' => 'Appointment Request',
			'badge'              => 'warning'],
		'price' 	=> [
			'simple'             => 'Price',
			'name'               => 'Pricing Request',
			'head_name'          => 'Price Requests',
			'head_name_singular' => 'Price Request',
			'badge'              => 'danger'],
		'info' 		=> [
			'simple'             => 'Information',
			'name'               => 'Info Request',
			'head_name'          => 'Information Requests',
			'head_name_singular' => 'Appointment Request',
			'badge'              => 'blue'],
		'contact_me' 		=> [
			'simple'             => 'Contact',
			'name'               => 'Contact Request',
			'head_name'          => 'Contact Requests',
			'head_name_singular' => 'Contact Request',
			'badge'              => 'navy'],
		'new_lead'	=> [
			'name'               => 'New Lead',
			'head_name'          => 'New Leads',
			'head_name_singular' => 'New Lead',
			'badge'              => 'warning'],
		'lead_reassign'	=> [
			'simple'             => 'Prospect Reassignment',
			'name'               => 'Lead Reassignment',
			'head_name'          => 'Lead Reassignments',
			'head_name_singular' => 'Lead Reassignment',
			'badge'              => 'warning'],
		'system'	=> [
			'simple'             => '',
			'name'               => '',
			'head_name'          => '',
			'head_name_singular' => '',
			'badge'              => 'default']
	],

	/**
	 * ZIP code API key: https://www.zipcodeapi.com/API
	 */
	'zipcodeapi_key' => env('ZIPCODEAPI_KEY', 'LtZSwmSuylcHGvgohmPLYP5Q8zGD1CHN6HGC3y7CUkjBQA07WPSVEXLMwspy57mc'),

	'offer' => [
		/**
		 * Show hide Dealer Offer menu.
		 * JIRA: LBT-304
		 */
		'enable_dealer_offers' => false,

		'author_type' => [
			'custom' => [ 'label' => 'Custom Offer', 'badge' => 'danger' ],
			'brand' => [ 'label' => 'Brand Offer', 'badge' => 'warning' ],
			'dealer' => [ 'label' => 'Dealer Offer', 'badge' => 'blue' ]]
	]
];