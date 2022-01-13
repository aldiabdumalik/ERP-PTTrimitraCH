@for ($i = 0; $i < count($getKey); $i++)

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print SJ</title>
    @include('tms.warehouse.do-entry.report.cssReport')
</head>
<body>
    <fieldset class="sq-cops">
        <fieldset class="sq-cops1">
            PT TRIMITRA CHITRAHASTA
        </fieldset>
        <fieldset class="sq-cops1" style="text-align:right">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($getKey[$i], 'C39', 1.5, 18) }}" alt="barcode" />
        </fieldset>
        <fieldset class="sq-cops2">
            SURAT JALAN
        </fieldset>
    </fieldset>
    <fieldset class="sq-header">
        <fieldset class="sq-no">
            <div class="sq-no-row">
                <div class="sq-no-col-1">NOMOR</div>
                <div class="sq-no-col-2">:</div>
                <div class="sq-no-col-3">{{ $groupItem[$getKey[$i]][0]->do_no }}</div>
            </div>
            <div class="sq-no-row">
                <div class="sq-no-col-1">P/O NO.</div>
                <div class="sq-no-col-2">:</div>
                <div class="sq-no-col-3">{{ $groupItem[$getKey[$i]][0]->po_no }}</div>
            </div>
            <div class="sq-no-row">
                <div class="sq-no-col-1">TANGGAL</div>
                <div class="sq-no-col-2">:</div>
                <div class="sq-no-col-3">{{\Carbon\Carbon::createFromFormat('Y-m-d', $groupItem[$getKey[$i]][0]->delivery_date)->format('d/m/Y')}}</div>
            </div>
            <div class="sq-no-row">
                <div class="sq-no-col-1">KEND. NO.</div>
                <div class="sq-no-col-2">:</div>
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
        <div style="clear: both;">
            Dengan hormat, <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bersama ini kami mengirimkan barang-barang yang tersebut sbb :
        </div>
    </fieldset>
    <fieldset class="sq-utama">
        <fieldset class="sq-1">
            <div class="sq-1-header">
                <p style="padding:3px;font-weight:bold;">NO.</p>
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
                <p style="padding:3px;font-weight:bold;">NO. PART / NAMA BARANG</p>
            </div>
            <div class="sq-2-row">
                <div class="sq-2-col">
                    <div style="line-height: 20px;font-size: 12px;">
                        @foreach($groupItem[$getKey[$i]] as $item)
                        <div style="text-align: left;padding-left: 10px;">{{ $item->part_no }}</div>
                        @endforeach
                    </div>
                </div>
                <div class="sq-2-col">
                    <div style="line-height: 20px;font-size: 12px;">
                        @foreach($groupItem[$getKey[$i]] as $item)
                        <div style="text-align: left;padding-left: 10px;">{{ $item->part_name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="sq-3">
            <div class="sq-3-header">
                <p style="padding:3px;font-weight:bold;">QTY</p>
            </div>
            <div style="line-height: 20px;font-size: 12px;">
                @foreach($groupItem[$getKey[$i]] as $item)
                <div style="text-align: right;padding-right: 10px;">{{ rupiah(addZero($item->quantity)) }} {{ $item->unit }}</div>
                @endforeach
            </div>
        </fieldset>
        <fieldset class="sq-4">
            <div class="sq-4-header">
                <p style="padding:3px;font-weight:bold;">KET.</p>
            </div>
            <div style="line-height: 20px;font-size: 12px;">
                @foreach($groupItem[$getKey[$i]] as $item)
                <div style="text-align: center">{{ $item->dn_no }}</div>
                @endforeach
            </div>
        </fieldset>
        <fieldset class="sq-ttd">
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi">
                    DITERIMA OLEH, 
                    <br>
                    <br>
                    <br>
                    (
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;
                    )
                </div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi">
                    Pengirim, 
                    <br>
                    <br>
                    <br>
                    (
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;
                    )
                </div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi">
                    Satpam, 
                    <br>
                    <br>
                    <br>
                    (
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;
                    )
                </div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi">
                    Gudang, 
                    <br>
                    <br>
                    <br>
                    (
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;
                    )
                </div>
            </fieldset>
            <fieldset class="sq-ttd-col">
                <div class="sq-ttd-isi">
                    HORMAT KAMI, 
                    <br>
                    <br>
                    <br>
                    (
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;
                    )
                </div>
            </fieldset>
        </fieldset>
    </fieldset>
    <fieldset class="footer">
        <div class="foot-1">
            LD - PPC - 013 Rev. 2
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;
            Putih Asli : Customer (Saat Penagihan), Merah : Finance, Putih, Biru : Customer, Hijau : PPIC,
        </div>
    </fieldset>
</body>

@endfor