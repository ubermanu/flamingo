<?php

namespace Flamingo\Service;

/**
 * Class TaskNameParser
 * @package Flamingo\Service
 */
class TaskNameParser
{
    /**
     * @link https://regex101.com/r/AkEmJQ/16
     * @var string
     */
    protected $pattern = '/([\w\/]+\.php)?(?:\:)?([\w]+)?(?:(\:\:|\-\>)([\w]+))?/i';

    /**
     * @var string
     */
    protected $filename = '';

    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var string
     */
    protected $method = '';

    /**
     * TaskNameParser constructor.
     * @param string $taskName
     */
    public function __construct($taskName)
    {
        $this->decode($taskName);
    }

    /**
     * @param string $taskName
     * @internal
     */
    public function decode($taskName)
    {
        preg_match($this->pattern, $taskName, $decodedTaskName);
        $this->filename = isset($decodedTaskName[1]) ? $decodedTaskName[1] : '';
        $this->class = isset($decodedTaskName[2]) ? $decodedTaskName[2] : '';
        $this->method = isset($decodedTaskName[4]) ? $decodedTaskName[4] : '';
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Include external resource if defined.
     * Run main class + method.
     *
     * @param array $arguments
     * @internal
     */
    public function run(array $arguments = [])
    {
        if ($this->filename) {
            require_once $this->filename;
        }

        if ($this->class) {
            if ($this->method) {
                call_user_func_array([$this->isStatic() ? $this->class : new $this->class, $this->method], $arguments);
            } else {
                // TODO: Check for __invoke method
                call_user_func_array(new $this->class, $arguments);
            }
        } else {
            if ($this->method) {
                call_user_func_array($this->method, $arguments);
            }
        }
    }

    /**
     * @return bool
     */
    protected function isStatic()
    {
        return (new \ReflectionMethod($this->class, $this->method))->isStatic();
    }
}
