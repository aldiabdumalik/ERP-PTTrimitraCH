<!DOCTYPE html>
<html>
<head>
	<title>Print Claim No. {{$data[0]->cl_no}}</title>
</head>
<style>
	    .footer { position: absolute; bottom: 0; }

</style>
<body>

    <h4 style="text-align: left"><b>PT TRIMITRA CHITRAHASTA</b></h4>
	<center><h4><b><u>SURAT JALAN</u></b></h4></center>
	<br>
	<br>
	<table class="table">
		
		<tbody>
			<tr>
				<td style="font-size: 12px"><b>P/O No</b></td>
				<td style="width: 1px">:</td>
				<td style="font-size: 12px">{{ $data[0]->po_no }}</td>
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
				<td style="font-size: 12px" ><b>Kepada Yth.</b></td>
				<td style="width: 40px"></td>
				<td style="font-size: 12px"></td>


			</tr>
			<tr>
				<td style="font-size: 12px"><b>TANGGAL</b></td>
				<td style="width: 40px">:</td>
				<td style="font-size: 12px">{{ \Carbon\Carbon::parse($data[0]->written)->format('d/m/Y') }}</td>
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
				<td style="font-size: 11px"><b>{{ $data[0]->company }}</b></td>
				<td style="width: 40px"></td>
				<td style="font-size: 12px"></td>
			</tr>
			<tr>
				<td style="font-size: 12px"><b>KEND. NO.</b></td>
				<td style="width: 40px">:</td>
				<td style="font-size: 12px">..................</td>
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
				<td style="font-size: 11px"><b>{{ $data[0]->addr1 }}</b></td>
				<td style="width: 40px"></td>
				<td style="font-size: 12px"> </td>
			</tr>
            <tr>
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
				<td></td>
				<td></td>
				<td></td>
				<td style="font-size: 11px"><b>{{ $data[0]->addr2 }}</b></td>
				<td style="width: 40px"></td>
				<td style="font-size: 12px"> </td>
			</tr>
            <tr>
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
				<td></td>
				<td></td>
				<td></td>
				<td style="font-size: 11px"><b>{{ $data[0]->addr3 }}</b></td>
				<td style="width: 40px"></td>
				<td style="font-size: 12px"> </td>
			</tr>
            <tr>
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
				<td></td>
				<td></td>
				<td></td>
				<td style="font-size: 11px"><b>{{ $data[0]->addr4 }}</b></td>
				<td style="width: 40px"></td>
				<td style="font-size: 12px"> </td>
			</tr>

		</tbody>
	</table>
    <br>
	<br>
    <p>Dengan Hormat,<br>Bersama ini kami mengirimkan barang-barang yang tersebut sbb:</p>
	<table class="table table-striped table-bordered" cellpadding="10" cellspacing="0" border="1" width="100%">
		<thead>
			<tr style="height: 21px;">
				<th style="font-size: 12px">&nbsp;NO.</th>
				<th style="font-size: 12px">&nbsp;NAMA BARANG/NO PART</th>
				<th style="font-size: 12px">&nbsp;KODE BARANG</th>
				<th style="font-size: 12px">&nbsp;QTY</th>
                <th style="font-size: 12px">&nbsp;KET</th>
			</tr>
		</thead>
		<tbody>
            @php $no =1; @endphp;
            @foreach($data as $row)
			<tr>
                <td style="font-size: 12px">{{ $no++ }}</td>
				<td style="font-size: 12px">{{ $row->descript }} / {{ $row->part_no }}</td>
				<td style="font-size: 12px">{{ $row->itemcode }}</td>
				<td style="font-size: 12px">{{ $row->qty }}.00</td>
				<td style="font-size: 12px">..............................</td>
			</tr>
            @endforeach
		</tbody>
	</table>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<div class="footer">
	<p style="font-size: 13px">&nbsp;&nbsp;DITERIMA OLEH
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		PENGIRIM
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SATPAM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DIKETAHUI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DIBUAT
	</p>
	<br>
	<br>
	<br>
	<p style="font-size: 13px">&nbsp;(...............................)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...............................)
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...............................)
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		(...............................)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(...............................)
	</p>
	<hr>
	<p style="font-size: 13px"> LD - PPC - 013 Rev.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Asli = PQE & MRP, Merah = Supplier, Kuning = Accounting, Hijau = Gudang </p>
	</div>
</body>
</html