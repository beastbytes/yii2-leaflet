<?php
/**
 * Rectangle Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\vector;

use yii\base\InvalidConfigException;
use beastbytes\leaflet\layers\Layer;

/**
 * Represents a Rectangle on the map.
 */
class Rectangle extends Layer
{
    use \BeastBytes\Leaflet\types\LatLngBoundsTrait;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If the `bounds` attribute is not set
     */
    public function init()
    {
        if (empty($this->_bounds)) {
            throw new InvalidConfigException('The `bounds` attribute must be set.');
        }

        parent::init();
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression("{$map->leafletVar}.rectangle({$this->_bounds->toJs($map)}, {$this->options->toJs()})"
            . $map->events2Js($this->events));
    }
}
