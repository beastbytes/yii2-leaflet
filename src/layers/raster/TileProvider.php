<?php
/**
 * TileProvider Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\raster;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Defines tile providers that can be used to load and display map tiles.
 *
 * Port of @link(https://github.com/leaflet-extras/leaflet-providers Leaflet Providers)
 */
class TileProvider extends TileLayer
{
    const ATTRIBUTION_PATTERN = '/\{attribution.(\w*)\}/';
    const HERE_CUSTOMER_INTEGRATION_TESTING = '.cit';

    /**
     * @var boolean Whether to force HTTP. By default HTTPS is tried first.
     */
    public $forceHTTP = false;

    /**
     * @var string Name of the tile provider. Variants are specified using 'dot'
     * format, e.g. OpenStreetMap.HOT
     */
    public $provider;

    /**
     * @var array Provider options as $key => $value pairs.
     * Use to set options such an app id, code, key, etc, or to overide values
     */
    public $providerOptions = [];

    private static $_providers = [
        'OpenStreetMap' => [
            'url' => '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'options' => [
                'maxZoom' => 19,
                'attribution' => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            ],
            'variants' => [
                'Mapnik' => [],
                'BlackAndWhite' => [
                    'url' => 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png',
                    'options' => [
                        'maxZoom' => 18
                    ]
                ],
                'DE' => [
                    'url' => 'http://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png',
                    'options' => [
                        'maxZoom' => 18
                    ]
                ],
                'France' => [
                    'url' => 'http://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png',
                    'options' => [
                        'maxZoom' => 20,
                        'attribution' => '&copy; Openstreetmap France | {attribution.OpenStreetMap}'
                    ]
                ],
                'HOT' => [
                    'url' => 'http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
                    'options' => [
                        'attribution' => '{attribution.OpenStreetMap}, Tiles courtesy of <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>'
                    ]
                ]
            ]
        ],
        'OpenSeaMap' => [
            'url' => 'http://tiles.openseamap.org/seamark/{z}/{x}/{y}.png',
            'options' => [
                'attribution' => 'Map data: &copy; <a href="http://www.openseamap.org">OpenSeaMap</a> contributors'
            ]
        ],
        'OpenTopoMap' => [
            'url' => '//{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
            'options' => [
                'maxZoom' => 17,
                'attribution' => 'Map data: {attribution.OpenStreetMap}, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
            ]
        ],
        'Thunderforest' => [
            'url' => '//{s}.tile.thunderforest.com/{variant}/{z}/{x}/{y}.png',
            'options' => [
                'attribution' => '&copy; <a href="http://www.opencyclemap.org">OpenCycleMap</a>, {attribution.OpenStreetMap}',
                'variant' => 'cycle',
                'maxZoom' => 22
            ],
            'variants' => [
                'OpenCycleMap' => 'cycle',
                'Transport' => [
                    'options' => [
                        'variant' => 'transport'
                    ]
                ],
                'TransportDark' => [
                    'options' => [
                        'variant' => 'transport-dark'
                    ]
                ],
                'SpinalMap' => [
                    'options' => [
                        'variant' => 'spinal-map'
                    ]
                ],
                'Landscape' => 'landscape',
                'Outdoors' => 'outdoors',
                'Pioneer' => 'pioneer'
            ]
        ],
        'OpenMapSurfer' => [
            'url' => 'http://korona.geog.uni-heidelberg.de/tiles/{variant}/x={x}&y={y}&z={z}',
            'options' => [
                'maxZoom' => 20,
                'variant' => 'roads',
                'attribution' => 'Imagery from <a href="http://giscience.uni-hd.de/">GIScience Research Group @ University of Heidelberg</a> &mdash; Map data {attribution.OpenStreetMap}'
            ],
            'variants' => [
                'Roads' => 'roads',
                'AdminBounds' => [
                    'options' => [
                        'variant' => 'adminb',
                        'maxZoom' => 19
                    ]
                ],
                'Grayscale' => [
                    'options' => [
                        'variant' => 'roadsg',
                        'maxZoom' => 19
                    ]
                ]
            ]
        ],
        'Hydda' => [
            'url' => 'http://{s}.tile.openstreetmap.se/hydda/{variant}/{z}/{x}/{y}.png',
            'options' => [
                'maxZoom' => 18,
                'variant' => 'full',
                'attribution' => 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data {attribution.OpenStreetMap}'
            ],
            'variants' => [
                'Full' => 'full',
                'Base' => 'base',
                'RoadsAndLabels' => 'roads_and_labels'
            ]
        ],
        'MapBox' => [
            'url' => '//api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}',
            'options' => [
                'attribution' => 'Imagery from <a href="http://mapbox.com/about/maps/">MapBox</a> &mdash; Map data {attribution.OpenStreetMap}',
                'subdomains' => 'abcd',
                'id' => 'streets',
                'accessToken' => '<insert your access token here>'
            ]
        ],
        'Stamen' => [
            'url' => '//stamen-tiles-{s}.a.ssl.fastly.net/{variant}/{z}/{x}/{y}.{ext}',
            'options' => [
                'attribution' => 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data {attribution.OpenStreetMap}',
                'subdomains' => 'abcd',
                'minZoom' => 0,
                'maxZoom' => 20,
                'variant' => 'toner',
                'ext' => 'png'
            ],
            'variants' => [
                'Toner' => 'toner',
                'TonerBackground' => 'toner-background',
                'TonerHybrid' => 'toner-hybrid',
                'TonerLines' => 'toner-lines',
                'TonerLabels' => 'toner-labels',
                'TonerLite' => 'toner-lite',
                'Watercolor' => [
                    'options' => [
                        'variant' => 'watercolor',
                        'minZoom' => 1,
                        'maxZoom' => 16
                    ]
                ],
                'Terrain' => [
                    'options' => [
                        'variant' => 'terrain',
                        'minZoom' => 0,
                        'maxZoom' => 18
                    ]
                ],
                'TerrainBackground' => [
                    'options' => [
                        'variant' => 'terrain-background',
                        'minZoom' => 0,
                        'maxZoom' => 18
                    ]
                ],
                'TopOSMRelief' => [
                    'options' => [
                        'variant' => 'toposm-color-relief',
                        'ext' => 'jpg',
                        'bounds' => [[22, -132], [51, -56]]
                    ]
                ],
                'TopOSMFeatures' => [
                    'options' => [
                        'variant' => 'toposm-features',
                        'bounds' => [[22, -132], [51, -56]],
                        'opacity' => 0.9
                    ]
                ]
            ]
        ],
        'Esri' => [
            'url' => '//server.arcgisonline.com/ArcGIS/rest/services/{variant}/MapServer/tile/{z}/{y}/{x}',
            'options' => [
                'variant' => 'World_Street_Map',
                'attribution' => 'Tiles &copy; Esri'
            ],
            'variants' => [
                'WorldStreetMap' => [
                    'options' => [
                        'attribution' => '{attribution.Esri} &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
                    ]
                ],
                'DeLorme' => [
                    'options' => [
                        'variant' => 'Specialty/DeLorme_World_Base_Map',
                        'minZoom' => 1,
                        'maxZoom' => 11,
                        'attribution' => '{attribution.Esri} &mdash; Copyright: &copy;2012 DeLorme'
                    ]
                ],
                'WorldTopoMap' => [
                    'options' => [
                        'variant' => 'World_Topo_Map',
                        'attribution' => '{attribution.Esri} &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'
                    ]
                ],
                'WorldImagery' => [
                    'options' => [
                        'variant' => 'World_Imagery',
                        'attribution' => '{attribution.Esri} &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                    ]
                ],
                'WorldTerrain' => [
                    'options' => [
                        'variant' => 'World_Terrain_Base',
                        'maxZoom' => 13,
                        'attribution' =>
                            '{attribution.Esri} &mdash; Source: USGS, Esri, TANA, DeLorme, and NPS'
                    ]
                ],
                'WorldShadedRelief' => [
                    'options' => [
                        'variant' => 'World_Shaded_Relief',
                        'maxZoom' => 13,
                        'attribution' => '{attribution.Esri} &mdash; Source: Esri'
                    ]
                ],
                'WorldPhysical' => [
                    'options' => [
                        'variant' => 'World_Physical_Map',
                        'maxZoom' => 8,
                        'attribution' => '{attribution.Esri} &mdash; Source: US National Park Service'
                    ]
                ],
                'OceanBasemap' => [
                    'options' => [
                        'variant' => 'Ocean_Basemap',
                        'maxZoom' => 13,
                        'attribution' => '{attribution.Esri} &mdash; Sources: GEBCO, NOAA, CHS, OSU, UNH, CSUMB, National Geographic, DeLorme, NAVTEQ, and Esri'
                    ]
                ],
                'NatGeoWorldMap' => [
                    'options' => [
                        'variant' => 'NatGeo_World_Map',
                        'maxZoom' => 16,
                        'attribution' => '{attribution.Esri} &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC'
                    ]
                ],
                'WorldGrayCanvas' => [
                    'options' => [
                        'variant' => 'Canvas/World_Light_Gray_Base',
                        'maxZoom' => 16,
                        'attribution' => '{attribution.Esri} &mdash; Esri, DeLorme, NAVTEQ'
                    ]
                ]
            ]
        ],
        'OpenWeatherMap' => [
            'url' => '//{s}.tile.openweathermap.org/map/{variant}/{z}/{x}/{y}.png',
            'options' => [
                'maxZoom' => 19,
                'attribution' => 'Map data &copy; <a href="http://openweathermap.org">OpenWeatherMap</a>',
                'opacity' => 0.5
            ],
            'variants' => [
                'Clouds' => 'clouds',
                'CloudsClassic' => 'clouds_cls',
                'Precipitation' => 'precipitation',
                'PrecipitationClassic' => 'precipitation_cls',
                'Rain' => 'rain',
                'RainClassic' => 'rain_cls',
                'Pressure' => 'pressure',
                'PressureContour' => 'pressure_cntr',
                'Wind' => 'wind',
                'Temperature' => 'temp',
                'Snow' => 'snow'
            ]
        ],
        'HERE' => [
            /*
             * HERE maps, formerly Nokia maps.
             * These basemaps are free, but you need an API key. Please sign up at
             * http://developer.here.com/getting-started
             *
             * Note that the base urls contain '.cit' whichs is HERE's
             * 'Customer Integration Testing' environment. Please remove for production
             * envirionments.
             */
            'url' => '//{s}.{base}.maps.cit.api.here.com/maptile/2.1/maptile/{mapID}/{variant}/{z}/{x}/{y}/256/png8?app_id={app_id}&app_code={app_code}',
            'options' => [
                'attribution' =>
                    'Map &copy; 1987-2014 <a href="http://developer.here.com">HERE</a>',
                'subdomains' => '1234',
                'mapID' => 'newest',
                'app_id' => '<insert your app_id here>',
                'app_code' => '<insert your app_code here>',
                'base' => 'base',
                'variant' => 'normal.day',
                'maxZoom' => 20,
                'type' => 'maptile',
				'language' => 'eng',
				'format' => 'png8',
                'size' => '256'
            ],
            'variants' => [
                'normalDay' => 'normal.day',
                'normalDayCustom' => 'normal.day.custom',
                'normalDayGrey' => 'normal.day.grey',
                'normalDayMobile' => 'normal.day.mobile',
                'normalDayGreyMobile' => 'normal.day.grey.mobile',
                'normalDayTransit' => 'normal.day.transit',
                'normalDayTransitMobile' => 'normal.day.transit.mobile',
                'normalNight' => 'normal.night',
                'normalNightMobile' => 'normal.night.mobile',
                'normalNightGrey' => 'normal.night.grey',
                'normalNightGreyMobile' => 'normal.night.grey.mobile',
                'basicMap' => [
					'options' => [
						'type' => 'basetile'
					]
                ],
                'mapLabels' => [
                    'options' => [
                        'type' => 'labeltile',
                        'format' => 'png'
                    ]
                ],
                'trafficFlow' => [
                    'options' => [
                        'base' => 'traffic',
                        'type' => 'flowtile'
                    ]
                ],

                'carnavDayGrey' => 'carnav.day.grey',
                'hybridDay' => [
                    'options' => [
                        'base' => 'aerial',
                        'variant' => 'hybrid.day'
                    ]
                ],
                'hybridDayMobile' => [
                    'options' => [
                        'base' => 'aerial',
                        'variant' => 'hybrid.day.mobile'
                    ]
                ],
                'pedestrianDay' => 'pedestrian.day',
                'pedestrianNight' => 'pedestrian.night',
                'satelliteDay' => [
                    'options' => [
                        'base' => 'aerial',
                        'variant' => 'satellite.day'
                    ]
                ],
                'terrainDay' => [
                    'options' => [
                        'base' => 'aerial',
                        'variant' => 'terrain.day'
                    ]
                ],
                'terrainDayMobile' => [
                    'options' => [
                        'base' => 'aerial',
                        'variant' => 'terrain.day.mobile'
                    ]
                ]
            ]
        ],
        'FreeMapSK' => [
			'url' =>'http://t{s}.freemap.sk/T/{z}/{x}/{y}.jpeg',
			'options' => [
				'minZoom' => 8,
				'maxZoom' => 16,
				'subdomains' => '1234',
				'bounds' => [[47.204642, 15.996093], [49.830896, 22.576904]],
				'attribution' =>
					'{attribution.OpenStreetMap}, vizualization CC-By-SA 2.0 <a href="http://freemap.sk">Freemap.sk</a>'
			]
        ],
        'MtbMap' => [
            'url' => 'http://tile.mtbmap.cz/mtbmap_tiles/{z}/{x}/{y}.png',
            'options' => [
                'attribution' =>'{attribution.OpenStreetMap} &amp; USGS'
            ]
        ],
        'CartoDB' => [
            'url' => 'http://{s}.basemaps.cartocdn.com/{variant}/{z}/{x}/{y}.png',
            'options' => [
                'attribution' => '{attribution.OpenStreetMap} &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
                'subdomains' => 'abcd',
                'maxZoom' => 19,
                'variant' => 'light_all'
            ],
            'variants' => [
                'Positron' => 'light_all',
                'PositronNoLabels' => 'light_nolabels',
                'PositronOnlyLabels' => 'light_only_labels',
                'DarkMatter' => 'dark_all',
                'DarkMatterNoLabels' => 'dark_nolabels',
                'DarkMatterOnlyLabels' => 'dark_only_labels'
            ]
        ],
        'HikeBike' => [
            'url' => 'http://{s}.tiles.wmflabs.org/{variant}/{z}/{x}/{y}.png',
            'options' => [
                'maxZoom' => 19,
                'attribution' => '{attribution.OpenStreetMap}',
                'variant' => 'hikebike'
            ],
            'variants' => [
                'HikeBike' => [],
                'HillShading' => [
                    'options' => [
                        'maxZoom' => 15,
                        'variant' => 'hillshading'
                    ]
                ]
            ]
        ],
        'BasemapAT' => [
            'url' => '//maps{s}.wien.gv.at/basemap/{variant}/normal/google3857/{z}/{y}/{x}.{format}',
            'options' => [
                'maxZoom' => 19,
                'attribution' => 'Datenquelle: <a href="www.basemap.at">basemap.at</a>',
                'subdomains' => ['', '1', '2', '3', '4'],
                'format' => 'png',
                'bounds' => [[46.358770, 8.782379], [49.037872, 17.189532]],
                'variant' => 'geolandbasemap'
            ],
            'variants' => [
                'basemap' => [
                    'maxZoom' => 20,
                    'variant' => 'geolandbasemap'
                ],
                'grau' => 'bmapgrau',
                'overlay' => 'bmapoverlay',
                'highdpi' => [
                    'options' => [
                        'variant' => 'bmaphidpi',
                        'format' => 'jpeg'
                    ]
                ],
                'orthofoto' => [
                    'options' => [
                        'maxZoom' => 20,
                        'variant' => 'bmaporthofoto30cm',
                        'format' => 'jpeg'
                    ]
                ]
            ]
        ],
        'NASAGIBS' => [
            'url' => '//map1.vis.earthdata.nasa.gov/wmts-webmerc/{variant}/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}',
            'options' => [
                'attribution' => 'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href="https://earthdata.nasa.gov">ESDIS</a>) with funding provided by NASA/HQ.',
                'bounds' => [[-85.0511287776, -179.999999975], [85.0511287776, 179.999999975]],
                'minZoom' => 1,
                'maxZoom' => 9,
                'format' => 'jpg',
                'time' => '',
                'tilematrixset' => 'GoogleMapsCompatible_Level'
            ],
            'variants' => [
                'ModisTerraTrueColorCR' => 'MODIS_Terra_CorrectedReflectance_TrueColor',
                'ModisTerraBands367CR' => 'MODIS_Terra_CorrectedReflectance_Bands367',
                'ViirsEarthAtNight2012' => [
                    'options' => [
                        'variant' => 'VIIRS_CityLights_2012',
                        'maxZoom' => 8
                    ]
                ],
                'ModisTerraLSTDay' => [
                    'options' => [
                        'variant' => 'MODIS_Terra_Land_Surface_Temp_Day',
                        'format' => 'png',
                        'maxZoom' => 7,
                        'opacity' => 0.75
                    ]
                ],
                'ModisTerraSnowCover' => [
                    'options' => [
                        'variant' => 'MODIS_Terra_Snow_Cover',
                        'format' => 'png',
                        'maxZoom' => 8,
                        'opacity' => 0.75
                    ]
                ],
                'ModisTerraAOD' => [
                    'options' => [
                        'variant' => 'MODIS_Terra_Aerosol',
                        'format' => 'png',
                        'maxZoom' => 6,
                        'opacity' => 0.75
                    ]
                ],
                'ModisTerraChlorophyll' => [
                    'options' => [
                        'variant' => 'MODIS_Terra_Chlorophyll_A',
                        'format' => 'png',
                        'maxZoom' => 7,
                        'opacity' => 0.75
                    ]
                ]
            ]
        ],
        'NLS' => [
            // NLS maps are copyright National library of Scotland.
            // http://maps.nls.uk/projects/api/index.html
            // Please contact NLS for anything other than non-commercial low volume usage
            //
            // Map sources: Ordnance Survey 1:1m to 1:63K, 1920s-1940s
            //   z0-9  - 1:1m
            //  z10-11 - quarter inch (1:253440)
            //  z12-18 - one inch (1:63360)
            'url' => '//nls-{s}.tileserver.com/{variant}/{z}/{x}/{y}.jpg',
            'options' => [
                'attribution' => '<a href="http://geo.nls.uk/maps/">National Library of Scotland Historic Maps</a>',
                'bounds' => [[49.6, -12], [61.7, 3]],
                'minZoom' => 1,
                'maxZoom' => 18,
                'subdomains' => '0123'
            ]
        ],
        'JusticeMap' => [
			// Justice Map (http://www.justicemap.org/)
			// Visualize race and income data for your community, county and country.
			// Includes tools for data journalists, bloggers and community activists.
			'url' => 'http://www.justicemap.org/tile/{size}/{variant}/{z}/{x}/{y}.png',
			'options' => [
				'attribution' => '<a href="http://www.justicemap.org/terms.php">Justice Map</a>',
				// one of 'county', 'tract', 'block'
				'size' => 'county',
				// Bounds for USA, including Alaska and Hawaii
				'bounds' => [[14, -180], [72, -56]]
			],
            'variants' => [
                'income' => 'income',
				'americanIndian' => 'indian',
				'asian' => 'asian',
				'black' => 'black',
				'hispanic' => 'hispanic',
				'multi' => 'multi',
				'nonWhite' => 'nonwhite',
				'white' => 'white',
				'plurality' => 'plural'
			]
        ]
    ];

