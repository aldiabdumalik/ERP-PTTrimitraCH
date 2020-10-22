/*
| -------------------------------------------------------------
|   TMS COMBO BOX FUNCTION
| -------------------------------------------------------------
|   1. Manufacturing - Raw Material
|   2. Manufacturing - Production Plan
|   3. Master Data - Master Item
| -------------------------------------------------------------
*/

// ++++++++++++++++++++++++++++++++++++
// 1. Manufacturing - Raw Material
// ++++++++++++++++++++++++++++++++++++
function populate_cmbPeriodRawMaterial(id, tmp_url, selectedValue){
    // Empty the dropdown
    $(id).find('option').not(':first').remove();
    // AJAX request
    $.ajax({
        url: tmp_url,
        type: 'get',
        dataType: 'json',
        success: function(response){
            var len = 0;
            if(response['data'] != null){
                len = response['data'].length;
            }
            if(len > 0){
                // Read data and create <option >
                for(var i=0; i<len; i++){
                    var populate_1 = response['data'][i].period;
                    if (selectedValue) {
                        if (populate_1 == selectedValue) {
                            var option = "<option value='"+populate_1+"' selected='"+selectedValue+"'>"+populate_1+"</option>";
                        } else {
                            var option = "<option value='"+populate_1+"'>"+populate_1+"</option>";
                        }
                    } else {
                        var option = "<option value='"+populate_1+"'>"+populate_1+"</option>";
                    }
                    $(id).append(option);
                }
            }
        }
});
}

function populate_cmbSupplierRawMaterial(id, tmp_url, selectedValue){
    // Empty the dropdown
    $(id).find('option').not(':first').remove();
    // AJAX request
    $.ajax({
        url: tmp_url,
        type: 'get',
        dataType: 'json',
        success: function(response){
            var len = 0;
            if(response['data'] != null){
                len = response['data'].length;
            }
            if(len > 0){
                // Read data and create <option >
                for(var i=0; i<len; i++){
                    var populate_1 = response['data'][i].vendcode;
                    var populate_2 = response['data'][i].company;
                    if (selectedValue) {
                        if (populate_1 == selectedValue) {
                            var option = "<option value='"+populate_1+"' selected='"+selectedValue+"'>"+populate_1+" :: "+populate_2+"</option>";
                        } else {
                            var option = "<option value='"+populate_1+"'>"+populate_1+" :: "+populate_2+"</option>";
                        }
                    } else {
                        var option = "<option value='"+populate_1+"'>"+populate_1+" :: "+populate_2+"</option>";
                    }
                    $(id).append(option);
                }
            }
        }
});
}

// ++++++++++++++++++++++++++++++++++++
// 2. Manufacturing - Production Plan
// ++++++++++++++++++++++++++++++++++++
function populate_cmbProdProcess(id, url, flag){
    // Empty the dropdown
    $(id).find('option').not(':first').remove();
    // AJAX request
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            flag: flag
        },
        success: function(response){
            var len = 0;
            if(response != null){
                len = response.length;
            }
            if(len > 0){
                // Read data and create <option >
                for(var i=0; i<len; i++){
                    var populate_1 = response[i].production_process;
                    var option = "<option value='"+populate_1+"'>"+populate_1+"</option>";
                    $(id).append(option);
                }
            }
        }
    });
}

function populate_cmbMachineNumber(id, url, prod_process, flag_machine, flag){
    // Empty the dropdown
    $(id).find('option').not(':first').remove();
    // AJAX request
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            prod_process: prod_process,
            flag_machine: flag_machine,
            flag: flag
        },
        success: function(response){
            var len = 0;
            if(response['data'] != null){
                len = response['data'].length;
            }
            if(len > 0){
                // Read data and create <option >
                for(var i=0; i<len; i++){
                    var populate_1 = response['data'][i].machine_number;
                    var option = "<option value='"+populate_1+"'>"+populate_1+"</option>";
                    $(id).append(option);
                }
            }
        }
    });
}
