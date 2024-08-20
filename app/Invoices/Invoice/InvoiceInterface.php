<?php

namespace App\Invoices\Invoice;

use Illuminate\Http\Request;


interface InvoiceInterface
{

    /**
     * @param array $params
     * create
     *
     * @return bool
     */
    public function create(
        array $params
    ): bool;


    /**
     * @param array $params
     * @param $purchase
     * update
     *
     * @return bool
     */
    public function update(
        array $params,
        $purchase
    ): bool;
}
