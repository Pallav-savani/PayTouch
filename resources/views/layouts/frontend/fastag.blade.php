 @include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PayTouch | Mobile Recharge</title>
</head>
<body>

<!-- Toast Container for Top Right Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div id="alertToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastBody">
            <!-- Message will be inserted here -->
        </div>
    </div>
</div>

<div class="row" style="max-width: 100%;">
    <div class="container" style="margin-top: 1rem; margin-bottom: 2rem;">
        <div class="d-flex align-items-start user-service-tab">
            <div class="px-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home"
                        role="tab" aria-controls="v-pills-home" aria-selected="true">Mobile Recharge</a>
                </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Mobile Recharge Tab -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="row martop">
                        <div id="entry" class="col-md-4">
                            <div class="row formobile">
                                <h4>Fastag Recharge</h4>
                            </div>
                        </div>
                        <div id="divService" class="martop10"> 
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form id="rechargeForm" class="p-0">
                                            <div class="row service">
                                                <div class="mb-3" style="text-align:left;">
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
                                                <div class="mb-3">
                                                    <label for="operator" class="form-label">Select Operator</label>
                                                    <select name="operator" id="operator" class="form-select" required>
                                                        <option value="">-- Select Operator --</option>
                                                        <option value="airtel">AIRTEL</option>
                                                        <option value="jio">JIO</option>
                                                        <option value="vi">VI</option>
                                                        <option value="bsnl">BSNL</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="circle" class="form-label">Select Plan Type</label>
                                                    <select name="circle" id="circle" class="form-select" required>
                                                        <option value="">-- Select Plan Type --</option>
                                                        <option value="prepaid">Prepaid</option>
                                                        <option value="postpaid">Postpaid</option>
                                                        <option value="talktime">Talktime</option>
                                                        <option value="validity">Validity</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 w-100 d-flex align-items-center justify-content-end">
                                                    <button class="rounded">Browse Plan</button>
                                                </div>
                                                <div class="mb-3" style="text-align:left;">
                                                    <label for="amount" class="form-label">Recharge Amount (₹)</label>
                                                    <input name="amount" type="number" class="form-control" id="amount" placeholder="Amount" min="1" max="10000" step="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <button type="submit" class="col-12 rounded" id="submitBtn">
                                                        <span id="btnText">Recharge Now</span>
                                                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="alertContainer"></div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>Recent Recharges</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Date</th>
                                                        <th>Operator</th>
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

                <!-- Recharge History Tab -->
                <div class="tab-pane fade" id="v-pills-history" role="tabpanel" aria-labelledby="v-pills-history-tab">
                    <div class="container-fluid">
                        <h4>Recharge History</h4>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form id="historyFilterForm" class="row g-3">
                                    <div class="col-md-3">
                                        <label for="historyFromDate" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="historyFromDate" name="from_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="historyToDate" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="historyToDate" name="to_date">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="historyStatus" class="form-label">Status</label>
                                        <select class="form-select" id="historyStatus" name="status">
                                            <option value="">All Status</option>
                                            <option value="Success">Success</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="historyOperator" class="form-label">Operator</label>
                                        <select class="form-select" id="historyOperator" name="operator">
                                            <option value="">All Operators</option>
                                            <option value="airtel">AIRTEL</option>
                                            <option value="jio">JIO</option>
                                            <option value="vi">VI</option>
                                            <option value="bsnl">BSNL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" id="searchHistoryBtn">Search</button>
                                            <button type="button" class="btn btn-secondary" id="resetHistoryBtn">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Operator</th>
                                        <th>Plan Type</th>
                                        <th>Mobile No.</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">
                                    <tr><td colspan="8" class="text-center">Click Search to load history</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Search Recharge Tab -->
                <div class="tab-pane fade" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
                    <div class="container-fluid">
                        <h4>Search Recharge</h4>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form id="searchForm" class="row g-3">
                                    <div class="col-md-4">
                                        <label for="searchMobileNo" class="form-label">Mobile Number</label>
                                        <input type="tel" class="form-control" id="searchMobileNo" name="mobile_no" placeholder="Enter 10-digit mobile number" pattern="[0-9]{10}" maxlength="10">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="searchTxnId" class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" id="searchTxnId" name="txn_id" placeholder="Enter transaction ID">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" id="searchBtn">
                                                <span id="searchBtnText">Search</span>
                                                <span id="searchBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="resetSearchBtn">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Operator</th>
                                        <th>Plan Type</th>
                                        <th>Mobile No.</th>
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="searchResultsTableBody">
                                    <tr><td colspan="8" class="text-center">Enter search criteria and click Search</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function rechargeFastag() {
    const vehicle = document.getElementById("vehicleNumber").value.trim();
    const bank = document.getElementById("bank").value;
    const amount = document.getElementById("amount").value.trim();

    if (!vehicle || !bank || !amount || isNaN(amount)) {
        alert("Please fill all fields correctly.");
        return;
    }

    // Simulate successful recharge
    alert("FASTag Recharge Successful!");

    // Add to dummy history table
    const table = document.getElementById("historyTable");
    const row = table.insertRow(1);
    row.innerHTML = `
        <td>${vehicle}</td>
        <td>${bank.toUpperCase()} Bank</td>
        <td>₹${amount}</td>
        <td>Success</td>
        <td>${new Date().toLocaleString()}</td>
    `;

    // Reset form
    document.getElementById("vehicleNumber").value = "";
    document.getElementById("bank").value = ""; 
    document.getElementById("amount").value = "";
}
</script>

</body>
</html>






