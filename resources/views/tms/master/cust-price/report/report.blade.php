<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Price List</title>
    <style>
        * {
            font-size: 12px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .cop {
            position: fixed;
            padding: 25px;
        }
        .header {
            width: 100%;
        }
        .header td {
            vertical-align: bottom;
        }
        .item {
            width: 100%;
            margin-top: 10px;
            font-size: 12px!important;
        }
        .item th {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            padding: 5px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .sub-item {
            width: 100%;
            padding-left: 25px;
            padding-right: 25px;

        }
        .sub-item td {
            padding: 5px;
        }
        .sub-item th {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            padding: 5px;
        }
        .text-bold {
            font-weight: bold;
        }
        .text-danger {
            color: red;
        }
        .text-miring {
            font-style: italic;
        }
    </style>
</head>
<body style="margin-bottom: 100px;">
    <script type="text/php">
        if (isset($pdf)) {
            $x = 535;
            $y = 80;
            $text = "Page: {PAGE_NUM}/{PAGE_COUNT}";
            $font = null;
            $size = 10;
            $color = array(0,0,0);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
    <div class="cop">
        <table class="header" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:70%;font-weight:bold;line-height:1.4;">
                    <div>PT TRIMITRA CHITRAHASTA</div>
                    <div>CUSTOMER PRICE LIST ENTRY</div>
                </td>
                <td style="width:30%;"></td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" style="margin-top: 15px;width:100%;">
            <tr>
                <td style="width: 14%;line-height:1.4;" class="text-bold">Customer Id</td>
                <td style="width: 2%;">:</td>
                <td colspan="4">{{$query[0]->cust_id}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$query[0]->cust_name}}</td>
            </tr>
            <tr>
                <td style="width: 14%;line-height:1.4;" class="text-bold">Updated/Method</td>
                <td style="width: 2%;">:</td>
                <td style="width: 43%;">{{convertDate($query[0]->active_date, 'Y-m-d', 'd/m/Y')}} - By {{$query[0]->price_by}}</td>
                <td class="text-bold" style="width:8%;">Operator</td>
                <td style="width: 2%;">:</td>
                <td>{{auth()->user()->FullName}}</td>
            </tr>
            <tr>
                <td style="width: 14%;line-height:1.4;" class="text-bold">Currency</td>
                <td style="width: 2%;">:</td>
                <td style="width: 43%;">{{$query[0]->currency}}</td>
                <td class="text-bold" style="width:8%;">Tgl Entry</td>
                <td style="width: 2%;">:</td>
                <td>{{convertDate($query[0]->created_date, 'Y-m-d H:i:s', 'd/m/Y')}}</td>
            </tr>
        </table>
    </div>
    <table class="sub-item" cellspacing="0" page-break-inside: auto;>
        <thead>
            <tr>
                <td colspan="8" style="height: 118px!important;"></td>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="width:3%" align="left">#</th>
                <th colspan="3" align="left" style="width:67%">Part No. / Description</th>
                <th align="right" style="width:15%">New Price</th>
                <th align="right" style="width:15%">Old Price</th>
            </tr>
        </thead>
        @foreach ($query as $i => $item)
        <tr>
            <td align="left" style="width: 3%;">{{++$i}}</td>
            <td align="left" style="width: 10%;">{{$item->part_no}}</td>
            <td align="left" style="width: 10%;">{{$item->item_code}}</td>
            <td align="left" style="width: 47%;">{{$item->desc}}</td>
            <td align="right" style="width:15%" class="{{ ($item->is_update == 1) ? 'text-danger text-miring text-bold' : '' }}">{{rupiah(addZero4($item->price_new))}}</td>
            <td align="right" style="width:15%">{{rupiah(addZero4($item->price_old))}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>