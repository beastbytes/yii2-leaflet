<?php
/**
 * Base Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2017 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Leaflet
 */

namespace BeastBytes\Leaflet;

use yii\base\Component;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Base class for all Leaflet components.
 */
abstract class Base extends Component
{
    /**
     * @param array options
     */
    protected $options = [];

    /**
     * @param array events
     */
    protected $events = [];

    /**
     * @var JsExpression JavaScript variable name. If set the generated JavaScript will assign the Leaflet object to this variable.
     */
    private $_jsVar;

    public function __isset($name)
    {
        if ($name == 'jsVar') {
            return isset($this->_jsVar);
        }

        return parent::__isset($name);
    }

    /**
     * Gets the events
     *
     * @return array The events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Gets jsVar
     *
     * @return string jsVar
     */
    public function getjsVar()
    {
        if (empty($this->_jsVar)) {
            $this->setJsVar(true);
        }

        return $this->_jsVar;
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
     * @param string $handler Event handler
     */
    public function setEvent($name, $handler)
    {
        $this->events[$name] = $handler;
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
     * Sets the JavaScript variable name for the object.
     * If $value is boolean TRUE a name is generated.
     *
     * @param boolean|string|JsExpression $value TRUE, The JavaScript variable name, or a JsExpression containing the JavaScript variable name
     * @throws InvalidParamExpression If $value is not or cannot be converted to a JsExpression
     */
    public function setJsVar($value)
    {
        if ($value === true) {
            $value = $this->jsVar();
        }

        if (is_string($value)) {
            $value = new JsExpression($value);
        }

        if (!($value instanceof JsExpression)) {
            throw new InvalidParamException(strtr('Invalid type for $value ({type}). $value must be a string that is a valid JavaScript variable name or a JsExpression of such a string.', [
                '{type}' => (is_scalar($value) ? gettype($value) : get_class($value))
            ]));
        }

        $this->_jsVar = $value;
    }

    /**
     * Sets a specified option
     *
     * @param string $name Option name
     * @param mixed $value Option value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Sets the options
     *
     * @param array $options Options for the object
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    private static $_counter = 0;

    /**
     * Creates a JavaScript variable name for the object
     *
     * @return string JavaScript variable name for the object
     */
    public function jsVar()
    {
        return $this->map->id . '_' . self::$_counter++;
//        return Inflector::variablize(basename(get_called_class()) . self::$_counter++);
    }

    abstract public function toJs($map); // Generates JavaScript code
    abstract protected function toJsExpression($js); // Generates the final JavaScript expression
}