    /**
     * Initialises the object.
     * Sets the tile provider configuration
     *
     * @throws InvalidConfigException If the requested provider does not exist
     */
    public function init()
    {
        $provider = explode('.', $this->provider);

        if (!key_exists($provider[0], self::$_providers)) {
            throw new InvalidConfigException(strtr('The requested provider ({provider}) does not exist', ['{provider}' => $this->provider]));
        }

        $this->url = self::$_providers[$provider[0]]['url'];
        $this->options = self::$_providers[$provider[0]]['options'];

        if (isset($provider[1])) {
            if (!key_exists($provider[1], self::$_providers[$provider[0]]['variants'])) {
                throw new InvalidConfigException(strtr('The requested provider ({provider}) does not exist', ['{provider}' => $this->_name]));
            }

            $variant = self::$_providers[$provider[0]]['variants'][$provider[1]];

            if (is_string($variant)) {
                $this->options['variant'] = $variant;
            } else {
                if (isset($variant['url'])) {
                    $this->url = $variant['url'];
                }

                if (isset($variant['options'])) {
                    $this->options = array_merge($this->options, $variant['options']);
                }
            }
        }

        // Add in any user provided options
        $this->options = array_merge($this->options, $this->providerOptions);

        // Force http if required
        if ($this->forceHTTP && strpos($this->url, '//') === 0) {
            $this->url = 'http:' . $this->url;
        }

        // Replace attribution placeholders
        $this->options['attribution'] = $this->replaceAttribution($this->options['attribution']);

        parent::init();
    }

