<?php
/**
 * Plugin Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Plugins
 */

namespace BeastBytes\Leaflet\plugins;

use BeastBytes\Leaflet\controls\Control;

/**
 * PluginTrait
 *
 * Common methods for map plugins
 */
trait PluginTrait
{
    /**
     * Registers the plugin
     */
    public function init()
    {
        parent::init();
        $assetClass = self::class . 'Asset';
        $assetClass::register($this->map->getView());
    }
}
