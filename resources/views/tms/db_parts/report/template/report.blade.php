<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }
    .header {
        display: table;
        /* border: 1px solid #000; */
        width: 100%;
        padding: 15px;
        font-size: 12px;
    }
    .header1 {
        display: table-cell;
        width: 25%;
    }
    .header2 {
        display: table-cell;
        width: 50%;
    }
    .header3 {
        display: table-cell;
        width: 25%;
    }
    </style>
</head>
<body>
    <div class="header">
        <div class="header1">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td colspan="3">
                        PT. Trimitra Chitrahasta <br/>
                        <i>PDE Dept.</i><br/>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="20%">Customer</td>
                    <td width="5%">:</td>
                    <td>{{ $params[0]->cust_name }}</td>
                </tr>
                <tr>
                    <td width="20%">Type</td>
                    <td width="5%">:</td>
                    <td>{{ $params[0]->type }}</td>
                </tr>
                <tr>
                    <td width="20%">Reff</td>
                    <td width="5%">:</td>
                    <td>{{ $params[0]->reff }}</td>
                </tr>
            </table>
        </div>
        <div class="header2">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center"><h1>DRAFT DATA BASE PARTS</h1></td>
                </tr>
                <tr>
                    <td align="center"><i>ISSUED DATE : November 10th. 2021</i></td>
                </tr>
            </table>
        </div>
        <div class="header3">
            <table border="1" cellpadding="20" cellspacing="0" width="100%" style="font-size: 7px;">
                <tr>
                    <td align="center">No.</td>
                    <td align="center">DATE</td>
                    <td align="center">REVISION</td>
                    <td align="center">PIC</td>
                </tr>
            </table>
        </div>
    </div>
    <div style="padding: 10px;">
        <table border="1" cellpadding="20" cellspacing="0" width="100%" style="font-size: 7px;">
            <tr>
                <td align="center" rowspan="3">No.</td>
                <td align="center" rowspan="3">Part No</td>
                <td align="center" rowspan="3">Part Name</td>
                <td align="center" rowspan="3">Picture</td>
                <td align="center" rowspan="3">Vol/Mtd</td>
                <td align="center" rowspan="3">Process</td>
                <td align="center" rowspan="3">ITEM CODE</td>
                <td align="center" rowspan="3">PROD. CODE</td>
                <td align="center" rowspan="3">CYCLE TIME <br/> <i>(Sec)</i></td>
                <td align="center" rowspan="3">Tools/Parts Similliar</td>
                <td align="center">Qty</td>
                <td align="center" colspan="2" rowspan="2">Group of Parts</td>
                <td align="center" rowspan="3">Purch. <br/> Part</td>
                <td align="center" rowspan="3">Tonage</td>
                <td align="center" colspan="11">Press M/C Tonase</td>
                <td align="center">CNC</td>
                <td align="center">Weld.</td>
                <td align="center">Weld.</td>
                <td align="center" rowspan="3">Jig Weld <br/> (Spot/CO)</td>
                <td align="center" rowspan="3">Jig <br/> Assembly</td>
                <td align="center" rowspan="3">Jig Drill/Roamer/ <br/> Cutting</td>
                <td align="center" colspan="2">Insp. Jig/CF</td>
                <td align="center" colspan="3">Tools Maker</td>
                <td align="center" colspan="6">MATERIAL SPEC.</td>
                <td align="center" rowspan="3">PARTS <br/> WEIGHT <br/> <i>(g)</i></td>
                <td align="center" rowspan="3">Plan Mass Prod. <br/> Vendor <br/> Name</td>
                <td align="center" rowspan="3">Project <br/> Officer/ <br>Co. P/J Off.</td>
                <td align="center" rowspan="3">Last Technical Data Reff. <br/> (ECI/ECN/DN)</td>
                <td align="center" rowspan="3">Remarks</td>
            </tr>
            <tr>
                <td align="center">Parts</td>
                <td align="center">&lt;35</td>
                <td align="center">45</td>
                <td align="center">60</td>
                <td align="center">80</td>
                <td align="center">100</td>
                <td align="center">150</td>
                <td align="center">200</td>
                <td align="center" rowspan="2">300</td>
                <td align="center" rowspan="2">400</td>
                <td align="center">500</td>
                <td align="center">630</td>
                <td align="center">Bender</td>
                <td align="center">Spot</td>
                <td align="center">CO2</td>
                <td align="center" rowspan="2">Single/ Press/ Sub-</td>
                <td align="center" rowspan="2">Finish Part</td>
                <td align="center" rowspan="2">In <br>House</td>
                <td align="center" rowspan="2">Out <br>House</td>
                <td align="center" rowspan="2">Nama <br>(PT/CV/etc.)</td>
                <td align="center" rowspan="2">Spec</td>
                <td align="center">t</td>
                <td align="center">width</td>
                <td align="center">Length</td>
                <td align="center">N/</td>
                <td align="center" rowspan="2">Coil/<br/>Pitch</td>
            </tr>
            <tr>
                <td align="center">Item</td>
                <td align="center">Assy</td>
                <td align="center">Single</td>
                <td align="center">35</td>
                <td align="center">55</td>
                <td align="center">65</td>
                <td align="center">85</td>
                <td align="center">110</td>
                <td align="center">160</td>
                <td align="center">250</td>
                <td align="center">550</td>
                <td align="center">&gt;650</td>
                <td align="center">M/C</td>
                <td align="center">M/C</td>
                <td align="center">M/C</td>
                <td align="center">mm.</td>
                <td align="center">mm.</td>
                <td align="center">mm.</td>
                <td align="center">Strip</td>
            </tr>
            @php
                $no=0;
            @endphp
            @foreach ($params as $item)
            <tr>
                <td align="center">{{++$no}}</td>
                <td align="center">{{$item->part_no}}</td>
                <td align="center">{{$item->part_name}}</td>
                <td align="center" style="padding: 10px;"><img src="{{public_path('db-parts/pictures/'.$item->part_pict)}}" alt="" style="width: 100px;"></td>
                <td align="center">{{$item->part_vol}} <i>Pcs <br>Per Month</i></td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">{{$item->qty_part_item}}</td>
                <td align="center">{{($item->gop_assy == 1 ? 1 : '')}}</td>
                <td align="center">{{($item->gop_single == 1 ? 1 : '')}}</td>
                <td align="center">{{($item->purch_part == 1 ? 1 : '')}}</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">{{$item->spec}}</td>
                <td align="center">{{$item->ms_t}}</td>
                <td align="center">{{$item->ms_w}}</td>
                <td align="center">{{$item->ms_l}}</td>
                <td align="center">{{$item->ms_n_strip}}</td>
                <td align="center">{{$item->ms_coil_pitch}}</td>
                <td align="center">{{$item->part_weight}}</td>
                <td align="center">{{$item->vendor_name}}</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>