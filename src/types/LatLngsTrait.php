<?php
/**
 * LatLngsTrait Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

/**
 * Common LatLng methods.
 */
trait LatLngsTrait
{
    use LatLngTrait;

    /**
     * @param array[]|LatLng[] Polyline geographical points
     */
    private $_latLngs = [];

    /**
     * @return array[]|LatLng[] Geographical points
     */
    public function getLatLngs()
    {
        return $this->_latLngs;
    }

    /**
     * Sets geographical points.
     * Points can be a LatLng object or an array in the form ['lat' => $lat, 'lng' => $lng] or [$lat, $lng]
     *
     * @param array[]|LatLng[] $latLngs geographical points.
     * $latLngs can be: an array of geographical points, an array of arrays of geographical points, or a multidimensional array
     */
    public function setLatLngs($latLngs)
    {
        $this->_latLngs = $this->parse($latLngs);
    }

    /**
     * Parses LatLngs
     *
     * @param array $latLngs The latLngs to parse
     * @return array $latLngs The parsed latLngs
     */
    public function parse($latLngs)
    {
        foreach ($latLngs as $i => $latLng) {
            if (is_array($latLng)) {
                if (is_array(current($latLng))) {
                    $latLngs[$i] = $this->parse($latLng);
                    continue;
                } else {
                    $latLngs[$i] = $this->array2LatLng($latLng);
                }
            }

            if (!($latLngs[$i] instanceof LatLng)) {
                throw new InvalidConfigException(strtr('Invalid LatLng ($latLngs{i}); it must be a LatLng object or an array that can be converted to a LatLng object', [
                    '{i}' => "[$i]"
                ]));
            }
        }

        return $latLngs;
    }
}
