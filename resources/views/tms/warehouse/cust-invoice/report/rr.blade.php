<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report - RR Form</title>
    <style>
        .item {
            width: 100%;
            
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .company {
            font-size: 16px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="company">PT TRIMITRA CHITRAHASTA</div>
    <div class="company">LISTING NOMOR SURAT JALAN</div>
    <div style="margin-top: 10px;margin-bottom: 5px;font-weight:800;font-size: 16px;">Nomor Invoice: TCH/01</div>
    <table class="item" border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th class="text-left">No</th>
            <th class="text-center">No RR</th>
            <th class="text-center">Tanggal RR</th>
            <th class="text-center">No SJ</th>
            <th class="text-center">Tanggal Stempel Security</th>
        </tr>
        @php $no=1; @endphp
        @foreach ($result['by_do'] as $item)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$item->rr_no}}</td>
            <td class="text-center">{{convertDate($item->rr_date, 'Y-m-d', 'd/m/Y')}}</td>
            <td class="text-center">{{$item->do_no}}</td>
            <td class="text-center">{{convertDate($item->scurity_stamp, 'Y-m-d', 'd/m/Y')}}</td>
        </tr>
        @endforeach
    </table>
    <div style="text-align:center;font-weight:bold;margin-top:20px;">*** End of Listing ***</div>
</body>
</html>