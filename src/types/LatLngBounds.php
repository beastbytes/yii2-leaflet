<?php
/**
 * LatLngBounds Class file
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
 * Represents a rectangular geographical area on a map.
 * The area can be defined by setting the northeast and southwest atributes, or
 * by setting the latLngs attribute; if the latLngs attribute is set this takes
 * precedence.
 *
 */
class LatLngBounds extends Type
{
    use \BeastBytes\Leaflet\types\LatLngTrait, \BeastBytes\Leaflet\types\LatLngsTrait;

    /**
     * @property LatLngs latLngs The geographical points creating the bounds
     */

    /**
     * @var LatLng north-east corner of the rectangle.
     */
    private $_northeast;

    /**
     * @var LatLng south-west corner of the rectangle.
     */
    private $_southwest;

    /**
     * Initialises the object
     *
     * @throws \yii\base\InvalidConfigException If `southwest` and/or `northeast` is not set
     */
    public function init()
    {
        parent::init();

        if (empty($this->getLatLngs()) && (empty($this->_northeast) || empty($this->_southwest))) {
            throw new InvalidConfigException('Either the `latLngs` attribute or the `southwest` and `northeast` attributes must be set.');
        }
    }

    /**
     * @return LatLng The northeast attribute
     */
    public function getNortheast()
    {
        return $this->_northeast;
    }

    /**
     * @return LatLng The southwest attribute
     */
    public function getSouthwest()
    {
        return $this->_southwest;
    }

    /**
     * Sets the northeast attribute.
     * The value can be a LatLng object or an array in the form ['lat' => $lat, 'lng' => $lng] or [$lat, $lng]
     *
     * @param array|LatLng $value northeast value
     * @throws \yii\base\InvalidConfigException If the value is not or cannot be converted to a LatLng object
     */
    public function setNortheast($value)
    {
        if (is_array($value)) {
            $value = $this->array2LatLng($value);
        }

        if (!($value instanceof LatLng)) {
            throw new InvalidConfigException('Invalid `northeast` attribute value; it must be a LatLng object or an array that can be converted to a LatLng object');
        }

        $this->_northeast = $value;
    }

    /**
     * Sets the southwest attribute.
     * The value can be a LatLng object or an array in the form ['lat' => $lat, 'lng' => $lng] or [$lat, $lng]
     *
     * @param array|LatLng $value southwest value
     * @throws \yii\base\InvalidConfigException If the value is not or cannot be converted to a LatLng object
     */
    public function setSouthwest($value)
    {
        if (is_array($value)) {
            $value = $this->array2LatLng($value);
        }

        if (!($value instanceof LatLng)) {
            throw new InvalidConfigException('Invalid `southwest` attribute value; it must be a LatLng object or an array that can be converted to a LatLng object');
        }

        $this->_southwest = $value;
    }

    /**
     * Generates the object's JavaScript code
     *
     * @param BeastBytes\Leaflet\Map The map widget
     * @return JsExpression Object JavaScript code
     */
    public function toJs($map)
    {
        if (!empty($this->getLatLngs())) {
            $latLngs = Json::encode($this->getLatLngs());
            $js = "{$map->leafletVar}.latLngBounds($latLngs)";
        } else {
            $js = "{$map->leafletVar}.latLngBounds({$this->_northeast->toJs($map)}, {$this->_southwest->toJs($map)})";
        }

        return new JsExpression($js);
    }
}
