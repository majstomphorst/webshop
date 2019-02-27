<?php
require_once "basic_doc.php";

/**
 * undocumented class
 */
abstract class productDoc extends basicDoc
{
    protected function showReatingPanel() /* JH: Wat is een 'Reating?' ik kan alleen vinden dat het een achternaam is */
    {
        echo"
    <!-- Rating Stars Box -->
    <h5>AVG:<span class='avgRating'></span></h5>
    <h5>Your rating:<span class='yourRating'></span></h5>
    <div class='rating-stars text-center'>
        <ul id='stars'>
            <li class='star' title='Poor' data-value='1'>
                <i class='fa fa-star fa-fw'></i>
            </li>
            <li class='star' title='Fair' data-value='2'>
                <i class='fa fa-star fa-fw'></i>
            </li>
            <li class='star' title='Good' data-value='3'>
                <i class='fa fa-star fa-fw'></i>
            </li>
            <li class='star' title='Excellent' data-value='4'>
                <i class='fa fa-star fa-fw'></i>
            </li>
            <li class='star' title='WOW!!!' data-value='5'>
                <i class='fa fa-star fa-fw'></i>
            </li>
        </ul>
    </div>
    ";
    }
}