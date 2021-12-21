<style>
    .modal{
        overflow: auto;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none !important; 
        margin: 0 !important; 
    }
    input[readonly] {
        cursor: not-allowed;
        pointer-events: all !important;
    }
    .row.no-gutters {
        margin-right: 0;
        margin-left: 0;

        & > [class^="col-"],
        & > [class*=" col-"] {
            padding-right: 0;
            padding-left: 0;
        }
    }
    button:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }
    .selected {
        background-color: #dddddd;
    }
    .auto-middle {
        margin-top: auto;
        margin-bottom: auto;
    }
    .bg-abu {
        background-color: #d3d3d3;
    }
    .bg-y {
        background-color: #FFEF78;
    }
    .form-check {
        padding: 0;
    }
    .form-check .custom-control-label {
        font-size: 14px;
        line-height: 2.0;
        padding-left:10px;
    }
    .form-check .custom-control-label::after,
    .form-check .custom-control-label::before {
        height: 25px;
        width: 25px;
    }
    .form-check .custom-control-label::before {
        background-color: #fff;
        border: 1px solid #2c2b2c;
    }
</style>