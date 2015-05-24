<?php
namespace petition\includes\tables;

/**
 * for the of Confirm Petition Table
 *
 * @author Harrison
 */
class ConfirmPetitionTable {
    
    static $confirm_tb = "confirmed_petition_tb";
    
    
    private $confirmId=0;
    private $name=null;
    private $email=null;
    private $pageNo=0;
    private $pageTitle=null;
    private $keepmyinfo=null;
    
    static function getTable() {
        return self::$confirm_tb;
    }

    function getConfirmId() {
        return $this->confirmId;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getPageNo() {
        return $this->pageNo;
    }

    function getPageTitle() {
        return $this->pageTitle;
    }

    function getKeepmyinfo() {
        return $this->keepmyinfo;
    }

    static function setTable($table) {
        self::$confirm_tb = $table;
    }

    function setConfirmId($confirmId) {
        $this->confirmId = $confirmId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPageNo($pageNo) {
        $this->pageNo = $pageNo;
    }

    function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
    }

    function setKeepmyinfo($keepmyinfo) {
        $this->keepmyinfo = $keepmyinfo;
    }

    function setInfo($persInfo){
        $this->name=$persInfo->getName();
        $this->email=$persInfo->getEmail();
        $this->pageNo=$persInfo->getPageNo();
        $this->pageTitle=$persInfo->getPageTitle();
        $this->keepmyinfo=$persInfo->getKeepMyInfo();
    }
    
    /**
     * Communicates with Database. Inserts data into confirm table
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param DBAdaptor $db Database adaptor object
     * @param PersonInfo $personInfo PersonInfo Object
     * @return void returnes nothing
     */
    public function insertToConfirmTable($db){
       
        //Insert if not already exists statement
        $sql="INSERT INTO ".self::$confirm_tb."(name, email,pageNo,pageTitle,keepmyinfo) ".
                " SELECT * FROM (SELECT :name,:email,:pageNo,:pageTitle,:keepmyinfo) AS tmp ".
                " WHERE NOT EXISTS(SELECT email FROM ". self::$confirm_tb." WHERE email=:email)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);        
        $stmt->bindParam(':pageNo', $this->pageNo);
        $stmt->bindParam(':pageTitle',  $this->pageTitle);
        $stmt->bindParam(':keepmyinfo',  $this->keepmyinfo);

        try{
            if($stmt->execute()){
                return true;
            }          
        }  catch (\Exception $ex){
            echo nl2br("Database Erro:\nSource: '<insertToTable>'Insert failed".$ex->getMessage());
        }      
        return false;
    }
    
    /**
     * checks if a row with a specific email exists in the confirm table
     * @param PDO $db A pdo database instance
     * @param String $email Email as the key
     * @return Integer The count of the rows with the email
     */
    static function rowExists($db,$email){

        $sql = "SELECT COUNT(*) as count FROM ".
                self::$confirm_tb." WHERE email=:email"; 
        
        $stmt = $db->Prepare($sql);
        $stmt->bindParam(':email',$email);
        
        if(!$stmt->execute()){
            throw new \Exception("Execution failed! Check the your SQL string");
        }   
        $count = $stmt->fetchColumn();
        return $count >0?true:false;
    }
}
