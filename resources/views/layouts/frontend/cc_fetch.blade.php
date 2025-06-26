<div class="tab-pane fade show active" id="v-pills-cc-fetch" role="tabpanel"
    aria-labelledby="v-pills-cc-fetch-tab" tabindex="0">
    <div class="container-fluid">
        <div class="row martop align-items-center">
            <div class="col-md-8 offset-md-2">
                <div class="row">
                    <div class="col-md-8">
                        <form id="ccBillForm" class="service p-3 rounded">
                            <h4 class="formobile mb-3 text-center">CC Bill Payment</h4>
                            <div class="mb-3">
                                <label for="ccNumber" class="form-label">Credit Card Number</label>
                                <input type="text" class="form-control" id="ccNumber" placeholder="Enter card number" maxlength="16" required>
                            </div>
                            <div class=" mb-3">
                                <label>Mobile Number:</label>
                                <input type="text" id="mobileNumber" name="mobile" class="form-control"
                                    placeholder="Enter Mobile Number" required />
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Fetch </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <div class="text-end w-100">
                            <img src="images/bbps.jpg" alt="BBPS Logo" style="width: 130px; margin-left:500px; margin-bottom:150px;" />
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
