<?php

return [
    // Main
	'' => [ // ������ �����
        'action' => 'index',
		'controller' => 'main',
	],
    'create' => [ // �������� ������
        'action' => 'create',
		'controller' => 'main',
	],
	'edit' => [ // �������������� ������
        'action' => 'edit',
		'controller' => 'main',
	],

    // Account
	'account/login' => [
		'action' => 'login',
		'controller' => 'account',
	],
	'account/logout' => [
		'action' => 'logout',
		'controller' => 'account',
	],
];
