<?php
function showHome($data)
{
    echo '
    <div class="container">
    <div class="card shadow my-5">
        <div class="card-body p-5">
                
                    <h1 class="font-weight-light text-center">Your ar on page: '. ucfirst($data['page']) .'</h1>
                    <p>
                        Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard
                        proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters
                        nam en
                        ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd
                        maar is
                        ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren \'60 populair
                        geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door
                        desktop
                        publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.
                    </p>';

            echo '</div>'; // card-body p-5
        echo '</div>'; // card border-0 shadow my-5
    echo '</div>'; // container
}
