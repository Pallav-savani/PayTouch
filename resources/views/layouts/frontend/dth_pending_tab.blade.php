<div class="tab-pane fade" id="v-pills-pending" role="tabpanel"
    aria-labelledby="v-pills-pending-tab" tabindex="0">
    <div class="container-fluid" style="padding: 0 !important;">
        <div class="row martop">
            <div class="col-md-12">
                <div class="row formobile">
                    <h4>Pending Transactions</h4>
                </div>
            </div>
            <div id="divPendingService" style="border-style: groove; margin-top: 10px;">
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-3 pb-3" style="border-bottom: 1px solid #acacac;">
                        <div class="col-md-12">
                            <form id="pendingFilterForm" style="max-width: 100%; align-items: center;">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-3">
                                        <label for="pendingMobileNo" class="form-label">Mobile No.</label>
                                        <input type="tel" class="form-control" id="pendingMobileNo" placeholder="Enter mobile number" pattern="[0-9]{10}" maxlength="10">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="pendingFromDate" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="pendingFromDate">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="pendingToDate" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="pendingToDate">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center mt-4">
                                        <button type="submit" class="btn btn-primary text-white me-2" id="pendingFilterBtn">
                                            <span id="pendingFilterBtnText">Filter</span>
                                            <span id="pendingFilterBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger me-2" id="resetPendingFilterBtn">Clear</button>
                                        <button type="button" class="btn btn-success" id="refreshPendingBtn">
                                            <i class="fas fa-refresh"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Pending Transactions Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Pending Transactions</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-info" id="retryAllPendingBtn">
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
                                    <tbody id="pendingTransactionsTableBody">
                                        <tr><td colspan="8" class="text-center">Loading pending transactions...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <nav aria-label="Pending transactions pagination">
                                    <ul class="pagination pagination-sm mb-0" id="pendingPagination">
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
