<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report - Kwitansi</title>
    <style>
        * {
            font-size: 14px;
        }
        .header{
            float: right;
        }
        .customer {
            width: 100%;
            padding-left: 100px;
            padding-top: 100px;
        }
        .nominal {
            width: 100%;
            padding-top: 100px;
            padding-left: 50px;
        }
        .pic {
            float: right;
            margin-top: 70px;
            margin-right: 100px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>{{date('Y')}}/KWTCH/{{$result['custinv'][0]->remark}}</div>
        <div>{{date('d/m/Y')}}</div>
    </div>
    <div class="customer">
        <div class="custname">{{$result['custinv'][0]->cust_name}}</div>
        <div class="terbilang">{{$terbilang}} Rupiah</div>
        <div style="margin-top: 50px">Tagihan sesuai faktur penjualan : {{$result['custinv'][0]->ref_no}}</div>
    </div>
    <div class="nominal">{{$balance}}</div>
    <div class="pic">{{auth()->user()->FullName}}</div>
</body>
</html>