<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace petition\includes\configs;

/**
 * Description of ServiceLocator
 *
 * @author Harrison
 */
class ServiceLocator {
    private static $locator = null;

    private $registry = array();

    public static function getInstance(){
        if(self::$locator === null){
            $locator = new ServiceLocator();
            $cfg = Config::getInstance();
            // Reading the service_locator setting from the application.config array
            $locator->registry = $cfg->get('service_locator');
            self::$locator = $locator;
        }
        return self::$locator;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name){
        foreach($this->registry as $type){
            if( isset($type[$name]) && is_string($type[$name]) && class_exists($type[$name])){
                return new $type[$name]();
            }
            if(isset($type[$name]) && is_callable($type[$name])){
                return call_user_func($type[$name], $this);
            }
            if(isset($type[$name]) && is_object($type[$name])){
                return $type[$name];
            }
        }
        throw new \InvalidArgumentException('Invalid Instance name');
    }

    public function setInvokable($key, $value){
        if(isset($value) && is_string($value) && class_exists($value)){
            $this->registry['invokables'][$key] = $value;
        }
        return $this;
    }
    public function setFactory($key, $value){
        if(isset($value) && is_callable($value)){
            $this->registry['factories'][$key] = $value;
        }

        return $this;
    }
}