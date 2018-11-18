<?php
/**
 * Bounds Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

use yii\base\InvalidConfigException;
use yii\web\JsExpression;

/**
 * Represents a rectangular area in pixel coordinates.
 */
class Bounds extends Type
{
    /**
     * @var Point bottom right corner of the rectangle.
     */
    private $_max;

    /**
     * @var Point top left corner of the rectangle.
     */
    private $_min;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If `min` and/or `max` is not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->_min) || empty($this->_max)) {
            throw new InvalidConfigException('The `min` and `max` attributes must be set.');
        }
    }

    /**
     * @return Point The max attribute
     */
    public function getMax()
    {
        return $this->_max;
    }

    /**
     * @return Point The min attribute
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Sets the max attribute.
     * The value can be a Point object or an array in the form ['x' => $x, 'y' => $y] or [$x, $y]
     *
     * @param array|Point $value max value
     * @throws \yii\base\InvalidConfigException If the value is not or cannot be converted to a Point object
     */
    public function setMax($value)
    {
        if (is_array($value)) {
            $value = array2Point($value);
        }

        if (!($value instanceof Point)) {
            throw new InvalidConfigException('Invalid `max` attribute value; it must be a Point object or an array that can be converted to a Point object');
        }

        $this->_max = $value;
    }

    /**
     * Sets the min attribute.
     * The value can be a Point object or an array in the form ['x' => $x, 'y' => $y] or [$x, $y]
     *
     * @param array|Point $value min value
     * @throws \yii\base\InvalidConfigException If the value is not or cannot be converted to a Point object
     */
    public function setMin($value)
    {
        if (is_array($value)) {
            $value = array2Point($value);
        }

        if (!($value instanceof Point)) {
            throw new InvalidConfigException('Invalid `min` attribute value; it must be a Point object or an array that can be converted to a Point object');
        }

        $this->_min = $value;
    }

    /**
     * Creates a Bounds object from an array of points.
     * Each point can be a Point object or an array of the form ['x' => $x, 'y' => $y] or [$x, $y]
     *
     * @param array $point list of points
     * @return Bounds
     */
    public static function points2Bounds($points)
    {
        $x = $y = [];

        foreach ($points as $i => $point) {
            if (is_array($point)) {
                $point = array2Point($point);
            }

            if (!($point instanceof Point)) {
                throw new InvalidConfigException('Invalid Point ($points['.$i.']); it must be a Point object or an array that can be converted to a Point object');
            }

            $x[] = $point->x;
            $y[] = $point->y;

            return new Bounds([
                'min' => new Point(['x' => min($x), 'y' => min($y)]),
                'max' => new Point(['x' => max($x), 'y' => max($y)])
            ]);
        };
    }

    /**
     * Generates the object's JavaScript code
     *
     * @return JsExpression Object JavaScript code
     */
    public function toJs()
    {
        $min = $this->_min->toJs($this->map);
        $max = $this->_max->toJs($this->map);
        return new JsExpression("{$this->map->leafletVar}.bounds($min, $max)");
    }
}
