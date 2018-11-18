<?php
/**
 * Routing Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Plugins.Routing
 */

namespace BeastBytes\Leaflet\plugins\routing;

use BeastBytes\Leaflet\controls\Control;
use BeastBytes\Leaflet\types\LatLng;

/**
 * Routing Class.
 * Adds a routing control to the map using @link{http://www.liedman.net/leaflet-routing-machine/}
 */
class Routing extends Control
{
    use \BeastBytes\Leaflet\types\LatLngTrait;

    /**
     * Registers the plugin
     */
    public function init()
    {
        parent::init();
        $assetClass = self::class . 'Asset';
        $assetClass::register($this->map->getView());

        if (isset($this->options['waypoints'])) {
            foreach ($this->options['waypoints'] as &$waypoint) {
                $waypoint = $this->array2LatLng($waypoint);
            }
        }

        if (isset($this->options['geocoder'])) {
            $this->options['geocoder'] = new \BeastBytes\Leaflet\plugins\geocoder\Geocoder(
                $this->options['geocoder'] + ['map' => $this->map, 'addToMap' => false]
            );
        }
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression("{$map->leafletVar}.Routing.control({$map->options2Js($this->options)})");
    }
}
