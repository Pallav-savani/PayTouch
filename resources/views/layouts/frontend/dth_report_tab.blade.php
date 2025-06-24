<head>
<style>
    .receipt-container {
        max-width: 400px;
        margin: 0 auto;
        background: white;
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        font-family: 'Courier New', monospace;
    }
    .receipt-header {
        text-align: center;
        border-bottom: 2px dashed #333;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .receipt-logo {
        max-width: 160px;
        margin-bottom: 10px;
    }
    .receipt-logo-b{
        width: 100px !important;
        object-fit: contain;
    }
    .logo-container{
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .logo-container img{
        width: 100%;
        object-fit: contain;
    }
    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .receipt-footer {
        text-align: center;
        border-top: 2px dashed #333;
        padding-top: 15px;
        margin-top: 15px;
        font-size: 12px;
    }
    .status-success { color: #28a745; font-weight: bold; }
    .status-failed { color: #dc3545; font-weight: bold; }
    .status-pending { color: #ffc107; font-weight: bold; }
</style>
</head>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportTableBody">
                                        <tr><td colspan="8" class="text-center">Click Search to load transactions</td></tr>
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
