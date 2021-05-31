<table>
    <tr>
        <th>CUST</th>
        <th>NAME PART</th>
        <th>TYPE</th>
        <th>PLAN</th>
        <th>C/T</th>
        <th>ROUTE</th>
        <th>TON</th>
        <th>PROSES</th>
        <th>TIME</th>
        <th>PLAN HOUR</th>
        <th>PLAN THP</th>
        <th>ACTUAL LHP</th>
        <th>ACT HOUR</th>
        <th>NOTE</th>
        <th>APNORMALITY</th>
        <th>ACTION PLAN</th>
        <th>STATUS</th>
    </tr>
    <tr>
        <th>Shift 1</th>
        <th>Shift 2</th>
        <th>Shift 1</th>
        <th>Shift 2</th>
        <th>%</th>
    </tr>
        @php
            $sum_shift_1 = 0;
            $sum_shift_2 = 0;
            $sum_lhp_1 = 0;
            $sum_lhp_2 = 0;
            $sum_persentase = 0;
            $sum_act_hour = 0;
        @endphp
    @foreach ($data as $v)
        @php
            $sum_shift_1 += $v->SHIFT_1;
            $sum_shift_2 += $v->SHIFT_2;
            $sum_lhp_1 += $v->LHP_1;
            $sum_lhp_2 += $v->LHP_2;
            $sum_persentase += $v->persentase;
            $sum_act_hour += $v->act_hour_new;
        @endphp
    <tr>
        <td>{{$v->customer_code}}</td>
        <td>{{$v->part_name}}</td>
        <td>{{$v->part_type}}</td>
        <td>{{$v->plan}}</td>
        <td>{{$v->ct}}</td>
        <td>{{$v->route}}</td>
        <td>{{$v->ton}}</td>
        <td>{{$v->process_sequence_1}}</td>
        <td>{{$v->time}}</td>
        <td>{{$v->plan_hour}}</td>
        <td>{{$v->SHIFT_1}}</td>
        <td>{{$v->SHIFT_2}}</td>
        <td>{{$v->LHP_1}}</td>
        <td>{{$v->LHP_2}}</td>
        <td>{{$v->persentase}}</td>
        <td>{{$v->act_hour_new}}</td>
        <td>{{$v->note}}</td>
        <td>{{$v->apnormality}}</td>
        <td>{{$v->action_plan}}</td>
        <td>{{$v->status}}</td>
    </tr>
    @endforeach
    <tr>
        <th>TOTAL</th>
        <td>{{$sum->total_plan}}</td>
        <td></td>
        <td>{{$sum->total_plan_hour}}</td>
        <td>{{$sum_shift_1}}</td>
        <td>{{$sum_shift_2}}</td>
        <td>{{$sum_lhp_1}}</td>
        <td>{{$sum_lhp_2}}</td>
        <td>{{round($sum_persentase, 2)}}</td>
        <td>{{$sum_act_hour}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>