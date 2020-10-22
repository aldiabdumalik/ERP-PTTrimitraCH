<div class="page-title-area">
    <div class="row" >
        <div class="#">
            <a href="{{ route('tms.manufacturing.raw-material.index') }}" class="btn btn-flat btn-info" id="dashboard" >
                Dashboard
            </a>
        </div>
        <div class="#" style="padding-left: 0.5%">
            <a href="{{ route('tms.manufacturing.raw-material.forecast-note') }}" class="btn btn-flat btn-info" id="forecast_note" >
                Forecast Note
            </a>
        </div>
        <div class="#" style="padding-left: 0.5%">
            <a href="#" class="btn btn-flat btn-info" id="forecast_order">
                Forecast Order
            </a>
        </div>
        <div class="#" style="padding-left: 0.5%">
            <a href="#" class="btn btn-flat btn-info" id="fix_order">
                Fix Order
            </a>
        </div>
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown" style="padding-left: 0.5%">
            <div class="btn-group" role="group">
                <button id="setup" type="button" class="btn btn-flat btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Setup
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="{{ route('tms.manufacturing.raw-material.setup-supplier-distribution') }}">Supplier Mapping</a>
                    <a class="dropdown-item" href="{{ route('tms.manufacturing.raw-material.setup-supplier-report') }}">Supplier Report</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('tms.manufacturing.raw-material.setup-lot') }}">Lot Material</a>
                </div>
            </div>
        </div>
        <div class="#" style="padding-left: 0.5%">
            <div class="btn-group" role="group">
                <button id="setup" type="button" class="btn btn-flat btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Reference
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="{{ route('tms.manufacturing.raw-material.reference-bom') }}">BoM Tree</a>
                </div>
            </div>
        </div>
    </div>
</div>
