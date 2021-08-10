@for ($i = 0; $i < count($getKey); $i++)

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * {margin: 0;}
        @page { size: 16.4cm 24.3cm landscape; }
        body {
            padding-left: 1.9cm;
            padding-right: 1.9cm;
            /* padding-top: 0.9cm; */
        }
        .sq-header {
            width: 20.3cm;
            height: 2.6cm;
            padding: 0;
            margin: 0;
            border: 1px solid transparent;
        }
        .sq-no {
            width: 10cm;
            height: 2.6cm;
            float: left;
            border: none;
            padding: 0;
        }
        .sq-cust span {
            display: block;
            font-weight: normal;
        }
        .sq-cust {
            width: 10cm;
            height: 2.6cm;
            float: left;
            padding: 0;
            padding: 0 5px 0 5px;
            border: 1px solid transparent;
            font-size: 12px;
            font-weight: bold;
        }
        .sq-dgnhor {
            height: 0.7cm;
            width: 20.3cm;
            font-size: 12px;
            text-align: left;
            padding: 0;
            padding-bottom: 4px;
            margin: 0;
            border: none;
        }
        .sq-utama {
            width: 20.3cm;
            height: 10cm;
            border: 1px solid transparent;
            padding: 0;
            margin: 0;
        }
        .sq-1 {
            width: 1.2cm; 
            height: 7.8cm;
            margin: 0;
            padding: 0;
            float: left;
            border: none;
            border-bottom: 1px solid transparent;
        }
        .sq-2 {
            width: 9.7cm; 
            height: 7.8cm;
            margin: 0;
            padding: 0;
            float: left;
            border: 1px solid transparent;
            border-top: none;
        }
        .sq-3 {
            width: 4.4cm; 
            height: 7.8cm;
            margin: 0;
            padding: 0;
            float: left;
            border: none;
            border-right: 1px solid transparent;
            border-bottom: 1px solid transparent;
        }
        .sq-4 {
            width: 4.9cm; 
            height: 7.8cm;
            margin: 0;
            padding: 0;
            float: left;
            border: none;
            border-bottom: 1px solid transparent;
        }
        .sq-1-header {
            width: 1.2cm;
            height: 0.8cm;
            margin: 0;
            padding: 0;
            text-align: center;
            border: none;
            border-bottom: 1px solid transparent;
        }
        .sq-2-header {
            width: 9.7cm;
            height: 0.8cm;
            margin: 0;
            padding: 0;
            text-align: center;
            border: none;
            border-bottom: 1px solid transparent;
        }
        .sq-3-header {
            width: 4.4cm;
            height: 0.8cm;
            margin: 0;
            padding: 0;
            text-align: center;
            border: none;
            border-bottom: 1px solid transparent;
        }
        .sq-4-header {
            width: 4.9cm;
            height: 0.8cm;
            margin: 0;
            padding: 0;
            text-align: center;
            border: none;
            border-bottom: 1px solid transparent;
        }
        .footer {
            width: 20.3cm;
            height: auto;
            position: absolute;
            bottom: 3px;
            font-size: 12px;
            padding: 0;
            margin: 0;
            border: none;
        }
        .foot-1 {
            display: inline-block;
            overflow: auto;
        }
        .foot-2 {
            display: inline-block;
            float: right;
            overflow: auto;
        }
        .sq-cops {
            width: 20.3cm;
            height: 2.2cm;
            padding: 0;
            margin: 0;
            border: none;
        }
        .sq-cops1 {
            width: 50%; 
            height: 1.1cm;
            padding: 0;
            margin: 0;
            padding-top: 0.5cm;
            float: left;
            border: none;
            font-size: 13px;
        }
        .sq-cops2 {
            width: 20.3cm;
            height: 1.1cm;
            padding: 0;
            margin: 0;
            border: none;
            font-size: 16px;
            text-align: center;
            font-weight: bold;
        }
        .sq-ttd {
            display: table;
            width: 20.3cm;
            height: 2.2cm;
            padding: 0;
            margin: 0;
            position: absolute;
            bottom: 26px;
            border: none;
        }
        .sq-ttd-col {
            display: table-cell;
            width: 20%;
            height: 2.2cm;
            padding: 0;
            margin: 0;
            border: none;
            /* border-right: 0.5px solid transparent; */
        }
        .sq-ttd-col:last-child {
            border-right: none;
        }
        .sq-ttd-isi {
            font-size: 14px;
            padding: 10px;
            text-align: center;
        }


        .sq-no-row {
            display: table;
            width: 100%;
            height: auto;
        }
        .sq-no-col-1 {
            display: table-cell;
            width: 20%;
            text-align: left;
            /* border: 0.1px solid blue; */
        }
        .sq-no-col-2 {
            display: table-cell;
            width: 5%;
            text-align: left;
            /* border: 0.1px solid blue; */
        }
        .sq-no-col-3 {
            display: table-cell;
            width: 50%;
            text-align: left;
            /* border: 0.1px solid blue; */
        }
    </style>
