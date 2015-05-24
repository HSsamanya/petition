<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 01.05.2015
 * Time: 01:52
 */
use petition\includes\front;
use petition\includes\tables;
use petition\includes\configs;
use petition\includes\MailHandlers;

return array(
//    'params' => require(__DIR__.'/params.php'),
    'service_locator' => array(
        'invokables' => array('Petition\Form\Form'=>'\Petition\Form\Form'),
        'factories' => array(
            'petition\includes\configs\DbAdapter'=> function(){
                return  configs\DbAdapter::getDB();
            },
            'petition\includes\front\PetitionForm'=> function(){
                $petitionform = new front\PetitionForm();
                return $petitionform;
            },
            'petition\includes\tables\ConfirmPetitionTable'=> function(){
                $confTable = new tables\ConfirmPetitionTable;
                return $confTable;
            },
            'petition\includes\tables\ParlamenterianTb'=>  function(){
                return new tables\ParlamenterianTb();
            },
            'petition\includes\tables\PetitionMailTb' => function (){
                return new tables\PetitionMailTb();
            }
            ,
            'petition\includes\MailHandlers\MailHandlerSwift'=> function (){
                return new MailHandlers\MailHandlerSwift();
            },
            'petition\includes\tables\TempTable' => function (){
                return new tables\TempTable();
            }
        )
    )
);

