<?php
/**
 * FullscreenAsset Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Plugins.Fullscreen
 */

namespace BeastBytes\Leaflet\plugins\fullscreen;

use yii\web\AssetBundle;

/**
 * FullscreenAsset Class
 *
 * Asset bundle for Fullscreen plugin
 */
class FullscreenAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $css      = ['Control.FullScreen.css'];
    public $js       = ['Control.FullScreen.js'];

    public function init()
    {
		$this->sourcePath = __DIR__ . '/assets';
    }
}
