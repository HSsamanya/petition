<?php
namespace petition\includes\tables;

/**
 * Description of PetitionMailTb
 *
 * @author Harrison
 */
class PetitionMailTb {
    
    private $letterPageNo=0;
    private $petitionName=null;
    private $sentMailCount=null;
    
    static $tableName="petition_mail_tb";  // with defaul value
    
    /**
     * Initializes this class with tracking data
     */
    function Initialization($letterPageNo,$petitionName,$sentMailCount){
        $this->letterPageNo=$letterPageNo;
        $this->petitionName=$petitionName;
        $this->sentMailCount=$sentMailCount;
    }
    
    
    /**
     * Tries to insert a new row, but if already exists, it updates the sentmailcount    
     * @param PDO $db The pdo Adapter class
     */
    function UpdateOrInsert($db){
        
        $sql1="INSERT INTO ".self::$tableName." (letterpageno,petitionname,sentmailcount) "
                . " VALUES(:letterpageno,:petitionname,:sentmailcount) "
                . " ON DUPLICATE KEY UPDATE sentmailcount=sentmailcount + :sentmailcount";        
        
        $stmt=$db->prepare($sql1);
        $stmt->bindParam(':letterpageno', $this->letterPageNo);
        $stmt->bindParam(':sentmailcount',  $this->sentMailCount);
        $stmt->bindParam(':petitionname', $this->petitionName); 
        
        if( !$stmt->execute()){
            throw new \Exception("Could not perform insert or Update");
        }
    }
    
    /**
     * Selects from the table.
     * @param PDO $db The database connection
     * @param Integer $key The column Identifying a row. (Page number)
     */
    function selectForCount($db,$key){
        
        $sql="SELECT sentmailcount FROM ".self::$tableName." WHERE letterpageno=:letterpageno";
        
        $stmt=$db->prepare($sql);
        $stmt->bindParam(':letterpageno',$key);
        $stmt->execute();

        if(($res= $stmt->fetch(\PDO::FETCH_ASSOC))){
            return $res['sentmailcount'];
        }
        return 0;
    }
}
