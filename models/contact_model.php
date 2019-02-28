<?php
require_once "models/page_model.php";

class ContactModel extends PageModel 
{
    public $name = '';
    public $email = '';
    public $text = '';
    public $nameErr = '';
    public $emailErr = '';
    public $textErr = '';
    public $valid = False;

    public function __construct(PageModel $model)
    {
        // pass the model on to our parent class (PageModel)
        parent::__construct($model);
    }

    public function validateForm()
    {
        $this->name = $this->test_input($this->getPostVar('name'));
        $this->email = $this->test_input($this->getPostVar('email'));
        $this->text = $this->test_input($this->getPostVar('text'));

        if (empty($this->name)) {$this->nameErr = "Name is required";}
        if (empty($this->email)) {$this->emailErr = "Email is required";}
        if (empty($this->text)) {$this->textErr = "Text is required";}

        if (empty($this->nameErr) && empty($this->emailErr) &&
            empty($this->textErr)) {
            $this->valid = true;
        }
    }


}