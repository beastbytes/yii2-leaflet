<?php
/**
 * Point Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Represents a point with x and y coordinates in pixels.
 */
class Point extends Type
{
    /**
     * @var boolean Whether the x znd y coordinates are to be rounded
     */
    private $_round = false;

    /**
     * @var float x coordinate
     */
    private $_x;

    /**
     * @var float y coordinate
     */
    private $_y;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If `x` and/or `y` is not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->x) || empty($this->y)) {
            throw new InvalidConfigException('The `x` and `y` attributes must be set.');
        }
    }

    /**
     * Gets the round attribute
     *
     * @return boolean The round atrribute
     */
    public function getRound()
    {
        return $this->_round;
    }

    /**
     * Gets the x attribute
     *
     * @return float The x atrribute
     */
    public function getX()
    {
        return $this->_x;
    }

    /**
     * Gets the y attribute
     *
     * @return float The y value
     */
    public function getY()
    {
        return $this->_y;
    }

    /**
     * Sets the round attribute
     *
     * @param boolean $value The round atrribute
     */
    public function setRound($value)
    {
        $this->_round = $value;
    }

    /**
     * Sets the x attribute
     *
     * @param float $value x value
     */
    public function setX($value)
    {
        $this->_x = $value;
    }

    /**
     * Sets the y attribute
     *
     * @param float $value y value
     */
    public function setY($value)
    {
        $this->_y = $value;
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return new JsExpression("{$map->leafletVar}.point({$this->_x}, {$this->_y}" . ($this->_round ? ', true' : '')
            . ')');
    }
}
