<?php
/**
 * Polyline Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\vector;

use yii\base\InvalidConfigException;
use yii\helpers\Json;

/**
 * Represents a Polyline on the map.
 */
class Polyline extends Path
{
    use \BeastBytes\Leaflet\types\LatLngsTrait;

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        $object = strtolower(basename(self::class));
        $latLngs = Json::encode($this->getLatLngs());

        return $this->toJsExpression(
            "{$map->leafletVar}.$object($latLngs, {$map->options2Js($this->options)})" .
            $map->events2Js($this->events)
        );
    }
}
