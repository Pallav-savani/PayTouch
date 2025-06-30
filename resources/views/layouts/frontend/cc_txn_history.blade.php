<div class="tab-pane fade" id="v-pills-cc-txn-history" role="tabpanel"
    aria-labelledby="v-pills-cc-txn-history-tab" tabindex="0">
    <div class="container-fluid">
        <div class="row martop align-items-center">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-8">
                        <div id="entry" class="col-md-12">
                            <div class="row formobile">
                                <h4>Transaction History</h4>
                            </div>
                        </div>
                        <div class="row">
                            <form id="txnHistoryForm" class="service p-2 rounded">
                                <div class="mb-3">
                                    <label for="txnId" class="form-label">Search by Transaction ID:</label>
                                    <input type="text" id="txnId" class="form-control" placeholder="Enter Transaction ID">
                                </div>
                                <div class="mb-3">
                                    <label for="fromDate" class="form-label">From Date:</label>
                                    <input type="date" id="fromDate" class="form-control mb-2" placeholder="From Date">
                                </div>
                                <div class="mb-3">
                                    <label for="toDate" class="form-label">To Date:</label>
                                    <input type="date" id="toDate" class="form-control" placeholder="To Date">
                                </div>
                                <div class="mb-3 text-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <div class="text-end w-100">
                            <img src="images/bbps.jpg" alt="BBPS Logo" style="width: 150px; margin-left:1000px; margin-bottom:150px;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row martop">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
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
                        <tbody>
                            <!-- Table rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
