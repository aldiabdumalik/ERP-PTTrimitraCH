<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Surat Jalan</title>
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
    </style>
</head>
<body>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 545;
            $y = 30;
            $text = "Page: {PAGE_NUM}";
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
                <td style="width:30%;">{{ date('F d, Y H:i A') }}, {{ auth()->user()->FullName }}</td>
                <td style="width:40%;text-align:center;font-weight:bold;">
                    <div>PT TRIMITRA CHITRAHASTA</div>
                    <div>List of Surat Jalan for Invoice No.{{$result['custinv'][0]->inv_no}}</div>
                </td>
                <td style="width:30%;"></td>
            </tr>
        </table>
    </div>
    <table class="sub-item" cellspacing="0" page-break-inside: auto;>
        <thead>
            <tr>
                <td colspan="8" style="height: 48px!important;"></td>
            </tr>
        </thead>
        <thead>
            <tr>
                <th>No. SJ</th>
                <th>RR No.</th>
                <th>DN No.</th>
                <th>PO No.</th>
                <th>Qty</th>
                <th>Sub Ammount</th>
                <th>VAT</th>
                <th>Total</th>
            </tr>
        </thead>
        @php
            $qty_tot = 0;
            $vat = $result['custinv'][0]->tax_rate;
        @endphp
        @foreach ($result['by_do'] as $item)
        @php
            $qty_tot += $item->tot_qty;
            $vat_tot = ($item->sub_ammount * $vat) / 100;
        @endphp
        <tr>
            <td class="text-center">{{ $item->do_no }}</td>
            <td class="text-center">{{ $item->rr_no }}</td>
            <td class="text-center">{{ $item->dn_no }}</td>
            <td class="text-center">{{ $item->po_no }}</td>
            <td class="text-right">{{ rupiah(addZero($item->tot_qty)) }}</td>
            <td class="text-right">{{ rupiah(addZero($item->sub_ammount)) }}</td>
            <td class="text-right">{{ rupiah(addZero( $vat_tot )) }}</td>
            <td class="text-right">{{ rupiah(addZero($item->sub_ammount + $vat_tot)) }}</td>
        </tr>
        @endforeach
        <tr>
            <th class="text-right" colspan="4">GRAND TOTAL :</th>
            <th class="text-right">{{ rupiah(addZero($qty_tot)) }}</th>
            <th class="text-right">{{ $subtotal }}</th>
            <th class="text-right">{{ $tax }}</th>
            <th class="text-right">{{ $balance }}</th>
        </tr>
    </table>
</body>
</html>