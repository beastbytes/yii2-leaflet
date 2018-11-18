<?php
/**
 * RoutingAsset Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Plugins.Routing
 */

namespace BeastBytes\Leaflet\plugins\routing;

use yii\web\AssetBundle;

/**
 * RoutingAsset Class
 *
 * Asset bundle for Routing plugin
 */
class RoutingAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $css      = ['leaflet-routing-machine.css'];
    public $js       = ['leaflet-routing-machine.min.js'];

    public function init()
    {
		$this->sourcePath = __DIR__ . '/assets';
    }
}
