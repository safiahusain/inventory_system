<?php

namespace App\Helper;

use App\Sale;
use App\Purchase;
use App\MilkSpoilation;
use Illuminate\Support\Facades\Log;

class Helper
{
    public static function update_stock()
    {
        try
        {
            $sale               =   Sale::get();
            $purchase           =   Purchase::get();
            $milk_spoilation    =   MilkSpoilation::get();
            $cow_stock          =   0;
            $buffalo_stock      =   0;
            $wanda_stock        =   0;
            $sale_array         =   [];
            $purchase_array     =   [];
            $spoilation_array   =   [];
            $stock              =   [];

            if(count($sale) >  0)
            {
                $sale_cow_qty       =   0;
                $sale_buffalo_qty   =   0;
                $sale_wanda_qty     =   0;

                foreach ($sale as $key => $value)
                {
                    $data   =   $value->data    ?   json_decode($value->data)  :   null;

                    if($data)
                    {
                        foreach ($data as $d_key => $d_value)
                        {
                            $qty_data   =   $d_value->qty;
                            $qty        =   0;

                            $m_qty      =   $qty_data->m_qty    ?   $qty_data->m_qty    :   0;
                            $e_qty      =   $qty_data->e_qty    ?   $qty_data->e_qty    :   0;
                            $qty        =   $m_qty  +   $e_qty;

                            if($d_key   ==  'cow')
                            {
                                $sale_cow_qty  +=  $qty;
                            }
                            else if($d_key   ==  'buffalo')
                            {
                                $sale_buffalo_qty  +=  $qty;
                            }
                            else
                            {
                                $sale_wanda_qty  +=  $qty;
                            }
                        }
                    }
                }

                $sale_array  +=  [
                    'cow'       =>  $sale_cow_qty,
                    'buffalo'   =>  $sale_buffalo_qty,
                    'wanda'     =>  $sale_wanda_qty,
                ];
            }

            if(count($purchase) >  0)
            {
                $purchase_cow_qty        =   0;
                $purchase_buffalo_qty    =   0;
                $purchase_wanda_qty      =   0;

                foreach ($purchase as $key => $value)
                {
                    $data   =   $value->data    ?   json_decode($value->data)  :   null;

                    if($data)
                    {
                        foreach ($data as $d_key => $d_value)
                        {
                            if($d_value->status)
                            {
                                $qty_data   =   $d_value->qty;
                                $qty        =   0;

                                if($d_key   !=  'wanda')
                                {
                                    $m_qty      =   $qty_data->m_qty    ?   $qty_data->m_qty    :   0;
                                    $e_qty      =   $qty_data->e_qty    ?   $qty_data->e_qty    :   0;
                                    $qty        =   $m_qty  +   $e_qty;

                                    if($d_key   ==  'cow')
                                    {
                                        $purchase_cow_qty  +=  $qty;
                                    }
                                    else
                                    {
                                        $purchase_buffalo_qty  +=  $qty;
                                    }
                                }
                                else
                                {
                                    $qty                =   $qty_data;
                                    $purchase_wanda_qty +=  $qty;
                                }
                            }
                        }
                    }
                }

                $purchase_array  +=  [
                    'cow'       =>  $purchase_cow_qty,
                    'buffalo'   =>  $purchase_buffalo_qty,
                    'wanda'     =>  $purchase_wanda_qty,
                ];
            }

            if(count($milk_spoilation ) >  0)
            {
                $spoilation_cow_qty        =   0;
                $spoilation_buffalo_qty    =   0;
                $spoilation_wanda_qty      =   0;

                foreach ($milk_spoilation  as $key => $value)
                {
                    $data   =   $value->data    ?   json_decode($value->data)  :   null;

                    if($data)
                    {
                        foreach ($data as $d_key => $d_value)
                        {
                            $qty_data   =   $d_value->qty;
                            $qty        =   0;

                            if($d_key   ==  'cow')
                            {
                                $spoilation_cow_qty  +=  $qty_data;
                            }
                            elseif($d_key   ==  'buffalo')
                            {
                                $spoilation_buffalo_qty  +=  $qty_data;
                            }
                            else
                            {
                                $spoilation_wanda_qty  +=  $qty_data;
                            }
                        }
                    }
                }

                $spoilation_array  +=  [
                    'cow'       =>  $spoilation_cow_qty,
                    'buffalo'   =>  $spoilation_buffalo_qty,
                    'wanda'     =>  $spoilation_wanda_qty,
                ];
            }

            $default_stock      =   config('default.stock');
            $sale_array         =   count($sale_array)      ?   $sale_array         :   config('default.stock');
            $purchase_array     =   count($purchase_array)  ?   $purchase_array     :   config('default.stock');
            $spoilation_array   =   count($spoilation_array)?   $spoilation_array   :   config('default.stock');


            foreach ($default_stock as $key => $value)
            {
                if($key   ==  'cow')
                {
                    $cow_stock  =  $purchase_array[$key]-($spoilation_array[$key]+$sale_array[$key]);
                }
                elseif($key   ==  'buffalo')
                {
                    $buffalo_stock  =  $purchase_array[$key]-($spoilation_array[$key]+$sale_array[$key]);
                }
                else
                {
                    $wanda_stock  =  $purchase_array[$key]-($spoilation_array[$key]+$sale_array[$key]);
                }
            }

            $stock  =  [
                'cow'       =>  $cow_stock,
                'buffalo'   =>  $buffalo_stock,
                'wanda'     =>  $wanda_stock,
            ];
        }
        catch (\Throwable $th)
        {
            Log::critical([
                'message'       =>  'error in updating stock',
                'error'         =>  $th->getMessage(),
                "line_number"   =>  $th->getLine(),
            ]);
        }

        return  ($stock);
    }

