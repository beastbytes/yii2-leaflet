<?php
/**
 * Popup Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\ui;

use yii\base\InvalidConfigException;
use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a Popup on the map.
 */
class Popup extends Layer
{
    use \BeastBytes\Leaflet\types\LatLngTrait;

    /**
     * @param string the Popup content
     */
    public $content;

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
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression("{$map->leafletVar}.popup({$map->options2Js($this->options)}).setContent('{$this->_content}').setLatLng({$this->_location
        ->toJs($map)})" . $map->events2Js($this->events));
    }
}