</head>
<body>
    <fieldset class="sq-cops">
        <fieldset class="sq-cops1">
            &nbsp;
        </fieldset>
        <fieldset class="sq-cops1" style="text-align:right">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($getKey[$i], 'C39', 1.5, 18) }}" alt="barcode" />
        </fieldset>
        <fieldset class="sq-cops2"></fieldset>
    </fieldset>
    <fieldset class="sq-header">
        <fieldset class="sq-no">
            <div class="sq-no-row">
                <div class="sq-no-col-1">&nbsp;</div>
                <div class="sq-no-col-2">&nbsp;</div>
                <div class="sq-no-col-3">{{ $groupItem[$getKey[$i]][0]->do_no }}</div>
            </div>
            <div class="sq-no-row">
                <div class="sq-no-col-1">&nbsp;</div>
                <div class="sq-no-col-2">&nbsp;</div>
                <div class="sq-no-col-3">{{ $groupItem[$getKey[$i]][0]->po_no }}</div>
            </div>
            <div class="sq-no-row">
                <div class="sq-no-col-1">&nbsp;</div>
                <div class="sq-no-col-2">&nbsp;</div>
                <div class="sq-no-col-3">{{\Carbon\Carbon::createFromFormat('Y-m-d', $groupItem[$getKey[$i]][0]->delivery_date)->format('d/m/Y')}}</div>
            </div>
            <div class="sq-no-row">
                <div class="sq-no-col-1">&nbsp;</div>
                <div class="sq-no-col-2">&nbsp;</div>
                <div class="sq-no-col-3"></div>
            </div>
        </fieldset>
        <fieldset class="sq-cust">
            <span>Kepada Yth.</span>
            <span>{{ $groupItem[$getKey[$i]][0]->cust_name }}</span>
            <span>{{ $groupItem[$getKey[$i]][0]->address1 }}</span>
            <span>{{ $groupItem[$getKey[$i]][0]->address2 }}</span>
            <span>{{ $groupItem[$getKey[$i]][0]->address3 }}</span>
            <span>{{ $groupItem[$getKey[$i]][0]->address4 }}</span>
        </fieldset>
    </fieldset>
    <fieldset class="sq-dgnhor">
        <div style="clear: both;"></div>
    </fieldset>
    <fieldset class="sq-utama">
        <fieldset class="sq-1">
            <div class="sq-1-header">
                <p style="padding:3px;font-weight:bold;">&nbsp;</p>
            </div>
            <div style="line-height: 20px;font-size: 12px;">
                @php $no=1; @endphp
                @foreach($groupItem[$getKey[$i]] as $item)
                <div style="text-align: center">{{ $no++ }}</div>
                @endforeach
            </div>
        </fieldset>
        <fieldset class="sq-2">
            <div class="sq-2-header">
                <p style="padding:3px;font-weight:bold;">&nbsp;</p>
            </div>
            <div style="line-height: 20px;font-size: 12px;">
                @foreach($groupItem[$getKey[$i]] as $item)
                <div style="text-align: left;padding-left: 10px;">{{ $item->part_no }} / {{ $item->part_name }}</div>
                @endforeach
            </div>
        </fieldset>
        <fieldset class="sq-3">
            <div class="sq-3-header">
                <p style="padding:3px;font-weight:bold;">&nbsp;</p>
            </div>
            <div style="line-height: 20px;font-size: 12px;">
                @foreach($groupItem[$getKey[$i]] as $item)
                <div style="text-align: right;padding-right: 10px;">{{ $item->quantity }}.00 {{ $item->unit }}</div>
                @endforeach
            </div>
        </fieldset>
        <fieldset class="sq-4">
            <div class="sq-4-header">
                <p style="padding:3px;font-weight:bold;">&nbsp;</p>
            </div>
            <div style="line-height: 20px;font-size: 12px;">
                @foreach($groupItem[$getKey[$i]] as $item)
                <div style="text-align: center">{{ $item->dn_no }}</div>
                @endforeach
            </div>
        </fieldset>
        <fieldset class="sq-ttd">
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi"></div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi"></div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi"></div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi"></div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi"></div>
            </fieldset>
        </fieldset>
    </fieldset>
    <fieldset class="footer">
        <div class="foot-1"></div>
    </fieldset>
</body>

@endfor