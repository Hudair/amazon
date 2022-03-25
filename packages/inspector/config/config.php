<?php
return [

    /**
     * Package name routes
     */
    'routes' => [
        'settings' => 'inspector.settings',
    ],

    /**
     * Models that will be inspected by the package
     */
    'models' => [
        [
    	    'class' => \App\Product::class,
            'show' => 'admin.catalog.product.show',
            'edit' => 'admin.catalog.product.edit'
        ], [
            'class' => \App\Inventory::class,
            'show' => 'admin.stock.inventory.show',
            'edit' => 'admin.stock.inventory.edit',
        ]
	],

];