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
        <td>: {{date('D, d M Y', strtotime($date1))}}</td>
    </tr>
    <tr>
        <td></td>
        <td>SHIFT</td>
        <td>: 1 DAN 2</td>
    </tr>
</table>
<table>
    <tr>
        <th rowspan="2"></th>
        <th rowspan="2">CUST</th>
        <th rowspan="2">PART NO</th>
        <th rowspan="2">ITEMCODE</th>
        <th rowspan="2">PART NAME</th>
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
        <th rowspan="2">NOTE</th>
        <th rowspan="2">APNORMALITY</th>
        <th rowspan="2">ACTION PLAN</th>
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
        <td>{{$v->note}}</td>
        <td>{{$v->apnormality}}</td>
        <td>{{$v->action_plan}}</td>
    </tr>
    @endforeach
    <tr>
        <th></th>
        <th colspan="10" align="center">TOTAL</th>
        <td>{{$sum->total_plan_hour}}</td>
        <td>{{$sum_thp}}</td>
        <td>{{$sum_lhp_1}}</td>
        <td>{{$sum_lhp_2}}</td>
        <td>{{round( (($sum_lhp_1 + $sum_lhp_2) / ($sum_thp)) * 100)}}</td>
        <td>{{$sum_act_hour}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<table border="0" cellspacing="0" cellpadding="10" style="width:100%">
    <tr>
        <td></td>
        <td rowspan="4">LD-PPC-10</td>
        <td>WAKTU TERSEDIA</td>
        <td>{{$waktu_tersedia}}</td>
        <td></td>
        <td>LOADING TIME</td>
        <td>{{$loading_time}} JAM</td>
    </tr>
    <tr>
        <td></td>
        <td>EFF</td>
        <td align="right">{{$eff * 100}} %</td>
        <td></td>
        <td>TOTAL MP</td>
        <td>{{$total_mp}} ORANG</td>
    </tr>
    <tr>
        <td></td>
        <td>MAX LOADING TIME</td>
        <td>{{$max_loading1}}</td>
        <td>{{$max_loading2}}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>TOTAL MAN POWER</td>
        <td>{{$man_power}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<table align="right">
    <tr>
        <td>Test</td>
    </tr>
</table>