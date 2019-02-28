<?php
require_once 'incl/session_manager.php';
require_once 'controllers/page_controller.php';
require_once 'incl/crud.php';

$pageController = new PageController();
$pageController->handleRequest();