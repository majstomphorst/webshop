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
        $this->name = test_input(getPostVar('name'));
        $this->email = test_input(getPostVar('email'));
        $this->text = test_input(getPostVar('text'));

        if (empty($this->name)) {$this->nameErr = "Name is required";}
        if (empty($this->email)) {$this->emailErr = "Email is required";}
        if (empty($this->text)) {$this->textErr = "Text is required";}

        if (!isset($this->nameErr) && !isset($this->emailErr) &&
            !isset($this->textErr)) {
            $this->valid = true;
        }
    }


}