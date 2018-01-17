<?php 

return [
	'pages' => [
		'admin.settings.profile' => [
			'layout_title' => 'My Profile',
			'sidemenu_active' => 'admin_settings',
			'breandcrumb' => [
				['label' => 'My Settings'],
				['label' => 'My Profile', 'is_active' => true]
			]
		],
		'admin.messages' => [
			'layout_title' => 'Inbox Messages',
			'sidemenu_active' => 'admin_messages',
			'breandcrumb' => [
				['label' => 'Messages'],
				['label' => 'All Messages', 'is_active' => true]
			]
		]		
	]
];

?>