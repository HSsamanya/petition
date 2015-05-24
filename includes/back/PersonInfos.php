<?php
namespace petition\includes\back;

/**
* Holds the details of the user
*/
class PersonInfos{
    
    private $name=null;
    private $email=null;
    private $street=null;
    private $zip=null;
    private $place=null;
    private $country=null;
    private $pageNo = null;
    private $pageTitle=null;
    private $keepMyInfo=false;
    
    public function getKeepMyInfo() {
        return $this->keepMyInfo;
    }
    function getName() {
        return $this->name;
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
    function getPageTitle() {
        return $this->pageTitle;
    }    
    function getPageNo() {
        return $this->pageNo;
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
        
        $this->keepMyInfo = $keepMyInfo==1? true:false;
    }
           
    function setTitle($subject){
        $this->pageTitle = $subject;
    }
    
    function set_Title($pageId){        
        $post = get_post($pageId);
        $this->pageTitle = $post->post_name;
    }
    
    function setAllInfos($allInfos){
        $this->name=$allInfos->name;
        $this->email=$allInfos->email;
        $this->street=$allInfos->street;
        $this->zip=$allInfos->zip;
        $this->place=$allInfos->place;
        $this->country=$allInfos->country;
        $this->pageNo=$allInfos->pageNo;
        $this->keepMyInfo=$allInfos->keepmyinfo; 
        $this->pageTitle="Integrationskurse für alle Flüchtlinge";        
    }
    
    /**
     * The function returns the html content of  page
     * @author Harrison Ssamanya <ssmny2@yahoo.co.uk>
     * @return String The Html Content of  page
     */
    function petitionPageContent(){
        $post = get_page($this->getPageNo()); 
        $content = apply_filters('the_content', $post->post_content);
        return $content;
    }
    
    /**
     * Raps address information into an array
     * @return array Associative array
     */
    function addressFormHtml(){
        $htmlCont=
            array("name" =>  "$this->name",
                "street" =>  "$this->street",
                "zip"    => "$this->zip &nbsp;$this->place");
        return $htmlCont;
    }
}
