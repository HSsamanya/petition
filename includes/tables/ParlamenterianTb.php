<?php
namespace petition\includes\tables;

/** Representing the petition table */
class ParlamenterianTb{
    
    private $id=0;
    private $name = null;
    private $email = null;    
    static $parlamentrianTb=null;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getParlamentrianTb() {        
        return self::$parlamentrianTb;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }
    
    function setParlamentrianTb($parlamentrianTb=null) {
        if($parlamentrianTb===null){
//            self::$parlamentrianTb = (WP_DEBUG_DISPLAY?"parliamentarier_tb":"parlamentarian_tbFull");
            self::$parlamentrianTb = "parliamentarier_tb";
        }else{
            self::$parlamentrianTb=$parlamentrianTb;
        }
    }

     /**
     * Performs an insert onto the parlamenterian table
     * @param PDO $db Database connection
     */
    function InsertNewParlementerian($db){
        
        $sql="INSERT INTO ".self::$parlamentrianTb."(name, email) "
                . "SELECT * FROM(SELECT :name, :email)AS tmp "
                . "WHERE NOT EXISTS (".
                      " SELECT email FROM ".self::$parlamentrianTb .
                      " WHERE email=:email)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        
        try {
             $stmt->execute();
        } catch (Exception $ex) {
            echo nl2br("Exception on inserting new Parlamenteria\r\n".$ex->getMessage());
        }        
    }
    
     /**
     * Performs a delete onto the parlamenterian table
     * @param PDO $db Database connection
     */
    function DeleteParlamentarian($db){
        $sql = "DELETE FROM ".self::$parlamentrianTb." WHERE email=:email";
        $stmt = $db->Prepare($sql);
        $stmt->bindParam(':email',  $this->email,\PDO::PARAM_STR);
        
        try {
            $stmt->execute();
        } catch (\Exception $ex) {
            echo nl2br('Error on DELETE\r\n'.$ex->getMessage());
        } 
    }
    /**
     * Performs an update onto the parlamenterian table
     * @param PDO $db Database connection
     */
    function UpdateParlamenterian($db){
        
        $sql = "UPDATE ". self::$parlamentrianTb .
               " SET (name= :name,email=:email)WHERE email = :email";
        $stmt = $db->Prepare($sql);
        $stmt->bindParam(':name',  $this->name,\PDO::PARAM_STR);
        $stmt->bindParam(':email',  $this->email,\PDO::PARAM_STR);
        
        try {
            $stmt->execute();
        } catch (Exception $ex) {
            echo nl2br('Error on UPDATE\r\n'.$ex->getMessage());
        }
    }
    
    
    /**
     * processes information from the table of Palamenterians
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param PDO $db The adapter to the database
     * @return Array An associative array of "email"="name"
     */
    public function getAllParlAndEmail($db){ 
                
        $sql = "SELECT * FROM ". self::$parlamentrianTb;
        $stmt = $db->Prepare($sql);        
        try {
            $stmt->execute();
        } catch (Exception $ex) {
            echo nl2br('Get all Parlamenterians failed\r\n'.$ex->getMessage());
        }
        
        $parl_emails = array();
        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $parl_emails[$row->email]= $row->name; 
        }
        return $parl_emails;
    }    
}