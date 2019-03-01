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
<nav class="navbar navbar-dark bg-primary navbar-fixed-top">
            <div class="container">
                <ul class="nav navbar-nav NAVBAR-RIGHT">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#speakers">Speakers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#schedule">Schedule</a>
                    </li>
                </ul>
            </div>
        </nav>
