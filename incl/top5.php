<?php
include_once 'incl/database.php';

function ShowTop5($data)
{
    echo '
        <div class="container">
            <div class="card shadow my-5">
                <div class="card-body p-5">

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>

                            <th scope="col">Name</th>
                            <th scope="col">Amount sold</th>
                        </tr>
                        </thead>
                        <tbody>';
                        $rank = 1;
                        foreach ($data['top5'] as $product) {
                            showRow($product,$rank);
                            $rank++;
                        }
                            
            echo '      </tbody>
                    </table>
                    </div>
                </div>
            </div>';      
}

function showRow($product,$rank) 
{
    echo '
    <tr>
      <th scope="row">'. $rank .'</th>
      <td>
        <a href="?page=detailProduct&id='. $product['product_id'] .'" title="button">'. $product['name'] .'</a>
      </td>
      <td>'. $product['sum_amount'] .'</td>
    </tr>';

}