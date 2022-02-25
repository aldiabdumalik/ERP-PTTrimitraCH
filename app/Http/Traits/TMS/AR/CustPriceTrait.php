<?php
namespace App\Http\Traits\TMS\AR;

use Exception;
use Illuminate\Support\Facades\DB;

trait CustPriceTrait {
    protected function triggerSO($data)
    {
        try {
            for ($x=0; $x < count($data); $x++) {
                $item_i = $data[$x]['item_code'];
                $act_date = $data[$x]['active_date'];
                $cust = $data[$x]['cust_id'];
                $price_new = $data[$x]['price_new'];

                if ($data[$x]['is_stock'] == 1) {
                    // Stock TMS
                    DB::table('db_tbs.item')
                        ->where('ITEMCODE', $data[$x]['item_code'])
                        ->update([
                            'PRICE' => $data[$x]['price_new']
                        ]);
                    // Stock TBS
                    DB::table('tch_tbs.item')
                        ->where('itemcode', $data[$x]['item_code'])
                        ->update([
                            'price' => $data[$x]['price_new']
                        ]);
                }

                $so = DB::table('db_tbs.entry_so_tbl as so')
                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                        $join->on('sso.so_header', '=', 'so.so_header');
                        $join->on('sso.item_code', '=', 'so.item_code');
                    })
                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                        $join->on('sj.so_no', '=', 'so.so_header');
                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                        $join->on('sj.item_code', '=', 'so.item_code');
                    })
                    ->where('so.cust_id', $cust)
                    ->where('so.written_date', '>=', $act_date)
                    ->where('so.item_code', $item_i)
                    // ->whereNull('sj.invoice_date')
                    ->select([
                        'so.so_header',
                        'so.so_period',
                        'so.tax_rate as so_tax_rate',
                        'so.item_code as so_item_code',
                        'so.price as so_price',
                        'so.qty_so as so_qty_so',
                        'so.sub_amount as so_sub_amount',
                        'so.tot_vat as so_tot_vat',
                        'so.total_amount as so_total_amount',
                        'sso.sso_header as sso_header',
                        'sj.do_no as sj_number',
                    ])
                    ->get();

                if ($so->isNotEmpty()) {
                    foreach ($so as $s) {
                        $so_header = $s->so_header;

                        DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            ->where('so.item_code', $item_i)
                            ->where('so.written_date', '>=', $act_date)
                            // ->whereNull('sj.invoice_date')
                            ->update([
                                'so.price' => $price_new
                            ]);
                        $new_so_tms = DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            // ->whereNull('sj.invoice_date')
                            ->select([
                                'so.so_header',
                                'so.so_period',
                                'so.tax_rate as so_tax_rate',
                                'so.item_code as so_item_code',
                                'so.price as so_price',
                                'so.qty_so as so_qty_so',
                                'so.sub_amount as so_sub_amount',
                                'so.tot_vat as so_tot_vat',
                                'so.total_amount as so_total_amount',
                                'sso.sso_header as sso_header',
                                'sj.do_no as sj_number',
                            ])
                            ->get();
                        $i_so = 0;
                        $numItems = count($new_so_tms);
                        $sub_amt = 0;
                        foreach($new_so_tms as $i => $s){
                            $sub_amt += $s->so_price * $s->so_qty_so;
                            if(++$i_so === $numItems) {
                                $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                $total_amount = $sub_amt + $tot_vat;

                                DB::table('db_tbs.entry_so_tbl as so')
                                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                        $join->on('sso.so_header', '=', 'so.so_header');
                                        $join->on('sso.item_code', '=', 'so.item_code');
                                    })
                                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                        $join->on('sj.so_no', '=', 'so.so_header');
                                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                                        $join->on('sj.item_code', '=', 'so.item_code');
                                    })
                                    ->where('so.so_header', $so_header)
                                    // ->whereNull('sj.invoice_date')
                                    ->update([
                                        'so.sub_amount' => $sub_amt,
                                        'so.tot_vat' => $tot_vat,
                                        'so.total_amount' => $total_amount
                                    ]);
                            }
                        }
                        // End update SO
                        // Start update SSO
                        if ($data[$x]['is_sso'] == 1) {
                            $cvrt = $act_date;
                            $custprice_id = $cust.'.'.$cvrt;
                            DB::table('db_tbs.entry_sso_tbl as sso')
                                ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                    $join->on('sj.sso_no', '=', 'sso.sso_header');
                                    $join->on('sj.item_code', '=', 'sso.item_code');
                                })
                                ->where('sso.so_header', $so_header)
                                ->where('sso.item_code', $item_i)
                                // ->whereNull('sj.invoice_date')
                                ->whereRaw('DATE(sso.created_date) >= ?', [$act_date])
                                ->update([
                                    'sso.id_custprice' => $custprice_id
                                ]);
                        }
                        // End SSO
                        // Start SJ
                        if ($data[$x]['is_sj'] == 1) {
                            $cvrt = $act_date;
                            $custprice_id = $cust.'.'.$cvrt;
                            DB::table('db_tbs.entry_do_tbl as sj')
                                ->where('sj.so_no', $so_header)
                                ->where('sj.item_code', $item_i)
                                ->whereNull('sj.invoice_date')
                                ->whereRaw('DATE(sj.created_date) >= ?', [$act_date])
                                ->update([
                                    'sj.id_custprice' => $custprice_id
                                ]);
                        }
                        // End SJ
                    }
                }
                // END update db_tbs
                // Start tch_tbs
                $so_tch = DB::table('tch_tbs.soline as so_dtl')
                    ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                    ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.so_no', '=', 'so_dtl.so_no')
                    ->leftJoin('tch_tbs.sso_dtl as sso_dtl', function ($join){
                        $join->on('sso_dtl.so_no', '=', 'so_dtl.so_no');
                        $join->on('sso_dtl.itemcode', '=', 'so_dtl.itemcode');
                    })
                    ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                        $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                        $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                        $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                    })
                    ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                    ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                        $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                    })
                    ->where(function ($wh) use ($cust, $act_date, $item_i){
                        $wh->where('so_hdr.custcode', $cust);
                        $wh->where('so_hdr.written', '>=', $act_date);
                        $wh->where('so_dtl.itemcode', $item_i);
                        // $wh->whereNull('inv_dtl.do_no');
                    })
                    ->select([
                        'so_dtl.so_no',
                        'so_hdr.period as so_period',
                        'so_hdr.taxrate as so_tax_rate',
                        'so_dtl.itemcode as so_item_code',
                        'so_dtl.price as so_price',
                        'so_dtl.quantity as so_qty_so',
                        'so_hdr.sub_amt as so_sub_amount',
                        'so_hdr.tot_disc as so_tot_vat',
                        'so_hdr.tot_amt as so_total_amount',
                        'inv_dtl.do_no as inv_do'
                    ])
                    ->get();
                if ($so_tch->isNotEmpty()) {
                    foreach ($so_tch as $so) {
                        $so_header = $so->so_no;
                        // Update SO
                        if ($data[$x]['is_so'] == 1) {

                            DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                    $wh->where('so_dtl.so_no', $so_header);
                                    $wh->where('so_dtl.itemcode', $item_i);
                                    $wh->where('so_dtl.written', '>=', $act_date);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'so_dtl.price' => $price_new,
                                    'so_hdr.sub_amt' => $sub_amt,
                                    'so_hdr.tot_disc' => $tot_vat,
                                    'so_hdr.tot_amt' => $total_amount
                                ]);
                            $new_so_tbs = DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $act_date){
                                    $wh->where('so_dtl.so_no', $so_header);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'so_dtl.so_no',
                                    'so_hdr.period as so_period',
                                    'so_hdr.taxrate as so_tax_rate',
                                    'so_dtl.itemcode as so_item_code',
                                    'so_dtl.price as so_price',
                                    'so_dtl.quantity as so_qty_so',
                                    'so_hdr.sub_amt as so_sub_amount',
                                    'so_hdr.tot_disc as so_tot_vat',
                                    'so_hdr.tot_amt as so_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_so = 0;
                            $numItems = count($new_so_tbs);
                            $sub_amt = 0;
                            foreach($new_so_tbs as $i => $s){
                                $sub_amt += $s->so_price * $s->so_qty_so;
                                if(++$i_so === $numItems) {
                                    $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.soline as so_dtl')
                                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header){
                                            $wh->where('so_dtl.so_no', $so_header);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'so_hdr.sub_amt' => $sub_amt,
                                            'so_hdr.tot_disc' => $tot_vat,
                                            'so_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                        // End update SO
                        // Start update SSO
                        if ($data[$x]['is_sso'] == 1) {
                            $sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                    $wh->where('sso_dtl.so_no', $so_header);
                                    $wh->where('sso_dtl.itemcode', $item_i);
                                    $wh->where('sso_dtl.written', '>=', $act_date);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sso_dtl.so_no',
                                    'sso_dtl.sso_no as sso_no',
                                    'sso_hdr.period as sso_period',
                                    'sso_hdr.taxrate as sso_tax_rate',
                                    'sso_dtl.itemcode as sso_item_code',
                                    'sso_dtl.price as sso_price',
                                    'sso_dtl.quantity as sso_qty_sso',
                                    'sso_hdr.sub_amt as sso_sub_amount',
                                    'sso_hdr.tot_disc as sso_tot_vat',
                                    'sso_hdr.tot_amt as sso_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            if ($sso_tch->isNotEmpty()) {
                                foreach ($sso_tch as $s) {
                                    $sso_header = $s->sso_no;
                                    DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                            $wh->where('sso_dtl.so_no', $so_header);
                                            $wh->where('sso_dtl.itemcode', $item_i);
                                            $wh->where('sso_dtl.written', '>=', $act_date);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sso_dtl.price' => $price_new
                                        ]);
                                    $new_sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($sso_header){
                                            $wh->where('sso_hdr.sso_no', $sso_header);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->select([
                                            'sso_dtl.so_no',
                                            'sso_dtl.sso_no as sso_no',
                                            'sso_hdr.period as sso_period',
                                            'sso_hdr.sso_no as sso_no',
                                            'sso_hdr.taxrate as sso_tax_rate',
                                            'sso_dtl.itemcode as sso_item_code',
                                            'sso_dtl.price as sso_price',
                                            'sso_dtl.quantity as sso_qty_sso',
                                            'sso_hdr.sub_amt as sso_sub_amount',
                                            'sso_hdr.tot_disc as sso_tot_vat',
                                            'sso_hdr.tot_amt as sso_total_amount',
                                            'inv_dtl.do_no as inv_do'
                                        ])
                                        ->get();
                                    $i_sso = 0;
                                    $numItems = count($new_sso_tch);
                                    $sub_amt = 0;
                                    foreach($new_sso_tch as $i => $s){
                                        $sub_amt += $s->sso_price * $s->sso_qty_sso;
                                        if(++$i_sso === $numItems) {
                                            $tot_vat = $sub_amt * $s->sso_tax_rate / 100;
                                            $total_amount = $sub_amt + $tot_vat;
                                            DB::table('tch_tbs.sso_dtl as sso_dtl')
                                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                                })
                                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                                })
                                                ->where(function ($wh) use ($sso_header){
                                                    $wh->where('sso_hdr.sso_no', $sso_header);
                                                    // $wh->whereNull('inv_dtl.do_no');
                                                })
                                                ->update([
                                                    'sso_hdr.sub_amt' => $sub_amt,
                                                    'sso_hdr.tot_disc' => $tot_vat,
                                                    'sso_hdr.tot_amt' => $total_amount
                                                ]);
                                        }
                                    }
                                }
                            }
                        }
                        // End SSO
                        // Start SJ
                        if ($data[$x]['is_sj'] == 1) {
                            $do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                    $wh->where('sj_dtl.so_no', $so_header);
                                    $wh->where('sj_dtl.itemcode', $item_i);
                                    $wh->where('sj_dtl.written', '>=', $act_date);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sj_dtl.do_no',
                                    'sj_hdr.period as sj_period',
                                    'sj_hdr.taxrate as sj_tax_rate',
                                    'sj_dtl.itemcode as sj_item_code',
                                    'sj_dtl.price as sj_price',
                                    'sj_dtl.quantity as sj_qty',
                                    'sj_hdr.sub_amt as sj_sub_amount',
                                    'sj_hdr.tot_disc as sj_tot_vat',
                                    'sj_hdr.tot_amt as sj_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            if ($do_tch->isNotEmpty()) {
                                foreach ($do_tch as $s) {
                                    $do_no = $s->do_no;
                                    DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                            $wh->where('sj_dtl.so_no', $so_header);
                                            $wh->where('sj_dtl.itemcode', $item_i);
                                            $wh->where('sj_dtl.written', '>=', $act_date);
                                            $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sj_dtl.price' => $price_new
                                        ]);
                                    $new_do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($do_no){
                                            $wh->where('sj_hdr.do_no', $do_no);
                                            $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->select([
                                            'sj_dtl.do_no',
                                            'sj_hdr.period as sj_period',
                                            'sj_hdr.taxrate as sj_tax_rate',
                                            'sj_dtl.itemcode as sj_item_code',
                                            'sj_dtl.price as sj_price',
                                            'sj_dtl.quantity as sj_qty',
                                            'sj_hdr.sub_amt as sj_sub_amount',
                                            'sj_hdr.tot_disc as sj_tot_vat',
                                            'sj_hdr.tot_amt as sj_total_amount',
                                            'inv_dtl.do_no as inv_do'
                                        ])
                                        ->get();
                                    $i_do = 0;
                                    $numItems = count($new_do_tch);
                                    $sub_amt = 0;
                                    foreach($new_do_tch as $i => $s){
                                        $sub_amt += $s->sj_price * $s->sj_qty;
                                        if(++$i_do === $numItems) {
                                            $tot_vat = $sub_amt * $s->sj_tax_rate / 100;
                                            $total_amount = $sub_amt + $tot_vat;
                                            DB::table('tch_tbs.do_dtl as sj_dtl')
                                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                                })
                                                ->where(function ($wh) use ($do_no){
                                                    $wh->where('sj_hdr.do_no', $do_no);
                                                    // $wh->whereNull('inv_dtl.do_no');
                                                })
                                                ->update([
                                                    'sj_hdr.sub_amt' => $sub_amt,
                                                    'sj_hdr.tot_disc' => $tot_vat,
                                                    'sj_hdr.tot_amt' => $total_amount
                                                ]);
                                        }
                                    }
                                }
                            }
                        }
                        // END SJ
                    }
                }
            }
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function triggerDate($data)
    {
        try {
            for ($x=0; $x < count($data); $x++) {
                $item_i = $data[$x]['item_code'];
                $act_date = $data[$x]['active_date'];
                $cust = $data[$x]['cust_id'];
                $price_new = $data[$x]['price_new'];

                if ($data[$x]['is_stock'] == 1) {
                    // Stock TMS
                    DB::table('db_tbs.item')
                        ->where('ITEMCODE', $data[$x]['item_code'])
                        ->update([
                            'PRICE' => $data[$x]['price_new']
                        ]);
                    // Stock TBS
                    DB::table('tch_tbs.item')
                        ->where('itemcode', $data[$x]['item_code'])
                        ->update([
                            'price' => $data[$x]['price_new']
                        ]);
                }

                // SO TMS
                $so = DB::table('db_tbs.entry_so_tbl as so')
                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                        $join->on('sso.so_header', '=', 'so.so_header');
                        $join->on('sso.item_code', '=', 'so.item_code');
                    })
                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                        $join->on('sj.so_no', '=', 'so.so_header');
                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                        $join->on('sj.item_code', '=', 'so.item_code');
                    })
                    ->where('so.cust_id', $cust)
                    ->where('so.item_code', $item_i)
                    ->where(function ($where) use ($act_date){
                        $where->whereRaw('DATE(so.written_date) >= ?', [$act_date]);
                    })
                    // ->whereNull('sj.invoice_date')
                    ->select([
                        'so.so_header',
                        'so.so_period',
                        'so.tax_rate as so_tax_rate',
                        'so.item_code as so_item_code',
                        'so.price as so_price',
                        'so.qty_so as so_qty_so',
                        'so.sub_amount as so_sub_amount',
                        'so.tot_vat as so_tot_vat',
                        'so.total_amount as so_total_amount',
                        'sso.sso_header as sso_header',
                        'sj.do_no as sj_number',
                    ])
                ->get();

                // Start SO TMS
                if ($so->isNotEmpty()) {
                    foreach ($so as $s) {
                        $so_header = $s->so_header;
                        DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.cust_id', $cust)
                            ->where('so.item_code', $item_i)
                            ->where('so.so_header', $so_header)
                            ->where(function ($where) use ($act_date){
                                $where->whereRaw('DATE(so.written_date) >= ?', [$act_date]);
                            })
                            // ->whereNull('sj.invoice_date')
                            ->update([
                                'price' => $price_new
                            ]);
                        
                        $new_so_tms = DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            // ->whereNull('sj.invoice_date')
                            ->select([
                                'so.so_header',
                                'so.so_period',
                                'so.tax_rate as so_tax_rate',
                                'so.item_code as so_item_code',
                                'so.price as so_price',
                                'so.qty_so as so_qty_so',
                                'so.sub_amount as so_sub_amount',
                                'so.tot_vat as so_tot_vat',
                                'so.total_amount as so_total_amount',
                                'sso.sso_header as sso_header',
                                'sj.do_no as sj_number',
                            ])
                            ->get();
                        
                        $i_so = 0;
                        $numItems = count($new_so_tms);
                        $sub_amt = 0;
                        foreach($new_so_tms as $i => $s){
                            $sub_amt += $s->so_price * $s->so_qty_so;
                            if(++$i_so === $numItems) {
                                $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                $total_amount = $sub_amt + $tot_vat;
                                DB::table('db_tbs.entry_so_tbl as so')
                                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                        $join->on('sso.so_header', '=', 'so.so_header');
                                        $join->on('sso.item_code', '=', 'so.item_code');
                                    })
                                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                        $join->on('sj.so_no', '=', 'so.so_header');
                                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                                        $join->on('sj.item_code', '=', 'so.item_code');
                                    })
                                    ->where('so.so_header', $so_header)
                                    // ->whereNull('sj.invoice_date')
                                    ->update([
                                        'so.sub_amount' => $sub_amt,
                                        'so.tot_vat' => $tot_vat,
                                        'so.total_amount' => $total_amount
                                    ]);
                            }
                        }
                    }
                }
                // END So TMS
                // Start SSO TMS
                if ($data[$x]['is_sso'] == 1) {
                    $sso_tms = DB::table('db_tbs.entry_sso_tbl as sso')
                        ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                            $join->on('sj.sso_no', '=', 'sso.sso_header');
                            $join->on('sj.item_code', '=', 'sso.item_code');
                        })
                        ->where('sso.item_code', $item_i)
                        ->where(function ($where) use ($act_date){
                            $where->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);
                        })
                        // ->whereNull('sj.invoice_date')
                        ->get();
                    if ($sso_tms->isNotEmpty()) {
                        $cvrt = $act_date;
                        $custprice_id = $cust.'.'.$cvrt;
                        DB::table('db_tbs.entry_sso_tbl as sso')
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'sso.item_code');
                            })
                            ->where('sso.item_code', $item_i)
                            ->where(function ($where) use ($act_date){
                                $where->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);
                            })
                            // ->whereNull('sj.invoice_date')
                            ->update([
                                'sso.id_custprice' => $custprice_id
                            ]);
                    }
                }
                // Start SJ TMS
                if ($data[$x]['is_sj'] == 1) {
                    $tbs_do = DB::table('db_tbs.entry_do_tbl as sj')
                        ->where('sj.cust_id', $cust)
                        ->where('sj.item_code', $item_i)
                        ->where(function ($where) use ($act_date){
                            $where->whereRaw('DATE(sj.created_date) >= ?', [$act_date]);
                        })
                        ->whereNull('sj.invoice_date')
                        ->get();

                    if ($tbs_do->isNotEmpty()) {
                        $cvrt = $act_date;
                        $custprice_id = $cust.'.'.$cvrt;
                        DB::table('db_tbs.entry_do_tbl as sj')
                            ->where('sj.cust_id', $cust)
                            ->where('sj.item_code', $item_i)
                            ->where(function ($where) use ($act_date){
                                $where->whereRaw('DATE(sj.created_date) >= ?', [$act_date]);
                            })
                            ->whereNull('sj.invoice_date')
                            ->update([
                                'sj.id_custprice' => $custprice_id
                            ]);
                    }
                }
                // END SJ TMS

                // Start TBS
                // Start SO TBS
                if ($data[$x]['is_so'] == 1) {
                    $so_tch = DB::table('tch_tbs.soline as so_dtl')
                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                        })
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date){
                            $where->whereNotNull('so_hdr.written');
                            $where->where('so_hdr.written', '>=', $act_date);
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('so_hdr.custcode', $cust);
                            $wh->where('so_dtl.itemcode', $item_i);
                            // $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'so_dtl.so_no',
                            'so_hdr.period as so_period',
                            'so_hdr.taxrate as so_tax_rate',
                            'so_dtl.itemcode as so_item_code',
                            'so_dtl.price as so_price',
                            'so_dtl.quantity as so_qty_so',
                            'so_hdr.sub_amt as so_sub_amount',
                            'so_hdr.tot_disc as so_tot_vat',
                            'so_hdr.tot_amt as so_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($so_tch->isNotEmpty()) {
                        foreach ($so_tch as $s) {
                            $so_header = $s->so_no;
                            DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date){
                                    $where->whereNotNull('so_hdr.written');
                                    $where->where('so_hdr.written', '>=', $act_date);
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('so_hdr.custcode', $cust);
                                    $wh->where('so_dtl.itemcode', $item_i);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'so_dtl.price' => $price_new
                                ]);

                            $new_so_tch = DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where('so_dtl.so_no', $so_header)
                                ->select([
                                    'so_dtl.so_no',
                                    'so_hdr.period as so_period',
                                    'so_hdr.taxrate as so_tax_rate',
                                    'so_dtl.itemcode as so_item_code',
                                    'so_dtl.price as so_price',
                                    'so_dtl.quantity as so_qty_so',
                                    'so_hdr.sub_amt as so_sub_amount',
                                    'so_hdr.tot_disc as so_tot_vat',
                                    'so_hdr.tot_amt as so_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_so = 0;
                            $numItems = count($new_so_tch);
                            $sub_amt = 0;
                            foreach($new_so_tch as $i => $s){
                                $sub_amt += $s->so_price * $s->so_qty_so;
                                if(++$i_so === $numItems) {
                                    $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.soline as so_dtl')
                                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where('so_dtl.so_no', $so_header)
                                        ->update([
                                            'so_hdr.sub_amt' => $sub_amt,
                                            'so_hdr.tot_disc' => $tot_vat,
                                            'so_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                    }
                }
                // END SO TBS
                // Start SSO TBS
                if ($data[$x]['is_sso'] == 1) {
                    $sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                        })
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date){
                            $where->whereNotNull('sso_hdr.written');
                            $where->where('sso_hdr.written', '>=', $act_date);
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('sso_hdr.custcode', $cust);
                            $wh->where('sso_dtl.itemcode', $item_i);
                            // $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'sso_dtl.so_no',
                            'sso_dtl.sso_no as sso_no',
                            'sso_hdr.period as sso_period',
                            'sso_hdr.taxrate as sso_tax_rate',
                            'sso_dtl.itemcode as sso_item_code',
                            'sso_dtl.price as sso_price',
                            'sso_dtl.quantity as sso_qty_sso',
                            'sso_hdr.sub_amt as sso_sub_amount',
                            'sso_hdr.tot_disc as sso_tot_vat',
                            'sso_hdr.tot_amt as sso_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($sso_tch->isNotEmpty()) {
                        foreach ($sso_tch as $s) {
                            $sso_header = $s->sso_no;
                            DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date){
                                    $where->whereNotNull('sso_hdr.written');
                                    $where->where('sso_hdr.written', '>=', $act_date);
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('sso_hdr.custcode', $cust);
                                    $wh->where('sso_dtl.itemcode', $item_i);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'sso_dtl.price' => $price_new
                                ]);
                            $new_sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($sso_header){
                                    $wh->where('sso_hdr.sso_no', $sso_header);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sso_dtl.so_no',
                                    'sso_dtl.sso_no as sso_no',
                                    'sso_hdr.period as sso_period',
                                    'sso_hdr.sso_no as sso_no',
                                    'sso_hdr.taxrate as sso_tax_rate',
                                    'sso_dtl.itemcode as sso_item_code',
                                    'sso_dtl.price as sso_price',
                                    'sso_dtl.quantity as sso_qty_sso',
                                    'sso_hdr.sub_amt as sso_sub_amount',
                                    'sso_hdr.tot_disc as sso_tot_vat',
                                    'sso_hdr.tot_amt as sso_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_sso = 0;
                            $numItems = count($new_sso_tch);
                            $sub_amt = 0;
                            foreach($new_sso_tch as $i => $s){
                                $sub_amt += $s->sso_price * $s->sso_qty_sso;
                                if(++$i_sso === $numItems) {
                                    $tot_vat = $sub_amt * $s->sso_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($sso_header){
                                            $wh->where('sso_hdr.sso_no', $sso_header);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sso_hdr.sub_amt' => $sub_amt,
                                            'sso_hdr.tot_disc' => $tot_vat,
                                            'sso_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                    }
                }
                // END SSO TBS
                // Start SJ TBS
                if ($data[$x]['is_sj'] == 1) {
                    $do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date){
                            $where->whereNotNull('sj_hdr.written');
                            $where->where('sj_hdr.written', '>=', $act_date);
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('sj_hdr.custcode', $cust);
                            $wh->where('sj_dtl.itemcode', $item_i);
                            $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'sj_dtl.do_no',
                            'sj_hdr.period as sj_period',
                            'sj_hdr.taxrate as sj_tax_rate',
                            'sj_dtl.itemcode as sj_item_code',
                            'sj_dtl.price as sj_price',
                            'sj_dtl.quantity as sj_qty',
                            'sj_hdr.sub_amt as sj_sub_amount',
                            'sj_hdr.tot_disc as sj_tot_vat',
                            'sj_hdr.tot_amt as sj_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($do_tch->isNotEmpty()) {
                        foreach ($do_tch as $s) {
                            $do_no = $s->do_no;
                            DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date){
                                    $where->whereNotNull('sj_hdr.written');
                                    $where->where('sj_hdr.written', '>=', $act_date);
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('sj_hdr.custcode', $cust);
                                    $wh->where('sj_dtl.itemcode', $item_i);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'sj_dtl.price' => $price_new
                                ]);
                            $new_do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($do_no){
                                    $wh->where('sj_hdr.do_no', $do_no);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sj_dtl.do_no',
                                    'sj_hdr.period as sj_period',
                                    'sj_hdr.taxrate as sj_tax_rate',
                                    'sj_dtl.itemcode as sj_item_code',
                                    'sj_dtl.price as sj_price',
                                    'sj_dtl.quantity as sj_qty',
                                    'sj_hdr.sub_amt as sj_sub_amount',
                                    'sj_hdr.tot_disc as sj_tot_vat',
                                    'sj_hdr.tot_amt as sj_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_do = 0;
                            $numItems = count($new_do_tch);
                            $sub_amt = 0;
                            foreach($new_do_tch as $i => $s){
                                $sub_amt += $s->sj_price * $s->sj_qty;
                                if(++$i_do === $numItems) {
                                    $tot_vat = $sub_amt * $s->sj_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($do_no){
                                            $wh->where('sj_hdr.do_no', $do_no);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sj_hdr.sub_amt' => $sub_amt,
                                            'sj_hdr.tot_disc' => $tot_vat,
                                            'sj_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                    }
                }
                // END SJ TBS
                // END TBS
            }
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function repostSO($data)
    {
        try {
            for ($x=0; $x < count($data); $x++) {
                $item_i = $data[$x]['item_code'];
                $act_date = $data[$x]['active_date'];
                $cust = $data[$x]['cust_id'];
                $price_new = $data[$x]['price_new'];
                $range_date = $data[$x]['range_date'];

                if ($data[$x]['is_stock'] == 1) {
                    // Stock TMS
                    DB::table('db_tbs.item')
                        ->where('ITEMCODE', $item_i)
                        ->update([
                            'PRICE' => $price_new
                        ]);
                    // Stock TBS
                    DB::table('tch_tbs.item')
                        ->where('itemcode', $item_i)
                        ->update([
                            'price' => $price_new
                        ]);
                }

                $so = DB::table('db_tbs.entry_so_tbl as so')
                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                        $join->on('sso.so_header', '=', 'so.so_header');
                        $join->on('sso.item_code', '=', 'so.item_code');
                    })
                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                        $join->on('sj.so_no', '=', 'so.so_header');
                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                        $join->on('sj.item_code', '=', 'so.item_code');
                    })
                    ->where('so.cust_id', $cust)
                    ->where('so.item_code', $item_i)
                    ->where(function ($is) use($act_date, $range_date){
                        $is->where('so.written_date', '>=', $act_date);
                        
                        if (!is_null($range_date)) {
                            $is->where('so.written_date', '<=', $range_date);
                        }
                    })
                    // ->whereNull('sj.invoice_date')
                    ->select([
                        'so.so_header',
                        'so.so_period',
                        'so.tax_rate as so_tax_rate',
                        'so.item_code as so_item_code',
                        'so.price as so_price',
                        'so.qty_so as so_qty_so',
                        'so.sub_amount as so_sub_amount',
                        'so.tot_vat as so_tot_vat',
                        'so.total_amount as so_total_amount',
                        'sso.sso_header as sso_header',
                        'sj.do_no as sj_number',
                    ])
                    ->get();
                if ($so->isNotEmpty()) {
                    foreach ($so as $s) {
                        $so_header = $s->so_header;

                        DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            ->where('so.item_code', $item_i)
                            ->where(function ($is) use($act_date, $range_date){
                                $is->where('so.written_date', '>=', $act_date);
                                
                                if (!is_null($range_date)) {
                                    $is->where('so.written_date', '<=', $range_date);
                                }
                            })
                            // ->whereNull('sj.invoice_date')
                            ->update([
                                'so.price' => $price_new
                            ]);
                        $new_so_tms = DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            // ->whereNull('sj.invoice_date')
                            ->select([
                                'so.so_header',
                                'so.so_period',
                                'so.tax_rate as so_tax_rate',
                                'so.item_code as so_item_code',
                                'so.price as so_price',
                                'so.qty_so as so_qty_so',
                                'so.sub_amount as so_sub_amount',
                                'so.tot_vat as so_tot_vat',
                                'so.total_amount as so_total_amount',
                                'sso.sso_header as sso_header',
                                'sj.do_no as sj_number',
                            ])
                            ->get();
                        $i_so = 0;
                        $numItems = count($new_so_tms);
                        $sub_amt = 0;
                        foreach($new_so_tms as $i => $s){
                            $sub_amt += $s->so_price * $s->so_qty_so;
                            if(++$i_so === $numItems) {
                                $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                $total_amount = $sub_amt + $tot_vat;

                                DB::table('db_tbs.entry_so_tbl as so')
                                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                        $join->on('sso.so_header', '=', 'so.so_header');
                                        $join->on('sso.item_code', '=', 'so.item_code');
                                    })
                                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                        $join->on('sj.so_no', '=', 'so.so_header');
                                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                                        $join->on('sj.item_code', '=', 'so.item_code');
                                    })
                                    ->where('so.so_header', $so_header)
                                    // ->whereNull('sj.invoice_date')
                                    ->update([
                                        'so.sub_amount' => $sub_amt,
                                        'so.tot_vat' => $tot_vat,
                                        'so.total_amount' => $total_amount
                                    ]);
                            }
                        }
                        // End update SO
                        // Start update SSO
                        if ($data[$x]['is_sso'] == 1) {
                            $cvrt = $act_date;
                            $custprice_id = $cust.'.'.$cvrt;
                            DB::table('db_tbs.entry_sso_tbl as sso')
                                ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                    $join->on('sj.sso_no', '=', 'sso.sso_header');
                                    $join->on('sj.item_code', '=', 'sso.item_code');
                                })
                                ->where('sso.so_header', $so_header)
                                ->where('sso.item_code', $item_i)
                                // ->whereNull('sj.invoice_date')
                                // ->whereRaw('DATE(sso.created_date) >= ?', [$act_date])
                                ->where(function ($is) use($act_date, $range_date){
                                    $is->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);
                                    
                                    if (!is_null($range_date)) {
                                        $is->whereRaw('DATE(sso.created_date) <= ?', [$range_date]);
                                    }
                                })
                                ->update([
                                    'sso.id_custprice' => $custprice_id
                                ]);
                        }
                        // End SSO
                        // Start SJ
                        if ($data[$x]['is_sj'] == 1) {
                            $cvrt = $act_date;
                            $custprice_id = $cust.'.'.$cvrt;
                            DB::table('db_tbs.entry_do_tbl as sj')
                                ->where('sj.so_no', $so_header)
                                ->where('sj.item_code', $item_i)
                                ->whereNull('sj.invoice_date')
                                // ->whereRaw('DATE(sj.created_date) >= ?', [$act_date])
                                ->where(function ($is) use($act_date, $range_date){
                                    $is->whereRaw('DATE(sj.created_date) >= ?', [$act_date]);
                                    
                                    if (!is_null($range_date)) {
                                        $is->whereRaw('DATE(sj.created_date) <= ?', [$range_date]);
                                    }
                                })
                                ->update([
                                    'sj.id_custprice' => $custprice_id
                                ]);
                        }
                        // End SJ
                    }
                }
                // END update db_tbs
                // Start tch_tbs
                $so_tch = DB::table('tch_tbs.soline as so_dtl')
                    ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                    ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.so_no', '=', 'so_dtl.so_no')
                    ->leftJoin('tch_tbs.sso_dtl as sso_dtl', function ($join){
                        $join->on('sso_dtl.so_no', '=', 'so_dtl.so_no');
                        $join->on('sso_dtl.itemcode', '=', 'so_dtl.itemcode');
                    })
                    ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                        $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                        $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                        $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                    })
                    ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                    ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                        $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                    })
                    ->where(function ($wh) use ($cust, $item_i){
                        $wh->where('so_hdr.custcode', $cust);
                        $wh->where('so_dtl.itemcode', $item_i);
                        // $wh->whereNull('inv_dtl.do_no');
                    })
                    ->where(function ($is) use($act_date, $range_date){
                        $is->where('so_hdr.written', '>=', $act_date);
                        
                        if (!is_null($range_date)) {
                            $is->where('so_hdr.written', '<=', $range_date);
                        }
                    })
                    ->select([
                        'so_dtl.so_no',
                        'so_hdr.period as so_period',
                        'so_hdr.taxrate as so_tax_rate',
                        'so_dtl.itemcode as so_item_code',
                        'so_dtl.price as so_price',
                        'so_dtl.quantity as so_qty_so',
                        'so_hdr.sub_amt as so_sub_amount',
                        'so_hdr.tot_disc as so_tot_vat',
                        'so_hdr.tot_amt as so_total_amount',
                        'inv_dtl.do_no as inv_do'
                    ])
                    ->get();
                if ($so_tch->isNotEmpty()) {
                    foreach ($so_tch as $so) {
                        $so_header = $so->so_no;

                        // Update SO
                        if ($data[$x]['is_so'] == 1) {

                            DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i){
                                    $wh->where('so_dtl.so_no', $so_header);
                                    $wh->where('so_dtl.itemcode', $item_i);
                                    // $wh->where('so_dtl.written', '>=', $act_date);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->where(function ($is) use($act_date, $range_date){
                                    $is->where('so_dtl.written', '>=', $act_date);
                                    
                                    if (!is_null($range_date)) {
                                        $is->where('so_dtl.written', '<=', $range_date);
                                    }
                                })
                                ->update([
                                    'so_dtl.price' => $price_new,
                                    'so_hdr.sub_amt' => $sub_amt,
                                    'so_hdr.tot_disc' => $tot_vat,
                                    'so_hdr.tot_amt' => $total_amount
                                ]);
                            $new_so_tbs = DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $act_date){
                                    $wh->where('so_dtl.so_no', $so_header);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'so_dtl.so_no',
                                    'so_hdr.period as so_period',
                                    'so_hdr.taxrate as so_tax_rate',
                                    'so_dtl.itemcode as so_item_code',
                                    'so_dtl.price as so_price',
                                    'so_dtl.quantity as so_qty_so',
                                    'so_hdr.sub_amt as so_sub_amount',
                                    'so_hdr.tot_disc as so_tot_vat',
                                    'so_hdr.tot_amt as so_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_so = 0;
                            $numItems = count($new_so_tbs);
                            $sub_amt = 0;
                            foreach($new_so_tbs as $i => $s){
                                $sub_amt += $s->so_price * $s->so_qty_so;
                                if(++$i_so === $numItems) {
                                    $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.soline as so_dtl')
                                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header){
                                            $wh->where('so_dtl.so_no', $so_header);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'so_hdr.sub_amt' => $sub_amt,
                                            'so_hdr.tot_disc' => $tot_vat,
                                            'so_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                        // End update SO
                        // Start update SSO
                        if ($data[$x]['is_sso'] == 1) {
                            $sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i){
                                    $wh->where('sso_dtl.so_no', $so_header);
                                    $wh->where('sso_dtl.itemcode', $item_i);
                                    // $wh->where('sso_dtl.written', '>=', $act_date);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->where(function ($is) use($act_date, $range_date){
                                    $is->where('sso_dtl.written', '>=', $act_date);
                                    
                                    if (!is_null($range_date)) {
                                        $is->where('sso_dtl.written', '<=', $range_date);
                                    }
                                })
                                ->select([
                                    'sso_dtl.so_no',
                                    'sso_dtl.sso_no as sso_no',
                                    'sso_hdr.period as sso_period',
                                    'sso_hdr.taxrate as sso_tax_rate',
                                    'sso_dtl.itemcode as sso_item_code',
                                    'sso_dtl.price as sso_price',
                                    'sso_dtl.quantity as sso_qty_sso',
                                    'sso_hdr.sub_amt as sso_sub_amount',
                                    'sso_hdr.tot_disc as sso_tot_vat',
                                    'sso_hdr.tot_amt as sso_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            if ($sso_tch->isNotEmpty()) {
                                foreach ($sso_tch as $s) {
                                    $sso_header = $s->sso_no;
                                    DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header, $item_i){
                                            $wh->where('sso_dtl.so_no', $so_header);
                                            $wh->where('sso_dtl.itemcode', $item_i);
                                            // $wh->where('sso_dtl.written', '>=', $act_date);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->where(function ($is) use($act_date, $range_date){
                                            $is->where('sso_dtl.written', '>=', $act_date);
                                            
                                            if (!is_null($range_date)) {
                                                $is->where('sso_dtl.written', '<=', $range_date);
                                            }
                                        })
                                        ->update([
                                            'sso_dtl.price' => $price_new
                                        ]);
                                    $new_sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($sso_header){
                                            $wh->where('sso_hdr.sso_no', $sso_header);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->select([
                                            'sso_dtl.so_no',
                                            'sso_hdr.period as sso_period',
                                            'sso_hdr.sso_no as sso_no',
                                            'sso_hdr.taxrate as sso_tax_rate',
                                            'sso_dtl.itemcode as sso_item_code',
                                            'sso_dtl.price as sso_price',
                                            'sso_dtl.quantity as sso_qty_sso',
                                            'sso_hdr.sub_amt as sso_sub_amount',
                                            'sso_hdr.tot_disc as sso_tot_vat',
                                            'sso_hdr.tot_amt as sso_total_amount',
                                            'inv_dtl.do_no as inv_do'
                                        ])
                                        ->get();
                                    $i_sso = 0;
                                    $numItems = count($new_sso_tch);
                                    $sub_amt = 0;
                                    foreach($new_sso_tch as $i => $s){
                                        $sub_amt += $s->sso_price * $s->sso_qty_sso;
                                        if(++$i_sso === $numItems) {
                                            $tot_vat = $sub_amt * $s->sso_tax_rate / 100;
                                            $total_amount = $sub_amt + $tot_vat;
                                            DB::table('tch_tbs.sso_dtl as sso_dtl')
                                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                                })
                                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                                })
                                                ->where(function ($wh) use ($sso_header){
                                                    $wh->where('sso_hdr.sso_no', $sso_header);
                                                    // $wh->whereNull('inv_dtl.do_no');
                                                })
                                                ->update([
                                                    'sso_hdr.sub_amt' => $sub_amt,
                                                    'sso_hdr.tot_disc' => $tot_vat,
                                                    'sso_hdr.tot_amt' => $total_amount
                                                ]);
                                        }
                                    }
                                }
                            }
                        }
                        // End SSO
                        // Start SJ
                        if ($data[$x]['is_sj'] == 1) {
                            $do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i){
                                    $wh->where('sj_dtl.so_no', $so_header);
                                    $wh->where('sj_dtl.itemcode', $item_i);
                                    // $wh->where('sj_dtl.written', '>=', $act_date);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->where(function ($is) use($act_date, $range_date){
                                    $is->where('sj_dtl.written', '>=', $act_date);
                                    
                                    if (!is_null($range_date)) {
                                        $is->where('sj_dtl.written', '<=', $range_date);
                                    }
                                })
                                ->select([
                                    'sj_dtl.do_no',
                                    'sj_hdr.period as sj_period',
                                    'sj_hdr.taxrate as sj_tax_rate',
                                    'sj_dtl.itemcode as sj_item_code',
                                    'sj_dtl.price as sj_price',
                                    'sj_dtl.quantity as sj_qty',
                                    'sj_hdr.sub_amt as sj_sub_amount',
                                    'sj_hdr.tot_disc as sj_tot_vat',
                                    'sj_hdr.tot_amt as sj_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            if ($do_tch->isNotEmpty()) {
                                foreach ($do_tch as $s) {
                                    $do_no = $s->do_no;
                                    DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header, $item_i){
                                            $wh->where('sj_dtl.so_no', $so_header);
                                            $wh->where('sj_dtl.itemcode', $item_i);
                                            // $wh->where('sj_dtl.written', '>=', $act_date);
                                            $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->where(function ($is) use($act_date, $range_date){
                                            $is->where('sj_dtl.written', '>=', $act_date);
                                            
                                            if (!is_null($range_date)) {
                                                $is->where('sj_dtl.written', '<=', $range_date);
                                            }
                                        })
                                        ->update([
                                            'sj_dtl.price' => $price_new
                                        ]);
                                    $new_do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($do_no){
                                            $wh->where('sj_hdr.do_no', $do_no);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->select([
                                            'sj_dtl.do_no',
                                            'sj_hdr.period as sj_period',
                                            'sj_hdr.taxrate as sj_tax_rate',
                                            'sj_dtl.itemcode as sj_item_code',
                                            'sj_dtl.price as sj_price',
                                            'sj_dtl.quantity as sj_qty',
                                            'sj_hdr.sub_amt as sj_sub_amount',
                                            'sj_hdr.tot_disc as sj_tot_vat',
                                            'sj_hdr.tot_amt as sj_total_amount',
                                            'inv_dtl.do_no as inv_do'
                                        ])
                                        ->get();
                                    $i_do = 0;
                                    $numItems = count($new_do_tch);
                                    $sub_amt = 0;
                                    foreach($new_do_tch as $i => $s){
                                        $sub_amt += $s->sj_price * $s->sj_qty;
                                        if(++$i_do === $numItems) {
                                            $tot_vat = $sub_amt * $s->sj_tax_rate / 100;
                                            $total_amount = $sub_amt + $tot_vat;
                                            DB::table('tch_tbs.do_dtl as sj_dtl')
                                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                                })
                                                ->where(function ($wh) use ($do_no){
                                                    $wh->where('sj_hdr.do_no', $do_no);
                                                    $wh->whereNull('inv_dtl.do_no');
                                                })
                                                ->update([
                                                    'sj_hdr.sub_amt' => $sub_amt,
                                                    'sj_hdr.tot_disc' => $tot_vat,
                                                    'sj_hdr.tot_amt' => $total_amount
                                                ]);
                                        }
                                    }
                                }
                            }
                        }
                        // END SJ
                    }
                }
            }
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function repostDate($data)
    {
        try {
            for ($x=0; $x < count($data); $x++) {
                $item_i = $data[$x]['item_code'];
                $act_date = $data[$x]['active_date'];
                $cust = $data[$x]['cust_id'];
                $price_new = $data[$x]['price_new'];
                $range_date = $data[$x]['range_date'];

                if ($data[$x]['is_stock'] == 1) {
                    // Stock TMS
                    DB::table('db_tbs.item')
                        ->where('ITEMCODE', $data[$x]['item_code'])
                        ->update([
                            'PRICE' => $data[$x]['price_new']
                        ]);
                    // Stock TBS
                    DB::table('tch_tbs.item')
                        ->where('itemcode', $data[$x]['item_code'])
                        ->update([
                            'price' => $data[$x]['price_new']
                        ]);
                }

                // SO TMS
                $so = DB::table('db_tbs.entry_so_tbl as so')
                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                        $join->on('sso.so_header', '=', 'so.so_header');
                        $join->on('sso.item_code', '=', 'so.item_code');
                    })
                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                        $join->on('sj.so_no', '=', 'so.so_header');
                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                        $join->on('sj.item_code', '=', 'so.item_code');
                    })
                    ->where('so.cust_id', $cust)
                    ->where('so.item_code', $item_i)
                    ->where(function ($where) use ($act_date, $range_date){
                        $where->whereRaw('DATE(so.written_date) >= ?', [$act_date]);
                        if (!is_null($range_date)) {
                            $where->whereRaw('DATE(so.written_date) <= ?', [$range_date]);
                        }
                    })
                    // ->whereNull('sj.invoice_date')
                    ->select([
                        'so.so_header',
                        'so.so_period',
                        'so.tax_rate as so_tax_rate',
                        'so.item_code as so_item_code',
                        'so.price as so_price',
                        'so.qty_so as so_qty_so',
                        'so.sub_amount as so_sub_amount',
                        'so.tot_vat as so_tot_vat',
                        'so.total_amount as so_total_amount',
                        'sso.sso_header as sso_header',
                        'sj.do_no as sj_number',
                    ])
                ->get();

                // Start SO TMS
                if ($so->isNotEmpty()) {
                    foreach ($so as $s) {
                        $so_header = $s->so_header;
                        DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.cust_id', $cust)
                            ->where('so.item_code', $item_i)
                            ->where('so.so_header', $so_header)
                            ->where(function ($where) use ($act_date, $range_date){
                                $where->whereRaw('DATE(so.written_date) >= ?', [$act_date]);
                                if (!is_null($range_date)) {
                                    $where->whereRaw('DATE(so.written_date) <= ?', [$range_date]);
                                }
                            })
                            // ->whereNull('sj.invoice_date')
                            ->update([
                                'price' => $price_new
                            ]);
                        
                        $new_so_tms = DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            // ->whereNull('sj.invoice_date')
                            ->select([
                                'so.so_header',
                                'so.so_period',
                                'so.tax_rate as so_tax_rate',
                                'so.item_code as so_item_code',
                                'so.price as so_price',
                                'so.qty_so as so_qty_so',
                                'so.sub_amount as so_sub_amount',
                                'so.tot_vat as so_tot_vat',
                                'so.total_amount as so_total_amount',
                                'sso.sso_header as sso_header',
                                'sj.do_no as sj_number',
                            ])
                            ->get();
                        
                        $i_so = 0;
                        $numItems = count($new_so_tms);
                        $sub_amt = 0;
                        foreach($new_so_tms as $i => $s){
                            $sub_amt += $s->so_price * $s->so_qty_so;
                            if(++$i_so === $numItems) {
                                $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                $total_amount = $sub_amt + $tot_vat;
                                DB::table('db_tbs.entry_so_tbl as so')
                                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                        $join->on('sso.so_header', '=', 'so.so_header');
                                        $join->on('sso.item_code', '=', 'so.item_code');
                                    })
                                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                        $join->on('sj.so_no', '=', 'so.so_header');
                                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                                        $join->on('sj.item_code', '=', 'so.item_code');
                                    })
                                    ->where('so.so_header', $so_header)
                                    // ->whereNull('sj.invoice_date')
                                    ->update([
                                        'so.sub_amount' => $sub_amt,
                                        'so.tot_vat' => $tot_vat,
                                        'so.total_amount' => $total_amount
                                    ]);
                            }
                        }
                    }
                }
                // END So TMS
                // Start SSO TMS
                if ($data[$x]['is_sso'] == 1) {
                    $sso_tms = DB::table('db_tbs.entry_sso_tbl as sso')
                        ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                            $join->on('sj.sso_no', '=', 'sso.sso_header');
                            $join->on('sj.item_code', '=', 'sso.item_code');
                        })
                        ->where('sso.item_code', $item_i)
                        ->where(function ($where) use ($act_date, $range_date){
                            $where->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);

                            if (!is_null($range_date)) {
                                $where->whereRaw('DATE(sso.created_date) <= ?', [$range_date]);
                            }
                        })
                        // ->whereNull('sj.invoice_date')
                        ->get();
                    if ($sso_tms->isNotEmpty()) {
                        $cvrt = $act_date;
                        $custprice_id = $cust.'.'.$cvrt;
                        DB::table('db_tbs.entry_sso_tbl as sso')
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'sso.item_code');
                            })
                            ->where('sso.item_code', $item_i)
                            ->where(function ($where) use ($act_date, $range_date){
                                $where->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);
    
                                if (!is_null($range_date)) {
                                    $where->whereRaw('DATE(sso.created_date) <= ?', [$range_date]);
                                }
                            })
                            // ->whereNull('sj.invoice_date')
                            ->update([
                                'sso.id_custprice' => $custprice_id
                            ]);
                    }
                }
                // Start SJ TMS
                if ($data[$x]['is_sj'] == 1) {
                    $tbs_do = DB::table('db_tbs.entry_do_tbl as sj')
                        ->where('sj.cust_id', $cust)
                        ->where('sj.item_code', $item_i)
                        ->where(function ($where) use ($act_date, $range_date){
                            $where->whereRaw('DATE(sj.created_date) >= ?', [$act_date]);

                            if (!is_null($range_date)) {
                                $where->whereRaw('DATE(sj.created_date) <= ?', [$range_date]);
                            }
                        })
                        ->whereNull('sj.invoice_date')
                        ->get();

                    if ($tbs_do->isNotEmpty()) {
                        $cvrt = $act_date;
                        $custprice_id = $cust.'.'.$cvrt;
                        DB::table('db_tbs.entry_do_tbl as sj')
                            ->where('sj.cust_id', $cust)
                            ->where('sj.item_code', $item_i)
                            ->where(function ($where) use ($act_date, $range_date){
                                $where->whereRaw('DATE(sj.created_date) >= ?', [$act_date]);
    
                                if (!is_null($range_date)) {
                                    $where->whereRaw('DATE(sj.created_date) <= ?', [$range_date]);
                                }
                            })
                            ->whereNull('sj.invoice_date')
                            ->update([
                                'sj.id_custprice' => $custprice_id
                            ]);
                    }
                }
                // END SJ TMS

                // Start TBS
                // Start SO TBS
                if ($data[$x]['is_so'] == 1) {
                    $so_tch = DB::table('tch_tbs.soline as so_dtl')
                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                        })
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date, $range_date){
                            $where->where('so_hdr.written', '>=', $act_date);

                            if (!is_null($range_date)) {
                                $where->where('so_hdr.written', '<=', $range_date);
                            }
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('so_hdr.custcode', $cust);
                            $wh->where('so_dtl.itemcode', $item_i);
                            // $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'so_dtl.so_no',
                            'so_hdr.period as so_period',
                            'so_hdr.taxrate as so_tax_rate',
                            'so_dtl.itemcode as so_item_code',
                            'so_dtl.price as so_price',
                            'so_dtl.quantity as so_qty_so',
                            'so_hdr.sub_amt as so_sub_amount',
                            'so_hdr.tot_disc as so_tot_vat',
                            'so_hdr.tot_amt as so_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($so_tch->isNotEmpty()) {
                        foreach ($so_tch as $s) {
                            $so_header = $s->so_no;
                            DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date, $range_date){
                                    $where->where('so_hdr.written', '>=', $act_date);
        
                                    if (!is_null($range_date)) {
                                        $where->where('so_hdr.written', '<=', $range_date);
                                    }
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('so_hdr.custcode', $cust);
                                    $wh->where('so_dtl.itemcode', $item_i);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'so_dtl.price' => $price_new
                                ]);

                            $new_so_tch = DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where('so_dtl.so_no', $so_header)
                                ->select([
                                    'so_dtl.so_no',
                                    'so_hdr.period as so_period',
                                    'so_hdr.taxrate as so_tax_rate',
                                    'so_dtl.itemcode as so_item_code',
                                    'so_dtl.price as so_price',
                                    'so_dtl.quantity as so_qty_so',
                                    'so_hdr.sub_amt as so_sub_amount',
                                    'so_hdr.tot_disc as so_tot_vat',
                                    'so_hdr.tot_amt as so_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_so = 0;
                            $numItems = count($new_so_tch);
                            $sub_amt = 0;
                            foreach($new_so_tch as $i => $s){
                                $sub_amt += $s->so_price * $s->so_qty_so;
                                if(++$i_so === $numItems) {
                                    $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.soline as so_dtl')
                                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where('so_dtl.so_no', $so_header)
                                        ->update([
                                            'so_hdr.sub_amt' => $sub_amt,
                                            'so_hdr.tot_disc' => $tot_vat,
                                            'so_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                    }
                }
                // END SO TBS
                // Start SSO TBS
                if ($data[$x]['is_sso'] == 1) {
                    $sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                        })
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date, $range_date){
                            $where->where('sso_hdr.written', '>=', $act_date);
                            
                            if (!is_null($range_date)) {
                                $where->where('sso_hdr.written', '<=', $range_date);
                            }
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('sso_hdr.custcode', $cust);
                            $wh->where('sso_dtl.itemcode', $item_i);
                            // $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'sso_dtl.so_no',
                            'sso_dtl.sso_no as sso_no',
                            'sso_hdr.period as sso_period',
                            'sso_hdr.taxrate as sso_tax_rate',
                            'sso_dtl.itemcode as sso_item_code',
                            'sso_dtl.price as sso_price',
                            'sso_dtl.quantity as sso_qty_sso',
                            'sso_hdr.sub_amt as sso_sub_amount',
                            'sso_hdr.tot_disc as sso_tot_vat',
                            'sso_hdr.tot_amt as sso_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($sso_tch->isNotEmpty()) {
                        foreach ($sso_tch as $s) {
                            $sso_header = $s->sso_no;
                            DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date, $range_date){
                                    $where->where('sso_hdr.written', '>=', $act_date);
                                    
                                    if (!is_null($range_date)) {
                                        $where->where('sso_hdr.written', '<=', $range_date);
                                    }
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('sso_hdr.custcode', $cust);
                                    $wh->where('sso_dtl.itemcode', $item_i);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'sso_dtl.price' => $price_new
                                ]);
                            $new_sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($sso_header){
                                    $wh->where('sso_hdr.sso_no', $sso_header);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sso_dtl.so_no',
                                    'sso_hdr.period as sso_period',
                                    'sso_hdr.sso_no as sso_no',
                                    'sso_hdr.taxrate as sso_tax_rate',
                                    'sso_dtl.itemcode as sso_item_code',
                                    'sso_dtl.price as sso_price',
                                    'sso_dtl.quantity as sso_qty_sso',
                                    'sso_hdr.sub_amt as sso_sub_amount',
                                    'sso_hdr.tot_disc as sso_tot_vat',
                                    'sso_hdr.tot_amt as sso_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_sso = 0;
                            $numItems = count($new_sso_tch);
                            $sub_amt = 0;
                            foreach($new_sso_tch as $i => $s){
                                $sub_amt += $s->sso_price * $s->sso_qty_sso;
                                if(++$i_sso === $numItems) {
                                    $tot_vat = $sub_amt * $s->sso_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($sso_header){
                                            $wh->where('sso_hdr.sso_no', $sso_header);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sso_hdr.sub_amt' => $sub_amt,
                                            'sso_hdr.tot_disc' => $tot_vat,
                                            'sso_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                    }
                }
                // END SSO TBS
                // Start SJ TBS
                if ($data[$x]['is_sj'] == 1) {
                    $do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date, $range_date){
                            $where->where('sj_hdr.written', '>=', $act_date);
                            
                            if (!is_null($range_date)) {
                                $where->where('sj_hdr.written', '<=', $range_date);
                            }
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('sj_hdr.custcode', $cust);
                            $wh->where('sj_dtl.itemcode', $item_i);
                            $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'sj_dtl.do_no',
                            'sj_hdr.period as sj_period',
                            'sj_hdr.taxrate as sj_tax_rate',
                            'sj_dtl.itemcode as sj_item_code',
                            'sj_dtl.price as sj_price',
                            'sj_dtl.quantity as sj_qty',
                            'sj_hdr.sub_amt as sj_sub_amount',
                            'sj_hdr.tot_disc as sj_tot_vat',
                            'sj_hdr.tot_amt as sj_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($do_tch->isNotEmpty()) {
                        foreach ($do_tch as $s) {
                            $do_no = $s->do_no;
                            DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date, $range_date){
                                    $where->where('sj_hdr.written', '>=', $act_date);
                                    
                                    if (!is_null($range_date)) {
                                        $where->where('sj_hdr.written', '<=', $range_date);
                                    }
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('sj_hdr.custcode', $cust);
                                    $wh->where('sj_dtl.itemcode', $item_i);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'sj_dtl.price' => $price_new
                                ]);
                            $new_do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($do_no){
                                    $wh->where('sj_hdr.do_no', $do_no);
                                    // $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sj_dtl.do_no',
                                    'sj_hdr.period as sj_period',
                                    'sj_hdr.taxrate as sj_tax_rate',
                                    'sj_dtl.itemcode as sj_item_code',
                                    'sj_dtl.price as sj_price',
                                    'sj_dtl.quantity as sj_qty',
                                    'sj_hdr.sub_amt as sj_sub_amount',
                                    'sj_hdr.tot_disc as sj_tot_vat',
                                    'sj_hdr.tot_amt as sj_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            $i_do = 0;
                            $numItems = count($new_do_tch);
                            $sub_amt = 0;
                            foreach($new_do_tch as $i => $s){
                                $sub_amt += $s->sj_price * $s->sj_qty;
                                if(++$i_do === $numItems) {
                                    $tot_vat = $sub_amt * $s->sj_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($do_no){
                                            $wh->where('sj_hdr.do_no', $do_no);
                                            // $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sj_hdr.sub_amt' => $sub_amt,
                                            'sj_hdr.tot_disc' => $tot_vat,
                                            'sj_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }
                    }
                }
                // END SJ TBS
                // END TBS
            }
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}