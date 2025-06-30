<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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

    // Check wallet balance function
    function checkWalletBalance(amount) {
        return new Promise((resolve, reject) => {
            const token = localStorage.getItem("auth_token");
            
            $.ajax({
                url: '{{ url("/api/wallet/balance") }}',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        const walletBalance = parseFloat(response.data.balance || 0);
                        const rechargeAmount = parseFloat(amount);
                        
                        if (walletBalance >= rechargeAmount) {
                            resolve(true);
                        } else {
                            resolve(false);
                        }
                    } else {
                        reject('Failed to fetch wallet balance');
                    }
                },
                error: function(xhr, status, error) {
                    reject('Error checking wallet balance: ' + error);
                }
            });
        });
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

        const amount = parseFloat($('#amount').val());
        
        $('#submitBtn').prop('disabled', true);
        $('#btnText').text('Checking Balance...');
        $('#btnSpinner').removeClass('d-none');

        // Check wallet balance first
        checkWalletBalance(amount)
            .then(function(hasSufficientBalance) {
                if (!hasSufficientBalance) {
                    // Insufficient balance - redirect to wallet page
                    showToast('Insufficient wallet balance. Redirecting to wallet page...', 'warning', 'Insufficient Balance');
                    
                    setTimeout(function() {
                        window.location.href = '{{ route("wallet") }}';
                    }, 2000);
                    
                    return;
                }

                // Sufficient balance - proceed with recharge
                $('#btnText').text('Processing...');
                
                const formData = {
                    service: $('#cmbService').val().trim(),
                    mobile_no: $('#customerId').val().trim(),
                    amount: amount
                };

                const token = localStorage.getItem("auth_token");

                $.ajax({
                    url: '{{ url("/api/dth") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    timeout: 30000,
                    success: function(response) {
                        if (response.status === 'success' && response.data && response.data.status === 'success') {
                            showToast('Recharge completed successfully!', 'success', 'Recharge Success');
                            $('#rechargeForm')[0].reset();
                            
                            // Refresh wallet balance immediately
                            if (typeof window.refreshUserWalletBalance === 'function') {
                                window.refreshUserWalletBalance();
                            }
                        } else if (response.status === 'failed' || (response.data && response.data.status === 'failed')) {
                            showToast('Recharge failed. Please try again.', 'error', 'Recharge Failed');
                            
                            // Refresh wallet balance (amount might have been refunded)
                            if (typeof window.refreshUserWalletBalance === 'function') {
                                window.refreshUserWalletBalance();
                            }
                        } else if (response.status === 'success' && response.data && response.data.status === 'pending') {
                            showToast('Recharge initiated successfully! Status: Pending', 'warning', 'Recharge Pending');
                            $('#rechargeForm')[0].reset();
                            
                            // Refresh wallet balance (amount has been deducted)
                            if (typeof window.refreshUserWalletBalance === 'function') {
                                window.refreshUserWalletBalance();
                            }
                        } else {
                            showToast('Recharge request processed. Please check the table below for status.', 'warning', 'Status Unclear');
                            
                            // Refresh wallet balance
                            if (typeof window.refreshUserWalletBalance === 'function') {
                                window.refreshUserWalletBalance();
                            }
                        }
                        setTimeout(function() { 
                            fetchRechargeRecords(); 
                            fetchPendingTransactions();
                            fetchFailedTransactions();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Failed to process recharge. Please try again.';
                        let shouldRefreshTable = false;
                        let shouldRefreshBalance = false;
                        
                        if (xhr.status === 500) {
                            errorMessage = 'Server error occurred. Recharge marked as failed.';
                            shouldRefreshTable = true;
                            shouldRefreshBalance = true;
                        } else if (xhr.status === 422) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors).flat().join(', ');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                                // Check if it's insufficient balance error
                                if (errorMessage.includes('Insufficient wallet balance')) {
                                    shouldRefreshBalance = true;
                                }
                            }
                        } else if (xhr.status === 0) {
                            errorMessage = 'Network error. Please check your internet connection.';
                        } else if (status === 'timeout') {
                            errorMessage = 'Request timeout. Please check your recharge history.';
                            shouldRefreshTable = true;
                            shouldRefreshBalance = true;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast(errorMessage, 'error', 'Recharge Failed');
                        
                        if (shouldRefreshBalance && typeof window.refreshUserWalletBalance === 'function') {
                            window.refreshUserWalletBalance();
                        }
                        
                        if (shouldRefreshTable) {
                            setTimeout(function() { 
                                fetchRechargeRecords(); 
                                fetchPendingTransactions();
                                fetchFailedTransactions();
                            }, 1500);
                        }
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false);
                        $('#btnText').text('Proceed to Recharge');
                        $('#btnSpinner').addClass('d-none');
                    }
                });
            })
            .catch(function(error) {
                showToast('Error checking wallet balance: ' + error, 'error', 'Balance Check Failed');
                $('#submitBtn').prop('disabled', false);
                $('#btnText').text('Proceed to Recharge');
                $('#btnSpinner').addClass('d-none');
            });
    });

    // Function to fetch and display recharge records
    function fetchRechargeRecords() {
        const token = localStorage.getItem("auth_token");
        
        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
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
        $('#reportTableBody').html('<tr><td colspan="8" class="text-center">Click Search to load transactions</td></tr>');
    });

    function fetchTransactionReport() {
        const fromDate = $('#reportFromDate').val();
        const toDate = $('#reportToDate').val();
        const status = $('#reportStatus').val();
        const service = $('#reportService').val();
        const customerNo = $('#reportCustomerNo').val().trim();
        const token = localStorage.getItem("auth_token");

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
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            timeout: 15000,
            beforeSend: function() {
                $('#reportTableBody').html('<tr><td colspan="8" class="text-center">Searching...</td></tr>');
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
                    rows = '<tr><td colspan="8" class="text-center text-muted">No transactions found for the selected criteria.</td></tr>';
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
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary view-receipt-btn" 
                                            data-id="${transaction.id}" 
                                            data-date="${transaction.created_at}" 
                                            data-service="${transaction.service}" 
                                            data-mobile="${transaction.mobile_no}" 
                                            data-amount="${transaction.amount}" 
                                            data-txn-id="${transaction.transaction_id}" 
                                            data-status="${transaction.status}">
                                        <i class="fas fa-receipt"></i> View Receipt
                                    </button>
                                </td>
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
                $('#reportTableBody').html(`<tr><td colspan="8" class="text-center text-danger">${errorMessage}</td></tr>`);
                showToast(errorMessage, 'error');
            }
        });
    }

    $(document).on('click', '.view-receipt-btn', function() {
        const data = {
            id: $(this).data('id'),
            date: $(this).data('date'),
            service: $(this).data('service'),
            mobile: $(this).data('mobile'),
            amount: $(this).data('amount'),
            txnId: $(this).data('txn-id'),
            status: $(this).data('status')
        };

        showReceiptModal(data);
    });


    // Updated showReceiptModal function with better PDF styling
    function showReceiptModal(data) {
        const formattedDate = data.date ? new Date(data.date).toLocaleString() : '-';
        const statusClass = getReceiptStatusClass(data.status);
        
        const receiptHtml = `
            <div class="receipt-container" id="receiptContent">
                <div class="receipt-header">
                    <div class="logo-container">
                        <img src="{{ asset('images/PayTouch_logo.png') }}" alt="BBPS Assured" class="receipt-logo">
                        <img src="{{ asset('images/bbps.jpg') }}" alt="BBPS Assured" class="receipt-logo-b">
                    </div>
                    <h5 style="margin: 10px 0; color: #333; text-align: center; font-size: 18px; font-weight: bold;">DTH Recharge Receipt</h5>
                    <hr style="border: 1px dashed #333; margin: 15px 0;">
                </div>
                
                <div class="receipt-body" style="padding: 15px 0;">
                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold; width: 50%;">Receipt ID:</td>
                            <td style="padding: 5px 0; text-align: right;">#${data.id || '-'}</td>
                        </tr>
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold;">Date:</td>
                            <td style="padding: 5px 0; text-align: right;">${formattedDate}</td>
                        </tr>
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold;">Service:</td>
                            <td style="padding: 5px 0; text-align: right;">${data.service ? data.service.toUpperCase() : '-'}</td>
                        </tr>
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold;">Mobile No.:</td>
                            <td style="padding: 5px 0; text-align: right;">${data.mobile || '-'}</td>
                        </tr>
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold;">Amount:</td>
                            <td style="padding: 5px 0; text-align: right; font-weight: bold; color: #007bff;">₹${data.amount ? parseFloat(data.amount).toFixed(2) : '0.00'}</td>
                        </tr>
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold;">Transaction ID:</td>
                            <td style="padding: 5px 0; text-align: right; font-size: 12px;">${data.txnId || '-'}</td>
                        </tr>
                        <tr style="margin-bottom: 8px;">
                            <td style="padding: 5px 0; font-weight: bold;">Status:</td>
                            <td style="padding: 5px 0; text-align: right;">
                                <span style="padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; 
                                            ${getInlineStatusStyle(data.status)}">${data.status || 'Pending'}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="receipt-footer" style="text-align: center; border-top: 1px dashed #333; padding-top: 15px; margin-top: 15px;">
                    <p style="margin: 5px 0; font-size: 16px; font-weight: bold; color: #333;">Thank you for using our service!</p>
                    <p style="margin: 5px 0; font-size: 14px; color: #666;">BBPS Assured - Secure & Reliable</p>
                </div>
            </div>
        `;
        
        // Create modal if it doesn't exist
        if ($('#receiptModal').length === 0) {
            $('body').append(`
                <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="receiptModalLabel">Transaction Receipt</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="receiptModalBody">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="downloadReceiptBtn">
                                    <i class="fas fa-download"></i> Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        $('#receiptModalBody').html(receiptHtml);
        $('#receiptModal').modal('show');
    }

    function getReceiptStatusClass(status) {
        switch (status) {
            case 'Success': 
            case 'success': 
                return 'status-success';
            case 'Failed': 
            case 'failed': 
                return 'status-failed';
            case 'Pending':
            case 'pending':
            default: 
                return 'status-pending';
        }
    }

    function getInlineStatusStyle(status) {
        switch (status) {
            case 'Success': 
            case 'success': 
                return 'background-color: #28a745; color: white;';
            case 'Failed': 
            case 'failed': 
                return 'background-color: #dc3545; color: white;';
            case 'Pending':
            case 'pending':
            default: 
                return 'background-color: #ffc107; color: #212529;';
        }
    }

    // Download PDF functionality
    $(document).on('click', '#downloadReceiptBtn', function() {
        const receiptContent = document.getElementById('receiptContent');
        
        if (!receiptContent) {
            showToast('Receipt content not found', 'error');
            return;
        }
        
        // Show loading state
        const btn = $(this);
        const originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm"></span> Generating PDF...');
        btn.prop('disabled', true);
        
        // Use html2pdf library
        if (typeof html2pdf !== 'undefined') {
            const opt = {
                margin: [0.5, 0.5, 0.5, 0.5],
                filename: `DTH_Receipt_${Date.now()}.pdf`,
                image: { 
                    type: 'jpeg', 
                    quality: 0.98 
                },
                html2canvas: { 
                    scale: 3,
                    useCORS: true,
                    allowTaint: false,
                    backgroundColor: '#ffffff',
                    width: 400,
                    height: 800
                },
                jsPDF: { 
                    unit: 'in', 
                    format: [5, 7], 
                    orientation: 'portrait'
                },
                pagebreak: { mode: 'avoid-all' }
            };
            
            // Clone the content and apply PDF-specific styles
            const clonedContent = receiptContent.cloneNode(true);
            clonedContent.style.width = '380px';
            clonedContent.style.padding = '20px';
            clonedContent.style.fontFamily = 'Arial, sans-serif';
            clonedContent.style.fontSize = '14px';
            clonedContent.style.lineHeight = '1.4';
            clonedContent.style.color = '#000';
            clonedContent.style.backgroundColor = '#fff';
            
            // Handle image loading for PDF
            const img = clonedContent.querySelector('.receipt-logo');
            if (img) {
                img.style.maxWidth = '100px';
                img.style.height = 'auto';
                img.style.display = 'block';
                img.style.margin = '0 auto 10px';
                
                // Convert image to base64 for better PDF compatibility
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const tempImg = new Image();
                
                tempImg.onload = function() {
                    canvas.width = this.width;
                    canvas.height = this.height;
                    ctx.drawImage(this, 0, 0);
                    
                    try {
                        const dataURL = canvas.toDataURL('image/jpeg', 0.9);
                        img.src = dataURL;
                        
                        // Generate PDF after image is processed
                        generatePDF();
                    } catch (e) {
                        console.log('Image conversion failed, proceeding without image');
                        img.style.display = 'none';
                        generatePDF();
                    }
                };
                
                tempImg.onerror = function() {
                    console.log('Image loading failed, proceeding without image');
                    img.style.display = 'none';
                    generatePDF();
                };
                
                tempImg.crossOrigin = 'anonymous';
                tempImg.src = img.src;
                
                function generatePDF() {
                    html2pdf().set(opt).from(clonedContent).save()
                        .then(() => {
                            showToast('PDF downloaded successfully!', 'success');
                        })
                        .catch((error) => {
                            console.error('PDF generation failed:', error);
                            showToast('Failed to generate PDF', 'error');
                            // Fallback to print
                            openPrintWindow(clonedContent);
                        })
                        .finally(() => {
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        });
                }
            } else {
                // No image, generate PDF directly
                html2pdf().set(opt).from(clonedContent).save()
                    .then(() => {
                        showToast('PDF downloaded successfully!', 'success');
                    })
                    .catch((error) => {
                        console.error('PDF generation failed:', error);
                        showToast('Failed to generate PDF', 'error');
                        // Fallback to print
                        openPrintWindow(clonedContent);
                    })
                    .finally(() => {
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    });
            }
        } else {
            // Fallback: open print dialog
            openPrintWindow(receiptContent);
            btn.html(originalText);
            btn.prop('disabled', false);
        }
    });

    function openPrintWindow(content) {
    const printWindow = window.open('', '_blank', 'width=600,height=800');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
            <head>
                <title>DTH Receipt</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px; 
                        background: white;
                        color: black;
                    }
                    .receipt-container { 
                        max-width: 400px; 
                        margin: 0 auto; 
                        border: 1px solid #ccc;
                        padding: 20px;
                    }
                    .receipt-header { 
                        text-align: center; 
                        border-bottom: 2px dashed #333; 
                        padding-bottom: 15px; 
                        margin-bottom: 15px; 
                    }
                    .receipt-logo { 
                        max-width: 120px; 
                        margin-bottom: 10px; 
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    td {
                        padding: 5px 0;
                        border-bottom: 1px dotted #ccc;
                    }
                    .receipt-footer { 
                        text-align: center; 
                        border-top: 2px dashed #333; 
                        padding-top: 15px; 
                        margin-top: 15px; 
                        font-size: 12px; 
                    }
                    @media print {
                        body { margin: 0; }
                        .receipt-container { border: none; }
                    }
                </style>
            </head>
            <body>
                ${content.outerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    };
                
            </body>
        </html>
    `);
    printWindow.document.close();
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
        const token = localStorage.getItem("auth_token");

        const params = {};
        if (mobileNo) params.mobile_no = mobileNo;
        if (transactionId) params.transaction_id = transactionId;

        $.ajax({
            url: '{{ url("/api/dth") }}',
            method: 'GET',
            data: params,
            headers: {
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
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
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
        $.ajax({
            url: '{{ url("/api/search-history") }}',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
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
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying...');
        
        $.ajax({
            url: `{{ url("/api/dth") }}/${transactionId}/retry`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
        
        if (!confirm('Are you sure you want to retry all pending transactions?')) {
            return;
        }
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying All...');
        
        $.ajax({
            url: '{{ url("/api/dth/retry-all") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + token,
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
        fetchPendingTransactions
    });

    // Function to fetch failed transactions
    function fetchFailedTransactions(page = 1) {
        const token = localStorage.getItem("auth_token");
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
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying...');
        
        $.ajax({
            url: `{{ url("/api/dth") }}/${transactionId}/retry-failed`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + token,
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
        const token = localStorage.getItem("auth_token");
        
        if (!confirm('Are you sure you want to retry all failed transactions?')) {
            return;
        }
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Retrying All...');
        
        $.ajax({
            url: '{{ url("/api/dth/retry-all-failed") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + token,
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

