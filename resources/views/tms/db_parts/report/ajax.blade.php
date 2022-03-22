<script>
$(document).ready(function () {
    const token_header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

    var tbl_customer;
    $(document).on('keypress keydown', '#partreport-index-customercode', function (e) {
        if(e.which == 13) {
            modalAction('#partreport-modal-customer').then((resolve) => {
                tbl_customer = $('#partreport-datatables-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('tms.db_parts.input_parts.header_tools') }}",
                        method: 'POST',
                        data: {"type": "customer"},
                        headers: token_header
                    },
                    columns: [
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                    ]
                });
            });
        }
        if(e.which == 8 || e.which == 46) { return false; }
        return false;
    });
    $(document).on('shown.bs.modal', '#partreport-modal-customer', function () {
        $('#partreport-datatables-customer_filter input').focus();
    });
    $('#partreport-datatables-customer').off('dblclick', 'tr').on('dblclick', 'tr', function () {
        var data = tbl_customer.row(this).data();
        modalAction('#partreport-modal-customer', 'hide').then(() => {
            $('#partreport-index-customercode').val(data.code);
            $('#partreport-index-customername').val(data.name);
            
            getType(data.code)
        });
    });

    $('#form-print').on('submit', function (e) {
        e.preventDefault();
        let customer = $('#partreport-index-customercode').val(),
            type = $('#partreport-index-parttype').val(),
            encrypt = btoa(`${customer}&${type}`),
            url = "{{route('tms.db_parts.report.print')}}?params=" + encrypt;
        window.open(url, '_blank');
    });

    function getType(customer) {
        $('#partreport-index-parttype').find('option').not(':first').remove();
        let route = "{{ route('tms.db_parts.report.parts', [':customer']) }}";
        route  = route.replace(':customer', customer);
        ajaxCall({route:route, method: "GET"}).then(response => {
            if (response.content != null) {
                $.each(response.content, function (i, type) {
                    $('#partreport-index-parttype').append($('<option>', {
                        text: type.type,
                        value: type.type,
                    }));
                });
            }
        });
    }

    // Lib func
    function date_convert($date) {
        if ($date.length < 0) { return null; }
        return $date.split(' ')[0].split('-').reverse().join('-');
    }
    function addZeroes( num ) {
        var value = Number(num);
        var res = num.split(".");
        if(res.length == 1 || (res[1].length < 4)) {
            value = value.toFixed(2);
        }
        return value;
    }
    function modalAction(elementId=null, action='show'){
        return new Promise(resolve => {
            $(elementId).modal(action);
            resolve($(elementId));
        });
    }

    function loading_start() {
        $('body').loading({
            message: "wait for a moment...",
            zIndex: 9999
        });
    }

    function loading_stop() {
        $('body').loading('stop');
    }

    function ajaxCall(params) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: params.route,
                method: params.method,
                dataType: "JSON",
                cache: false,
                headers: token_header,
                data: params.data,
                error: function(response, status, x){
                    $('body').loading('stop');
                    Swal.fire({
                        title: 'Something was wrong',
                        text: response.responseJSON.message,
                        icon: 'error'
                    }).then(() => {
                        console.clear();
                        reject(response);
                    });
                },
                complete: function (response){
                    resolve(response);
                }
            });
        });
    }
    function isHidden(element=null, hide=true){
        return ((hide == true) ? $(element).addClass('d-none') : $(element).removeClass('d-none'));
    }
    function adjustDraw(tbl) {
        return tbl.columns.adjust().draw();
    }
    
    function currency(bilangan) {
        var	number_string = bilangan.toString(),
        split	= number_string.split('.'),
        sisa 	= split[0].length % 3,
        rupiah 	= split[0].substr(0, sisa),
        ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);

        if (ribuan) {
            separator = sisa ? ',' : '';
            rupiah += separator + ribuan.join(',');
        }
        return rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
    }
});
</script>