function initializeSelect2($id, $route, $placeholder = "Select Data"){
    $($id).select2({
        width: 'resolve',
        placeholder: $placeholder,
        allowClear: true,
        ajax: {
            url: $route,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    _token: "{{ csrf_token() }}",
                    search: params.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
}