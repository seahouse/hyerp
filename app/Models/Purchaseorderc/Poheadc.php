<?php

namespace App\Models\Purchaseorderc;

use Illuminate\Database\Eloquent\Model;

class Poheadc extends Model
{
    //
    protected $fillable = [
        'interchange_sender_id',
        'interchange_receiver_id',
        'interchange_datetime',
        'interchange_control_number',
        'test_indicator',
        'data_interchange_datetime',
        'transaction_set_control_no',
        'transaction_set_purpose_code',
        'purchase_order_number',
        'release_number',
        'po_extract_date',
        'currency_code',
        'exchange_rate',
        'po_type',
        'product_type',
        'weave_type',
        'salesman_name',
        'origin_country',
        'export_country',
        'destination_country',
        'incoterms',
        'incoterms_code',
        'payment_days',
        'payment_term',
        'manufacturing_method',
        'packing_instruction',
        'remark',
        'payee_name',
        'payee_code',
        'supplier_name',
        'supplier_code',
        'ship_to',
        'factory_code',
        'ship_to_address1',
        'ship_to_address2',
        'country_of_consignee',
        'buyer_name',
        'buyer_code',
        'garment_customer_name',
        'garment_customer_code',
        'number_of_line_items',
    ];
}
