<div id="alertToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <strong id="toastTitle" class="me-auto"></strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div id="toastBody" class="toast-body"></div>
</div>

<div class="tab-pane fade show active" id="v-pills-cc-fetch" role="tabpanel"
    aria-labelledby="v-pills-cc-fetch-tab" tabindex="0">
    <div class="container-fluid">
        <div class="row martop">
            <div class="center">
                <div class="img-box">
                    <img src="images/bbps.jpg" style="width: 150px;" class="right-logo" />
                </div>
                
                
                <!-- Fetch Bill Form -->
                <form id="ccFetchBillForm">
                    <h2>Fetch Bill</h2>

                    <div class="form-group">
                        <label>Credit Card Number:</label>
                        <input type="text" id="ccNumber" name="cn" placeholder="Enter Credit Card Number" required />
                    </div>
                    <div class="form-group">
                        <label>Mobile Number:</label>
                        <input type="text" id="mobileNumber" name="mobile" placeholder="Enter Mobile Number" required />
                    </div>
                    <button type="submit" id="fetchBillBtn" class="btn btn-primary">
                        <span id="fetchBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <span id="fetchBtnText">Fetch</span>
                    </button>
                </form>
                
                <!-- Bills Table Container -->
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
