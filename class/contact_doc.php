<?php

require_once "abstracte_form_doc.php";

class ContactDoc extends FormDoc
{
    public $title = '';
    public $fields = array();

    public function __construct($mydata, $title = 'abc',$fields)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($mydata);
        $this->title = $title;
        $this->fields = $fields;
    }


    protected function mainContent() 
    {
        $this->formTitle($this->title);
        foreach ($this->fields as $key => $value) {
            $this->formField($value['type'],$value['name'],$value['placeHolder'],$value['value']);
        }
        $this->formButton("submit");
        
    }

}
?>