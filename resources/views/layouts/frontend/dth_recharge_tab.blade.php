<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
    aria-labelledby="v-pills-home-tab" tabindex="0">
    <div class="">
        <div class="row martop">
            <div id="entry" class="col-md-4">
                <div class=" row formobile">
                    <h4>DTH Recharge</h4>
                </div>
            </div>
            <div id="divService" class=" martop10"> 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form id="rechargeForm" style="padding: 0;">
                                <div class="row service "> 
                                <!-- <div class="row formobile"> --> 
                                    <div>
                                        <label for="cmbService" class="form-label">Operator</label>
                                        <select name="service" id="cmbService" class="form-select" required>
                                            <option value="">-- Select Operator --</option>
                                            <option value="airtel">AIRTEL DTH</option>
                                            <option value="bigtv">BIG TV DTH</option>
                                            <option value="dishtv">DISH TV DTH</option>
                                            <option value="tatasky">TATA SKY DTH</option>
                                            <option value="videocon">VIDEOCON DTH</option>
                                            <option value="suntv">SUN TV DTH</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" style="text-align:left;">
                                        <label for="customerId" class="form-label">Mobile No.</label>
                                        <input name="mobile_no" type="tel" class="form-control" id="customerId" placeholder="Enter any mobile number" pattern="[0-9]{10}" maxlength="10" required>
                                        <small class="form-text text-muted">You can recharge any mobile number</small>
                                    </div>
                                    <div class="mb-3" style="text-align:left;">
                                        <label for="amount" class="form-label">Amount (₹)</label>
                                        <input name="amount" type="number" class="form-control" id="amount" placeholder="Amount" min="1" max="10000" step="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="col-12 rounded" id="submitBtn">
                                            <span id="btnText">Proceed to Recharge</span>
                                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </div>
                                </div>

                            </form>
                            <div id="alertContainer"></div>
                        </div>
                        <div class="col-md-8">
                            <h5 class="">Recent Recharges</h5>
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
                                    <tbody id="rechargeTableBody">
                                        <tr><td colspan="7" class="text-center">Loading...</td></tr>
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
