<?php

class htmlDoc
{
    public function show()
    {
        $this->beginDoc();
        $this->beginHeader();
        $this->headerContent();
        $this->endHeader();
        $this->beginBody();
        $this->bodyContent();
        $this->endBody();
        $this->endDoc();
    }

    private function beginDoc()
    {
        echo "<!DOCTYPE html>\n<html lang=en>\n";
    }

    private function beginHeader()
    {
        echo "<head>\n";
    }

    protected function headerContent()
    {
        echo "<title>Mijn eerste class</title>\n";
    }

    private function endHeader()
    {
        echo "</head>\n";
    }

    private function beginBody()
    {
        echo "<body>\n";
    }

    protected function bodyContent()
    {
        echo "<h1>Mijn eerste class</h1>\n";
    }

    private function endBody()
    {
        echo "</body>\n";
    }

    private function endDoc()
    {
        echo "</html>\n";
    }

}
