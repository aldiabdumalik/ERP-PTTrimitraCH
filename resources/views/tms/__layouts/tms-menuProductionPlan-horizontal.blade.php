<div class="page-title-area">
    <div class="row">
        <div class="#">
            <a href="{{ route('tms.manufacturing.production-plan.index') }}" class="btn btn-flat btn-info" id="dashboard" >
                Dashboard
            </a>
        </div>
        <div class="col">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop11" type="button" class="btn btn-flat btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Capacity vs Loading
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="{{ route('tms.manufacturing.production-plan.summaryLoadingCapacityPerMonth') }}">Summary Per Month</a>
                        <a class="dropdown-item" href="{{ route('tms.manufacturing.production-plan.summaryLoadingCapacityPerDate') }}">Summary Per Date</a>
                        <a class="dropdown-item" href="{{ route('tms.manufacturing.production-plan.summaryLoadingCapacityPerMachine') }}">Summary Per Machine</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('tms.manufacturing.production-plan.detailLoadingCapacityPerDate') }}">Details Per Date</a>
                        <a class="dropdown-item" href="{{ route('LoadingCapacityPerMachineDetails') }}">Details Per Machine</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
