<?php
/**
 * LatLngBoundsTrait Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

use yii\base\InvalidConfigException;

/**
 * Common LatLngBounds methods.
 */
trait LatLngBoundsTrait
{
    use LatLngTrait;

    /**
     * @param LatLngBounds the bounds
     */
    private $_bounds;

    /**
     * Creates a LatLngBounds object from an array
     * The array can be in the form
     * ['northeast' => $northeast, 'southwest' => $southwest] or
     * [$northeast, $southwest] where $northeast and $southwest can be LatLng
     * objects or arrays of the form ['lat' => $lat, 'lng' => $lng] or [$lat, $lng]
     *
     * @param array $value The array with the LatLngBounds values
     * @return LatLngBounds
     */
    public function array2LatLngBounds($value)
    {
        if (isset($value['northeast'])) {
            $northeast = $value['northeast'];
            $southwest = $value['southwest'];
        } else {
            $northeast = $value[0];
            $southwest = $value[1];
        }

        foreach (['northeast', 'southwest'] as $corner) {
            if (is_array($$corner)) {
                $$corner = $this->array2LatLng($$corner);
            }

            if (!($$corner instanceof LatLng)) {
                throw new InvalidConfigException(strtr('Invalid `{corner}` value; it must be a LatLng object or an array that can be converted to a LatLng object', [
                    '{corner}' => $corner
                ]));
            }
        }

        return  new LatLngBounds(compact('northeast', 'southwest'));
    }

    /**
     * @return LatLngBounds The bounds
     */
    public function getBounds()
    {
        return $this->_bounds;
    }

    /**
     * Sets the bounds property.
     * $value can be a LatLngBounds object or an array in the form
     * ['northeast' => $northeast, 'southwest' => $southwest] or
     * [$northeast, $southwest] where $northeast and $southwest can be LatLng
     * objects or arrays of the form ['lat' => $lat, 'lng' => $lng] or [$lat, $lng]
     *
     * @param array|LatLngBounds $value The bounds
     * @return LatLngBounds
     * @throws InvalidConfigException If $value is not a LatLngBounds object or an array than can be converted to one
     */
    public function setBounds($value)
    {
        if (is_array($value)) {
            $value = array2LatLngBounds($value);
        }

        if (!($value instanceof LatLngBounds)) {
            throw new InvalidConfigException('Invalid `bounds` attribute value; it must be a LatLngBounds object or an array that can be converted to a LatLngBounds object');
        }

        return $value;
    }
}
