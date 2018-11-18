<?php
/**
 * Marker Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\ui;

use yii\base\InvalidConfigException;
use BeastBytes\Leaflet\layers\Layer;
use BeastBytes\Leaflet\types\Icon;

/**
 * Represents a marker on the map.
 */
class Marker extends Layer
{
    use \BeastBytes\Leaflet\types\LatLngTrait, \BeastBytes\Leaflet\layers\ui\PopupTrait;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If the `location` attribute is not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->_location)) {
            throw new InvalidConfigException('The `location` attribute must be set.');
        }

        $this->options['icon'] = new Icon($this->options['icon']);
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression("{$map->leafletVar}.marker({$this->_location->toJs($map)}, {$map->options2Js($this->options)})" . $map->events2Js($this->events) . $this->popup());
    }
}
