@include('layouts.header')

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
                                        <input name="mobile_no" type="tel" class="form-control" id="customerId" placeholder="Enter any mobile number" pattern="[0-9]{10}" maxlength="10" required>
                                        <small class="form-text text-muted">You can recharge any mobile number</small>
                                    </div>
                                    <div class="mb-3" style="text-align:left;">
                                        <label for="amount" class="form-label">Amount (₹)</label>
                                        <input name="amount" type="number" class="form-control" id="amount" placeholder="Amount" min="1" max="10000" step="1" required>
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
</div>

</div>

<!-- Custom CSS for Toast Styling -->
<style>
.toast-container {
    z-index: 1055 !important;
}

.toast {
    min-width: 300px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.toast.success .toast-header {
    background-color: #d1edff;
    color: #0c5460;
    border-bottom: 1px solid #bee5eb;
}

.toast.success .toast-body {
    background-color: #d4edda;
    color: #155724;
}

.toast.error .toast-header {
    background-color: #f8d7da;
    color: #721c24;
    border-bottom: 1px solid #f5c6cb;
}

.toast.error .toast-body {
    background-color: #f8d7da;
    color: #721c24;
}

.toast.warning .toast-header {
    background-color: #fff3cd;
    color: #856404;
    border-bottom: 1px solid #ffeaa7;
}

.toast.warning .toast-body {
    background-color: #fff3cd;
    color: #856404;
}

.toast.info .toast-header {
    background-color: #d1ecf1;
    color: #0c5460;
    border-bottom: 1px solid #bee5eb;
}

.toast.info .toast-body {
    background-color: #d1ecf1;
    color: #0c5460;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Load recharge records on page load
    fetchRechargeRecords();

    // Form validation
    function validateForm() {
        const service = $('#cmbService').val();
        const mobileNo = $('#customerId').val();
        const amount = $('#amount').val();

        if (!service) {
            showToast('Please select an operator', 'error');
            return false;
        }

        if (!mobileNo || mobileNo.length !== 10 || !/^\d{10}$/.test(mobileNo)) {
            showToast('Please enter a valid 10-digit mobile number', 'error');
            return false;
        }

        if (!amount || amount < 1 || amount > 10000) {
            showToast('Please enter a valid amount between ₹1 and ₹10,000', 'error');
            return false;
        }

        return true;
    }

    // Show toast notification function
    function showToast(message, type = 'info', title = null) {
        const toast = $('#alertToast');
        const toastTitle = $('#toastTitle');
        const toastBody = $('#toastBody');
        
        // Remove existing classes
        toast.removeClass('success error warning info');
        
        // Set title based on type
        let toastTitleText = title;
        if (!toastTitleText) {
            switch(type) {
                case 'success':
                    toastTitleText = 'Success';
                    break;
                case 'error':
                    toastTitleText = 'Error';
                    break;
                case 'warning':
                    toastTitleText = 'Warning';
                    break;
                default:
                    toastTitleText = 'Information';
            }
        }
        
        // Add appropriate class and set content
        toast.addClass(type);
        toastTitle.text(toastTitleText);
        toastBody.text(message);
        
        // Show the toast
        const bsToast = new bootstrap.Toast(toast[0], {
            autohide: true,
            delay: 5000
        });
        bsToast.show();
    }

    // Handle form submission
    $('#rechargeForm').on('submit', function(e) {
        e.preventDefault();

        // Validate form
        if (!validateForm()) {
            return;
        }

        // Disable button and show spinner
        $('#submitBtn').prop('disabled', true);
        $('#btnText').text('Processing...');
        $('#btnSpinner').removeClass('d-none');

        const formData = {
            service: $('#cmbService').val().trim(),
            mobile_no: $('#customerId').val().trim(),
            amount: parseFloat($('#amount').val())
        };

        console.log('Submitting recharge data:', formData);

        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data: JSON.stringify(formData),
            timeout: 30000, // 30 second timeout
            success: function(response) {
                console.log('Recharge successful:', response);
                
                // Check if the response indicates success
                if (response.status === 'success' || response.message.toLowerCase().includes('success')) {
                    showToast('Recharge initiated successfully!', 'success', 'Recharge Initiated');
                    
                    // Reset form
                    $('#rechargeForm')[0].reset();
                    
                    // Automatically refresh the table after successful recharge
                    console.log('Auto-refreshing table after successful recharge...');
                    setTimeout(function() {
                        fetchRechargeRecords();
                    }, 1000);
                } else {
                    showToast('Recharge request processed but status unclear. Please check the table below.', 'warning', 'Status Unclear');
                    setTimeout(function() {
                        fetchRechargeRecords();
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                console.error('Recharge error details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    responseJSON: xhr.responseJSON
                });

                let errorMessage = 'Failed to process recharge. Please try again.';
                let shouldRefreshTable = false;
                
                // Handle different error scenarios
                if (xhr.status === 500) {
                    // Server error - recharge will be marked as failed
                    errorMessage = 'Server error occurred. Recharge marked as failed.';
                    shouldRefreshTable = true;
                } else if (xhr.status === 422) {
                    // Validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your internet connection.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timeout. Please check your recharge history.';
                    shouldRefreshTable = true;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                    // Check if it's actually a success message
                    if (errorMessage.toLowerCase().includes('success') || 
                        errorMessage.toLowerCase().includes('initiated')) {
                        showToast('Recharge initiated successfully! Status: Pending', 'success', 'Recharge Initiated');
                        $('#rechargeForm')[0].reset();
                        shouldRefreshTable = true;
                        errorMessage = null; // Don't show error
                    }
                }

                // Show error message only if there's actually an error
                if (errorMessage) {
                    showToast(errorMessage, 'error', 'Recharge Failed');
                }

                // Refresh table if needed
                if (shouldRefreshTable) {
                    console.log('Refreshing table to check recharge status...');
                    setTimeout(function() {
                        fetchRechargeRecords();
                    }, 1500);
                }
            },
            complete: function() {
                // Re-enable button and hide spinner
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
            timeout: 15000,
            beforeSend: function() {
                if ($('#rechargeTableBody tr').length === 1 && $('#rechargeTableBody tr td').text().includes('Loading')) {
                    $('#rechargeTableBody').html('<tr><td colspan="7" class="text-center">Loading records...</td></tr>');
                }
            },
            success: function(response) {
                console.log('Records fetched successfully:', response);
                
                let data = [];
                
                if (response.data && response.data.data) {
                    data = response.data.data;
                } else if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                } else if (Array.isArray(response)) {
                    data = response;
                } else if (response.records) {
                    data = response.records;
                }
                
                console.log('Table will be updated with:', data.length, 'records');
                
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center text-muted">No recharge records found.</td></tr>';
                } else {
                    $.each(data, function(index, recharge) {
                        if (recharge) {
                            rows += `<tr>
                                <td>${recharge.id || '-'}</td>
                                <td>${recharge.created_at ? new Date(recharge.created_at).toLocaleString() : '-'}</td>
                                <td>${recharge.service ? recharge.service.toUpperCase() : '-'}</td>
                                <td>${recharge.mobile_no || '-'}</td>
                                <td>₹${recharge.amount ? parseFloat(recharge.amount).toFixed(2) : '0.00'}</td>
                                <td>${recharge.transaction_id || '-'}</td>
                                <td><span class="badge ${getStatusClass(recharge.status)}">${recharge.status || 'Pending'}</span></td>
                            </tr>`;
                        }
                    });
                }
                
                // Update table content with smooth transition
                $('#rechargeTableBody').fadeOut(300, function() {
                    $(this).html(rows).fadeIn(300);
                    console.log('Table updated and displayed');
                });
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch records:', {
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
                } else if (status === 'timeout') {
                    errorMessage = 'Request timeout. Please refresh the page.';
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
