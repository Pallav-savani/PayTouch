<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Set up AJAX defaults
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });

    // Load initial data on page load
    fetchCCBillRecords();
    
    // Form validation for CC Fetch Bill
    function validateCCFetchForm() {
        const ccNumber = $('#ccNumber').val().trim();
        const mobileNumber = $('#mobileNumber').val().trim();
        
        if (!ccNumber || ccNumber.length < 10) {
            showToast('Please enter a valid credit card number', 'error');
            return false;
        }
        if (!mobileNumber || mobileNumber.length !== 10) {
            showToast('Please enter a valid 10-digit mobile number', 'error');
            return false;
        }
        // Basic mobile number validation (10 digits)
        if (!/^\d{10}$/.test(mobileNumber)) {
            showToast('Mobile number must be 10 digits', 'error');
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
    
    // Handle CC Fetch Bill form submission
    $('#ccFetchBillForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateCCFetchForm()) { 
            return; 
        }
        
        $('#fetchBillBtn').prop('disabled', true);
        $('#fetchBtnText').text('Fetching...');
        $('#fetchBtnSpinner').removeClass('d-none');
        
        const formData = {
            cc_number: $('#ccNumber').val().trim(),
            mobile_number: $('#mobileNumber').val().trim()
        };
        
        $.ajax({
            url: '{{ url("/api/cc-bill-payments") }}',
            method: 'GET',
            data: formData,
            timeout: 30000,
            success: function(response) {
                if (response.status === 'success' && response.data && response.data.length > 0) {
                    displayCCBills(response.data);
                    showToast(`Found ${response.data.length} bill(s) for the provided details`, 'success', 'Bills Fetched');
                    $('#ccBillsContainer').show();
                    startAutoRefresh(formData);
                } else {
                    showToast('No bills found for the provided credit card number and mobile number', 'warning', 'No Bills Found');
                    $('#ccBillsContainer').hide();
                    $('#ccBillsTableBody').html('<tr><td colspan="13" class="text-center text-muted">No bills found for the provided details.</td></tr>');
                    stopAutoRefresh();
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to fetch credit card bills. Please try again.';
                
                if (xhr.status === 404) {
                    errorMessage = 'No bills found for the provided details.';
                } else if (xhr.status === 422) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Please try again later.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your internet connection.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timeout. Please try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showToast(errorMessage, 'error', 'Fetch Failed');
                $('#ccBillsContainer').hide();
            },
            complete: function() {
                $('#fetchBillBtn').prop('disabled', false);
                $('#fetchBtnText').text('Fetch');
                $('#fetchBtnSpinner').addClass('d-none');
            }
        });
    });
    
    // Function to display CC bills in table format
    function displayCCBills(bills) {
        let rows = '';

        if (!bills || bills.length === 0) {
            rows = '<tr><td colspan="13" class="text-center text-muted">No bills found for the provided details.</td></tr>';
        } else {
            $.each(bills, function(index, bill) {
                if (bill) {
                    const processedDate = bill.processed_at ? new Date(bill.processed_at).toLocaleString() : '-';
                    const createdDate = bill.created_at ? new Date(bill.created_at).toLocaleString() : '-';
                    const amount = bill.amt ? parseFloat(bill.amt).toFixed(2) : '0.00';
                    const status = bill.status || 'pending';
                    const statusClass = getStatusClass(status);

                    // Decrypt or mask credit card number safely
                    let ccDisplay = '-';
                    if (bill.cn) {
                        if (bill.cn.length > 4) {
                            ccDisplay = '****' + bill.cn.slice(-4);
                        } else {
                            ccDisplay = '****' + bill.cn;
                        }
                    }

                    rows += `<tr>
                        <td>${bill.id || '-'}</td>
                        <td>${bill.user_id || '-'}</td>
                        <td>${ccDisplay}</td>
                        <td>${bill.op || '-'}</td>
                        <td>${bill.cir || '-'}</td>
                        <td>₹${amount}</td>
                        <td>${bill.reqid || '-'}</td>
                        <td>${bill.ad9 || '-'}</td>
                        <td>${bill.ad3 || '-'}</td>
                        <td><span class="badge ${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                        <td>${bill.transaction_id || '-'}</td>
                        <td>${bill.operator_ref || '-'}</td>
                        <td>${processedDate}</td>
                    </tr>`;
                }
            });
        }

        $('#ccBillsTableBody').fadeOut(300, function() {
            $(this).html(rows).fadeIn(300);
        });
        
        // Show the container
        $('#ccBillsContainer').show();
    }

    // Auto-refresh functionality
    let autoRefreshInterval;
    let currentFormData;

    function startAutoRefresh(formData) {
        currentFormData = formData;
        
        // Clear any existing interval
        stopAutoRefresh();
        
        // Set up auto-refresh every 30 seconds
        autoRefreshInterval = setInterval(function() {
            refreshCCBills();
        }, 30000); // 30 seconds
        
        console.log('Auto-refresh started for CC bills');
    }

    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
            console.log('Auto-refresh stopped');
        }
    }

    function refreshCCBills() {
        if (!currentFormData) return;
        
        $.ajax({
            url: '{{ url("/api/cc-bill-payments") }}',
            method: 'GET',
            data: currentFormData,
            timeout: 15000,
            success: function(response) {
                if (response.status === 'success' && response.data && response.data.length > 0) {
                    displayCCBills(response.data);
                    console.log('CC Bills refreshed automatically');
                } else {
                    console.log('No bills found during auto-refresh');
                }
            },
            error: function(xhr, status, error) {
                console.log('Auto-refresh failed:', error);
                // Don't show error toast for auto-refresh failures to avoid spam
            }
        });
    }

    // Stop auto-refresh when user navigates away or form is reset
    $(window).on('beforeunload', function() {
        stopAutoRefresh();
    });

    // Stop auto-refresh when switching tabs or form is reset
    $('#ccFetchBillForm').on('reset', function() {
        stopAutoRefresh();
        $('#ccBillsContainer').hide();
    });

    // Stop auto-refresh when clicking on other tabs
    $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
        if (!$(e.target).attr('href').includes('cc-fetch')) {
            stopAutoRefresh();
        }
    });

    // Form validation for CC Bill payment
    function validateCCBillForm() {
        const service = $('#cmbCCService').val();
        const customerNo = $('#ccCustomerId').val();
        const amount = $('#ccAmount').val();
        
        if (!service) {
            showToast('Please select a service provider', 'error');
            return false;
        }
        if (!customerNo || customerNo.length < 8 || customerNo.length > 20) {
            showToast('Please enter a valid customer ID (8-20 characters)', 'error');
            return false;
        }
        if (!amount || amount < 1 || amount > 50000) {
            showToast('Please enter a valid amount between ₹1 and ₹50,000', 'error');
            return false;
        }
        return true;
    }
    
    // Handle CC Bill payment form submission
    $('#ccBillForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateCCBillForm()) { return; }
        
        $('#ccSubmitBtn').prop('disabled', true);
        $('#ccBtnText').text('Processing...');
        $('#ccBtnSpinner').removeClass('d-none');
        
        const formData = {
            user_id: 1, // You need to get this from authenticated user or form
            cn: $('#ccCustomerId').val().trim(), // Credit card number
            op: $('#cmbCCService').val().trim(), // Operator/Service
            cir: 'CC_BILL', // Circle or category
            amt: parseFloat($('#ccAmount').val()),
            ad9: $('#mobileNumber').val() || '', // Mobile number
            ad3: '' // Additional field
        };
        
        $.ajax({
            url: '{{ url("/api/cc-bill-payments") }}',
            method: 'POST',
            data: JSON.stringify(formData),
            timeout: 30000,
            success: function(response) {
                if (response.status === 'success' && response.data && response.data.status === 'success') {
                    showToast('CC Bill payment completed successfully!', 'success', 'Payment Success');
                    $('#ccBillForm')[0].reset();
                } else if (response.status === 'failed' || (response.data && response.data.status === 'failed')) {
                    showToast('CC Bill payment failed. Please try again.', 'error', 'Payment Failed');
                } else if (response.status === 'success' && response.data && response.data.status === 'pending') {
                    showToast('CC Bill payment initiated successfully! Status: Pending', 'warning', 'Payment Pending');
                    $('#ccBillForm')[0].reset();
                } else {
                    showToast('CC Bill payment request processed. Please check the table below for status.', 'warning', 'Status Unclear');
                }
                
                setTimeout(function() { 
                    fetchCCBillRecords(); 
                }, 1000);
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to process CC Bill payment. Please try again.';
                let shouldRefreshTable = false;
                
                if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Payment marked as failed.';
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
                    errorMessage = 'Request timeout. Please check your payment history.';
                    shouldRefreshTable = true;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showToast(errorMessage, 'error', 'Payment Failed');
                
                if (shouldRefreshTable) {
                    setTimeout(function() { 
                        fetchCCBillRecords(); 
                    }, 1500);
                }
            },
            complete: function() {
                $('#ccSubmitBtn').prop('disabled', false);
                $('#ccBtnText').text('Proceed to Pay');
                $('#ccBtnSpinner').addClass('d-none');
            }
        });
    });
    
    // Function to fetch and display CC Bill records
    function fetchCCBillRecords() {
        $.ajax({
            url: '{{ url("/api/cc-bill-payments") }}',
            method: 'GET',
            timeout: 15000,
            beforeSend: function() {
                if ($('#ccBillTableBody').length && $('#ccBillTableBody tr').length === 0) {
                    $('#ccBillTableBody').html('<tr><td colspan="14" class="text-center">Loading records...</td></tr>');
                }
            },
            success: function(response) {
                let data = [];
                if (response.data && Array.isArray(response.data)) {
                    data = response.data;
                } else if (Array.isArray(response)) {
                    data = response;
                } else if (response.records) {
                    data = response.records;
                }
                
                let rows = '';
                if (!data || data.length === 0) {
                    rows = '<tr><td colspan="14" class="text-center text-muted">No CC Bill payment records found.</td></tr>';
                } else {
                    $.each(data, function(index, payment) {
                        if (payment) {
                            const createdDate = payment.created_at ? new Date(payment.created_at).toLocaleString() : '-';
                            const processedDate = payment.processed_at ? new Date(payment.processed_at).toLocaleString() : '-';
                            const amount = payment.amt ? parseFloat(payment.amt).toFixed(2) : '0.00';
                            const status = payment.status || 'pending';
                            const statusClass = getStatusClass(status);
                            
                            // Safely display credit card number
                            let ccDisplay = '-';
                            if (payment.cn) {
                                if (payment.cn.length > 4) {
                                    ccDisplay = '****' + payment.cn.slice(-4);
                                } else {
                                    ccDisplay = '****' + payment.cn;
                                }
                            }
                            
                            rows += `<tr>
                                <td>${payment.id || '-'}</td>
                                <td>${payment.user_id || '-'}</td>
                                <td>${payment.uid || '-'}</td>
                                <td>${ccDisplay}</td>
                                <td>${payment.op || '-'}</td>
                                <td>${payment.cir || '-'}</td>
                                <td>₹${amount}</td>
                                <td>${payment.reqid || '-'}</td>
                                <td><span class="badge ${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                                <td>${payment.transaction_id || '-'}</td>
                                <td>${payment.operator_ref || '-'}</td>
                                <td class="text-truncate" style="max-width: 150px;" title="${payment.response_message || '-'}">${payment.response_message || '-'}</td>
                                <td>${createdDate}</td>
                                <td>${processedDate}</td>
                            </tr>`;
                        }
                    });
                }
                
                if ($('#ccBillTableBody').length) {
                    $('#ccBillTableBody').fadeOut(300, function() {
                        $(this).html(rows).fadeIn(300);
                    });
                }
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
                
                if ($('#ccBillTableBody').length) {
                    $('#ccBillTableBody').html(`<tr><td colspan="14" class="text-center text-danger">${errorMessage}</td></tr>`);
                }
            }
        });
    }
    
    // Utility to return Bootstrap class for status
    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'success': 
                return 'bg-success';
            case 'failed': 
                return 'bg-danger';
            case 'pending':
            default: 
                return 'bg-warning';
        }
    }
});
</script>

