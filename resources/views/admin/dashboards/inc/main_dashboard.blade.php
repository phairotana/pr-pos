<div class="row">
    <div class="col-lg-3">
        <div class="card text-grey bg-w">
            <div class="card-header text-center bg-primary text-white p-0" style="text-transform: uppercase;font-size: 20px;">
                Sales
            </div>
            <div class="row p-3">
                <div class="col-md-12 text-center">
                    <h5 class="d-inline dash-customer">{{ $sales_dash }}</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card text-grey bg-w">
            <div class="card-header text-center bg-success text-white p-0" style="text-transform: uppercase;font-size: 20px;">
                Purchases
            </div>
            <div class="row p-3">
                <div class="col-md-12 text-center">
                    <h5 class="d-inline dash-active-customer">{{ $purchases_dash }}</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card text-grey bg-w">
            <div class="card-header text-center bg-info text-white p-0" style="text-transform: uppercase;font-size: 20px;">
                Sales Return
            </div>
            <div class="row p-3">
                <div class="col-md-12 text-center">
                    <h5 class="d-inline dash-inactive-customer">{{ $sales_return_dash }}</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card text-grey bg-w">
            <div class="card-header text-center bg-warning text-white p-0" style="text-transform: uppercase;font-size: 20px;">
                Purchases Return
            </div>
            <div class="row p-3">
                <div class="col-md-12 text-center">
                    <h5 class="d-inline dash-inactive-customer">{{ $purchases_return_dash }}</h5>
                </div>
            </div>
        </div>
    </div>    
</div>