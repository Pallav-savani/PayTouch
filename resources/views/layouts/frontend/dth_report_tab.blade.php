<div class="tab-pane fade" id="v-pills-report" role="tabpanel"
    aria-labelledby="v-pills-report-tab" tabindex="0">
    <div class="container-fluid" style="padding: 0 !important;">
        <div class="row martop">
            <div class="col-md-12">
                <div class="row formobile">
                    <h4>Transaction Report</h4>
                </div>
            </div>
            <div id="divReportService" style="border-style: groove; margin-top: 10px;">
                <div class="card-body">
                    <div class="row mb-3 pb-3" style="border-bottom: 1px solid #acacac;">
                        <div class="col-md-2">
                            <label for="reportFromDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="reportFromDate">
                        </div>
                        <div class="col-md-2">
                            <label for="reportToDate" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="reportToDate">
                        </div>
                        <div class="col-md-2">
                            <label for="reportService" class="form-label">Service</label>
                            <select class="form-select" id="reportService">
                                <option value="">All Services</option>
                                <option value="airtel">AIRTEL DTH</option>
                                <option value="bigtv">BIG TV DTH</option>
                                <option value="dishtv">DISH TV DTH</option>
                                <option value="tatasky">TATA SKY DTH</option>
                                <option value="videocon">VIDEOCON DTH</option>
                                <option value="suntv">SUN TV DTH</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="reportStatus" class="form-label">Status</label>
                            <select class="form-select" id="reportStatus">
                                <option value="">All Status</option>
                                <option value="success">Success</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="reportCustomerNo" class="form-label">Customer No.</label>
                            <input type="tel" class="form-control" id="reportCustomerNo" placeholder="Mobile number" pattern="[0-9]{10}" maxlength="10">
                        </div>
                        <div class="col-md-2 mt-3 d-flex align-items-end justify-content-center">
                            <button type="button" class="btn btn-primary text-white me-2" id="searchReportBtn">
                                Search
                            </button>
                            <button type="button" class="btn btn-danger" id="resetReportBtn">Reset</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Transaction Report</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Service</th>
                                            <th>Mobile No.</th>
                                            <th>Amount</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportTableBody">
                                        <tr><td colspan="7" class="text-center">Click Search to load transactions</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
