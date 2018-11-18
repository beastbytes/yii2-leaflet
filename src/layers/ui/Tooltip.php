<?php
/**
 * Tooltip Class file
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
 * Represents a Tooltip on the map.
 */
class Tooltip extends Layer
{
    /**
     * @param string the Tooltip content
     */
    public $content;

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression("{$map->leafletVar}.tooltip({$map->options2Js($this->options)}).setContent('{$this->_content}')" . $map->events2Js($this->events));
    }
}
