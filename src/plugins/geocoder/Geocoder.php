<?php
/**
 * Geocoder Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Plugins.Geocoder
 */

namespace BeastBytes\Leaflet\plugins\geocoder;

use BeastBytes\Leaflet\controls\Control;

/**
 * Geocoder Class
 * Adds geocoding to the map using @link{https://github.com/perliedman/leaflet-control-geocoder}
 */
class Geocoder extends Control
{
    use \BeastBytes\Leaflet\plugins\PluginTrait;

    public $service;
    public $addToMap = true;

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression("{$map->leafletVar}.Control.Geocoder.{$this->service}({$map->options2Js($this->options)})");
    }
}
