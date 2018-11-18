<?php
/**
 * Type Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

use yii\helpers\Inflector;
use yii\web\JsExpression;
use BeastBytes\Leaflet\Base;

/**
 * Base class for Leaflet types.
 */
abstract class Type extends Base
{
    /**
     * Finalises the JavaScript code and creates a JsExpression object.
     * Assigns the object to a variable if required
     *
     * @param string $js Object initialisation JavaScript
     * @return JsExpression Object JavaScript code
     */
    protected function toJsExpression($js)
    {
        if (isset($this->jsVar)) {
            $js = "var {$this->jsVar} = $js;";
        }

        return new JsExpression($js);
    }
}
