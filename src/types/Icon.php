<?php
/**
 * Icon Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

use yii\base\InvalidConfigException;
use yii\web\JsExpression;

/**
 * Represents an icon to provide when creating a marker.
 */
class Icon extends Type
{
    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If the `iconUrl` option is not set
     */
    public function init()
    {
        parent::init();

        if (!isset($this->options['iconUrl'])) {
            throw new InvalidConfigException('The `iconUrl` option must be set.');
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
        return new JsExpression("{$map->leafletVar}.icon({$map->options2Js($this->options)})");
    }
}
