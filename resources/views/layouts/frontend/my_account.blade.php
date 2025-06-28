@include('layouts.header')

<div class="service-card card mt-3">
    <div class="card-body">
        <div class="d-flex align-items-start user-service-tab">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link show active" id="v-pills-account-info-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-account-info" type="button" role="tab" aria-controls="v-pills-account-info"
                    aria-selected="true">Account Info</button> 
                <button class="nav-link" id="v-pills-complaint-register-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-complaint-register" type="button" role="tab"
                    aria-controls="v-pills-complaint-register" aria-selected="false">Complaint Register</button>
                <button class="nav-link" id="v-pills-complaint-status-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-complaint-status" type="button" role="tab"
                    aria-controls="v-pills-complaint-status" aria-selected="false">Complaint Status</button>
                <button class="nav-link" id="v-pills-redeem-discount-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-redeem-discount" type="button" role="tab"
                    aria-controls="v-pills-redeem-discount" aria-selected="false">Redeem Discount</button>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- account info -->
                <div class="tab-pane fade show active" id="v-pills-account-info" role="tabpanel"
                    aria-labelledby="v-pills-account-info-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>MY ACCOUNT</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <div id="loading-spinner" class="text-center" style="display: none;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p>Loading account information...</p>
                            </div>
                            
                            <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                            
                            <div class="row justify-content-center mt-2">
                                <form class="row form-horizontal" id="account-info-form" method="post" action="#"
                                    style="border-style: groove; margin-bottom:5px;">
                                    
                                    <label for="member_id" class="col-md-2 col-form-label fw-bold">Member ID</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member_id" data-field="member_id">-</span>
                                    </div>
                                    
                                    <label for="member_no" class="col-md-2 col-form-label fw-bold">Member No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member_no" data-field="member_no">-</span>
                                    </div>
                                    
                                    <label for="member_code" class="col-md-2 col-form-label fw-bold">Member Code</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member_code" data-field="member_code">-</span>
                                    </div>
                                    
                                    <label for="mobile_no" class="col-md-2 col-form-label fw-bold">Mobile No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="mobile_no" data-field="mobile_no">-</span>
                                    </div>
                                    
                                    <label for="firm_name" class="col-md-2 col-form-label fw-bold">Firm Name</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="firm_name" data-field="firm_name">-</span>
                                    </div>
                                    
                                    <label for="member_name" class="col-md-2 col-form-label fw-bold">Member Name</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member_name" data-field="member_name">-</span>
                                    </div>
                                    
                                    <label for="birth_date" class="col-md-2 col-form-label fw-bold">Birth Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="birth_date" data-field="birth_date">-</span>
                                    </div>
                                    
                                    <label for="age" class="col-md-2 col-form-label fw-bold">Age</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="age" data-field="age">-</span>
                                    </div>
                                    
                                    <label for="firm_address" class="col-md-2 col-form-label fw-bold">Firm Address</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="firm_address" data-field="firm_address">-</span>
                                    </div>
                                    
                                    <label for="home_address" class="col-md-2 col-form-label fw-bold">Home Address</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="home_address" data-field="home_address">-</span>
                                    </div>
                                    
                                    <label for="city_name" class="col-md-2 col-form-label fw-bold">City Name</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="city_name" data-field="city_name">-</span>
                                    </div>
                                    
                                    <label for="email" class="col-md-2 col-form-label fw-bold">Email</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="email" data-field="email">-</span>
                                    </div>
                                    
                                    <label for="status" class="col-md-2 col-form-label fw-bold">Status</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="status" data-field="status">-</span>
                                    </div>
                                    
                                    <label for="discount_pattern" class="col-md-2 col-form-label fw-bold">Discount Pattern</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="discount_pattern" data-field="discount_pattern">-</span>
                                    </div>
                                    
                                    <label for="pan_card_no" class="col-md-2 col-form-label fw-bold">Pan Card No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="pan_card_no" data-field="pan_card_no">-</span>
                                    </div>
                                    
                                    <label for="aadhaar_no" class="col-md-2 col-form-label fw-bold">Aadhaar No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="aadhaar_no" data-field="aadhaar_no">-</span>
                                    </div>
                                    
                                    <label for="gst_no" class="col-md-2 col-form-label fw-bold">GST No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="gst_no" data-field="gst_no">-</span>
                                    </div>
                                    
                                    <label for="registration_date" class="col-md-2 col-form-label fw-bold">Registration Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="registration_date" data-field="registration_date">-</span>
                                    </div>
                                    
                                    <label for="activation_date" class="col-md-2 col-form-label fw-bold">Activation Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="activation_date" data-field="activation_date">-</span>
                                    </div>
                                    
                                    <label for="password_change_date" class="col-md-2 col-form-label fw-bold">Password Change Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="password_change_date" data-field="password_change_date">-</span>
                                    </div>
                                    
                                    <label for="last_topup_date" class="col-md-2 col-form-label fw-bold">Last Topup Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="last_topup_date" data-field="last_topup_date">-</span>
                                    </div>
                                    
                                    <label for="balance" class="col-md-2 col-form-label fw-bold">Balance</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="balance" data-field="balance">-</span>
                                    </div>
                                    
                                    <label for="dmr_balance" class="col-md-2 col-form-label fw-bold">DMR Balance</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="dmr_balance" data-field="dmr_balance">-</span>
                                    </div>
                                    
                                    <label for="discount" class="col-md-2 col-form-label fw-bold">Discount</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="discount" data-field="discount">-</span>
                                    </div>
                                    
                                    <div class="col-12 mt-3">
                                        <button type="button" id="refresh-data" class="btn btn-primary">
                                            <i class="fas fa-sync-alt"></i> Refresh Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- account info --> 
                
                <!-- Complaint Register -->
                <div class="tab-pane fade" id="v-pills-complaint-register" role="tabpanel"
                    aria-labelledby="v-pills-complaint-register-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>REGISTER COMPLAINT</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <form class="row justify-content-center mt-2" method="post" action="#"
                                style="border-style: groove; margin-bottom:5px;">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Complaint Type</label>
                                    <select type="text" class="form-select" required>
                                        <option selected="selected" value="0">-- Select Type --</option>
                                        <option value="MOBILE">MOBILE</option>
                                        <option value="DTH">DTH</option>
                                        <option value="BILL PAY">BILL PAY</option>
                                        <option value="BUS">BUS</option>
                                        <option value="RAILWAY">RAILWAY</option>
                                        <option value="CINEMA">CINEMA</option>
                                        <option value="DMR">DMR</option>
                                        <option value="SERVICE">SERVICE</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" placeholder="Subject" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" placeholder="Description"></textarea>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label class="form-label">Error Screenshot</label>
                                    <input type="file" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="submit" class="btn btn-primary w-25">Submit</button>
                                    <button type="button" class="btn btn-secondary w-25 ms-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- Complaint Register -->

                <!-- Complaint Status -->
                <div class="tab-pane fade" id="v-pills-complaint-status" role="tabpanel"
                    aria-labelledby="v-pills-complaint-status-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>COMPLAINT STATUS</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <form class="row justify-content-center mt-2" method="post" action="#"
                                style="border-style: groove; margin-bottom:5px;">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" placeholder="Subject">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Complaint Date</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Status</label>
                                    <select type="text" class="form-select">
                                        <option selected="selected" value="0">-- Select Status --</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Under Process">Under Process</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="button" class="btn btn-primary w-25">Show</button>
                                    <button type="button" class="btn btn-secondary w-25 ms-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- Complaint Status -->

                <!-- Redeem Discount -->
                <div class="tab-pane fade" id="v-pills-redeem-discount" role="tabpanel"
                    aria-labelledby="v-pills-redeem-discount-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>REDEEM DISCOUNT</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <form class="row justify-content-center mt-2" method="post" action="#"
                                style="border-style: groove; margin-bottom:5px;">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Available</label>
                                    <input type="text" class="form-control" id="available-discount" placeholder="Available" value="0" readonly>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Redeem</label>
                                    <input type="text" class="form-control" placeholder="Amount to redeem" value="0">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="button" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary ms-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- Redeem Discount -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Function to show loading spinner
    function showLoading() {
        $('#loading-spinner').show();
        $('#error-message').hide();
        $('#account-info-form').hide();
    }
    
    // Function to hide loading spinner
    function hideLoading() {
        $('#loading-spinner').hide();
        $('#account-info-form').show();
    }
    
    // Function to show error message
    function showError(message) {
        $('#error-message').text(message).show();
        hideLoading();
    }
    
    // Function to fetch account info
    function fetchAccountInfo() {
        showLoading();
        
        $.ajax({
            url: '/api/kyc/account-info', // Use the getAccountInfo endpoint
            type: 'GET',
            dataType: 'json',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'), // Add auth token if needed
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            success: function(response) {
                hideLoading();
                
                if (response.success && response.kyc_data) {
                    var data = response.kyc_data;
                    
                    // Update all fields using their IDs
                    $('#member_id').text(data.member_id || '-');
                    $('#member_no').text(data.member_no || '-');
                    $('#member_code').text(data.member_code || '-');
                    $('#mobile_no').text(data.mobile_no || '-');
                    $('#firm_name').text(data.firm_name || '-');
                    $('#member_name').text(data.member_name || '-');
                    $('#birth_date').text(data.birth_date || '-');
                    $('#age').text(data.age || '-');
                    $('#firm_address').text(data.firm_address || '-');
                    $('#home_address').text(data.home_address || '-');
                    $('#city_name').text(data.city_name || '-');
                    $('#email').text(data.email || '-');
                    
                    // Special handling for status with color
                    var statusElement = $('#status');
                    statusElement.text(data.status || '-');
                    if (data.status === 'Active') {
                        statusElement.css('color', 'green');
                    } else {
                        statusElement.css('color', 'red');
                    }
                    
                    $('#discount_pattern').text(data.discount_pattern || '-');
                    $('#pan_card_no').text(data.pan_card_no || '-');
                    $('#aadhaar_no').text(data.aadhaar_no || '-');
                    $('#gst_no').text(data.gst_no || '-');
                    $('#registration_date').text(data.registration_date || '-');
                    $('#activation_date').text(data.activation_date || '-');
                    $('#password_change_date').text(data.password_change_date || '-');
                    $('#last_topup_date').text(data.last_topup_date || '-');
                    $('#balance').text(data.balance || '-');
                    $('#dmr_balance').text(data.dmr_balance || '-');
                    $('#discount').text(data.discount || '-');
                    
                    // Update available discount in redeem section
                    $('#available-discount').val(data.discount || '0.00 [ Rupees Only]');
                    
                } else {
                    showError('Failed to load account information: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = 'Failed to load account information';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage += ': ' + xhr.statusText;
                }
                
                showError(errorMessage);
                console.error('Account info fetch error:', xhr, status, error);
            }
        });
    }
    
    // Fetch account info on page load
    fetchAccountInfo();
    
    // Refresh button click handler
    $('#refresh-data').on('click', function(e) {
        e.preventDefault();
        fetchAccountInfo();
    });
    
    // Fetch when account info tab is shown
    $('#v-pills-account-info-tab').on('shown.bs.tab', function() {
        fetchAccountInfo();
    });
    
    // Auto-refresh every 5 minutes (optional)
    setInterval(function() {
        if ($('#v-pills-account-info-tab').hasClass('active')) {
            fetchAccountInfo();
        }
    }, 300000); // 5 minutes
});
</script>