    /**
     * Recursively replaces placeholders in the attribution with values from the
     * top level provider attribution
     *
     * @param string $attribution The attribution containing placeholders to replace
     * @return string The attribution with placeholders replaced
     */
    private function replaceAttribution($attribution)
    {
        if (isset($attribution) && strpos($attribution, '{attribution.') !== false) {
            $matches = [];
            preg_match(self::ATTRIBUTION_PATTERN, $attribution, $matches);

            $attribution = preg_replace(
                self::ATTRIBUTION_PATTERN,
                $this->replaceAttribution(self::$_providers[$matches[1]]['options']['attribution']),
                $attribution
            );
        }

        return $attribution;
    }

    /**
     * @return array List of supported providers
     */
    public static function providers()
    {
        $providerList = [];

        foreach (self::$_providers as $name => $settings) {
            $providerList[] = $name;

            if (isset($settings['variants'])) {
                foreach (array_keys($settings['variants']) as $variant) {
                    $providerList[] = $name . '.' . $variant;
                }
            }
        };

        return $providerList;
    }

    /**
     * Returns a value indicating whether or not the provider is supported
     *
     * @return boolean TRUE if the provider is supported, FALSE if not
     */
    public static function hasProvider($name)
    {
        $name = explode('.', $name);

        if (key_exists($name[0], self::$_providers)) {
            if (isset($name[1])) {
                if (key_exists($name[1], self::$_providers[$name[0]]['variants'])) {
                    return true;
                }

                return false;
            }

            return true;
        }

        return false;
    }
}
