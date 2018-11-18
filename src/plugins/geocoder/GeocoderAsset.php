<?php
/**
 * GeocoderAsset Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Plugins.Geocoder
 */

namespace BeastBytes\Leaflet\plugins\geocoder;

use yii\web\AssetBundle;

/**
 * GeocoderAsset Class
 *
 * Asset bundle for Geocoder plugin
 */
class GeocoderAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $css      = ['Control.Geocoder.css'];
    public $js       = ['Control.Geocoder.js'];

    public function init()
    {
		$this->sourcePath = __DIR__ . '/assets';
    }
}
