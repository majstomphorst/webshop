<?php
include_once "classes/basic_doc.php";

class Top5Doc extends basicDoc
{
    public function __construct($model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }


    public function mainContent()
    {
        echo '
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
        foreach ($this->model->products as $product) {
            $this->showRow($product, $rank);
            $rank++;
        }

        echo '</tbody>
        </table>';
    }

    public function showRow($product, $rank)
    {
        echo '
    <tr>
      <th scope="row">' . $rank . '</th>
      <td>
        <a href="?page=detailProduct&id=' . $product['product_id'] . '" title="button">' . $product['name'] . '</a>
      </td>
      <td>' . $product['sum_amount'] . '</td>
    </tr>';

    }

}
