<?php

function validateRegisterForm()
{
    $data = array();
    $data['name'] = test_input(getPostVar('name'));
    $data["email"] = test_input(getPostVar('email'));
    $data["password"] = test_input(getPostVar('password'));
    $data["passwordCheck"] = test_input(getPostVar('passwordCheck'));

    // check if field are empty
    if (empty($data['name'])) {$data['nameErr'] = "Name is required";}
    if (empty($data['email'])) {$data['emailErr'] = "Email is required";}
    if (empty($data['password'])) {$data['passwordErr'] = "password is required";}
    if (empty($data['passwordCheck'])) {$data['passwordCheckErr'] = "password is required";}

    if (isset($data['nameErr']) or isset($data['passwordCheckErr']) or
        isset($data['emailErr']) or isset($data['passwordErr'])) {

        $data['valid'] = false;
        return $data;
    }

    // check if provided passwords are the same
    if ($data["password"] != $data["passwordCheck"]) {
        $data['passwordNotEqualErr'] = 'Your passwords are not equal!';
        $data['valid'] = false;
    }

    return $data;
}

function showRegisterForm($data)
{
    echo '
    <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
          
            <h5 class="card-title text-center">Register</h5>
            <hr>
            <p class="text-danger text-center">' . getArrayVar($data, 'userExists') . '</p>

            <form class="form-group" action="index.php" method="POST">

                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" name="name" value="' . getArrayVar($data, 'name') . '" placeholder="Name">
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'nameErr') . '</small>
                </div> <!-- form-group// -->

                <div class="form-group">
                    <div class="input-group">
                      <input class="form-control" type="email" name="email" value="' . getArrayVar($data, 'email') . '" placeholder="Email">
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'emailErr') . '</small>
                </div> <!-- form-group// -->

                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="password" name="password" value="' . getArrayVar($data, 'password') . '" placeholder="Password">
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'passwordErr') . '</small>
                </div> <!-- form-group// -->

                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="password" name="passwordCheck" value="' . getArrayVar($data, 'passwordCheck') . '" placeholder="Password">
                    </div> <!-- input-group.// -->
                    <small class="form-text text-danger">' . getArrayVar($data, 'passwordCheckErr') . '</small>
                    <small class="form-text text-danger">' . getArrayVar($data, 'passwordNotEqualErr') . '</small>
                </div> <!-- form-group// -->

                <input type="hidden" name="page" value="register">

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div> <!-- form-group// -->

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
    ';
}