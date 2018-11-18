<?php
/**
 * LeafletAsset Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet
 */

namespace BeastBytes\Leaflet;

use yii\web\AssetBundle;

/**
 * LeafletAsset Class
 *
 * Asset bundle for Leaflet
 */
class LeafletAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $css      = ['leaflet.css'];

    public function init()
    {
        $this->js = (defined('YII_DEBUG') && YII_DEBUG
            ? ['leaflet-src.js']
            : ['leaflet.js']
        );

		$this->sourcePath = __DIR__ . '/assets';
    }
}
