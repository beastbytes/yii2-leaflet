<?php
/**
 * CircleMarker Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\vector;

use yii\base\InvalidConfigException;

/**
 * Represents a CircleMarker on the map.
 */
class CircleMarker extends Path
{
    use \BeastBytes\Leaflet\types\LatLngTrait;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If the `location` attribute or `radius` option are not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->_location)) {
            throw new InvalidConfigException('The `location` attribute must be set.');
        }

        if (empty($this->options['radius'])) {
            throw new InvalidConfigException('The `radius` option must be set.');
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
        $object = strtolower(basename(self::class));
        return $this->toJsExpression(
            "{$map->leafletVar}.$object({$this->_location->toJs($map)}, {$map->options2Js($this->options)})" .
            $map->events2Js($this->events)
        );
    }
}
