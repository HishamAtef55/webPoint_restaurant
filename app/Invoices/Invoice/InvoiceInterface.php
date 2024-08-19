<?php

namespace App\Invoices\Invoice;

use Illuminate\Http\Request;


interface InvoiceInterface
{

    /**
     * @param array $params
     * store
     *
     * @return void
     */
    public function create(
        array $params
    );
}
