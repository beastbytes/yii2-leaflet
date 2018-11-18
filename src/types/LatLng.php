<?php
/**
 * LatLng Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Types
 */

namespace BeastBytes\Leaflet\types;

use yii\base\InvalidConfigException;

/**
 * Represents a geographical point with a certain latitude and longitude.
 */
class LatLng extends Type
{
    /**
     * Maximum latitude value
     */
    const LAT_MAX = 90;

    /**
     * Maximum longitude value
     */
    const LNG_MAX = 180;

    /**
     * @var float Altitude in metres
     */
    private $_alt;

    /**
     * @var float Latitude in degrees [-90 <= lat <= 90]
     */
    private $_lat;

    /**
     * @var float Longitude in degrees [-180 <= lng <= 180]
     */
    private $_lng;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If `lat` and/or `lng` is not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->_lat) || empty($this->_lng)) {
            throw new InvalidConfigException('The `lat` and `lng` attributes must be set.');
        }
    }

    /**
     * @return float The altitude attribute
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @return float The latitude attribute
     */
    public function getLat()
    {
        return $this->_lat;
    }

    /**
     * @return float The longitude attribute
     */
    public function getLng()
    {
        return $this->_lng;
    }

    /**
     * Sets the altitude attribute
     *
     * @param float $value Altitude value
     */
    public function setAlt($value)
    {
        $this->_alt = $value;
    }

    /**
     * Sets the latitude attribute
     *
     * @param float $value Latitude value
     * @throws \yii\base\InvalidConfigException If the value is out of range
     */
    public function setLat($value)
    {
        if (abs($value) > self::LAT_MAX) {
            throw new InvalidConfigException('Invalid `lat` attribute value; [-'.(self::LAT_MAX * -1).' <= lat <= '.self::LAT_MAX.']');
        }

        $this->_lat = $value;
    }

    /**
     * Sets the longitude attribute
     *
     * @param float $value Longitude value
     * @throws \yii\base\InvalidConfigException If the value is out of range
     */
    public function setLng($value)
    {
        if (abs($value) > self::LNG_MAX) {
            throw new InvalidConfigException('Invalid `lng` attribute value; [-'.(self::LNG_MAX * -1).' <= lng <= '.self::LNG_MAX.']');
        }

        $this->_lng = $value;
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        return $this->toJsExpression(isset($this->_alt)
            ? "{$map->leafletVar}.latLng({$this->_lat}, {$this->_lng}, {$this->_alt})"
            : "{$map->leafletVar}.latLng({$this->_lat}, {$this->_lng})"
        );
    }
}
