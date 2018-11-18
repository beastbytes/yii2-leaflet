<?php
/**
 * Map Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet
 */

namespace BeastBytes\Leaflet;

use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use BeastBytes\Leaflet\types\LatLngBounds;

/**
 * A leaflet map
 */
class Map extends Widget
{
    use \BeastBytes\Leaflet\ComponentTrait, \BeastBytes\Leaflet\types\LatLngTrait;

    const LEAFLET_VAR = 'L';

    /**
     * @var array Array of map controls
     */
    public $controls = [];

    /**
     * @var array Array of map layers
     */
    public $layers = [];

    /**
     * @var string The variable used by Leaflet. If this is not the default `L`
     * the noConflict() method is called and the new variable used for Leaflet.
     * WARNING: Some plugins require the default variable name for Leaflet.
     */
    public $leafletVar = self::LEAFLET_VAR;

    /**
     * @var array Map configuration options
     */
    public $mapOptions = [];

    /**
     * @var array HTML options for the container eleme
     *
     * The "tag" element specifies the tag name of the container element; it
     * defaults to "div".
     */
    public $options = [];

    /**
     * @var array Plugins
     */
    public $plugins = [];

    /**
    * @var string HTML container tag; defaults to 'div'
    */
    protected $tag;

    /**
     * @var array Events for the map. Each event is in the format $name => $handler
     */
    private $_events = [];

    /**
     * @var array Map JavaScript
     */
    private $_js = [];

    /**
     * @var array Layers added to the map
     */
    private $_layers = [];

    /**
     * @inheritdoc
     */
    public static function begin($config = [])
    {
        ob_start();
        return parent::begin($config);
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        if (parent::beforeRun()) {
            $this->registerScript();
            return true;
        }

        return false;
    }

    /**
     * Initialises the widget
     *
     * @throws \yii\base\InvalidConfigException If mapOptions['center'] or mapOptions['zoom'] are not set
     */
    public function init()
    {
        if (!isset($this->mapOptions['center'])) {
            throw new InvalidConfigException('mapOptions["center"] must be set.');
        }

        if (!isset($this->mapOptions['zoom'])) {
            throw new InvalidConfigException('mapOptions["zoom"] must be set.');
        }

        if (isset($this->options['id'])) {
            $this->setId($this->options['id']);
        } else {
            $this->options['id'] = $this->getId();
        }

        $this->mapOptions['center'] = $this->array2LatLng([
            $this->mapOptions['center']['lat'],
            $this->mapOptions['center']['lng']
        ]);

        if (isset($this->mapOptions['layers'])) {
            foreach ($this->mapOptions['layers'] as $key => $config) {
                $config['addToMap'] = false;
                $config['map']      = $this;
                $component          = $this->createComponent($config);

                unset($this->mapOptions['layers'][$key]);
                $this->_layers[$key] = $component->jsVar;
                $this->mapOptions['layers'][] = $component->jsVar;
                $this->_js[] = $component->toJs($this);
            };
        }

        if (isset($this->mapOptions['maxBounds'])) {
            $this->mapOptions['maxBounds'] = new LatLngBounds([
                'northeast' => $this->mapOptions['maxBounds']['northeast'],
                'southwest' => $this->mapOptions['maxBounds']['southwest']
            ]);
        }

        parent::init();

        $this->tag = ArrayHelper::remove($this->options, 'tag', 'div');
    }

    /**
     * Runs the widget
     *
     * @return string HTML for the widget
     */
    public function run()
    {
        echo Html::tag($this->tag, '', $this->options);
    }

    /**
     * Returns a layer.
     * Used by the Layers control
     *
     * @param strin $name Name of the layer
     * @return string Layer variable name
     * @throws  \yii\base\InvalidParamException if layer not found
     */
    public function getLayer($name)
    {
        if (isset($this->_layers[$name])) {
            return $this->_layers[$name];
        }

        if (isset($this->layers[$name])) {
            return $this->layers[$name];
        }

        throw new InvalidParamException(strtr('Layer `{name}` not found', ['{name}' => $name]));
    }

    /**
     * Gets the options
     *
     * @return array The options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets an event
     *
     * @param string $name Event name
     * @param string $handler Event handler.
     */
    public function setEvent($name, $handler)
    {
        $this->_events[$name] = $handler;
    }

    /**
     * Sets the events
     *
     * @param array $events Events for the object. Each event is in the format $name => $handler
     */
    public function setEvents($events)
    {
        foreach ($events as $name => $handler) {
            $this->setEvent($name, $handler);
        };
    }

    /**
     * Registers the JavaScript for the widget
     */
    protected function registerScript()
    {
        $view = $this->getView();

        LeafletAsset::register($view);

        if ($this->leafletVar !== self::LEAFLET_VAR) {
            array_unshift($this->_js, "var {$this->leafletVar} = " . self::LEAFLET_VAR . '.noConflict();');
        }

        $this->_js[] = "var {$this->id} = {$this->leafletVar}.map('{$this->id}', {$this->options2Js($this->mapOptions)})" . $this->events2Js($this->_events) . ";";

        $this->components2Js();

        $view->registerJs("function {$this->id}(){" . implode('', $this->_js) . ob_get_clean() . "}{$this->id}();");
    }

    /**
     * Generates JavaScript for map components; layers, controls, and plugins
     *
     * @return \yii\web\JsExpression[] JavaScript for map components
     */
    private function components2Js()
    {
        foreach (['layers', 'controls', 'plugins'] as $components) {
            foreach ($this->$components as $key => $config) {
                $config['map'] = $this;
                $component = $this->createComponent($config);

                if ($components === 'layers') {
                    $this->layers[$key] = $component->jsVar;
                }

                $this->_js[] = $component->toJs($this);
            };
        };
    }

    /**
     * Encodes events to JavaScript
     *
     * @param array $events The events to encode
     * @return string The encoded events
     */
    public function events2Js($events)
    {
        $js = '';

        foreach ($events as $name => $handler) {
            if (is_array($handler)) {
                $method  = (isset($handler['once']) && $handler['once'] ? 'once' : 'on');
                $handler = $handler['handler'];
            } else {
                $method = 'on';
            }

            $js .= ".$method('$name', $handler)";
        }

        return $js;
    }

    /**
     * Encodes options to JavaScript
     *
     * @param array $options The options to encode
     * @return string The encoded options
     */
    public function options2Js($options)
    {
        foreach ($options as $key => $value) {
            if ($value instanceof Base) {
                $value = $value->toJs($this);
            } elseif (is_array($value) && current($value) instanceof Base) {
                foreach ($value as $i => $v) {
                    $value[$i] = $v->toJs($this);
                }
            }

            $options[$key] = $value;
        }

        return empty($options) ? '{}' : Json::encode($options);
    }
}
