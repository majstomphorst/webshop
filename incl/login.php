<?php
require_once 'incl/data_storage.php';

function validateLoginForm()
{
    $data['email'] = test_input(getPostVar('email'));
    $data['password'] = test_input(getPostVar('password'));
    $data['valid'] = true;

    // check if field are empty
    if (empty($data['email'])) { $data['emailErr'] = "Email is required";}
    if (empty($data['password'])) { $data['passwordErr'] = "password is required";}

    if (isset($data['emailErr']) or isset($data['passwordErr'])) {
        $data['valid'] = false;
    }

    return $data;
}

function showLoginForm($data)
{
    echo '
    <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
          
            <h5 class="card-title text-center">Login</h5>
            <hr>
            <p class="text-danger text-center">' . getArrayVar($data, 'LoginErr') . '</p>

            <form class="form-group" action="index.php" method="POST">

                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="text" name="email" value="' . getArrayVar($data, 'email') . '"placeholder="Email">
                        </div> <!-- input-group.// -->
                        <small class="form-text text-danger">' . getArrayVar($data, 'emailErr') . '</small>
                    </div> <!-- form-group// -->


                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="password" name="password" value="' . getArrayVar($data, 'password') . '"placeholder="Password">
                        </div> <!-- input-group.// -->
                        <small class="form-text text-danger">' . getArrayVar($data, 'passwordErr') . '</small>
                    </div> <!-- form-group// -->

                    <input type="hidden" name="page" value="login">

              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Login</button>          
            
              </form>
        </div>
        </div>
      </div>
    </div>
  </div>
    ';
}