<?php

namespace petition\includes\back;
use petition\includes\configs;

/**
 * This plugin hooks
 * @author Harrison
 */
class ThisPlugin {
    
    private $sl;
    
    public function __construct()
    {
        $this->sl = configs\ServiceLocator::getInstance();
        
//        add_action('admin_init', array(&$this,'admin_init'));
//        add_action('admin_menu', array(&$this,'admin_menu'));
        
        add_shortcode( 'get_petition_form', array($this,'petitionFormShortcode') );
        add_shortcode('address_position', array($this,'address_shortcode'));
    }

    public static function deactivate(){
        
    }
    
    public static function activate_PETITION(){
  /*
        if($_REQUEST['action']=='error_scape'){
            ob_start();
            file_put_contents(__DIR__.'../../p_log.txt', ob_get_clean());
        }
   */
        return;
    }    
    
    function petitionFormShortcode($atts) {

        $pId=strval($atts['pageidparam']);
        $pageId = ($pId===null) ? '' : $pId;
        $actionUrl= plugins_url().'/petition/includes/back/ReceivePetitionForm.php';

        $petition_form = $this->sl->get('petition\includes\front\PetitionForm');
        $petition_form->setPageId($pageId);
        $petition_form->setActionUrl($actionUrl);
        $formBody = $petition_form->get_petitionForm();
        
        return $formBody;
    }

    /** create a place holder div for the user's address.
     * The address is passed when the user send the later to 
     * Parlamenterian*/
    function address_shortcode() {            
        return '<div id="#"><div id="user-address"></div></div>';
    } 

    /**Hook for the Parlamenterian form*/
    function create_parlamenterians(){
        ob_start();    
        $content=file_get_contents(plugins_url().'/petition/create_parlamenterian.html');
        //$content = preg_replace("path:parlamenterian", "".plugins_url().'/petition/formHandler.php', $content);
        ob_get_clean();
        return $content;
    }
}
