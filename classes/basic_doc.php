<?php
require_once 'html_doc.php';

class basicDoc extends htmlDoc
{
    protected $model;

    public function __construct(PageModel $model)
    {
        $this->model = $model;
    }

    // Override function from htmlDoc
    protected function headerContent()
    {
        $this->title();
        $this->metaAuthor();
        $this->metaTags();
        $this->cssLinks();
    }

    // Override function from htmlDoc
    protected function bodyContent()
    {
        $this->bodyHeader();
        $this->mainMenu();
        $this->startContainer();
        $this->mainContent();
        $this->endContainer();
        $this->bodyFooter();
        $this->inclScripts();
    }

    protected function title()
    {
        echo "<title>My website - " . $this->model->requested_page . "</title>";
    }

    private function metaAuthor()
    {
        echo "<meta name='author' content='Maxim Stomphorst'/>";
    }

    private function metaTags()
    {
        echo "<meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
    }

    private function cssLinks()
    {
        echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css' integrity='sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS' crossorigin='anonymous'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
            <link rel='stylesheet' href='assets/css/mystyle.css'>";
    }

    private function bodyHeader()
    {
        return 0;
    }

    private function mainMenu()
    {
        echo "<nav class='navbar navbar-expand-lg navbar-light bg-light static-top shadow'>
                
                <a class='navbar-brand' href='index.php?page=home'>
                    <img src='assets/images/logo.png' width='30' height='30' alt=''>
                </a>
                <a class='navbar-brand' href='#'>Educom</a>
                
                <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarTogglerDemo02' aria-controls='navbarTogglerDemo02' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>

                <div class='collapse navbar-collapse justify-content' id='navbarTogglerDemo02'>
                    <ul class='navbar-nav mr-auto'>";
                    foreach ($this->model->menuLeft as $pageLink => $buttonText) {
                        $this->showMenuItem($pageLink, $buttonText);
                    }
            echo   "</ul>";
        echo   '</div>';
        echo   "<div class='navbar-nav collapse navbar-collapse' id='navbarTogglerDemo02'>
                    <ul class='navbar-nav ml-auto'>";  
                    foreach ($this->model->menuRight as $pageLink => $buttonText) {
                                    $this->showMenuItem($pageLink, $buttonText);
                                }
        echo        '</ul>
                </div>';
       echo "</nav>";
    }

    private function showMenuItem($pageLink, $buttonText)
    {
        if ($pageLink == $this->model->requested_page) {
            echo "<li class='nav-item'>
                <a class='nav-link nav-link active' href='index.php?page=" . $pageLink . "'>" . $buttonText . "</a>
                </li>";
        } else {
            echo "<li class='nav-item'>
            <a class='nav-link' href='index.php?page=" . $pageLink . "'>" . $buttonText . "</a>
            </li>";
        }
    }

    protected function startContainer()
    {
        echo "<div class='container'>
            <div class='card shadow my-5'>
            <div class='card-body p-5'>";
    }

    protected function mainContent()
    {
        echo "<p>mainContent basic_doc.php</p>";
    }

    protected function endContainer()
    {
        echo "</div>
            </div>
            </div>";
    }

    private function bodyFooter()
    {
        echo "<footer class='py-3'>
            <p class='m-0 text-center'>Copyright &copy; 2019, Maxim Stomphorst</p>
            </footer>";
    }

    private function inclScripts()
    {
        echo "<script src ='assets/js/jquery-3.3.1.js'></script>
            <script src='assets/js/ratings.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js' integrity='sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut'
                crossorigin='anonymous'></script>
            <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js' integrity='sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k'
                crossorigin=;anonymous'></script>";
    }
}
