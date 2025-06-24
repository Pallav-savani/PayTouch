<div class="tab-pane fade show active" id="v-pills-cc-fetch" role="tabpanel"
    aria-labelledby="v-pills-cc-fetch-tab" tabindex="0">
    <div class="container-fluid">
        <div class="row martop justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    
                    <!-- Fetch Form -->
                    <div class="flex-fill me-4 " style="min-width: 280px;">
                        <form id="ccFetchBillForm">
                            <h5 class="formobile col-md-6">Credit Card Bill</h5>
                            <h2>Fetch Bill</h2>

                            <div class="form-group mb-3">
                                <label>Credit Card Number:</label>
                                <input type="text" id="ccNumber" name="cn" class="form-control"
                                    placeholder="Enter Credit Card Number" required />
                            </div>
                            <div class="form-group mb-3">
                                <label>Mobile Number:</label>
                                <input type="text" id="mobileNumber" name="mobile" class="form-control"
                                    placeholder="Enter Mobile Number" required />
                            </div>
                            <button type="submit" id="fetchBillBtn" class="btn btn-primary">
                                <span id="fetchBtnSpinner" class="spinner-border spinner-border-sm d-none"
                                    role="status"></span>
                                <span id="fetchBtnText">Fetch</span>
                            </button>
                        </form>
                    </div>

                    <!-- BBPS Logo -->
                    <div class="text-end">
                        <img src="images/bbps.jpg" alt="BBPS Logo" style="width: 150px;" />
                    </div>
                </div>

                <!-- Bills Table -->
                <div id="ccBillsContainer" class="mt-4">
                    <h4>Credit Card Bills</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Credit Card Number</th>
                                    <th>Opt</th>
                                    <th>Cir</th>
                                    <th>Amount</th>
                                    <th>ReqID</th>
                                    <th>ad9</th>
                                    <th>ad3</th>
                                    <th>Status</th>
                                    <th>Transaction ID</th>
                                    <th>Operator Ref.</th>
                                    <th>Processed At</th>
                                </tr>
                            </thead>
                            <tbody id="ccBillsTableBody">
                                <!-- Bills will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
