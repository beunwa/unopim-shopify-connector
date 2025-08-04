<?php

return [
    /**
     * Prestashop.
     */
    [
        'key'   => 'prestashop',
        'name'  => 'prestashop::app.components.layouts.sidebar.prestashop',
        'route' => 'prestashop.credentials.index',
        'sort'  => 10,
        'icon'  => 'icon-prestashop',
    ], [
        'key'   => 'prestashop.credentials',
        'name'  => 'prestashop::app.components.layouts.sidebar.credentials',
        'route' => 'prestashop.credentials.index',
        'sort'  => 1,
    ], [
        'key'    => 'prestashop.export-mappings',
        'name'   => 'prestashop::app.components.layouts.sidebar.export-mappings',
        'route'  => 'admin.prestashop.export-mappings',
        'params' => [1],
        'sort'   => 2,
    ], [
        'key'    => 'prestashop.import-mappings',
        'name'   => 'prestashop::app.components.layouts.sidebar.import-mappings',
        'route'  => 'admin.prestashop.import-mappings',
        'params' => [3],
        'sort'   => 3,
    ], [
        'key'    => 'prestashop.meta-fields',
        'name'   => 'prestashop::app.components.layouts.sidebar.meta-fields',
        'route'  => 'prestashop.metafield.index',
        'sort'   => 4,
    ], [
        'key'    => 'prestashop.settings',
        'name'   => 'prestashop::app.components.layouts.sidebar.settings',
        'route'  => 'admin.prestashop.settings',
        'params' => [2],
        'sort'   => 5,
    ],
];
