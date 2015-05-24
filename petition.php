<?php
/*
Plugin Name: Petition
Description: Hilft bei der E-Mail Aktionsprozess.
Version: 1.1
Author: Harrison Ssamanya
*/
namespace petition;
use petition\includes\back;

require_once realpath(__DIR__.DIRECTORY_SEPARATOR.'bootstrap.php');

if(class_exists('petition\includes\back\ThisPlugin')){
   $file= realpath(__DIR__.'/includes/back/ThisPlugin.php');
   register_activation_hook($file, array('petition\includes\back\ThisPlugin','activate_PETITION'));    
   
    //instiate the plugin
   $thisplugin = new back\ThisPlugin();
} 