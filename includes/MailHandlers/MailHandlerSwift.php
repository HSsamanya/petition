<?php
namespace petition\includes\MailHandlers;

/** Mail Handler using Swift Mailer*/
require_once realpath(__DIR__.'/../../swiftmailer/lib/swift_required.php');


class MailHandlerSwift{
    
    private $sentMailCount=0;
    private static $ccReceivers=null;
    
    private static $serverPort=25;
    private static $password= null;
    private static $userName= null;
    private static $serverName= null;
    private static $mailAddress = null;
    private static $userFullName=null;
    private static $encryption ="ssl";
    

    function getSentMailCount() {
        return $this->sentMailCount;
    }

    function setSentMailCount($sentMailCount) {
        $this->sentMailCount = $sentMailCount;
    }

    /**
     * creates mailer transport
     * @return Object swift mailer transport
     */
    private function getTransport(){
        
        self::setSmtpDetails();        
        
        $transport = \Swift_SmtpTransport::newInstance(self::$serverName, self::$serverPort);
        $transport->setUsername(self::$userName);
        $transport->setPassword(self::$password);
        $transport->setEncryption(self::$encryption);
        
        return $transport;
    }
    
    private static function setSmtpDetails(){  

        $file = realpath("../configs/dbconfig.ini");
        $res = parse_ini_file($file, true);
        
        $smtpDetails = WP_DEBUG_DISPLAY ? "mailerlocal":"mailerremote";

        if(($detail = $res[$smtpDetails]) !==null){
            self::$serverName=$detail['serverName'];
            self::$userName=$detail['userName'];
            self::$password=$detail['password'];
            self::$serverPort=$detail['serverPort'];
            self::$encryption=$detail['encryption'];
            self::$mailAddress=$detail['emailAddress'];
            self::$userFullName=$detail['userFullName'];
            self::$ccReceivers=array(self::$mailAddress =>  self::$userFullName);
        }
    }


    /**
     * Preprocesses the mail body out of and html page content
     * @param String $HtmlBody String of other html body
     * @param String $address The Address of the user. Is to be embedded into the mails body
     * @return String The body of the email.
     */
    function mailBodyForParlamenterian($HtmlBody,$address){

        $doc = new \DOMDocument(1.0);
        $doc->loadHTML('<?xml encoding="UTF-8">'.$HtmlBody);
        
        //removing unwanted content
        $introText = $doc->getElementById("introtext");
        $introText->parentNode->removeChild($introText); 
        
        //adding address
        $addDiv=$doc->getElementById("user-address");
        foreach($address as $key=>$value){
            $elem = $doc->createElement('div', $value);
            $elem->setAttribute('id', 'user-'.$key);
            $addDiv->appendChild($elem);
        }        
        $content = $doc->saveHTML();    
        return $content;
    }   
    
    /**Merges the recever array with the parallel cc email information*/
    function addParallelEmailsToReceiverList(array $array1,array $array2){
        
        if(count($array1)>0){            
            return array_merge($array1, $array2);
        }
        return null;
    }
   
    /**
     * Sends the email to the destination
     * @param String $subject The text for email subject
     * @param String $HtmlBody The emails body
     * @param Array $senderInf The array for the senders email address(es)
     * @param Array $receiverInf Array of receivers' email addresses
     * @param Boolean $isHtml indicating whether the email body is html or not
     * @return void 
     */
    public function sendMail($subject, $HtmlBody,$senderAddress,$receiverInf,$isHtml){

        $mailer = \Swift_Mailer::newInstance($this->getTransport());
        $messege =  \Swift_Message::newInstance($subject,$HtmlBody);
        
        if($isHtml){
            $messege->setContentType('text/html');
        }
        
        //Modifying the smtp sender address with the senderaddress
        $fromAddress = array(self::$mailAddress => reset($senderAddress));
//        print_r($fromAddress);
//        exit;
        $messege->setFrom($fromAddress);        
        $messege->setTo(array_slice($receiverInf, 0, 1)); // get first element
        array_shift($receiverInf); // take of the first element
        
        if(!empty($receiverInf)){
            $receiverInf=  $this->addParallelEmailsToReceiverList($receiverInf,self::$ccReceivers);
            $messege->setBcc($receiverInf); // now use the rest of the array
            $reply = array(self::$mailAddress=>self::$userFullName);
            $reply = array_merge($reply,$senderAddress);
//            print_r($reply);
//            exit;
            $messege->setReplyTo($reply);
        }
        
        //Try catch is still neccessery: one problem may be an internal error
        //Another error is when mailer returns 0. Could not send the mail.
        //Mail capacity reached, wrong mail address etc        
        $sent = $sentCount=$mailer->send($messege);
        if($sent > 0){ //Sending mail
            $this->sentMailCount=1;  //two mailes are send to Administrators
        }else{                
            throw new \Exception( 
                    nl2br("Die E-Mail konnte leider nicht gesendet werden!\r\n"
                        . "Möglicherweise würde die max. Zahl an E-Mails einer Stunde erreicht.\r\n"
                        . "Bitte versuchen es später erneut. Danke!\n"));
        }        
    }
    
    
    /**
     * creates and html body mail for sending to the user.
     * @param string $confirm_code The confirmation code for identifying the user
     * @return string The html massege body.
     */
    public static function confirmationLink($confirm_code){
        $baseUrl=plugins_url();
        $messege=  "<!DOCTYP html>
                    <html>
                    <body>
                    <h3>Ihr Bestätigungslink</h3>
                        <p>Bitte diesen Link anklicken, um die Email an die Abgeordneten zu versenden.</p>
                        <p>
                            <a href='$baseUrl/petition/includes/back/Confirmation.php?pky=$confirm_code'>
                                $baseUrl/petition/includes/back/Confirmation
                            </a>
                        </p>
                        <p><em>Wenn dieser sich nicht anklicken lässt, bitte einfach kopieren und im Browser-Adressfeld einfügen und bestätigen.</em></p>
                        <p><em>Mit dem Bestätigen des Links erkläre ich mich damit einverstanden, dass
                           meine angegebenen Adressdaten als Unterschrift in den versendeten E-Mails erscheinen.</em></p>
                   </body>
                   </html>";
       return $messege;
    }
}

