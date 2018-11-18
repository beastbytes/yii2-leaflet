<?php
/**
 * Layers Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Controls
 */

namespace BeastBytes\Leaflet\controls;

use yii\helpers\Json;

/**
 * Represents a layers control
 */
class Layers extends Control
{
    /**
     * @var array Map base layers in the format "label" => layer
     * Only one base layer is visible at any time
     */
    public $baseLayers = [];

    /**
     * @var array Map overlay layers in the format "label" => layer
     * Overlay layers can be individually shown or hidden
     */
    public $overlays = [];

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        $baseLayers = $overlays = [];

        foreach ($this->baseLayers as $name => $label) {
            if (is_int($name)) {
                $name = $label;
            }

            $baseLayers[$label] = $this->map->getLayer($name);
        }

        foreach ($this->overlays as $name => $label) {
            if (is_int($name)) {
                $name = $label;
            }

            $overlays[$label] = $this->map->getLayer($name);
        }

        $baseLayers = Json::encode($baseLayers);
        $overlays   = Json::encode($overlays);

        return $this->toJsExpression(
            "{$map->leafletVar}.control.layers($baseLayers, $overlays, {$map->options2Js($this->options)})"
        );
    }
}
