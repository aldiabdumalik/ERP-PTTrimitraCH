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
    .custom-control-lg .custom-control-label::before,
    .custom-control-lg .custom-control-label::after {
        top: 0.1rem !important;
        left: -2rem !important;
        width: 1.25rem !important;
        height: 1.25rem !important;
    }

    .custom-control-lg .custom-control-label {
        margin-left: 0.5rem !important;
        font-size: 1rem !important;
    }
</style>