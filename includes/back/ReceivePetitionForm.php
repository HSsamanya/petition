<?php

 require_once realpath(__DIR__.'/../../../../../wp-load.php'); 

 
 use petition\includes\configs;
 use petition\includes\MailHandlers;
 
 // -------------Get Service locator -----------------
 $sLocator = configs\ServiceLocator::getInstance();
 
    // Random confirmation code 
    $confirm_code = md5(uniqid(rand()));
     //-------------form Data ------------------
    $sanitizeString = array('flags' => FILTER_FLAG_STRIP_LOW);
    
    $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
    $name  = filter_input (INPUT_POST,'name',FILTER_SANITIZE_STRING,$sanitizeString);  
    
   //--------------------------------------------

    $db = $sLocator->get('petition\includes\configs\DbAdapter');
    
    $tempInfo=$sLocator->get('petition\includes\tables\TempTable');
    $tempInfo->setConfirm_code($confirm_code); 
    $tempInfo->extractPostedData();
    
    $isInserted = false;
    try {
        $isInserted = $tempInfo->insertPostedData($db);
    } catch (Exception $ex) {
         // Error page
            _default_wp_die_handler($ex->getMessage());
    }
    if($isInserted){
        $subject = get_option('blogname'); 
        $receiverInf = array($email=>$name);        
        $senderAddress=array("team@integrationskurse-oeffnen.de"=>get_option('blogname'));       
        
        // sending mail
        $mailer = $sLocator->get('petition\includes\MailHandlers\MailHandlerSwift');
        $message = MailHandlers\MailHandlerSwift::confirmationLink($confirm_code);

        try{
            $mailer->sendMail($subject, $message,$senderAddress,$receiverInf ,true);
         }catch(\Exception $ex){
             // Error page
            _default_wp_die_handler($ex->getMessage());
        }        
        wp_safe_redirect( get_permalink(436 ),301); // live app
        exit;
    }
