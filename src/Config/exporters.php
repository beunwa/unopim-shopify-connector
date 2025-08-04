<?php

return [
    'prestashopProduct' => [
        'title'    => 'prestashop::app.exporters.prestashop.product',
        'exporter' => 'Webkul\Prestashop\Helpers\Exporters\Product\Exporter',
        'source'   => 'Webkul\Product\Repositories\ProductRepository',
        'filters'  => [
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
                    'name'       => 'currency',
                    'title'      => 'prestashop::app.prestashop.job.currency',
                    'required'   => true,
                    'type'       => 'select',
                    'validation' => 'required',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.currency.fetch-all',
                ], [
                    'name'     => 'productfilter',
                    'title'    => 'prestashop::app.prestashop.job.productfilter',
                    'required' => false,
                    'type'     => 'textarea',
                ],
            ],
        ],
    ],

    'prestashopCategories' => [
        'title'    => 'prestashop::app.exporters.prestashop.category',
        'exporter' => 'Webkul\Prestashop\Helpers\Exporters\Category\Exporter',
        'source'   => 'Webkul\Category\Repositories\CategoryRepository',
        'filters'  => [
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
                ],
            ],
        ],
    ],

    'prestashopMetafield' => [
        'title'    => 'prestashop::app.exporters.prestashop.metafields',
        'exporter' => 'Webkul\Prestashop\Helpers\Exporters\MetaField\Exporter',
        'source'   => 'Webkul\Prestashop\Repositories\ShopifyMetaFieldRepository',
        'filters'  => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'Prestashop credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'prestashop.credential.fetch-all',
                ],
            ],
        ],
    ],
];
