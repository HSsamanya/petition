<?php
namespace petition\includes\front;
/*
* Constructor.
* Create a new Form object
* @param string $name The name of the parlamenterian
* @param String $email The email of the parlamenterian
* @author Harrison Ssamanya
*/
class ParlamenterianForm{
    
    private $id=0;
    private $name=null;
    private $email=null;
    
    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Constructor.
     * Create a new Form object
     * @param string $name The name of the parlamenterian
     * @param String $email The email of the parlamenterian
     * @author Harrison Ssamanya
     */
    function ParlamenterianForm($name,$email){
        $this->name=$name;
        $this->email=$email;
    }   
    
    
    /**
     * Method sets the forms attributes
     * @param Parlamenterian $parlamenterian The object of a parlamenterian class
     * @author Harrison Ssamanya
     * @return void nothing
     */
    function setParlamenterian($parlamenterian){
        
        $this->id = $parlamenterian->getId();
        $this->name = $parlamenterian->getName();
        $this->email = $parlamenterian->getName();
    }
    
    
    /**
     * Builds a Parlamenterian Form. When updating a parlamentrian, call <setParlamenterian> first
     * before you call the this method for form creations. In that case, the current values of the 
     * Parlamentarian shall be fed into the form.
     * @param String $actionUrl The url of the action to which the form posts back
     * @return String The string containing the form
     * @author Harrison Ssamanya
     */
    function getParlamenterianForm($actionUrl){
        
        $formBody = "<form id='create-parlamenterian' class='form-horizontal' action='$actionUrl' >
                      <fieldset><legend>Neue Abgeordneter Eingeben</legend>

                        <!-- Text input-->
                        <div class='control-group'>
                          <label class='control-label' for='name'>Name</label>
                          <div class='controls'>
                            <input id='name' name='name' type='text' placeholder='placeholder' class='input-medium' required='' value='$this->name'>
                            <p class='help-block'>name des Abegorneters</p>
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class='control-group'>
                          <label class='control-label' for='email'>Email</label>
                          <div class='controls'>
                            <input id='email' name='email' type='text' placeholder='example@domail.de' class='input-medium' required='' value='$this->email'>
                          </div>
                        </div>
                        
                      </fieldset>
                    </form>";
        return $formBody;
    }
    
    
    function ListOfParmentariansForm($actionUrl){
        
        $formBody = "<form id='create-parlamenterian' action='$actionUrl' >
                    <fieldset>

                    <!-- Form Name -->
                    <legend>Liste Abgeordneter</legend>
                    
                    <div id='inlineElements'>
                    
                      <!-- Name field-->
                      <div class='controls'>
                        <label id='name'></label>
                      </div>

                      <!-- Email field-->
                      <div class='controls'>
                        <label id='email'></label>
                      </div>
                      
                      <!-- Delete Control-->
                      <div class='controls'>
                        <label id='delete'></label>
                      </div>
                      
                      <!-- Edit control-->
                      <div class='controls'>
                        <label id='edit'></label>
                      </div>
                      
                    </di>                    
                </fieldset>
                </form>";
        return $formBody;
    }
    
    function eachParlamenterian($parlamenterian){
        
        $row = "<div id='inlineElements'>
                      <!-- Name field-->
                      <div class='controls'>
                        <label id='name'>$parlamenterian->name</label>
                      </div>

                      <!-- Email field-->
                      <div class='controls'>
                        <label id='email'>$parlamenterian->email</label>
                      </div>
                      
                      <!-- Delete Control-->
                      <div class='controls'>
                        <label id='delete'></label>
                      </div>
                      <!-- Edit control-->
                      <div class='controls'>
                        <label id='edit'></label>
                      </div>                      
                    </di>";
        
        return $row;
    }
}