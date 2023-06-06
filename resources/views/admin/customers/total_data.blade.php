<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="statistics-title"><strong>Total Bought</strong></p>
                                            <h3 class="rate-percentage">{{ $total_data['sale'], '$' }}</h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title"><strong>Total Amount</strong></p>
                                            <h3 class="rate-percentage">
                                                {{ App\Helpers\Helper::formatCurrency($total_data['total_amount'], '$') }}
                                            </h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title"><strong>Total Paid Amount</strong></p>
                                            <h3 class="rate-percentage">
                                                {{ App\Helpers\Helper::formatCurrency($total_data['paid_amount'], '$') }}
                                            </h3>
                                        </div>
                                        <div class="d-none d-md-block">
                                            <p class="statistics-title"><strong>Total Due Amount</strong></p>
                                            <h3 class="rate-percentage">
                                                {{ App\Helpers\Helper::formatCurrency($total_data['due_amount'], '$') }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
