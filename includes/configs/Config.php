<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 02.05.2015
 * Time: 01:57
 */

namespace petition\includes\configs;


class Config {
    private static $instance = null;
    private $cfg  = null;
    public static function getInstance(){
        if(!self::$instance){
            $cfg = require __DIR__ .DIRECTORY_SEPARATOR.'application.config.php';
            self::$instance = new Config($cfg);
        }
        return self::$instance;
    }

    public function __construct(array $cfg){
        $this->cfg = $cfg;
    }

    public function setParam($key,$value){
        $this->cfg['params'][$key] = $value;
        return $this;
    }

    public function getParam($key){
        if(!$this->cfg['params']){
            return null;
        }
        if(!$this->cfg['params'][$key]){
            return false;
        }

        return $this->cfg['params'][$key];
    }
    
    public function get($key){
         if(!isset($this->cfg[$key])){
            throw new \InvalidArgumentException(sprintf("No data with the index '%s' found!"));
        }
        return $this->cfg[$key];
    }
}