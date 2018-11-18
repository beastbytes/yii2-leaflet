<?php
/**
 * ComponentTrait Trait file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet
 */

namespace BeastBytes\Leaflet;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Common methods for Leaflet components (controls, layers, and plugins).
 */
Trait ComponentTrait
{
    /**
     * @var array Map of Leaflet components to their namespace
     */
    private $_components = [
        'Attribution'  => 'controls',
        'Layers'       => 'controls',
        'Scale'        => 'controls',
        'Zoom'         => 'controls',
        'FeatureGroup' => 'layers\\other',
        'GeoJson'      => 'layers\\other',
        'GridLayer'    => 'layers\\other',
        'LayerGroup'   => 'layers\\other',
        'ImageOverlay' => 'layers\\raster',
        'TileLayer'    => 'layers\\raster',
        'TileProvider' => 'layers\\raster',
        'Marker'       => 'layers\\ui',
        'Popup'        => 'layers\\ui',
        'Circle'       => 'layers\\vector',
        'CircleMarker' => 'layers\\vector',
        'Path'         => 'layers\\vector',
        'Polygon'      => 'layers\\vector',
        'Polyline'     => 'layers\\vector',
        'Rectangle'    => 'layers\\vector'
    ];

    private function createComponent($config)
    {
        $class = ArrayHelper::remove($config, 'class');

        if (is_null($class)) {
            throw new InvalidConfigException('Components must define a `class`');
        }

        if (strpos($class, '\\') === false) {
            $class = __NAMESPACE__ . '\\' . (array_key_exists($class, $this->_components)
                ? $this->_components[$class]
                : 'plugins\\' . strtolower($class)
            ) . "\\$class";
        }

        return new $class($config);
    }
}
