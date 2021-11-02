<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report - VAT Form</title>
    <style>
        .footer { 
            position: absolute; 
            bottom: 0; 
            font-size: 14px;
            width: 100px;
            right: 0;
        }
        .text-center {
            text-align: center;
        }
        .ttd-nama {
            margin-top: 70px;
        }
        .header { 
            display: table;
            margin: 0;
            padding: 0;
            width: 100%;
            font-size: 14px;
        }
        .h-1{
            display: table-cell;
            width: 50%;
            /* border: 1px solid black; */
            padding-left: 30px;
        }
        .h-2{
            display: table-cell;
            width: 50%;
            /* border: 1px solid black; */
        }
        .customer {
            width: 100%;
            font-size: 12px;
            padding-left: 150px;
            padding-top: 50px;
            /* border: 1px solid black; */
        }
        .glar{
            width: 100%;
            font-size: 12px;
            padding-left: 150px;
            padding-top: 30px;
        }
        .item {
            width: 100%;
            font-size: 12px;
            margin-top: 50px;
            display: table;
            border: 1px solid black;
        }
        .item-cell {
            display: table-cell;
        }
        .table-item {
            width: 100%;
            margin-top: 50px;
            font-size: 12px;
        }
        .terbilang {
            width: 100%;
            margin-top: 50px;
            padding-left: 100px;
            font-size: 14px;
        }
        </style>
</head>
<body>
    <div class="customer">
        <div style="font-weight: bold">{{$result['custinv'][0]->cust_name}}</div>
        <div>{{$result['custinv'][0]->ad1}}</div>
        <div>{{$result['custinv'][0]->ad2}}</div>
        <div>{{$result['custinv'][0]->ad3}}</div>
        <div>{{$result['custinv'][0]->ad4}}</div>
    </div>
    <table class="table-item" cellpadding="0" cellspacing="0">
        @php $no = 1; @endphp
        @foreach ($result['by_item'] as $item)
        <tr>
            <td style="width:2%;">{{$no++}}</td>
            <td style="width:15%">{{$item->part_no}}</td>
            <td style="width:27%">{{$item->descript}}</td>
            <td align="right" style="width:18%">{{$item->qty_sj}} {{$item->unit}}</td>
            <td align="right" style="width:18%">{{ ($item->item_price_new == 0) ? rupiah(addZero($item->item_price)) : rupiah(addZero($item->item_price_new))}}</td>
            <td align="right" style="width:20%">{{rupiah(addZero($item->item_price_hasil))}}</td>
        </tr>
        @endforeach
    </table>
    <table class="table-item" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="5" align="center">============================================</td>
            <td align="right">{{$subtotal}}</td>
        </tr>
        <tr>
            <td align="right" colspan="6">0.00</td>
        </tr>
        <tr>
            <td align="right" colspan="6">{{$subtotal}}</td>
        </tr>
        <tr>
            <td align="right" colspan="6">{{$tax}}</td>
        </tr>
        <tr>
            <td align="right" colspan="6">0.00</td>
        </tr>
        <tr>
            <td align="right" colspan="6">{{$balance}}</td>
        </tr>
    </table>
    <div class="footer">
        <div class="ttd-tgl text-center">{{ bulan(date('Y-m-d')) }}</div>
        <div class="ttd-nama text-center">{{auth()->user()->FullName}}</div>
    </div>
</body>
</html>