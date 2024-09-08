<?php

namespace App\Movements\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use App\Models\Stock\Exchange;
use App\Models\Stock\Purchases;
use App\Models\Stock\MaterialHalk;
use App\Models\Stock\ExchangeDetails;
use App\Models\Stock\MaterialHalkItem;
use App\Models\Stock\MaterialTransfer;
use App\Models\Stock\PurchasesDetails;
use App\Models\Stock\MaterialHalkItemDetails;
use App\Models\Stock\MaterialMovementDetails;
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
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteExchangeMovement(
        Exchange $exchange,
        MaterialMovementDetails $details,
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
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteTransferFromMovement(
        MaterialTransfer $transfer,
        MaterialMovementDetails $details,
    ): bool;

    /**
     * deleteTransferToMovement
     * 
     * @param MaterialTransfer $transfer
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteTransferToMovement(
        MaterialTransfer $transfer,
        MaterialMovementDetails $details,
    ): bool;

    /**
     * deleteHalkMovement
     * 
     * @param MaterialHalk $material_halk
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteHalkMovement(
        MaterialHalk $material_halk,
        MaterialMovementDetails $details,
    ): bool;

    /**
     * deleteHalkItemMovement
     * 
     * @param MaterialHalkItem $halk_item,alk
     * @param MaterialHalkItemDetails $details
     * @return bool
     */
    public function deleteHalkItemMovement(
        MaterialHalkItem $halk_item,
        MaterialHalkItemDetails $details,
    ): bool;
}
