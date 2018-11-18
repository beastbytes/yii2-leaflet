<?php
/**
 * Path Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\vector;

use BeastBytes\Leaflet\layers\Layer;

/**
 * Represents a vector path on the map.
 * This is an abstract class that provides Popup functionality
 */
abstract class Path extends Layer
{
    use \BeastBytes\Leaflet\layers\ui\PopupTrait;

    /**
     * Finalises the JavaScript code and creates a JsExpression object.
     * This method adds any required Popup code before calling the parent implementation.
     *
     * @param string $js Object initialisation JavaScript
     * @return JsExpression Object JavaScript code
     */
    protected function toJsExpression($js)
    {
        return parent::toJsExpression($js . $this->popup());
    }
}
