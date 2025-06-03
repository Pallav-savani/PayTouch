@include('layouts.header')

<div class="row">
<div class="container" style="margin-top: 5rem;">
    <div class="d-flex align-items-start user-service-tab">
        <div class="px-3">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home"
                    role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
                <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                    role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
                <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages"
                    role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
                <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings"
                    role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
            </div>
        </div>
    </div>
</div>
    <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-recharge" role="tabpanel"
            aria-labelledby="v-pills-recharge-tab" tabindex="0">
            <div class="container-fluid">
                <div class="row martop ">
                    <div id="entry" class="col-md-4">
                        <div class=" row formobile">
                    <h4>DTH Recharge</h4>
                </div>
            </div>
             <div id="divService" class=" martop10"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <form id="rechargeForm">
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
                                    <input name="mobile_no" type="number" class="form-control" id="customerId" placeholder="Mobile No" required>
                                </div>
                                <div class="mb-3" style="text-align:left;">
                                    <label for="amount" class="form-label">Amount (₹)</label>
                                    <input name="amount" type="number" class="form-control" id="amount" placeholder="Amount" min="1" max="10000" required>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="col-12 btn gradient-bg martop10 text-white" id="submitBtn">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Load recharge records on page load
    fetchRechargeRecords();

    // Handle form submission
    $('#rechargeForm').on('submit', function(e) {
        e.preventDefault();

        // Disable button and show spinner
        $('#submitBtn').prop('disabled', true);
        $('#btnText').text('Processing...');
        $('#btnSpinner').removeClass('d-none');

        const formData = {
            service: $('#cmbService').val(),
            mobile_no: $('#customerId').val(),
            amount: $('#amount').val()
        };

        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function(response) {
                $('#alertContainer').html('<div class="alert alert-success">Recharge added successfully!</div>');
                $('#rechargeForm')[0].reset();
                fetchRechargeRecords();
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                let errorMessage = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                $('#alertContainer').html('<div class="alert alert-danger">Error: ' + errorMessage + '</div>');
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('#btnText').text('Proceed to Recharge');
                $('#btnSpinner').addClass('d-none');
            }
        });
    });

    // Function to fetch and display recharge records
    function fetchRechargeRecords() {
        console.log('Fetching recharge records...');
        
        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            beforeSend: function() {
                $('#rechargeTableBody').html('<tr><td colspan="7" class="text-center">Loading records...</td></tr>');
            },
            success: function(response) {
                console.log('API Response:', response);
                
                // Handle paginated response structure
                let data = [];
                
                if (response.data && response.data.data) {
                    // Paginated response: response.data.data contains the actual records
                    data = response.data.data;
                } else if (response.data && Array.isArray(response.data)) {
                    // Direct array in data property
                    data = response.data;
                } else if (Array.isArray(response)) {
                    // Direct array response
                    data = response;
                } else if (response.records) {
                    // Records property
                    data = response.records;
                }
                
                console.log('Processed data:', data);
                
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center text-muted">No recharge records found.</td></tr>';
                } else {
                    $.each(data, function(index, recharge) {
                        // Add null checks for each property
                        if (recharge) {
                            rows += `<tr>
                                <td>${recharge.id || '-'}</td>
                                <td>${recharge.created_at ? new Date(recharge.created_at).toLocaleString() : '-'}</td>
                                <td>${recharge.service || '-'}</td>
                                <td>${recharge.mobile_no || '-'}</td>
                                <td>₹${recharge.amount ? parseFloat(recharge.amount).toFixed(2) : '0.00'}</td>
                                <td>${recharge.transaction_id || '-'}</td>
                                <td><span class="badge ${getStatusClass(recharge.status)}">${recharge.status || 'Pending'}</span></td>
                            </tr>`;
                        }
                    });
                }
                $('#rechargeTableBody').html(rows);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    xhr: xhr,
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
                
                let errorMessage = 'Failed to load records.';
                if (xhr.status === 404) {
                    errorMessage = 'API endpoint not found (404).';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error (500).';
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error or CORS issue.';
                }
                
                $('#rechargeTableBody').html(`<tr><td colspan="7" class="text-center text-danger">${errorMessage}</td></tr>`);
            }
        });
    }

    // Utility to return Bootstrap class for status
    function getStatusClass(status) {
        switch (status) {
            case 'Success': 
            case 'success': 
                return 'bg-success';
            case 'Failed': 
            case 'failed': 
                return 'bg-danger';
            case 'Pending':
            case 'pending':
            default: 
                return 'bg-warning';
        }
    }

});
</script>
