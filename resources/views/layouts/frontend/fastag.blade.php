 @include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PayTouch | Fastag Recharge</title>
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
                        role="tab" aria-controls="v-pills-home" aria-selected="true">FASTag Recharge</a>
                </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Mobile Recharge Tab -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="row martop">
                        <div id="entry" class="col-md-4">
                            <div class="row formobile">
                                <h4>Recharge Your FASTag</h4>
                            </div>
                        </div>
                        <div id="divService" class="martop10"> 
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form id="rechargeForm" class="p-0">
                                            <div class="row service">
                                                 <div class="mb-3" style="text-align:left;">
                                                    <label for="vehicleNumber" class="form-label">Vehicle Number</label>
                                                    <input name="vehicleNumber" type="text" class="form-control" id="vehicleNumber" placeholder="Enter Vehicle Number (e.g. KA01AB1234)" required>
                                                </div> 
                                                <div class="mb-3" style="text-align:left;">
                                                    <label for="cmbService" class="form-label">Select Operator</label>
                                                    <select name="service" id="bank" class="form-select" required>
                                                        <option value="">-- Select FASTag Provider --</option> 
                                                        <option value="icici">ICICI Bank</option>
                                                        <option value="sbi">SBI Bank</option>
                                                        <option value="hdfc">HDFC Bank</option>
                                                        <option value="axis">Axis Bank</option>
                                                    </select>
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
                                         <h5>FASTag Recharge History</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle</th>
                                                        <th>Bank</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Time</th>
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






