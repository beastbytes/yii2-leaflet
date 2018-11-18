<?php
/**
 * GeoJson Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\other;

use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a layer group.
 * GeoJson is used to group several layers and handle them as one. If a
 * GeoJson is added to the map, any layers added to or removed from the group
 * using JavaScriptwill be added/removed on the map as well.
 */
class GeoJson extends Layer
{
    /**
     * @var array GeoJSON data as an array
     */
    private $_data;

    /**
     * @return array The GeoJSON data array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Sets the GeoJSON data array
     *
     * @param array $value GeoJSON data
     */
    public function setLayers($value)
    {
        $this->_data = $value;
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        $data = Json::encode($this->_data);
        return $this->toJsExpression("{$map->leafletVar}.geoJson($data, {$map->options2Js($this->options)})" . $map->events2Js($this->events));
    }
}
