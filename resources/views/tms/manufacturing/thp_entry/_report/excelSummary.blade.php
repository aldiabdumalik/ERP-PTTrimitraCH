<table>
    <tr>
        <td></td>
        <td>PT.TRIMITRA CHITRAHASTA</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>PPC DAN DELV DEPT</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>TUGAS HARIAN PRODUKSI PRESSING</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>HARI/TANGGAL</td>
        <td>: {{date('D, d M Y', strtotime($date1))}} sd {{date('D, d M Y', strtotime($date2))}}</td>
    </tr>
    <tr>
        <td></td>
        <td>SHIFT</td>
        <td>: 1 DAN 2</td>
    </tr>
</table>
<table>
    <tr>
        <td rowspan="2"></td>
        <th rowspan="2">DATE</th>
        <th rowspan="2">CUST</th>
        <th rowspan="2">PART NO</th>
        <th rowspan="2">ITEMCODE</th>
        <th rowspan="2">NAME PART</th>
        <th rowspan="2">TYPE</th>
        <th rowspan="2">C/T</th>
        <th rowspan="2">ROUTE</th>
        <th rowspan="2">TON</th>
        <th rowspan="2">PROSES</th>
        <th rowspan="2">TIME</th>
        <th rowspan="2">PLAN HOUR</th>
        <th rowspan="2">PLAN THP</th>
        <th colspan="3">ACTUAL LHP</th>
        <th rowspan="2">ACT HOUR</th>
        <th rowspan="2">OUTSTANDING</th>
    </tr>
    <tr>
        <th>Shift 1</th>
        <th>Shift 2</th>
        <th>%</th>
    </tr>
        @php
            $sum_thp = 0;
            $sum_lhp_1 = 0;
            $sum_lhp_2 = 0;
            $sum_persentase = 0;
            $sum_act_hour = 0;
        @endphp
    @foreach ($data as $v)
        @php
            $sum_thp += $v->thp_qty;
            $sum_lhp_1 += $v->LHP_1;
            $sum_lhp_2 += $v->LHP_2;
            $sum_persentase += $v->persentase;
            $sum_act_hour += $v->act_hour_new;
        @endphp
    <tr>
        <td></td>
        <td>{{date('d/m/Y', strtotime($v->thp_date))}}</td>
        <td>{{$v->customer_code}}</td>
        <td>{{$v->part_number}}</td>
        <td>{{$v->item_code}}</td>
        <td>{{$v->part_name}}</td>
        <td>{{$v->part_type}}</td>
        <td>{{$v->ct}}</td>
        <td>{{$v->route}}</td>
        <td>{{$v->ton}}</td>
        <td>{{$v->process_sequence_1}}</td>
        <td>{{$v->time}}</td>
        <td>{{$v->plan_hour}}</td>
        <td>{{$v->thp_qty}}</td>
        <td>{{$v->LHP_1}}</td>
        <td>{{$v->LHP_2}}</td>
        <td>{{$v->persentase}}</td>
        <td>{{$v->act_hour_new}}</td>
        <td>{{$v->outstanding_beta}}</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <th colspan="11" align="center">TOTAL</th>
        <td>{{$sum->total_plan_hour}}</td>
        <td>{{$sum_thp}}</td>
        <td>{{$sum_lhp_1}}</td>
        <td>{{$sum_lhp_2}}</td>
        <td>{{round( (($sum_lhp_1 + $sum_lhp_2) / ($sum_thp)) * 100)}}</td>
        <td>{{$sum_act_hour}}</td>
        <td></td>
    </tr>
</table>