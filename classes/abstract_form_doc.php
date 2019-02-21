<?php
require_once 'basic_doc.php';

/**
 * undocumented class
 */
abstract class FormDoc extends basicDoc
{

    protected function startContainer()
    {
        echo "<div class='col-sm-9 col-md-7 col-lg-5 mx-auto'>
        <div class='card card-signin my-5'>
        <div class='card-body'>";
    }
    protected function endContainer()
    {
        echo "</div>
        </div>
        </div>";
    }

    protected function startForm(String $action = "index.php", String $methode = "POST")
    {
        echo "<form class='form-group' action='$action' method='$methode'>";
    }
    protected function endForm()
    {
        echo "</form>";   
    }

    protected function formField(String $type, String $name, String $placeHolder,String $value,String $error) 
    {
        echo "<div class='form-group'>
        <input class='form-control' type='". $type ."' name='". $name ."' placeholder='". $placeHolder ."' value='". $value ."'>
        <small class='form-text text-danger'>". $error ."</small>
        </div>";
    }

    protected function hiddenFormField()
    {
        echo"<input type='hidden' name='page' value='".$this->data['page']."'>";
    }

    protected function formButton(String $buttonText, String $buttonType = "primary")
    {
        echo "<button type='submit' class='btn btn-$buttonType btn-block'>$buttonText</button>";
    }

    protected function formTitle(String $title)
    {
        echo "<h5 class='card-title text-center'>$title</h5>
        <hr>";
    }
    protected function formErrorLine($message)
    {
        echo "<small class='form-text text-danger'>". $message ."</small>";
    }
}
