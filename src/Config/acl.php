<?php

return [
    [
        'key'   => 'prestashop',
        'name'  => 'prestashop::app.components.layouts.sidebar.prestashop',
        'route' => 'prestashop.credentials.index',
        'sort'  => 11,
    ], [
        'key'   => 'prestashop.credentials',
        'name'  => 'prestashop::app.components.layouts.sidebar.credentials',
        'route' => 'prestashop.credentials.index',
        'sort'  => 1,
    ], [
        'key'   => 'prestashop.credentials.create',
        'name'  => 'prestashop::app.prestashop.acl.credential.create',
        'route' => 'prestashop.credentials.store',
        'sort'  => 1,
    ], [
        'key'   => 'prestashop.credentials.edit',
        'name'  => 'prestashop::app.prestashop.acl.credential.edit',
        'route' => 'prestashop.credentials.edit',
        'sort'  => 2,
    ], [
        'key'   => 'prestashop.credentials.delete',
        'name'  => 'prestashop::app.prestashop.acl.credential.delete',
        'route' => 'prestashop.credentials.delete',
        'sort'  => 3,
    ], [
        'key'   => 'prestashop.export-mappings',
        'name'  => 'prestashop::app.components.layouts.sidebar.export-mappings',
        'route' => 'admin.prestashop.export-mappings',
        'sort'  => 2,
    ], [
        'key'   => 'prestashop.import-mappings',
        'name'  => 'prestashop::app.components.layouts.sidebar.import-mappings',
        'route' => 'admin.prestashop.import-mappings',
        'sort'  => 3,
    ], [
        'key'   => 'prestashop.meta-fields',
        'name'  => 'prestashop::app.components.layouts.sidebar.metafield-definitions',
        'route' => 'prestashop.metafield.index',
        'sort'  => 4,
    ], [
        'key'   => 'prestashop.meta-fields.create',
        'name'  => 'prestashop::app.prestashop.acl.metafield.create',
        'route' => 'prestashop.metafield.store',
        'sort'  => 1,
    ], [
        'key'   => 'prestashop.meta-fields.edit',
        'name'  => 'prestashop::app.prestashop.acl.metafield.edit',
        'route' => 'prestashop.metafield.edit',
        'sort'  => 2,
    ], [
        'key'   => 'prestashop.meta-fields.delete',
        'name'  => 'prestashop::app.prestashop.acl.metafield.delete',
        'route' => 'prestashop.metafield.delete',
        'sort'  => 3,
    ], [
        'key'   => 'prestashop.settings',
        'name'  => 'prestashop::app.components.layouts.sidebar.settings',
        'route' => 'admin.prestashop.settings',
        'sort'  => 5,
    ],
];
