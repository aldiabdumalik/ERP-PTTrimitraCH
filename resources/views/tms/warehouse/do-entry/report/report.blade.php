@for ($i = 0; $i < count($getKey); $i++)

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>@page { size: 16.5cm 24.3cm landscape; }</style>
</head>
<body>
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th width="50%" align="left">PT TRIMITRA CHITRAHASTA</th>
            <th width="50%" align="right"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($getKey[$i], 'C39', 1.5, 20) }}" alt="barcode" /></th>
        </tr>
        <tr>
            <th colspan="2" height="80px"> <u>SURAT JALAN</u> </th>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px">
        <tr>
            <td width="40%">
                <table cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                        {{-- <td height="15px" width="30%">P/O No.</td>
                        <td height="15px" width="10%">:</td> --}}
                        <td height="15px" width="60%">{{$groupItem[$getKey[$i]][0]->po_no}}</td>
                    </tr>
                    <tr>
                        {{-- <td height="15px" width="30%">TANGGAL</td>
                        <td height="15px" width="10%">:</td> --}}
                        <td height="15px" width="60%">{{\Carbon\Carbon::createFromFormat('Y-m-d', $groupItem[$getKey[$i]][0]->delivery_date)->format('d/m/Y')}}</td>
                    </tr>
                    <tr>
                        {{-- <td height="15px" width="30%">KEND. No.</td>
                        <td height="15px" width="10%">:</td> --}}
                        <td height="15px" width="60%">{{$groupItem[$getKey[$i]][0]->remark}}</td>
                    </tr>
                    <tr><td colspan="3" height="15px">{{$groupItem[$getKey[$i]][0]->do_no}}</td></tr>
                    <tr><td colspan="3" height="15px"></td></tr>
                    <tr><td colspan="3" height="15px"></td></tr>
                </table>
            </td>
            <td width="60%">
                <table cellpadding="3" cellspacing="0" width="100%">
                    <tr>
                        <td height="15px">Kepada Yth.</td>
                    </tr>
                    <tr>
                        <td height="15px">{{$groupItem[$getKey[$i]][0]->cust_name}}</td>
                    </tr>
                    <tr>
                        <td height="15px">{{$groupItem[$getKey[$i]][0]->address1}}</td>
                    </tr>
                    <tr>
                        <td height="15px">{{$groupItem[$getKey[$i]][0]->address2}}</td>
                    </tr>
                    <tr>
                        <td height="15px">{{$groupItem[$getKey[$i]][0]->address3}}</td>
                    </tr>
                    <tr>
                        <td height="15px">{{$groupItem[$getKey[$i]][0]->address4}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($data->type == 'blank')
    <table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-size:12px">
    @else
    <table border="0" cellpadding="5" cellspacing="0" width="100%" style="font-size:12px">
    @endif
        <tr>
            <th>NO.</th>
            <th>NAMA BARANG/NO PART</th>
            <th>KODE BARANG</th>
            <th>QTY</th>
            <th>DN NO.</th>
        </tr>
        @php $no=1; @endphp;
        @foreach($groupItem[$getKey[$i]] as $item)
        <tr>
            <td align="center" style="font-size: 12px">{{ $no++ }}</td>
            <td align="center" style="font-size: 12px">{{ $item->part_name }} / {{ $item->part_no }}</td>
            <td align="center" style="font-size: 12px">{{ $item->item_code }}</td>
            <td align="center" style="font-size: 12px">{{ $item->quantity }}.00 {{ $item->unit}}</td>
            <td align="center" style="font-size: 12px">{{ $item->dn_no }}</td>
        </tr>
        @endforeach
    </table>
</body>

@endfor