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
            service: $('#cmbService').val().trim(),
            mobile_no: $('#customerId').val().trim(),
            amount: parseFloat($('#amount').val())
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
            timeout: 30000,
            success: function(response) {
                if (response.status === 'success' && response.data && response.data.status === 'success') {
                    showToast('Recharge completed successfully!', 'success', 'Recharge Success');
                    $('#rechargeForm')[0].reset();
                } else if (response.status === 'failed' || (response.data && response.data.status === 'failed')) {
                    showToast('Recharge failed. Please try again.', 'error', 'Recharge Failed');
                } else if (response.status === 'success' && response.data && response.data.status === 'pending') {
                    showToast('Recharge initiated successfully! Status: Pending', 'warning', 'Recharge Pending');
                    $('#rechargeForm')[0].reset();
                } else {
                    showToast('Recharge request processed. Please check the table below for status.', 'warning', 'Status Unclear');
                }
                setTimeout(function() { 
                    fetchRechargeRecords(); 
                    fetchPendingTransactions(); // Refresh pending transactions
                    fetchFailedTransactions(); // Add this line to also refresh failed transactions
                }, 1000);
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to process recharge. Please try again.';
                let shouldRefreshTable = false;
                if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Recharge marked as failed.';
                    shouldRefreshTable = true;
                } else if (xhr.status === 422) {
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
                }
                showToast(errorMessage, 'error', 'Recharge Failed');
                if (shouldRefreshTable) {
                    setTimeout(function() { 
                        fetchRechargeRecords(); 
                        fetchPendingTransactions();
                        fetchFailedTransactions(); // Add this line
                    }, 1500);
                }
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
    // Set default dates for report
    setDefaultReportDates();
    function setDefaultReportDates() {
        const today = new Date();
        const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
        $('#reportFromDate').val(lastMonth.toISOString().split('T')[0]);
        $('#reportToDate').val(today.toISOString().split('T')[0]);
    }
    $('#searchReportBtn').off('click').on('click', function() {
        fetchTransactionReport();
    });
    $('#resetReportBtn').on('click', function() {
        setDefaultReportDates();
        $('#reportCustomerNo').val('');
        $('#reportStatus').val('');
        $('#reportService').val('');
        $('#reportTableBody').html('<tr><td colspan="7" class="text-center">Click Search to load transactions</td></tr>');
    });
    function fetchTransactionReport() {
        const fromDate = $('#reportFromDate').val();
        const toDate = $('#reportToDate').val();
        const status = $('#reportStatus').val();
        const service = $('#reportService').val();
        const customerNo = $('#reportCustomerNo').val().trim();
        if (!fromDate || !toDate) {
            showToast('Please select both from and to dates', 'error');
            return;
        }
        if (customerNo && (customerNo.length !== 10 || !/^\d{10}$/.test(customerNo))) {
            showToast('Please enter a valid 10-digit mobile number', 'error');
            return;
        }
        const params = {
            from_date: fromDate,
            to_date: toDate
        };
        if (customerNo && customerNo !== '') {
            params.mobile_no = customerNo;
        }
        if (status && status.trim() !== '') {
            params.status = status.trim();
        }
        if (service && service.trim() !== '') {
            params.service = service.trim();
        }
        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'GET',
            data: params,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            timeout: 15000,
            beforeSend: function() {
                $('#reportTableBody').html('<tr><td colspan="7" class="text-center">Searching...</td></tr>');
            },
            success: function(response) {
                let data = [];
                if (response.data && response.data.data) {
                    data = response.data.data;
                } else if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                } else if (Array.isArray(response)) {
                    data = response;
                }
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center text-muted">No transactions found for the selected criteria.</td></tr>';
                } else {
                    $.each(data, function(index, transaction) {
                        if (transaction) {
                            rows += `<tr>
                                <td>${transaction.id || '-'}</td>
                                <td>${transaction.created_at ? new Date(transaction.created_at).toLocaleString() : '-'}</td>
                                <td>${transaction.service ? transaction.service.toUpperCase() : '-'}</td>
                                <td>${transaction.mobile_no || '-'}</td>
                                <td>₹${transaction.amount ? parseFloat(transaction.amount).toFixed(2) : '0.00'}</td>
                                <td>${transaction.transaction_id || '-'}</td>
                                <td><span class="badge ${getStatusClass(transaction.status)}">${transaction.status || 'Pending'}</span></td>
                            </tr>`;
                        }
                    });
                }
                $('#reportTableBody').fadeOut(300, function() {
                    $(this).html(rows).fadeIn(300);
                });
                showToast(`Found ${data.length} transactions`, 'success');
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to load transaction report.';
                if (xhr.status === 404) {
                    errorMessage = 'Report API endpoint not found.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred.';
                }
                $('#reportTableBody').html(`<tr><td colspan="7" class="text-center text-danger">${errorMessage}</td></tr>`);
                showToast(errorMessage, 'error');
            }
        });
    }
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        const mobileNo = $('#searchMobileNo').val().trim();
        const transactionId = $('#searchTransactionId').val().trim();
        if (!mobileNo && !transactionId) {
            showToast('Please enter either mobile number or transaction ID', 'error');
            return;
        }
        if (mobileNo && (mobileNo.length !== 10 || !/^\d{10}$/.test(mobileNo))) {
            showToast('Please enter a valid 10-digit mobile number', 'error');
            return;
        }
        searchRechargeRecords(mobileNo, transactionId);
    });
    $('#resetSearchBtn').on('click', function() {
        $('#searchForm')[0].reset();
        $('#searchResultsTableBody').html('<tr><td colspan="7" class="text-center">Enter search criteria and click Show</td></tr>');
    });
    function searchRechargeRecords(mobileNo, transactionId) {
        $('#searchBtn').prop('disabled', true);
        $('#searchBtnText').text('Searching...');
        $('#searchBtnSpinner').removeClass('d-none');
        const params = {};
        if (mobileNo) params.mobile_no = mobileNo;
        if (transactionId) params.transaction_id = transactionId;
        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'GET',
            data: params,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            timeout: 15000,
            beforeSend: function() {
                $('#searchResultsTableBody').html('<tr><td colspan="7" class="text-center">Searching...</td></tr>');
            },
            success: function(response) {
                let data = [];
                if (response.data && response.data.data) {
                    data = response.data.data;
                } else if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                } else if (Array.isArray(response)) {
                    data = response;
                }
                displaySearchResults(data);
                let foundStatus = 'not_found';
                if (data.length > 0) {
                    foundStatus = data[0].status || 'unknown';
                }
                saveSearchHistory(mobileNo, transactionId, foundStatus);
                showToast(`Found ${data.length} matching records`, data.length > 0 ? 'success' : 'info');
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to search records.';
                if (xhr.status === 404) {
                    errorMessage = 'No records found matching your criteria.';
                    $('#searchResultsTableBody').html('<tr><td colspan="7" class="text-center text-muted">No records found</td></tr>');
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred.';
                    $('#searchResultsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Server error occurred</td></tr>');
                } else {
                    $('#searchResultsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Search failed</td></tr>');
                }
                saveSearchHistory(mobileNo, transactionId, 'not_found');
                showToast(errorMessage, 'error');
            },
            complete: function() {
                $('#searchBtn').prop('disabled', false);
                $('#searchBtnText').text('Show');
                $('#searchBtnSpinner').addClass('d-none');
            }
        });
    }
    function displaySearchResults(data) {
        let rows = '';
        if (!data || data.length === 0) {
            rows = '<tr><td colspan="7" class="text-center text-muted">No records found matching your criteria.</td></tr>';
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
        $('#searchResultsTableBody').fadeOut(300, function() {
            $(this).html(rows).fadeIn(300);
        });
    }
    function saveSearchHistory(mobileNo, transactionId, status) {
        const historyData = {
            customer_id: mobileNo || null,
            transaction_id: transactionId || null,
            status: status || 'unknown'
        };
        $.ajax({
            url: '{{ url("/api/search-history") }}',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data: JSON.stringify(historyData),
            success: function(response) {
                fetchSearchHistory();
            },
            error: function(xhr, status, error) {
            }
        });
    }
    function fetchSearchHistory() {
        $.ajax({
            url: '{{ url("/api/search-history") }}',
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            success: function(response) {
                let data = [];
                if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                }
                displaySearchHistory(data);
            },
            error: function(xhr, status, error) {
                $('#searchHistoryTableBody').html('<tr><td colspan="4" class="text-center text-danger">Failed to load search history</td></tr>');
            }
        });
    }
    function displaySearchHistory(data) {
        let rows = '';
        if (!data || data.length === 0) {
            rows = '<tr><td colspan="4" class="text-center text-muted">No search history found.</td></tr>';
        } else {
            $.each(data, function(index, history) {
                if (history) {
                    const searchTime = history.search_time ? new Date(history.search_time).toLocaleString() : '-';
                    rows += `<tr>
                        <td>${searchTime}</td>
                        <td>${history.customer_id || '-'}</td>
                        <td>${history.transaction_id || '-'}</td>
                        <td><span class="badge ${getStatusClass(history.status)}">${history.status || 'Unknown'}</span></td>
                    </tr>`;
                }
            });
        }
        $('#searchHistoryTableBody').html(rows);
    }
    $('#v-pills-status-tab').on('shown.bs.tab', function() {
        fetchSearchHistory();
    });
    $('#refreshHistoryBtn').on('click', function() {
        fetchSearchHistory();
    });


    function fetchPendingTransactions(page = 1) {
        const params = {
            page: page,
            per_page: 10
        };
        
        // Add filter parameters
        const mobileNo = $('#pendingMobileNo').val().trim();
        const fromDate = $('#pendingFromDate').val();
        const toDate = $('#pendingToDate').val();
        
        if (mobileNo) params.mobile_no = mobileNo;
        if (fromDate) params.from_date = fromDate;
        if (toDate) params.to_date = toDate;
        
        $.ajax({
            url: '{{ url("/api/dth?status=pending") }}',
            method: 'GET',
            data: params,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            timeout: 15000,
            beforeSend: function() {
                $('#pendingTransactionsTableBody').html('<tr><td colspan="8" class="text-center">Loading pending transactions...</td></tr>');
            },
            success: function(response) {
                let data = [];
                if (response.data && response.data.data) {
                    data = response.data.data;
                }
                
                // Update pending count
                const count = response.count || 0;
                $('#pendingCount').text(count);
                
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="8" class="text-center text-muted">No pending transactions found.</td></tr>';
                } else {
                    $.each(data, function(index, transaction) {
                        rows += `<tr>
                            <td>${transaction.id || '-'}</td>
                            <td>${transaction.created_at ? new Date(transaction.created_at).toLocaleString() : '-'}</td>
                            <td>${transaction.service ? transaction.service.toUpperCase() : '-'}</td>
                            <td>${transaction.mobile_no || '-'}</td>
                            <td>₹${transaction.amount ? parseFloat(transaction.amount).toFixed(2) : '0.00'}</td>
                            <td>${transaction.transaction_id || '-'}</td>
                            <td><span class="badge ${getStatusClass(transaction.status)}">${transaction.status || 'Pending'}</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary retry-btn" data-id="${transaction.id}">
                                    <i class="fas fa-redo"></i> Retry
                                </button>
                            </td>
                        </tr>`;
                    });
                }
                
                $('#pendingTransactionsTableBody').html(rows);
            },
            error: function(xhr, status, error) {
                $('#pendingTransactionsTableBody').html('<tr><td colspan="8" class="text-center text-danger">Failed to load pending transactions</td></tr>');
                $('#pendingCount').text('0');
            }
        });
    }
    
    // Event handlers for pending tab
    $('#pendingFilterForm').on('submit', function(e) {
        e.preventDefault();
        fetchPendingTransactions();
    });
    
    $('#resetPendingFilterBtn').on('click', function() {
        $('#pendingFilterForm')[0].reset();
        fetchPendingTransactions();
    });
    
    $('#refreshPendingBtn').on('click', function() {
        fetchPendingTransactions();
    });
    
    // Retry single transaction
    $(document).on('click', '.retry-btn', function() {
        const transactionId = $(this).data('id');
        const btn = $(this);
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying...');
        
        $.ajax({
            url: `{{ url("/api/dth") }}/${transactionId}/retry`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                showToast(response.message, 'success');
                fetchPendingTransactions();
                fetchRechargeRecords();
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to retry transaction';
                showToast(message, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-redo"></i> Retry');
            }
        });
    });
    
    // Retry all pending transactions
    $('#retryAllPendingBtn').on('click', function() {
        const btn = $(this);
        
        if (!confirm('Are you sure you want to retry all pending transactions?')) {
            return;
        }
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying All...');
        
        $.ajax({
            url: '{{ url("/api/dth/retry-all") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                showToast(response.message, 'success');
                fetchPendingTransactions();
                fetchRechargeRecords();
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to retry transactions';
                showToast(message, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-redo"></i> Retry All');
            }
        });
    });
    
    // Load pending transactions when tab is shown
    $('#v-pills-pending-tab').on('shown.bs.tab', function() {
        fetchPendingTransactions();
    });


    // Function to fetch failed transactions
    function fetchFailedTransactions(page = 1) {
        const params = {
            page: page,
            per_page: 10
        };
        
        // Add filter parameters
        const mobileNo = $('#failedMobileNo').val().trim();
        const fromDate = $('#failedFromDate').val();
        const toDate = $('#failedToDate').val();
        
        if (mobileNo) params.mobile_no = mobileNo;
        if (fromDate) params.from_date = fromDate;
        if (toDate) params.to_date = toDate;
        
        $.ajax({
            url: '{{ url("/api/dth?status=failed") }}',
            method: 'GET',
            data: params,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            timeout: 15000,
            beforeSend: function() {
                $('#failedTransactionsTableBody').html('<tr><td colspan="8" class="text-center">Loading failed transactions...</td></tr>');
            },
            success: function(response) {
                let data = [];
                if (response.data && response.data.data) {
                    data = response.data.data;
                }
                
                // Update failed count
                const count = response.count || 0;
                $('#failedCount').text(count);
                
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="8" class="text-center text-muted">No failed transactions found.</td></tr>';
                } else {
                    $.each(data, function(index, transaction) {
                        rows += `<tr>
                            <td>${transaction.id || '-'}</td>
                            <td>${transaction.created_at ? new Date(transaction.created_at).toLocaleString() : '-'}</td>
                            <td>${transaction.service ? transaction.service.toUpperCase() : '-'}</td>
                            <td>${transaction.mobile_no || '-'}</td>
                            <td>₹${transaction.amount ? parseFloat(transaction.amount).toFixed(2) : '0.00'}</td>
                            <td>${transaction.transaction_id || '-'}</td>
                            <td><span class="badge ${getStatusClass(transaction.status)}">${transaction.status || 'Failed'}</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary retry-failed-btn" data-id="${transaction.id}">
                                    <i class="fas fa-redo"></i> Retry
                                </button>
                            </td>
                        </tr>`;
                    });
                }
                
                $('#failedTransactionsTableBody').html(rows);
            },
            error: function(xhr, status, error) {
                $('#failedTransactionsTableBody').html('<tr><td colspan="8" class="text-center text-danger">Failed to load failed transactions</td></tr>');
                $('#failedCount').text('0');
            }
        });
    }
    
    // Event handlers for failed tab
    $('#failedFilterForm').on('submit', function(e) {
        e.preventDefault();
        fetchFailedTransactions();
    });
    
    $('#resetFailedFilterBtn').on('click', function() {
        $('#failedFilterForm')[0].reset();
        fetchFailedTransactions();
    });
    
    $('#refreshFailedBtn').on('click', function() {
        fetchFailedTransactions();
    });
    
    // Retry single failed transaction
    $(document).on('click', '.retry-failed-btn', function() {
        const transactionId = $(this).data('id');
        const btn = $(this);
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying...');
        
        $.ajax({
            url: `{{ url("/api/dth") }}/${transactionId}/retry-failed`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                showToast(response.message, 'success');
                fetchFailedTransactions();
                fetchRechargeRecords();
                fetchPendingTransactions(); // Also refresh pending as it might move there
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to retry transaction';
                showToast(message, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-redo"></i> Retry');
            }
        });
    });
    
    // Retry all failed transactions
    $('#retryAllFailedBtn').on('click', function() {
        const btn = $(this);
        
        if (!confirm('Are you sure you want to retry all failed transactions?')) {
            return;
        }
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying All...');
        
        $.ajax({
            url: '{{ url("/api/dth/retry-all-failed") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                showToast(response.message, 'success');
                fetchFailedTransactions();
                fetchRechargeRecords();
                fetchPendingTransactions(); // Also refresh pending as retried transactions might move there
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to retry transactions';
                showToast(message, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-redo"></i> Retry All');
            }
        });
    });
    
    // Load failed transactions when tab is shown
    $('#v-pills-failed-tab').on('shown.bs.tab', function() {
        fetchFailedTransactions();
    });


});
</script>
