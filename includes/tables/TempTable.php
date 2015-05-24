<?php
namespace petition\includes\tables;
use petition\includes\back;
/**
 * A table that stores user information temporarilly as we wait for his 
 * confirmation.
 * @author Harrison
 */
class TempTable {
    static $Temp_tb="temp_petition_tb";
    private $confirm_code = null;

    private $name=null;
    private $email=null;
    private $street=null;
    private $zip=null;
    private $place=null;
    private $country=null;
    private $pageNo = null;
    private $pageTitle=null;
    private $keepMyInfo=false;
    
    static function getTable() {
        return self::$Temp_tb;
    }

    function getName() {
        return $this->name;
    }
    function setConfirm_code($confirm_code) {
        $this->confirm_code = $confirm_code;
    }

        
    function getEmail() {
        return $this->email;
    }

    function getStreet() {
        return $this->street;
    }

    function getZip() {
        return $this->zip;
    }

    function getPlace() {
        return $this->place;
    }

    function getCountry() {
        return $this->country;
    }

    function getPageNo() {
        return $this->pageNo;
    }

    function getPageTitle() {
        return $this->pageTitle;
    }

    function getKeepMyInfo() {
        return $this->keepMyInfo;
    }

    static function setTable($table) {
        self::$Temp_tb = $table;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setStreet($street) {
        $this->street = $street;
    }

    function setZip($zip) {
        $this->zip = $zip;
    }

    function setPlace($place) {
        $this->place = $place;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setPageNo($pageNo) {
        $this->pageNo = $pageNo;
    }

    function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
    }

    function setKeepMyInfo($keepMyInfo) {
        $this->keepMyInfo = $keepMyInfo;
    }
    
    /**
     * Is used to trim trailing spaces off a string
     * @param String $value A reference to a string to be trimmed
     */
    function trim_value(&$value){
        $value = trim($value);
    }
    /**
     * Gets the first row with the confirm_code!
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @param DbAdapter $db The PersonalInfo object
     * @return PersonalInfo The personalInfo object
     */
    public function getTempRow($db){
        
        $personObj = new back\PersonInfos();
        
        $sql1="SELECT * FROM ". self::$Temp_tb ." WHERE confirm_code=:confirm_code";        
        $stmt = $db->prepare($sql1);
        $stmt->bindParam(':confirm_code',  $this->confirm_code,  \PDO::PARAM_STR);

        try{
           $stmt->execute();
           $res = $stmt->fetch(\PDO::FETCH_OBJ);

            if($res){
                $personObj->setAllInfos($res);                
                return $personObj;
            }            
            //print_r($personObj->getEmail());
        }  catch (\Exception $e){
            echo nl2br('Database Error:\nSource:"<ConfirmationHandler->fromTempTbToConfirmTb>"\n'+$e,true);
        } 
        return NULL;
    }
    
    /**
     * communicates with the database:Temp_table, to remove the row the the 
     * confirm_code.
     * @author Harrison Ssamanya <ssmny2@yaho.co.uk>
     * @param DBAdaptor $db db addaptor Object
     */
    public function deleteTempRow($db){     
        
        $sql = "DELETE FROM ".self::$Temp_tb." WHERE confirm_code=:confirm_code";
        $stmt = $db->Prepare($sql);
        $stmt->bindParam(':confirm_code',  $this->confirm_code,\PDO::PARAM_STR);
        
        try {
            $stmt->execute();
        } catch (\Exception $ex) {
            echo nl2br('Database DELETE Error:\nSource: "<deleteTempRow>"\n'.$ex->getMessage());
        } 
    }
    
    function extractPostedData(){
        //trim the array content using  a call back in the array_filter function
        array_filter($_POST, array($this,'trim_value'));
        
        // Sanitizer filters
        $sanitizeEmail = array('filter'  => FILTER_SANITIZE_EMAIL);
        $sanitizeString = array('filter' => FILTER_SANITIZE_STRING,'flags' => FILTER_FLAG_STRIP_LOW);        
        
        // Match filters to the Fields
        $sanitizFilters =array('name'=>$sanitizeString,'email'=>$sanitizeEmail,'street'  =>$sanitizeString,
                            'zip'=> $sanitizeString,'place'=>$sanitizeString,'country'=>$sanitizeString,
                            'pageId'=>$sanitizeString,'keepmyinfo'=>$sanitizeString);
        
        //Apply filters onto the trimed array content
        $revisedPost = filter_var_array($_POST,$sanitizFilters);

        $this->email=$revisedPost['email'];
        $this->name  = $revisedPost['name'];  
        $this->street=$revisedPost['street'];  
        $this->zip   =$revisedPost['zip'];  
        $this->place =$revisedPost['place'];
        $this->country=$revisedPost['country'];
        $this->pageId= $revisedPost['pageId'];
        $this->keepmyinfo=$revisedPost['keepmyinfo'] ? 1:0;
    }
    /**
     * Attempts to insert a new row into this table. The email column is unique,
     * If the email already exists, the insert will fail, and the execute will return
     * a boolean false.
     * @param PDO $db The Database connection
     * @return Boolean Insert successful or not*/
    public function insertPostedData($db){ 
        
       if($this->alreadyExists($db)){
           throw new \Exception(
            nl2br("Prozess Terminiert. Wahrscheinlich hast du noch nicht den link in deiner E-Mail Box bestätigt.\n"
                    . "Wenn das nich der Fall ist bitte nimmst du dir ein Paar minuten das Problem per Kommenter anzumelden.\n"
                    . "Danke!"));
       }
        
        $sql="INSERT INTO ". self::$Temp_tb."(confirm_code, name, email,street,zip,place,country,pageNo,keepmyinfo)".
             "VALUES(:confirm_code, :name, :email, :street,:zip,:place,:country,:pageNo,:keepmyinfo)";
        
        $stmt = $db->prepare($sql);        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':street', $this->street);
        $stmt->bindParam(':zip', $this->zip);
        $stmt->bindParam(':place', $this->place);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':pageNo',  $this->pageId);
        $stmt->bindParam(':keepmyinfo',  $this->keepmyinfo);
        $stmt->bindParam(':confirm_code', $this->confirm_code);
        
        $stmt->execute();
        return true;
    }
    
    public function alreadyExists($db){
        $sql = "SELECT COUNT(email) FROM ".self::$Temp_tb." WHERE email=:email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email',  $this->email);
        $stmt->execute();
        if($stmt->fetchColumn() > 0){
            return true;
        }
        return false;
    }
}
