<?php
namespace petition\includes\front;
use petition\includes\configs;

/** Petiton form */
class PetitionForm {
    
    private  $actionUrl=null;
    private  $pageId=null;
    
    /**
     * calls the value for the progress of the petition
     */
    function petitionProgress(){
        $sLocator = configs\ServiceLocator::getInstance();
        
        $db = $sLocator->get('petition\includes\configs\DbAdapter');
        $petitionMailTb = $sLocator->get('petition\includes\tables\PetitionMailTb');
        $res = $petitionMailTb->selectForCount($db, $this->pageId);
        
        unset($db);
        return $res;
    }
    
    
    function getActionUrl() {
        return $this->actionUrl;
    }

    function getPageId() {
        return $this->pageId;
    }

    function setActionUrl($actionUrl) {
        $this->actionUrl = $actionUrl;
    }

    function setPageId($pageId) {
        $this->pageId = $pageId;
    }
    
    /**
     * The petition form function
     * @return String The HTML string of the form
     */
    function get_petitionForm() { 
        
        $progress = $this->petitionProgress();
        $formBody = "<br/><form id='petition-form' class='form-horizontal' role='form' action='$this->actionUrl' method='post'>
                    <fieldset><legend>E-Mail-Aktions-Formular</legend>
                        <!-- Text input-->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='name'>Name</label>
                            <div class='controls col-sm-10'>
                                <input name='name' class='input-medium' id='name' required='' type='text' placeholder='Vor- und Nachname'>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='email'>E-mail</label>
                            <div class='controls col-sm-10'>
                                <input name='email' class='input-medium' id='email' required='' type='text' placeholder='beispiel@domäne.de'>
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='street'>Strasse</label>
                            <div class='col-sm-10'>
                                <input name='street' class='input-medium' id='street' required='' type='text' placeholder='beispielstrasse Nr.'>
                            </div></div>
                        <!-- Text input-->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='street'>PLZ</label>
                            <div class='col-sm-10'>
                                <input name='zip' class='input-medium' id='zip' required='' type='text' placeholder=''>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='place'>Ort</label>
                            <div class='col-sm-10'>
                                <input name='place' class='input-medium' id='place' required='' type='text' placeholder=''>
                            </div>
                        </div>     
                        <!-- Text input-->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='country'>Land</label>
                            <div class='col-sm-10'>
                                <input name='country' class='input-medium' id='country' required='' type='text' placeholder=''>
                            </div>
                        </div><br/>
                        <!--Checkbox-->
                        <label class='checkbox' for='keepmyinfo'>
                            <input name='keepmyinfo' id='keepmyinfo' type='checkbox' value='1'> 
                            <em>Bitte haltet mich auf dem Laufenden</em>
                        </label>
                        <div></div><br/>
                        <!-- Button (Double) -->
                        <div class='form-group'>
                            <label class='control-label col-sm-4' for='submit'></label>
                            <div class='col-sm-10'>
                                <button name='pt_submit' class='btn btn-primary' id='pt_submit'>Abschicken</button>&nbsp;&nbsp; 
                                <label>Bisher gesendete E-Mails:&nbsp; $progress</label>
                                <input type='hidden' name='pageId' value='$this->pageId'>
                            </div>
                        </div>
                    </fieldset>
                </form>";
        return $formBody;
    }
}
