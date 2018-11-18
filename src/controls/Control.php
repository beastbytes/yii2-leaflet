<?php
/**
 * Control Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Controls
 */

namespace BeastBytes\Leaflet\controls;

use yii\helpers\Inflector;
use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a UI control on a map
 */
abstract class Control extends Layer
{
    /**
     * Bottom left of the map.
     */
    const POSITION_BOTTOM_LEFT = 'bottomleft';
    /**
     * Bottom right of the map.
     */
    const POSITION_BOTTOM_RIGHT = 'bottomright';
    /**
     * Top left of the map.
     */
    const POSITION_TOP_LEFT = 'topleft';
    /**
     * Top right of the map.
     */
    const POSITION_TOP_RIGHT = 'topright';

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        $classname = get_called_class();
        $control = strtolower(substr($classname, strrpos($classname, '\\') + 1));

        return $this->toJsExpression("{$map->leafletVar}.control.$control({$map->options2Js($this->options)})");
    }
}
