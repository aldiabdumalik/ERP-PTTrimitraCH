<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Formula extends Model
{
    protected $connection = 'db_tbs';
    protected $table = 'formula';

    static function getChild($itemcode){
        $formula = DB::table('db_tbs.formula')
            ->select('child.id', 'formula.frm_code', 'child.itemcode', 'formula.frm_desc', 'formula.frm_desc1', 'item.part_no', 'item.custcode')
            ->leftJoin('db_tbs.item', 'item.itemcode', '=', 'formula.fin_code')
            ->leftJoin('db_tbs.item AS child', 'child.itemcode', '=', 'formula.frm_code')
            ->where('formula.fin_code', $itemcode);
        return $formula;
    }

    static function getBomTree($itemcode){
        return $query = DB::connection('db_tbs')
                            ->select('call proc_bomtree_item(?)',array($itemcode));
    }
}
