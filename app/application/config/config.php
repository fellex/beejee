<?php

$config = [
    'mysql' => [ // подключение к БД
        'host' => '127.0.0.1',
        'dbname' => '',
        'user' => '',
        'password' => '',
    ],
    'site_root_path' => '/',  // default => '/' - root_path расположения сайта
    'paging' => [ // настройка пагинации
        'page_delta' => 2, // +- кол-во страниц относительно выбранной страницы
        'rows_per_page' => 3, // строк на странице
    ],
];

return $config;
