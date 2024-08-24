<?php

namespace App\Invoices\Invoice;

use Illuminate\Http\Request;
use App\Models\Stock\Purchases;


interface InvoiceInterface
{

    /**
     * create
     * @param array $params
     *
     * @return bool
     */
    public function create(
        array $params
    ): bool;


    /**
     * update
     * @param array $params
     * @param $purchase
     *
     * @return bool
     */
    public function update(
        array $params,
        $purchase
    ): bool;

    /**
     * delete
     * @param Purchases $purchase
     * @param   int $id
     *
     * @return bool
     */
    public function delete(
        Purchases $purchase,
        int $id
    ): bool;
}
