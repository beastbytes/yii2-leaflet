<?php
/**
 * LayerGroup Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\other;

use yii\helpers\Json;
use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a layer group.
 * LayerGroup is used to group several layers and handle them as one.
 */
class LayerGroup extends Layer
{
    use \BeastBytes\Leaflet\ComponentTrait;

    /**
     * @var Layer[] Layers in the layer group
     */
    private $_layers = [];

    /**
     * Sets the layers
     *
     * @param Layers[] $layers The layers
     */
    public function setLayers($layers)
    {
        foreach ($layers as $layer) {
            $layer['addToMap'] = false;
            $this->_layers[] = $this->createComponent($layer);
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
        $layers = [];
        foreach ($this->_layers as $layer) {
            $layers[] = $layer->toJs($map);
        }

        $layers = Json::encode($layers);
        return $this->toJsExpression("{$map->leafletVar}.layerGroup($layers)" . $map->events2Js($this->events));
    }
}
