<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Parts Report</title>
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
    .p-td {
        padding-left: 1.5px;
        padding-right: 1.5px;
    }
    .rev {
        width: 0px;
        height: 0px;
        border-style: inset;
        border-width: 0 10px 15px 10px;
        border-color: transparent transparent red transparent;
        /* float: left; */
        transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -webkit-transform: rotate(360deg);
        -o-transform: rotate(360deg);
    }
    .rev p {
        text-align: center;
        top: 5px;
        left: -3px;
        position: relative;
        width: 5px;
        height: 5px;
        margin: 0px;
        color: white;
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
                    <td>{{ $project->custname }}</td>
                </tr>
                <tr>
                    <td width="20%">Type</td>
                    <td width="5%">:</td>
                    <td>{{ $project->type }}</td>
                </tr>
                <tr>
                    <td width="20%">Reff</td>
                    <td width="5%">:</td>
                    <td>{{ $project->reff }}</td>
                </tr>
            </table>
        </div>
        <div class="header2">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center"><h1>DATABASE PARTS</h1></td>
                </tr>
                <tr>
                    <td align="center"><i>ISSUED DATE : {{ date('F jS. Y') }}</i></td>
                </tr>
            </table>
        </div>
        <div class="header3">
            {{-- <table border="1" cellpadding="20" cellspacing="0" width="100%" style="font-size: 7px;">
                <tr>
                    <td align="center">No.</td>
                    <td align="center">DATE</td>
                    <td align="center">REVISION</td>
                    <td align="center">PIC</td>
                </tr>
                @php $no=0; @endphp
                @foreach ($log as $l)
                <tr>
                    <td align="center">{{++$no}}</td>
                    <td align="center">{{ $l->date_log  }}</td>
                    <td align="left">{{$l->note}}</td>
                    <td align="center">{{$l->log_by}}</td>
                </tr>
                @endforeach 
            </table> --}}
        </div>
    </div>
    <div style="padding: 10px;">
        <table border="1" cellpadding="20" cellspacing="0" width="100%" style="font-size: 7px;">
            <tr>
                <td align="center" rowspan="3" class="p-td">No.</td>
                <td align="center" rowspan="3" class="p-td">Part No</td>
                <td align="center" rowspan="3" class="p-td">Part Name</td>
                <td align="center" rowspan="3" class="p-td">Picture</td>
                <td align="center" rowspan="3" class="p-td">Vol/Mtd</td>
                <td align="center" rowspan="3" class="p-td">Process</td>
                <td align="center" rowspan="3" class="p-td">ITEM CODE</td>
                <td align="center" rowspan="3" class="p-td">PROD. CODE</td>
                <td align="center" rowspan="3" class="p-td">CYCLE TIME <br/> <i>(Sec)</i></td>
                <td align="center" rowspan="3" class="p-td">Tools/Parts Similliar</td>
                <td align="center" class="p-td">Qty</td>
                <td align="center" colspan="2" rowspan="2" class="p-td">Group of Parts</td>
                <td align="center" rowspan="3" class="p-td">Purch. <br/> Part</td>
                <td align="center" rowspan="3" class="p-td">Tonage</td>
                <td align="center" colspan="11" class="p-td">Press M/C Tonase</td>
                <td align="center" class="p-td">CNC</td>
                <td align="center" class="p-td">Weld.</td>
                <td align="center" class="p-td">Weld.</td>
                <td align="center" rowspan="3" class="p-td">Jig Weld <br/> (Spot/CO)</td>
                <td align="center" rowspan="3" class="p-td">Jig <br/> Assembly</td>
                <td align="center" rowspan="3" class="p-td">Jig Drill/Roamer/ <br/> Cutting</td>
                <td align="center" colspan="2" class="p-td">Insp. Jig/CF</td>
                <td align="center" colspan="3" class="p-td">Tools Maker</td>
                <td align="center" colspan="6" class="p-td">MATERIAL SPEC.</td>
                <td align="center" rowspan="3" class="p-td">PARTS <br/> WEIGHT <br/> <i>(g)</i></td>
                <td align="center" rowspan="3" class="p-td">Plan Mass Prod. <br/> Vendor <br/> Name</td>
                <td align="center" rowspan="3" class="p-td">Project <br/> Officer/ <br>Co. P/J Off.</td>
                <td align="center" rowspan="3" class="p-td">Last Technical Data Reff. <br/> (ECI/ECN/DN)</td>
                <td align="center" rowspan="3" class="p-td">Remarks</td>
            </tr>
            <tr>
                <td align="center" class="p-td">Parts</td>
                <td align="center" class="p-td">&lt;35</td>
                <td align="center" class="p-td">45</td>
                <td align="center" class="p-td">60</td>
                <td align="center" class="p-td">80</td>
                <td align="center" class="p-td">100</td>
                <td align="center" class="p-td">150</td>
                <td align="center" class="p-td">200</td>
                <td align="center" rowspan="2" class="p-td">300</td>
                <td align="center" rowspan="2" class="p-td">400</td>
                <td align="center" class="p-td">500</td>
                <td align="center" class="p-td">630</td>
                <td align="center" class="p-td">Bender</td>
                <td align="center" class="p-td">Spot</td>
                <td align="center" class="p-td">CO2</td>
                <td align="center" rowspan="2" class="p-td">Single/ Press/ Sub-</td>
                <td align="center" rowspan="2" class="p-td">Finish Part</td>
                <td align="center" rowspan="2" class="p-td">In <br>House</td>
                <td align="center" rowspan="2" class="p-td">Out <br>House</td>
                <td align="center" rowspan="2" class="p-td">Nama <br>(PT/CV/etc.)</td>
                <td align="center" rowspan="2" class="p-td">Spec</td>
                <td align="center" class="p-td">t</td>
                <td align="center" class="p-td">width</td>
                <td align="center" class="p-td">Length</td>
                <td align="center" class="p-td">N/</td>
                <td align="center" rowspan="2" class="p-td">Coil/<br/>Pitch</td>
            </tr>
            <tr>
                <td align="center" class="p-td">Item</td>
                <td align="center" class="p-td">Assy</td>
                <td align="center" class="p-td">Single</td>
                <td align="center" class="p-td">35</td>
                <td align="center" class="p-td">55</td>
                <td align="center" class="p-td">65</td>
                <td align="center" class="p-td">85</td>
                <td align="center" class="p-td">110</td>
                <td align="center" class="p-td">160</td>
                <td align="center" class="p-td">250</td>
                <td align="center" class="p-td">550</td>
                <td align="center" class="p-td">&gt;650</td>
                <td align="center" class="p-td">M/C</td>
                <td align="center" class="p-td">M/C</td>
                <td align="center" class="p-td">M/C</td>
                <td align="center" class="p-td">mm.</td>
                <td align="center" class="p-td">mm.</td>
                <td align="center" class="p-td">mm.</td>
                <td align="center" class="p-td">Strip</td>
            </tr>
            @php $no=0; @endphp
            @foreach ($res as $key => $item)
            @php
                $part_no = explode('|', $item['part_no']);
                $part_name = explode('|', $item['part_name']);
                $part_pict = explode('|', $item['part_pict']);
                $qty_part_item = explode('|', $item['qty_part_item']);
                $part_vol = explode('|', $item['part_vol']);
                $gop_assy = explode('|', $item['gop_assy']);
                $gop_single = explode('|', $item['gop_single']);
                $purch_part = explode('|', $item['purch_part']);
                $spec = explode('|', $item['spec']);
                $ms_t = explode('|', $item['ms_t']);
                $ms_l = explode('|', $item['ms_l']);
                $ms_w = explode('|', $item['ms_w']);
                $ms_n_strip = explode('|', $item['ms_n_strip']);
                $ms_coil_pitch = explode('|', $item['ms_coil_pitch']);
                $part_weight = explode('|', $item['part_weight']);
                $vendor_name = explode('|', $item['vendor_name']);
            @endphp
            <tr>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{++$no}}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$part_no[0]}} {!! (!empty($part_no[1])) ? $part_no[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$part_name[0]}} {!! (!empty($part_name[1])) ? $part_name[1] : null !!}</td>
                <td align="center" style="padding: 10px;" rowspan="{{ count($item['production']) }}"><img src="{{public_path('db-parts/pictures/'.$part_pict[0])}}" alt="" style="width: 50px;"></td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$part_vol[0]}} <i>Pcs <br>Per Month</i>{!! (!empty($part_vol[1])) ? $part_vol[1] : null !!}</td>
                @if (count($item['production']) > 0)
                <td align="center" class="p-td">{{$item['production'][0]['process_name']}} <br/> {{ $item['production'][0]['process_detail_name'] }}</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                @else
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                @endif
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$qty_part_item[0]}} {!! (!empty($qty_part_item[1])) ? $qty_part_item[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{($gop_assy[0] == 1 ? 1 : '')}} {!! (!empty($gop_assy[1])) ? $gop_assy[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{($gop_single[0] == 1 ? 1 : '')}} {!! (!empty($gop_single[1])) ? $gop_single[1] : null !!}</td>
                <td align="center" class="p-td">{{($purch_part[0] == 1 ? 1 : '')}} {!! (!empty($purch_part[1])) ? $purch_part[1] : null !!}</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$spec[0]}} {!! (!empty($spec[1])) ? $spec[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$ms_t[0]}} {!! (!empty($ms_t[1])) ? $ms_t[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$ms_w[0]}} {!! (!empty($ms_w[1])) ? $ms_w[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$ms_l[0]}} {!! (!empty($ms_l[1])) ? $ms_l[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$ms_n_strip[0]}} {!! (!empty($ms_n_strip[1])) ? $ms_n_strip[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$ms_coil_pitch[0]}} {!! (!empty($ms_coil_pitch[1])) ? $ms_coil_pitch[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$part_weight[0]}} {!! (!empty($part_weight[1])) ? $part_weight[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">{{$vendor_name[0]}} {!! (!empty($vendor_name[1])) ? $vendor_name[1] : null !!}</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">&nbsp;</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">&nbsp;</td>
                <td align="center" class="p-td" rowspan="{{ count($item['production']) }}">&nbsp;</td>
            </tr>
            @if (count($item['production']) > 0)
            @for ($i = 1; $i < count($item['production']); $i++)
            <tr>
                <td align="center" class="p-td">{{$item['production'][$i]['process_name']}} <br/> {{ $item['production'][$i]['process_detail_name'] }}</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
            </tr>
            @endfor
                {{-- @foreach($item['production'] as $prod)

            <tr>
                <td align="center" class="p-td">{{ $prod['process_detail_name'] }}</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
            </tr>

                @endforeach --}}
            @endif
            @endforeach
            {{-- @foreach ($params as $key => $item)
            <tr>
                <td align="center" class="p-td">{{++$no}}</td>
                <td align="center" class="p-td">{{$item->part_no}}</td>
                <td align="center" class="p-td">{{$item->part_name}}</td>
                <td align="center" style="padding: 10px;"><img src="{{public_path('db-parts/pictures/'.$item->part_pict)}}" alt="" style="width: 100px;"></td>
                <td align="center" class="p-td">{{$item->part_vol}} <i>Pcs <br>Per Month</i></td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">{{$item->qty_part_item}}</td>
                <td align="center" class="p-td">{{($item->gop_assy == 1 ? 1 : '')}}</td>
                <td align="center" class="p-td">{{($item->gop_single == 1 ? 1 : '')}}</td>
                <td align="center" class="p-td">{{($item->purch_part == 1 ? 1 : '')}}</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">{{$item->spec}}</td>
                <td align="center" class="p-td">{{$item->ms_t}}</td>
                <td align="center" class="p-td">{{$item->ms_w}}</td>
                <td align="center" class="p-td">{{$item->ms_l}}</td>
                <td align="center" class="p-td">{{$item->ms_n_strip}}</td>
                <td align="center" class="p-td">{{$item->ms_coil_pitch}}</td>
                <td align="center" class="p-td">{{$item->part_weight}}</td>
                <td align="center" class="p-td">{{$item->vendor_name}}</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
                <td align="center" class="p-td">&nbsp;</td>
            </tr>
            @endforeach --}}
        </table>
    </div>
</body>
</html>