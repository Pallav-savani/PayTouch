<div class="tab-pane fade" id="v-pills-cc-txn-history" role="tabpanel"
    aria-labelledby="v-pills-cc-txn-history-tab" tabindex="0">
    <div class="container-fluid">
        <div class="row martop ">
            <div id="entry" class="col-md-4">
                 
            </div>
            <div class="container mt-4">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h3>Transaction History</h3>
                <div class="img-box">
                    <img src="images/bbps.jpg" style="width: 150px;" class="right-logo" />
                </div>
              </div>

              <div class="alert alert-success text-center">
                Transaction History page loaded successfully!
              </div>

              <!-- Search Options -->
              <div class="row mb-4">
                <!-- Option 1: Search by Transaction ID -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="txnId">Search by Transaction ID:</label>
                    <input type="text" id="txnId" class="form-control" placeholder="Enter Transaction ID">
                  </div>
                </div>

                <!-- Option 2: Search by Date Range -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="dateRange">Search by Date:</label>
                    <input type="date" id="fromDate" class="form-control mb-2" placeholder="From Date">
                    <input type="date" id="toDate" class="form-control" placeholder="To Date">
                  </div>
                </div>
              </div>

              <div class="text-end">
                <button class="btn btn-primary">Search</button>
              </div>
            </div>
        </div>
    </div>
</div>

