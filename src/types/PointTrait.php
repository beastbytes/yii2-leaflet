<?php
/**
 * PointTrait Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

/**
 * Common Point methods
 */
trait PointTrait
{
    /**
     * Creates a Point object from an array
     * The array can be in the format ['x' => $x, 'y' => $y] or [$x, $y]
     *
     * @param array $value The array with the point values
     * @return Point
     */
    public function array2Point($value)
    {
        if (isset($value['x'])) {
            return new Point($value);
        }

        return  new Point([
            'x' => $value[0],
            'y' => $value[1]
        ]);
    }
}
