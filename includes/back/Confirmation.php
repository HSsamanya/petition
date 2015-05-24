<?php
/** Landing code after user clicks confirmation link in his email*/

require_once realpath(__DIR__.'/../../../../../wp-load.php');
require_once realpath(__DIR__.'/../../../../../wp-includes/pluggable.php');
require_once realpath(__DIR__.'/../../../../../wp-includes/functions.php');

use petition\includes\configs;
// ================ Processing Confirmation Request ============================

$personalkey=filter_input (INPUT_GET,'pky',FILTER_SANITIZE_STRING);

//============= Contact Service Locator =============================
$sLocator = configs\ServiceLocator::getInstance();
$db = $sLocator->get('petition\includes\configs\DbAdapter');

$tempTabInfo = $sLocator->get('petition\includes\tables\TempTable');
$tempTabInfo->setConfirm_code($personalkey);

$personalInfo = $tempTabInfo->getTempRow($db);
if($personalInfo != null){
    
    $isInserted=false;    
     
    $confirmTab = $sLocator->get('petition\includes\tables\ConfirmPetitionTable');
    $exists=$confirmTab->rowExists($db, $personalInfo->getEmail());
    
    if(!$exists){
        if($personalInfo->getKeepMyInfo()){        
            $confirmTab->setInfo($personalInfo);
            $confirmTab->insertToConfirmTable($db);
        }
    
            
        // ================ Prepare to send mailer to the Parlamenterians===============
        
        $Parlementerian = $sLocator->get('petition\includes\tables\ParlamenterianTb');
        $Parlementerian->setParlamentrianTb(null);

        $senderAddress = array($personalInfo->getEmail()=> $personalInfo->getName());
        $receivers = $Parlementerian->getAllParlAndEmail($db);
        $subject = $personalInfo->getPageTitle();

        $mailer = $sLocator->get('petition\includes\MailHandlers\MailHandlerSwift');
        $message = $mailer->mailBodyForParlamenterian($personalInfo->petitionPageContent(), $personalInfo->addressFormHtml());
        
        try {// sending mail using Swift mailer
            $mailer->sendMail($subject, $message,$senderAddress,$receivers, true);
            
        }catch(\Exception $ex){
            _default_wp_die_handler($ex->getMessage());
        }        

        //Update/insert the petitionMail table
        $PetitionMailTb = $sLocator->get('petition\includes\tables\PetitionMailTb');
        $PetitionMailTb->Initialization($personalInfo->getPageNo(), $personalInfo->getPageTitle(), $mailer->getSentMailCount());
        $PetitionMailTb->UpdateOrInsert($db);
        
        //If everything went well, delete the temporary table row
        $tempTabInfo->deleteTempRow($db);  
        

        wp_safe_redirect( get_permalink(443 ), 301 ); // Live app
        exit;
    }else{
        _default_wp_die_handler("Es tut uns sehr leid, aber es sieht so aus als hättest"
                . " du dich schon an diese E-Mail Aktion beteiligt");
    }
}else{    
    $path404= get_bloginfo('template_url')."/404.php";
    wp_safe_redirect( get_permalink($path404),404);
    exit;
}