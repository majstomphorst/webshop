<?php

// check if the form is correct
function validateForm()
{
  $data = array();
  $data['name'] = test_input(getPostVar('name'));
  $data['email'] = test_input(getPostVar('email'));
  $data['text'] = test_input(getPostVar('text'));

  if (empty($data['name'])) {$data['nameErr'] = "Name is required";}
  if (empty($data['email'])) {$data['emailErr'] = "Email is required";}
  if (empty($data['text'])) {$data['textErr'] = "Text is required";}

  if (!isset($data['nameErr']) && !isset($data['emailErr']) &&
      !isset($data['textErr'])) {
      $data['valid'] = true;
  }

  return $data;
}

function showForm($data)
{
  if (getArrayVar($data,'valid',false)) {
    echo '
    <div class="container">
      <div class="alert alert-success" role="alert">
      <strong>Aww yeah,</strong> you successfully filled out  the form you\'re AWSOME! <br>
      Your name is: ' . getArrayVar($data, 'name') . '<br>
      Your email is: ' . getArrayVar($data, 'email') . '<br>
      Your text is: ' . getArrayVar($data, 'text') . '
      </div>
    </div>';

    unset($data['name'],$data['email'],$data['text'],$data['valid']);
  }

  echo '
    <div class="container">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
          
            <h5 class="card-title text-center">Contact</h5>
            <hr>
            <p class="text-danger text-center"></p>

            <form class="form-group" action="index.php" method="POST">

                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" name="name" placeholder="Your name..." value="' . getArrayVar($data, 'name') . '">
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'nameErr') . '</small>
                </div> <!-- form-group// -->

                <div class="form-group">
                    <div class="input-group">
                    <input class="form-control" type="text" name="email" placeholder="Your email..." value="' . getArrayVar($data, 'email') . '">
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'emailErr') . '</small>
                </div> <!-- form-group// -->

                <div class="form-group">
                    <div class="input-group">
                    <textarea class="form-control" name="text" placeholder="Write something..">' . getArrayVar($data, 'text') . '</textarea>
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'textErr') . '</small>
                </div> <!-- form-group// -->

                <input type="hidden" name="page" value="contact">

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div> <!-- form-group// -->

            </form>
          </div>
        </div>
      </div>
  </div>
    ';
}
