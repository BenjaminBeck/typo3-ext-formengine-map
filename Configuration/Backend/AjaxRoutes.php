<?php
return [
	'FormengineMap::geocodeEndpoint' => [
		'path' => '/formengine_map/tx_formenginemap_address_geocode_handler',
		'target' => \CedricZiel\FormEngine\Map\Controller\GeocodingController::class . '::geocode'
	],
];
