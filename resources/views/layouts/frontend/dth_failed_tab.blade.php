<div class="tab-pane fade" id="v-pills-failed" role="tabpanel"
    aria-labelledby="v-pills-failed-tab" tabindex="0">
    <div class="container-fluid" style="padding: 0 !important;">
        <div class="row martop">
            <div class="col-md-12">
                <div class="row formobile">
                    <h4>Failed Transactions</h4>
                </div>
            </div>
            <div id="divFailedService" style="border-style: groove; margin-top: 10px;">
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-3 pb-3" style="border-bottom: 1px solid #acacac;">
                        <div class="col-md-12">
                            <form id="failedFilterForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="failedMobileNo" class="form-label">Mobile No.</label>
                                        <input type="tel" class="form-control" id="failedMobileNo" placeholder="Enter mobile number" pattern="[0-9]{10}" maxlength="10">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="failedFromDate" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="failedFromDate">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="failedToDate" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="failedToDate">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary text-white me-2" id="failedFilterBtn">
                                            <span id="failedFilterBtnText">Filter</span>
                                            <span id="failedFilterBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger me-2" id="resetFailedFilterBtn">Clear</button>
                                        <button type="button" class="btn btn-success" id="refreshFailedBtn">
                                            <i class="fas fa-refresh"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Failed Transactions Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Failed Transactions</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-info" id="retryAllFailedBtn">
                                        <i class="fas fa-redo"></i> Retry All
                                    </button>
                                </div>
                            </div>
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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="failedTransactionsTableBody">
                                        <tr><td colspan="8" class="text-center">Loading failed transactions...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <nav aria-label="Failed transactions pagination">
                                    <ul class="pagination pagination-sm mb-0" id="failedPagination">
                                        <!-- Pagination will be generated here -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>
