<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustPricePosted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $arr_data;
    protected $cust_id;
    protected $period;
    protected $priceby;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr_data, $cust_id, $period, $priceby)
    {
        $this->data = $arr_data;
        $this->cust_id = $cust_id;
        $this->period = $period;
        $this->priceby = $priceby;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->cust_id)) {
            switch ($this->priceby) {
                case 'SO':
                    DB::beginTransaction();
                    try {
                        
                        foreach ($this->data as $d) {
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
                                ->where('so.cust_id', $this->cust_id)
                                ->where('so.so_period', '>=', $this->period)
                                ->where('so.item_code', $d['item_code'])
                                ->whereNull('sj.invoice_date')
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
                                    $sub_amt = $d['price_new'] * $s->qty_so;
                                    $tot_vat = $sub_amt * $s->tax_rate / 100;
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
                                        ->where('so.cust_id', $this->cust_id)
                                        ->where('so.so_period', $this->period)
                                        ->where('so.item_code', $d['item_code'])
                                        ->whereNull('sj.invoice_date')
                                        ->update([
                                            'price' => $d['price_new'],
                                            'sub_amount' => $sub_amt,
                                            'tot_vat' => $tot_vat,
                                            'total_amount' => $total_amount
                                        ]);
                                    DB::commit();
                                    Log::channel('queue')->info("Itemcode ".$d['item_code']." updated price");
                                }
                            }
                        }

                    } catch (Exception $e) {
                        Log::channel('queue')->info($e->getMessage());
                        DB::rollBack();
                    }

                    break;
                
                case 'DATE':
                    
                    

                    break;
                default:
                    Log::channel('queue')->info("Apaan nih kawan");
                    break;
            }
        }
        
    }
}
