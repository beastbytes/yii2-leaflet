<?php
/**
 * Layer Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers;

use yii\helpers\Inflector;
use yii\web\JsExpression;
use BeastBytes\Leaflet\Base;

/**
 * Base class for Leaflet Layers.
 */
abstract class Layer extends Base
{
    /**
     * @var bool Whether the component is added to the map using the addTo() method
     * Set FALSE when the addTo() method is not to be generated, e.g. layers defined in the map constructor
     */
    public $addToMap = true;
    /**
     * @var boolean Whether the component is draggable
     */
    public $draggable = false;
    /**
     * @var \BeastBytes\Leaflet\Map The map
     */
    public $map;

    /**
     * Finalises the JavaScript code and creates a JsExpression object.
     * Assigns the object to a variable and/or adds the layer to the map if required
     *
     * @param string $js Object initialisation JavaScript
     * @return JsExpression Object JavaScript code
     */
    protected function toJsExpression($js)
    {
        if (isset($this->jsVar)) {
            $js = "var {$this->jsVar} = $js" . ($this->addToMap ? '' : ';');
        }

        if ($this->addToMap) {
            $js .= ".addTo({$this->map->id});";
        }

        return new JsExpression($js);
    }
}
