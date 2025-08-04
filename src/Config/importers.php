<?php

return [
    'prestashopCategories' => [
        'title'     => 'prestashop::app.importers.prestashop.category',
        'importer'  => 'Webkul\Prestashop\Helpers\Importers\Category\Importer',
        'validator' => 'Webkul\Prestashop\Validators\JobInstances\Import\ShopifyCategoryAndAttrValidator',
        'filters'   => [
            'fields'  => [
                [
                    'name'       => 'credentials',
                    'title'      => 'prestashop::app.prestashop.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.credential.fetch-all',
                ], [
                    'name'       => 'locale',
                    'title'      => 'prestashop::app.prestashop.job.locale',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.locale.fetch-all',
                ],
            ],
        ],

    ],

    'prestashopAttribute' => [
        'title'     => 'prestashop::app.importers.prestashop.attribute',
        'importer'  => 'Webkul\Prestashop\Helpers\Importers\Attribute\Importer',
        'validator' => 'Webkul\Prestashop\Validators\JobInstances\Import\ShopifyCategoryAndAttrValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'prestashop::app.prestashop.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.credential.fetch-all',
                ], [
                    'name'       => 'locale',
                    'title'      => 'prestashop::app.prestashop.job.locale',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.locale.fetch-all',
                ],
            ],
        ],
    ],

    'prestashopfamily' => [
        'title'     => 'prestashop::app.importers.prestashop.family',
        'importer'  => 'Webkul\Prestashop\Helpers\Importers\Family\Importer',
        'validator' => 'Webkul\Prestashop\Validators\JobInstances\Import\ShopifyFamilyValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'prestashop::app.prestashop.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.credential.fetch-all',
                ], [
                    'name'       => 'locale',
                    'title'      => 'prestashop::app.prestashop.job.locale',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.locale.fetch-all',
                ], [
                    'name'       => 'attributegroupid',
                    'title'      => 'prestashop::app.prestashop.job.attribute-groups',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.attribute-group.fetch-all',
                ],
            ],
        ],

    ],

    'prestashopProduct' => [
        'title'     => 'prestashop::app.importers.prestashop.product',
        'importer'  => 'Webkul\Prestashop\Helpers\Importers\Product\Importer',
        'validator' => 'Webkul\Prestashop\Validators\JobInstances\Import\ShopifyProductValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'prestashop::app.prestashop.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.credential.fetch-all',
                ], [
                    'name'       => 'channel',
                    'title'      => 'prestashop::app.prestashop.job.channel',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.channel.fetch-all',
                ], [
                    'name'       => 'locale',
                    'title'      => 'prestashop::app.prestashop.job.locale',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.locale.fetch-all',
                ], [
                    'name'       => 'currency',
                    'title'      => 'prestashop::app.prestashop.job.currency',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.currency.fetch-all',
                ],
            ],
        ],
    ],

    'prestashopMetaField' => [
        'title'     => 'prestashop::app.importers.prestashop.metafield',
        'importer'  => 'Webkul\Prestashop\Helpers\Importers\Metafield\Importer',
        'validator' => 'Webkul\Prestashop\Validators\JobInstances\Import\ShopifyCategoryAndAttrValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'prestashop::app.prestashop.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.credential.fetch-all',
                ], [
                    'name'       => 'locale',
                    'title'      => 'prestashop::app.prestashop.job.locale',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.locale.fetch-all',
                ],
            ],
        ],
    ],
];
