<?php 
$plainText = 'abc';
$hash = password_hash($plainText, PASSWORD_DEFAULT);




if (password_verify($plainText, $hash)) {
    var_dump($plainText);
    var_dump($hash);
    echo 'Password is valid!';
} else {
    var_dump($plainText);
    var_dump($has);
    echo 'Invalid password.';
}

?>
