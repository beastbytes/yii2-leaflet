<?php
/**
 * TileLayer Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\raster;

use yii\base\InvalidConfigException;
use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a tile layer used to load and display a tile layer on the map.
 *
 * Use this class for providers not implemented by the TileProvider class
 */
class TileLayer extends Layer
{
    /**
     * @param string the TileLayer URL template
     */
    public $url;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If the `url` attribute is not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->url)) {
            throw new InvalidConfigException('The `url` attribute must be set.');
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
        return $this->toJsExpression("{$map->leafletVar}.tileLayer('{$this->url}', {$map->options2Js($this->options)})" . $map->events2Js($this->events));
    }
}
