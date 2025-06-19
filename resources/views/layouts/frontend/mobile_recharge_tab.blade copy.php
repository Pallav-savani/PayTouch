@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTouch | Mobile Recharge</title>
    <!-- <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f9fc;
            margin: 0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
        }
        .recharge-form, .history {
            background: linear-gradient(to bottom right, #c471ed, #f64f59);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin: 10px;
        }
        .recharge-form {
            flex: 1;
            min-width: 300px;
        }
        .history {
            flex: 2;
            background: white;
            color: black;
            overflow-x: auto;
            min-width: 400px;
        }
        h2 {
            text-align: center;
            color: #c471ed;
        }
        input, select {
            padding: 10px;
            width: 90%;
            margin-bottom: 10px;
            border: none;
            border-radius: 6px;
        }
        button {
            padding: 10px 20px;
            background-color: white;
            color: #c471ed;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .loading {
            color: #f64f59;
            font-weight: bold;
        }
    </style> -->
</head>
<body>

<!-- <h2>Mobile Recharge - PayTouch</h2> -->



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
                <div class="row martop ">
            <div id="entry" class="col-md-4">
                <div class=" row formobile">
                    <h4>Mobile Recharge</h4>
                    <form id="rechargeForm">
                </div>
            </div>
            <div id="divService" class=" martop10"> 
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form id="rechargeForm">
                                <div class="row service "> 
                                <!-- <div class="row formobile"> --> 
                                    
                                    <div class="mb-3" style="text-align:left;">
                                        <label for="customerId" class="form-label">Mobile No.</label>
                                        <input name="mobile_no" type="tel" class="form-control" id="mobile_no" placeholder="Enter any mobile number" pattern="[0-9]{10}" maxlength="10" required>
                                        <small class="form-text text-muted">You can recharge any mobile number</small>
                                    </div>
                                    <div>
                                        <label for="cmbService" class="form-label">Select Operator</label>
                                        <select name="operator" id="operator" class="form-select" required>
                                        <option value="">-- Select Operator --</option>
                                        <option value="airtel">AIRTEL</option>
                                        <option value="jio">Jio</option>
                                        <option value="vi">VI</option>
                                        <option value="bsnl">BSNL</option>
                                         </select>
                                    </div>
                                    <div>
                                        <label for="cmbService" class="form-label">Select Circle</label>
                                        <select name="service" id="cmbService" class="form-select" required>
                                            <option value="">-- Select Operator --</option>
                                            <option value="airtel">Andhra Pradesh</option>
                                            <option value="bigtv">Delhi</option> 
                                        </select>
                                    </div>
                                    <div>
                                        <label for="cmbService" class="form-label">All Plan</label>
                                    <select name="circle" id="circle" class="form-select" required>
                                        <option value="">-- Select Plan --</option>
                                        <option value="prepaid">Prepaid</option>
                                        <option value="postpaid">Postpaid</option>
                                        <option value="talktime">Talktime</option>
                                        <option value="validity">Validity</option>
                                        <!-- Add more circles as needed -->
                                    </select>

                                    </div>
                                    <div class="mb-3" style="text-align:left;">
                                        <label for="amount" class="form-label">Recharge Amount (₹)</label>
                                        <input name="amount" type="number" class="form-control" id="amount" placeholder="Amount" min="1" max="10000" step="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="col-12 btn gradient-bg martop10 text-white" id="submitBtn">
                                            <span id="btnText">Recharge now</span>
                                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </form>
                                    </div>
                                </div> 
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
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="alertToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastBody"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>


 
 
<script>
function showToast(message, type = 'success') {
    const toastBody = document.getElementById('toastBody');
    toastBody.innerHTML = message;
    toastBody.className = type === 'success' ? 'text-success' : 'text-danger';
    const toast = new bootstrap.Toast(document.getElementById('alertToast'));
    toast.show();
}

function validateMobileInput() {
    const mobileInput = document.getElementById('mobile_no');
    mobileInput.addEventListener('input', function() {
        if (this.value.length === 10 && /^[0-9]+$/.test(this.value)) {
            this.style.borderColor = "green";
        } else {
            this.style.borderColor = "red";
        }
    });
}

function loadRechargeHistory() {
    $.ajax({
        url: "{{ url('/api/recharge/history') }}",
        method: "GET",
        dataType: "json",
        success: function(response) {
            let rows = '';
            if (response.length > 0) {
                response.forEach(function(item, idx) {
                    rows += `<tr>
                        <td>${idx + 1}</td>
                        <td>${item.created_at}</td>
                        <td>${item.operator}</td>
                        <td>${item.mobile_no}</td>
                        <td>₹${item.amount}</td>
                        <td>${item.txn_id}</td>
                        <td>
                            <span class="badge bg-${item.status === 'Success' ? 'success' : (item.status === 'Pending' ? 'warning' : 'danger')}">
                                ${item.status}
                            </span>
                        </td>
                    </tr>`;
                });
            } else {
                rows = `<tr><td colspan="7" class="text-center">No recharge history found.</td></tr>`;
            }
            $('#rechargeTableBody').html(rows);
        },
        error: function() {
            $('#rechargeTableBody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load history.</td></tr>');
        }
    });
}

$(document).ready(function() {
    validateMobileInput();
    loadRechargeHistory();

    $('#rechargeForm').on('submit', function(e) {
        e.preventDefault();
        $('#submitBtn').prop('disabled', true);
        $('#btnText').addClass('d-none');
        $('#btnSpinner').removeClass('d-none');

        $.ajax({
            url: "{{ url('/api/recharge/submit') }}",
            method: "POST",
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    showToast('Recharge successful!', 'success');
                    $('#rechargeForm')[0].reset();
                    loadRechargeHistory();
                } else {
                    showToast(response.message || 'Recharge failed.', 'danger');
                }
            },
            error: function(xhr) {
                let msg = 'Recharge failed.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showToast(msg, 'danger');
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('#btnText').removeClass('d-none');
                $('#btnSpinner').addClass('d-none');
            }
        });
    });
});
</script>

</body>
</html>

 