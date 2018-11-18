<?php
/**
 * PopupTrait Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet.Layers
 */

namespace BeastBytes\Leaflet\layers\ui;

use BeastBytes\Leaflet\types\Point;

/**
 * Provides bound Popup and/or Tooltip functionality
 */
trait PopupTrait
{
    /**
     * @var array|string
     * array: [
     * content => HTML content,
     * options => [options]
     * ]
     * string: HTML content
     */
    public $popup;

    /**
     * @var array|string
     * array: [
     * content => HTML content,
     * options => [options]
     * ]
     * string: HTML content
     */
    public $tooltip;

    /**
     * Binds a popup and/or tooltip
     *
     * @return string JavaScript to bind the popup and/or tooltip
     */
    protected function popup()
    {
        $js = '';

        if (isset($this->popup)) {
            if (is_string($this->popup)) {
                $content = addslashes($this->popup);
                $options = '{}';
            } else {
                $content = addslashes($this->popup['content']);
                $options = (isset($this->popup['options']) ? $this->options2Js($this->popup['options']) : '{}');
            }

            $js .= ".bindPopup(\"$content\", $options).openPopup()";
        }

        if (isset($this->tooltip)) {
            if (is_string($this->tooltip)) {
                $content = addslashes($this->tooltip);
                $options = '{}';
            } else {
                $content = addslashes($this->tooltip['content']);
                $options = (isset($this->tooltip['options']) ? $this->options2Js($this->tooltip['options']) : '{}');
            }

            $js .= ".bindTooltip(\"$content\", $options).openTooltip()";
        }

        return $js;
    }

    private function options2Js($options)
    {
        foreach ($options as $key => &$value) {
            if (strpos($key, 'autoPanPadding') !== false || strpos($key, 'offset') !== false) {
                $value = new Point($value);
            }
        }

        return $this->map->options2Js($options);
    }
}
