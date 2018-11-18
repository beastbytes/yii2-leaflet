# BeastBytes\Leaflet
Yii2 extension to integrate the Leaflet JavaScript mapping library.

## Features

-   For Leaflet V1.*
-   Easy to use predefined tile providers (port of [Leaflet Providers](/leaflet-extras/leaflet-providers))
-   Simple popup creation for markers and vector components; just set the 'content' option
-   Simple plugin interface

For license information see the [LICENSE](LICENSE.md)-file.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist beastbytes/yii2-leaflet
```

or add

```json
"beastbytes/yii2-leaflet": "*"
```

to the require section of your composer.json.

## Usage

To create a map, first create the components, e.g. markers, controls, shapes, etc., to display on the map, then run the Map widget, adding the the components in its configuration.

The example below displays a map using OpenStreetMap as the tile provider. It has a marker in the centre of the map and a 5km raduis circle centred on the marker; these are in a layer group that is not initially displayed. When the layer is shown using the Layers control, the centre marker can be dragged and dropped and it's new position is shown - this demonstrates using component events. Three other markers are added in another layer group, and a layers and fullscreen control is added to the map; the fullscreen control is a plugin.

### Example
```php
use BeastBytes\Leaflet\Map;

$centre = new LatLng([
    'lat' => 51.737022,
    'lng' => -4.931467
]);

$centreLayerGroup = new LayerGroup([ // Layer group with a marker and circle
    'layers' => [
        new Circle([
            'latLng' => $centre,
            'content' => 'This circle has a 5km raduis', // Setting 'content' creates a popup
            'options' => [
                'radius' => 5000
            ]
        ]),
        new Marker([
            'latLng' => $centre,
            'options' => [
                'draggable' => true,
                'icon' => new Icon([
                    'options' => [
                        'iconAnchor' => new Point(['x' => 12, 'y' => 40]), // This is important - it anchors a point in the image, measured in pixels from the top left of the image, to the geographical point given by latLng
                        'iconUrl' => "/images/leaflet/marker-icon-magenta.png", // replace with your own image URL
                        'shadowUrl' => '/images/leaflet/marker-shadow.png' // replace with your own image URL
                    ]
                ])
            ],
            'events' => [
                'dragend' => 'function(e){
                    var marker = e.target;
                    var position = marker.getLatLng();
                    window.alert("New position " + position.lat + " " + position.lng);
                }'
            ]
        ])
    ]
]);
$centreLayerGroup->map = false; // don't show initially

$icon = new Icon([
    'options' => [
        'iconAnchor' => new Point(['x' => 12, 'y' => 40]),
        'iconUrl' => "/images/leaflet/marker-icon-green.png",
        'shadowUrl' => '/images/leaflet/marker-shadow.png'
    ]
]);

$pubLayers = [];
$pubs = [
    [
        'name' => 'The Cottage Inn',
        'address' => 'Llangwm, Haverfordwest, Dyfed SA62 4HH',
        'tel' => '+44 1437 891494',
        'location' => ['lat' => 51.749558, 'lng' => -4.911994]
    ],
    [
        'name' => 'The Ferry Inn',
        'address' => 'Pembroke Ferry, Pembroke Dock, Pembrokeshire SA72 6UD',
        'tel' => '+44 1646 682947',
        'location' => ['lat' => 51.707498, 'lng' => -4.927023]
    ]
];

foreach ($pubs as $pub) {
    $pubLayers[] = new Marker([
        'latLng' => new LatLng($pub['location']),
        'options' => compact('icon'),
        'content' => '<p><b>'.$pub['name'].'</b></p><p>'.$pub['address'].'</p><p>Tel: '.$pub['tel'].'</p>'
    ]);
}

$pubsLayerGroup = new LayerGroup([ // group the public layers
    'layers' => $pubLayers
]);

$layersControl = new Layers([ // create a layers control to control layer visibility
    'overlays' => [
        'Centre of Map' => $centreLayerGroup,
        'Pubs' => $pubsLayerGroup
    ]
]);

echo Map::widget([
    'options' => [
        'id' => 'leaflet',
        'style' => 'height:800px' // a height must be specified
    ],
    'mapOptions' => [
        'center' => $centre,
        'layers' => [
            new TileProvider('OpenStreetMap') // this creates the tile layer
        ],
        'zoom' => 10
    ],
    'controls' => [
        $layersControl,
        new Scale()
    ],
    'layers' => [
        $centreLayerGroup,
        $pubsLayerGroup
    ],
    'plugins' => [
        new Fullscreen()
    ]
]);
```
