<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


	<center><h4><b>INTERNAL PROCESS (MTO)</b></h4></center>
	<br>
	<br>
	<table class="table">
		
		<tbody>
			<tr>
				<td style="font-size: 11px"><b>No. / Date</b></td>
				<td style="width: 1px">:</td>
				<td style="font-size: 11px">{{ $data->mto_no }} - {{ \Carbon\Carbon::parse($data->written)->format('d/m/Y') }}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				{{-- <td></td>
				<td></td> --}}
				{{-- <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> --}}
				<td style="font-size: 11px" ><b>FG Code</b></td>
				<td style="width: 40px">:</td>
                <td style="font-size: 11px">{{ $data->frm_code }} - {{ $data->descript }}</td>


			</tr>
			<tr>
				<td style="font-size: 11px"><b>Ref No</b></td>
				<td style="width: 40px">:</td>
				<td style="font-size: 11px">{{ $data->mto_no }}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				{{-- <td></td>
				<td></td> --}}
				{{-- <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> --}}
				<td style="font-size: 11px"><b>Qty In/Ng</b></td>
				<td style="width: 40px">:</td>
                <td style="font-size: 11px">{{ $data->quantity }}.00/ {{ $data->qty_ng }}.00</td>
			</tr>
			<tr>
				<td style="font-size: 11px"><b>Remark</b></td>
				<td style="width: 40px">:</td>
				<td style="font-size: 11px">{{ $data->remark }}</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				{{-- <td></td>
				<td></td> --}}
				{{-- <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> --}}
				<td style="font-size: 11px"><b>Type/Wh</b></td>
				<td style="width: 40px">:</td>
                <td style="font-size: 11px"> {{ $data->types }} / {{ $data->warehouse }}</td>
			</tr>

		</tbody>
    <br>
	</table>
	<table class="table table-striped table-bordered" cellpadding="3" cellspacing="0" border="1" width="100%">
		<thead>
			<tr style="height: 21px;">
				<th style="font-size: 11px">&nbsp;No</th>
				<th style="font-size: 11px">&nbsp;Kode/Nama Barang</th>
                <th style="font-size: 11px">&nbsp;Jumlah</th>
                <th style="font-size: 11px">&nbsp;Keterangan</th>
			</tr>
		</thead>
		<tbody style="text-align: center">
            @php $no = 1; @endphp
            <tr>
            <td style="font-size: 11px">{{ $no++ }}</td>
            <td style="font-size: 11px">{{ $data->fin_code }} / {{ $data->descript }}</td>
            <td style="font-size: 11px">{{ $data->quantity }}.00 {{ $data->unit }}</td>
            <td style="font-size: 11px">..............</td>
            </tr>
		</tbody>
	</table>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<hr>
<p style="font-size: 13px">&nbsp;&nbsp;&nbsp;Tanda Terima
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengirim
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ka. Gudang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jakarta, Date tgl
</p>
<br>
<br>
<br>
<p style="font-size: 13px">(...........................)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........................)
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...........................)
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    (...................................)
</p>
<hr>
<p style="font-size: 13px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Barang sudah diterima dalam keadaan baik dan benar</p>
</body>
</html