    // public static function update_wallet_customer($user,$currency,$amount,$type,$transaction,$tx_type,$is_pending)
    // {
    //     try
    //     {
    //         $currency           =   strtolower($is_pending  ?   $currency."_pending"    :   $currency);
    //         $wallet             =   CustomerWallet::where('customer_id',$user->id)->first();
    //         $previous_balance   =   $wallet->$currency;
    //         $update_data        =   [
    //             $currency   =>  $wallet->$currency + $amount
    //         ];

    //         if($type    ==  'decrement')
    //         {
    //             $update_data    =   [
    //                 $currency   =>  $wallet->$currency - $amount
    //             ];
    //         }

    //         $updated            =   $wallet->update($update_data);
    //         $new_balance        =   $wallet->$currency;

    //         if ($updated)
    //         {
    //             unset($transaction->data);
    //             $created    =   CustomerWalletHistory::create([
    //                 'customer_id'   =>  $user->id,
    //                 'wallet_id'     =>  $wallet->id,
    //                 'ref_id'        =>  $transaction->id,
    //                 'order_id'      =>  (in_array($tx_type,['deposit','share']))    &&  isset($transaction->order_id)
    //                                     ?   $transaction->order_id
    //                                     :   null,
    //                 'currency'      =>  $currency,
    //                 'amount'        =>  $amount,
    //                 'previous'      =>  $previous_balance,
    //                 'current'       =>  $new_balance,
    //                 'type'          =>  $type,
    //                 'tx_type'       =>  $tx_type,
    //                 'data'          =>  json_encode($transaction)
    //             ]);
    //         }
    //     }
    //     catch (\Throwable $th)
    //     {
    //         Log::critical([
    //             "message"       =>  "Wallet Update Error",
    //             'Error'         =>  $th->getMessage(),
    //             'line_number'   =>  $th->getLine()
    //         ]);

    //         $updated = false;
    //         $created = false;
    //     }

    //     return  $updated && $created;
    // }
}
?>
