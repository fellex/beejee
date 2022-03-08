<?php

return [
    // Main
	'' => [ // список задач
        'action' => 'index',
		'controller' => 'main',
	],
    'create' => [ // создание задачи
        'action' => 'create',
		'controller' => 'main',
	],
	'edit' => [ // редактирование задачи
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
