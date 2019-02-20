<?php
require_once "abstracte_form_doc.php";

class ContactDoc extends FormDoc
{

    public function __construct($mydata)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($mydata);
    }


    protected function mainContent() 
    {
        if (getArrayVar($this->data,'valid',false)) {
            $this->message();
            unset($this->data['name'],$this->data['email'],$this->data['text'],$this->data['valid']);
        }
        $this->formTitle($this->data['page']);
        $this->startForm();
        $this->formField("text","name","Your name...",
                            getArrayVar($this->data, 'name'), 
                            getArrayVar($this->data, 'nameErr'));
        $this->formField("email","email","Your email...",
                            getArrayVar($this->data, 'email'),
                            getArrayVar($this->data, 'emailErr'));
        $this->formField("","text","Your text...",
                            getArrayVar($this->data, 'text'),
                            getArrayVar($this->data, 'textErr'));
        $this->hiddenFormField();
        $this->formButton("submit");
    }

    private function message() 
    {
        echo "<div class='alert alert-success' role='alert'>
        <strong>Aww yeah,</strong> you successfully filled out the form you're AWSOME! <br>
        Your name is: ". getArrayVar($this->data, 'name') . "<br>
        Your email is: ". getArrayVar($this->data, 'email') ."<br>
        Your text is: ". getArrayVar($this->data, 'text') . "
        </div>";   
    }
}

?>