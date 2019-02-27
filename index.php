<?php
require_once 'incl/session_manager.php';
require_once 'controllers/page_controller.php';
require_once 'incl/crud.php';

$pageController = new PageController();

$pageController->handleRequest();

// romve everything
exit();

/* JH: Verplaats naar PageModel */

// get the form data that is posted to the server or returns the default
function getPostVar($key, $default = '')
{
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

/* JH: Verplaats naar PageModel */
// extracts the requested page (string) or the default
function getUrlVar($key, $default = '')
{
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}

// pulls the data from a array by its key of returns the default
function getArrayVar($arry, $key, $default = '')
{
    return isset($arry[$key]) ? $arry[$key] : $default;
}

/* JH: Verplaats naar PageModel */
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }