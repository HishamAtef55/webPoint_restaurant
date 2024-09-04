<?php

namespace App\Movements\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use App\Models\Stock\Exchange;
use App\Models\Stock\Purchases;
use App\Models\Stock\ExchangeDetails;
use App\Models\Stock\MaterialTransfer;
use App\Models\Stock\PurchasesDetails;
use App\Models\Stock\MaterialTransferDetails;

interface MovementInterface
{

    /**
     * create
     * @param Section|Store $model
     * @return bool
     */
    public function create(
        Section|Store $model,
    ): bool;

    /** 
     * delete
     * @param Purchases $purchases
     * @param PurchasesDetails $details
     * @return bool
     */
    public function deletePurchaseMovement(
        Purchases $purchases,
        int $id,
    ): bool;

    /**
     * deleteExchangeMovement
     * @param Exchange $exchange
     * @param ExchangeDetails $details
     * @return bool
     */
    public function deleteExchangeMovement(
        Exchange $exchange,
        ExchangeDetails $details,
    ): bool;


    /**
     * createTransferFromMovement
     * @param Section|Store $model
     * @return bool
     */
    public function createTransferFromMovement(
        Section|Store $model
    ): bool;

    /**
     * createTransferToMovement
     * @param Section|Store $model
     * @return bool
     */
    public function createTransferToMovement(
        Section|Store $model
    ): bool;

    /**
     * deleteTransferFromMovement
     * 
     * @param MaterialTransfer $transfer
     * @param MaterialTransferDetails $details
     * @return bool
     */
    public function deleteTransferFromMovement(
        MaterialTransfer $transfer,
        MaterialTransferDetails $details,
    ): bool;

    /**
     * deleteTransferToMovement
     * 
     * @param MaterialTransfer $transfer
     * @param MaterialTransferDetails $details
     * @return bool
     */
    public function deleteTransferToMovement(
        MaterialTransfer $transfer,
        MaterialTransferDetails $details,
    ): bool;
}
