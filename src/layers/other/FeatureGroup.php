<?php
/**
 * FeatureGroup Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\other;

use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a feature group.
 */
class FeatureGroup extends LayerGroup
{
    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        $layers = Json::encode($this->getLayers());
        return $this->toJsExpression("{$map->leafletVar}.featureGroup($layers)".$map->events2Js($this->events));
    }
}
