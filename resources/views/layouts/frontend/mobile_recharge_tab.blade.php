@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
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
                    <a class="nav-link" id="v-pills-history-tab" data-bs-toggle="pill" href="#v-pills-history"
                        role="tab" aria-controls="v-pills-history" aria-selected="false">Recharge History</a>
                    <a class="nav-link" id="v-pills-search-tab" data-bs-toggle="pill" href="#v-pills-search"
                        role="tab" aria-controls="v-pills-search" aria-selected="false">Search Recharge</a>
                </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Mobile Recharge Tab -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="row martop">
                        <div id="entry" class="col-md-4">
                            <div class="row formobile">
                                <h4>Mobile Recharge</h4>
                            </div>
                        </div>
                        <div id="divService" class="martop10"> 
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form id="rechargeForm" class="p-0">
                                            <div class="row service">
                                                <div class="mb-3" style="text-align:left;">
                                                    <label for="mobile_no" class="form-label">Mobile No.</label>
                                                    <input name="mobile_no" type="tel" class="form-control" id="mobile_no" placeholder="Enter mobile number" pattern="[0-9]{10}" maxlength="10" required>
                                                    <small class="form-text text-muted">Enter 10-digit mobile number</small>
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
$(document).ready(function() {
    // Load recharge history on page load
    fetchRechargeHistory();
    
    // Form validation
    function validateForm() {
        const mobileNo = $('#mobile_no').val();
        const operator = $('#operator').val();
        const circle = $('#circle').val();
        const amount = $('#amount').val();
        
        if (!mobileNo || mobileNo.length !== 10 || !/^\d{10}$/.test(mobileNo)) {
            showToast('Please enter a valid 10-digit mobile number', 'error');
            return false;
        }
        if (!operator) {
            showToast('Please select an operator', 'error');
            return false;
        }
        if (!circle) {
            showToast('Please select a plan type', 'error');
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
        
        toast.removeClass('success error warning info');
        
        let toastTitleText = title;
        if (!toastTitleText) {
            switch(type) {
                case 'success': toastTitleText = 'Success'; break;
                case 'error': toastTitleText = 'Error'; break;
                case 'warning': toastTitleText = 'Warning'; break;
                default: toastTitleText = 'Information';
            }
        }
        
        toast.addClass(type);
                toastTitle.text(toastTitleText);
        toastBody.text(message);
        
        const bsToast = new bootstrap.Toast(toast[0], { autohide: true, delay: 5000 });
        bsToast.show();
    }
    
    // Handle form submission
    $('#rechargeForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) { return; }
        
        $('#submitBtn').prop('disabled', true);
        $('#btnText').text('Processing...');
        $('#btnSpinner').removeClass('d-none');
        
        const formData = {
            mobile_no: $('#mobile_no').val().trim(),
            operator: $('#operator').val().trim(),
            circle: $('#circle').val().trim(),
            amount: parseFloat($('#amount').val())
        };
        
        $.ajax({
            url: '{{ url("/api/recharge/submit") }}',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            data: JSON.stringify(formData),
            timeout: 30000,
            success: function(response) {
                if (response.success) {
                    showToast('Mobile recharge completed successfully!', 'success', 'Recharge Success');
                    $('#rechargeForm')[0].reset();
                    
                    // Refresh wallet balance immediately
                    if (typeof window.refreshUserWalletBalance === 'function') {
                        window.refreshUserWalletBalance();
                    }
                } else {
                    showToast(response.message || 'Recharge failed. Please try again.', 'error', 'Recharge Failed');
                    
                    // Refresh wallet balance (amount might have been refunded)
                    if (typeof window.refreshUserWalletBalance === 'function') {
                        window.refreshUserWalletBalance();
                    }
                }
                
                setTimeout(function() { 
                    fetchRechargeHistory(); 
                }, 1000);
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to process recharge. Please try again.';
                let shouldRefreshBalance = false;
                
                if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Please try again.';
                    shouldRefreshBalance = true;
                } else if (xhr.status === 422) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                        // Check if it's insufficient balance error
                        if (errorMessage.includes('Insufficient wallet balance')) {
                            shouldRefreshBalance = true;
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join(', ');
                    }
                } else if (xhr.status === 401) {
                    errorMessage = 'Please login to continue.';
                    // Optionally redirect to login
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your internet connection.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timeout. Please check your recharge history.';
                    shouldRefreshBalance = true;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showToast(errorMessage, 'error', 'Recharge Failed');
                
                if (shouldRefreshBalance && typeof window.refreshUserWalletBalance === 'function') {
                    window.refreshUserWalletBalance();
                }
                
                setTimeout(function() { 
                    fetchRechargeHistory(); 
                }, 1500);
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('#btnText').text('Recharge Now');
                $('#btnSpinner').addClass('d-none');
            }
        });
    });
    
    // Function to fetch and display recharge history
    function fetchRechargeHistory() {
        $.ajax({
            url: '{{ url("/api/recharge/history") }}',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
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
                let data = [];
                if (Array.isArray(response)) {
                    data = response;
                } else if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                }
                
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center text-muted">No recharge records found.</td></tr>';
                } else {
                    $.each(data, function(index, recharge) {
                        if (recharge) {
                            rows += `<tr>
                                <td>${recharge.id || '-'}</td>
                                <td>${recharge.created_at ? new Date(recharge.created_at).toLocaleString() : '-'}</td>
                                <td>${recharge.operator ? recharge.operator.toUpperCase() : '-'}</td>
                                <td>${recharge.mobile_no || '-'}</td>
                                <td>₹${recharge.amount ? parseFloat(recharge.amount).toFixed(2) : '0.00'}</td>
                                <td>${recharge.txn_id || '-'}</td>
                                <td><span class="badge ${getStatusClass(recharge.status)}">${recharge.status || 'Pending'}</span></td>
                            </tr>`;
                        }
                    });
                }
                
                $('#rechargeTableBody').fadeOut(300, function() {
                    $(this).html(rows).fadeIn(300);
                });
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to load records.';
                if (xhr.status === 404) {
                    errorMessage = 'API endpoint not found (404).';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error (500).';
                } else if (xhr.status === 401) {
                    errorMessage = 'Please login to view records.';
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
                return 'bg-success';
            case 'Failed': 
                return 'bg-danger';
            case 'Pending':
            default: 
                return 'bg-warning';
        }
    }
    
    // Set default dates for history
    function setDefaultHistoryDates() {
        const today = new Date();
        const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
        $('#historyFromDate').val(lastMonth.toISOString().split('T')[0]);
        $('#historyToDate').val(today.toISOString().split('T')[0]);
    }
    
    // History filter form
    $('#historyFilterForm').on('submit', function(e) {
        e.preventDefault();
        fetchRechargeHistoryFiltered();
    });
    
    $('#resetHistoryBtn').on('click', function() {
        $('#historyFilterForm')[0].reset();
        setDefaultHistoryDates();
        $('#historyTableBody').html('<tr><td colspan="8" class="text-center">Click Search to load history</td></tr>');
    });
    
    function fetchRechargeHistoryFiltered() {
        const formData = {
            from_date: $('#historyFromDate').val(),
            to_date: $('#historyToDate').val(),
            status: $('#historyStatus').val(),
            operator: $('#historyOperator').val()
        };
        
        if (!formData.from_date || !formData.to_date) {
            showToast('Please select both from and to dates', 'error');
            return;
        }
        
        $.ajax({
            url: '{{ url("/api/recharge/search") }}',
            method: 'GET',
            data: formData,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            timeout: 15000,
            beforeSend: function() {
                $('#historyTableBody').html('<tr><td colspan="8" class="text-center">Searching...</td></tr>');
            },
            success: function(response) {
                let data = [];
                if (response.success && response.data && response.data.data) {
                    data = response.data.data;
                } else if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                } else if (Array.isArray(response)) {
                    data = response;
                }
                
                displayHistoryResults(data);
                showToast(`Found ${data.length} transactions`, data.length > 0 ? 'success' : 'info');
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to load transaction history.';
                if (xhr.status === 404) {
                    errorMessage = 'No records found matching your criteria.';
                    $('#historyTableBody').html('<tr><td colspan="8" class="text-center text-muted">No records found</td></tr>');
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred.';
                    $('#historyTableBody').html('<tr><td colspan="8" class="text-center text-danger">Server error occurred</td></tr>');
                } else {
                    $('#historyTableBody').html('<tr><td colspan="8" class="text-center text-danger">Search failed</td></tr>');
                }
                showToast(errorMessage, 'error');
            }
        });
    }
    
    function displayHistoryResults(data) {
        let rows = '';
        if (!data || data.length === 0) {
            rows = '<tr><td colspan="8" class="text-center text-muted">No records found matching your criteria.</td></tr>';
        } else {
            $.each(data, function(index, recharge) {
                if (recharge) {
                    rows += `<tr>
                        <td>${recharge.id || '-'}</td>
                        <td>${recharge.created_at ? new Date(recharge.created_at).toLocaleString() : '-'}</td>
                        <td>${recharge.operator ? recharge.operator.toUpperCase() : '-'}</td>
                        <td>${recharge.circle ? recharge.circle.toUpperCase() : '-'}</td>
                        <td>${recharge.mobile_no || '-'}</td>
                        <td>₹${recharge.amount ? parseFloat(recharge.amount).toFixed(2) : '0.00'}</td>
                        <td>${recharge.txn_id || '-'}</td>
                        <td><span class="badge ${getStatusClass(recharge.status)}">${recharge.status || 'Pending'}</span></td>
                    </tr>`;
                }
            });
        }
        
        $('#historyTableBody').fadeOut(300, function() {
            $(this).html(rows).fadeIn(300);
        });
    }
    
    // Search form
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        const mobileNo = $('#searchMobileNo').val().trim();
        const txnId = $('#searchTxnId').val().trim();
        
        if (!mobileNo && !txnId) {
            showToast('Please enter either mobile number or transaction ID', 'error');
            return;
        }
        
        if (mobileNo && (mobileNo.length !== 10 || !/^\d{10}$/.test(mobileNo))) {
            showToast('Please enter a valid 10-digit mobile number', 'error');
            return;
        }
        
        searchRechargeRecords(mobileNo, txnId);
    });
    
    $('#resetSearchBtn').on('click', function() {
        $('#searchForm')[0].reset();
        $('#searchResultsTableBody').html('<tr><td colspan="8" class="text-center">Enter search criteria and click Search</td></tr>');
    });
    
    function searchRechargeRecords(mobileNo, txnId) {
        $('#searchBtn').prop('disabled', true);
        $('#searchBtnText').text('Searching...');
        $('#searchBtnSpinner').removeClass('d-none');
        
        const params = {};
        if (mobileNo) params.mobile_no = mobileNo;
        if (txnId) params.txn_id = txnId;
        
        $.ajax({
            url: '{{ url("/api/recharge/search") }}',
            method: 'GET',
            data: params,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            timeout: 15000,
            beforeSend: function() {
                $('#searchResultsTableBody').html('<tr><td colspan="8" class="text-center">Searching...</td></tr>');
            },
            success: function(response) {
                let data = [];
                if (response.success && response.data && response.data.data) {
                    data = response.data.data;
                } else if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                } else if (Array.isArray(response)) {
                    data = response;
                }
                
                displaySearchResults(data);
                showToast(`Found ${data.length} matching records`, data.length > 0 ? 'success' : 'info');
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to search records.';
                if (xhr.status === 404) {
                    errorMessage = 'No records found matching your criteria.';
                    $('#searchResultsTableBody').html('<tr><td colspan="8" class="text-center text-muted">No records found</td></tr>');
                } else if (xhr.status === 500) {
                                        errorMessage = 'Server error occurred.';
                    $('#searchResultsTableBody').html('<tr><td colspan="8" class="text-center text-danger">Server error occurred</td></tr>');
                } else {
                    $('#searchResultsTableBody').html('<tr><td colspan="8" class="text-center text-danger">Search failed</td></tr>');
                }
                showToast(errorMessage, 'error');
            },
            complete: function() {
                $('#searchBtn').prop('disabled', false);
                $('#searchBtnText').text('Search');
                $('#searchBtnSpinner').addClass('d-none');
            }
        });
    }
    
    function displaySearchResults(data) {
        let rows = '';
        if (!data || data.length === 0) {
            rows = '<tr><td colspan="8" class="text-center text-muted">No records found matching your criteria.</td></tr>';
        } else {
            $.each(data, function(index, recharge) {
                if (recharge) {
                    rows += `<tr>
                        <td>${recharge.id || '-'}</td>
                        <td>${recharge.created_at ? new Date(recharge.created_at).toLocaleString() : '-'}</td>
                        <td>${recharge.operator ? recharge.operator.toUpperCase() : '-'}</td>
                        <td>${recharge.circle ? recharge.circle.toUpperCase() : '-'}</td>
                        <td>${recharge.mobile_no || '-'}</td>
                        <td>₹${recharge.amount ? parseFloat(recharge.amount).toFixed(2) : '0.00'}</td>
                        <td>${recharge.txn_id || '-'}</td>
                        <td><span class="badge ${getStatusClass(recharge.status)}">${recharge.status || 'Pending'}</span></td>
                    </tr>`;
                }
            });
        }
        
        $('#searchResultsTableBody').fadeOut(300, function() {
            $(this).html(rows).fadeIn(300);
        });
    }
    
    // Mobile number validation
    $('#mobile_no, #searchMobileNo').on('input', function() {
        const value = this.value;
        if (value.length === 10 && /^[0-9]+$/.test(value)) {
            this.style.borderColor = "green";
        } else if (value.length > 0) {
            this.style.borderColor = "red";
        } else {
            this.style.borderColor = "";
        }
    });
    
    // Initialize default dates
    setDefaultHistoryDates();
    
    // Tab change events
    $('#v-pills-history-tab').on('shown.bs.tab', function() {
        setDefaultHistoryDates();
    });
});
</script>

</body>
</html>


