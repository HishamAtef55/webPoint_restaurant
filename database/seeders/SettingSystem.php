<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service_tables;
use App\Models\ToGo;
use App\Models\Delavery;
use App\Models\Others;
use App\Models\Printers;

class SettingSystem extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run(){
         Service_tables::create([
             'branch'=>'1',
             'fast_checkout'=>'1',
             'print_invoic'=>'1',
             'reser_recipt'=>'1',
             'invoice_payment'=>'1',
             'payment_teble'=>'1',
             'invoic_teble'=>'1',
             'end_teble'=>'1',
             'vou_copon'=>'1',
             'mincharge_screen'=>'1',
             'display_table'=>'1',
             'receipt_checkout'=>'1',
             'receipt_send'=>'1',
             'slip_all'=>'1',
             'slip_copy'=>'1',
             'pr_reservation'=>'1',
             'car_receipt'=>'1',
             'print_slip'=>'1',
             'tax_service'=>'0',
             'r_bank'=>'0',
             'printers_input'=>'Cash',
             'invoic_copies'=>'1',
             'min_charge'=>'0',
             'service_ratio'=>'0',
             'tax'=>'0',
             'discount_tax_service'=>'0',
         ]);
         ToGo::create([
             'branch'=>'1',
             'print_slip'=>'1',
             'print_togo'=>'1',
             'display_checkout_screen'=>'1',
             'print_reservation_receipt'=>'1',
             'print_invice'=>'1',
             'fast_check'=>'1',
             'convert_togo_table'=>'1',
             'invoice_copies'=>'1',
             'printer'=>'Cash',
             'service_ratio'=>'0',
             'tax'=>'0',
             'discount_tax_service'=>'0'
         ]);
         Delavery::create([
             'branch'=>'1',
             'tax'=>'0',
             'discount_tax_service'=>'1',
             'type_ser'=>'1',
             'ser_ratio'=>'0',
             'print_slip'=>'1',
             'user_slip'=>'1',
             'print_pilot_slip'=>'1',
             'printer'=>'Cash',
             'pilot_copies'=>'1',
             'Pay_copies'=>'1',
             'print_invoice'=>'1'
         ]);
         Others::create([
             'branch'=>1,
             'close_day' =>'07:00',
         ]);
         Printers::create([
             'branch_id'=> 1,
             'printer' => 'Cash',
             'active' => 1
         ]);
     }
}
