<div class="tab-pane fade" id="v-pills-status" role="tabpanel"
    aria-labelledby="v-pills-status-tab" tabindex="0">
    <div class="container-fluid" style="padding: 0 !important;">
        <div class="row martop">
            <div class="col-md-12">
                <div class="row formobile">
                    <h4>Transaction Status</h4>
                </div>  
            </div>
            <div id="divStatusService" style="border-style: groove; margin-top: 10px;">
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="row mb-3 pb-3" style="border-bottom: 1px solid #acacac;">
                        <div class="col-md-12">
                            <form id="searchForm" style="max-width: 100%; align-items: center;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="searchMobileNo" class="form-label">Mobile No.</label>
                                        <input type="tel" class="form-control" id="searchMobileNo" placeholder="Enter mobile number" pattern="[0-9]{10}" maxlength="10">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="searchTransactionId" class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" id="searchTransactionId" placeholder="Enter transaction ID">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center mt-3">
                                        <button type="submit" class="btn btn-primary text-white me-2" id="searchBtn">
                                            <span id="searchBtnText">Show</span>
                                            <span id="searchBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                        <button type="button" class="btn btn-danger" id="resetSearchBtn">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Search Results -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Search Results</h5>
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
                                    <tbody id="searchResultsTableBody">
                                        <tr><td colspan="7" class="text-center">Enter search criteria and click Show</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Search History -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Search History</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="refreshHistoryBtn">
                                    <i class="fas fa-refresh"></i> Refresh
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Search Time</th>
                                            <th>Mobile No.</th>
                                            <th>Transaction ID</th>
                                            <th>Status Found</th>
                                        </tr>
                                    </thead>
                                    <tbody id="searchHistoryTableBody">
                                        <tr><td colspan="4" class="text-center">Loading search history...</td></tr>
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
