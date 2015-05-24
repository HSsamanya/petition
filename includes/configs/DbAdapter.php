<?php
namespace petition\includes\configs;

/**
 * A singliton class for making connection to the Database
 */
final class DbAdapter {
    private static $host = null; // Host name 
    private static $username = null; // Mysql username 
    private static $password = null; // Mysql password 
    private static $dbname = null; // Database name
    
    private static $instance=null;
  
    /**
     * @return type \PDO*/
    public static function getDB() {
        
        self::setDBDetails();
        
        if (self::$instance===null) {
            self::$instance = new \PDO("mysql:host=".self::$host.";dbname=".self::$dbname, self::$username, self::$password);
        }
        return self::$instance;
    }
    

    private static function setDBDetails(){        

        $res=parse_ini_file("dbconfig.ini", TRUE);
        $mode = WP_DEBUG_DISPLAY ? 'dblocal':'dbremote';
        
        if($res[$mode]){        
            self::$host=$res[$mode]['host'];
            self::$dbname=$res[$mode]['dbname'];
            self::$username=$res[$mode]['username'];
            self::$password=$res[$mode]['password'];        
        }
    }
}
