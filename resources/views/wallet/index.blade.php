@include('layouts.header')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>My Wallet</h4>
                </div>
                <div class="card-body">
                    <!-- Alert container -->
                    <div id="alert-container"></div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Wallet Balance</h5>
                                    <h3 id="wallet-balance">₹0.00</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Add Money to Wallet</h5>
                                    <form id="add-money-form">
                                        <div class="form-group">
                                            <input type="number" name="amount" class="form-control" 
                                                   placeholder="Enter amount" min="10" max="50000" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">Add Money</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Make Payment</h5>
                        </div>
                        <div class="card-body">
                            <form id="payment-form">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" 
                                           placeholder="Enter payment amount" min="1" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control" 
                                           placeholder="Payment description">
                                </div>
                                <button type="submit" class="btn btn-primary">Process Payment</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Transaction History</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="transactions-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction ID</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Status</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactions-tbody">
                                        <tr>
                                            <td colspan="7" class="text-center">Loading transactions...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-container"></div>
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
    // Get bearer token from localStorage or meta tag
    const bearerToken = localStorage.getItem('auth_token') || $('meta[name="api-token"]').attr('content');
    
    // Set default AJAX headers
    $.ajaxSetup({
        headers: {
            'Authorization': 'Bearer ' + bearerToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    // Load initial data
    loadUserData();
    loadTransactions();

    // Add Money Form Handler
    $('#add-money-form').on('submit', function(e) {
        e.preventDefault();
        
        const amount = $(this).find('input[name="amount"]').val();
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Processing...');
        
        $.ajax({
            url: '/api/wallet/add-money',
            method: 'POST',
            data: JSON.stringify({ amount: parseFloat(amount) }),
            success: function(response) {
                if (response.success) {
                    if (response.redirect_url) {
                        showMessage('Redirecting to payment gateway...', 'info');
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    } else {
                        showMessage(response.message, 'success');
                        loadUserData(); // Refresh balance
                        loadTransactions(); // Refresh transactions
                        $('#add-money-form')[0].reset();
                    }
                } else {
                    showMessage(response.message || 'Failed to add money', 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (xhr.status === 401) {
                    showMessage('Please login to continue', 'error');
                    // Redirect to login or refresh token
                } else {
                    showMessage(response?.message || 'Failed to add money', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Payment Form Handler
    $('#payment-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            amount: parseFloat($(this).find('input[name="amount"]').val()),
            description: $(this).find('input[name="description"]').val()
        };
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Processing...');
        
        $.ajax({
            url: '/api/wallet/process-payment',
            method: 'POST',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response.success) {
                    if (response.redirect_required && response.redirect_url) {
                        showMessage('Redirecting to payment gateway...', 'info');
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    } else {
                        showMessage(response.message || 'Payment processed successfully', 'success');
                        loadUserData(); // Refresh balance
                        loadTransactions(); // Refresh transactions
                        $('#payment-form')[0].reset();
                    }
                } else {
                    showMessage(response.message || 'Payment failed', 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (xhr.status === 401) {
                    showMessage('Please login to continue', 'error');
                } else if (xhr.status === 422) {
                    // Validation errors
                    let errorMessage = 'Validation failed: ';
                    if (response.errors) {
                        const errors = Object.values(response.errors).flat();
                        errorMessage += errors.join(', ');
                    } else {
                        errorMessage += response.message || 'Invalid input';
                    }
                    showMessage(errorMessage, 'error');
                } else {
                    showMessage(response?.message || 'Payment processing failed', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Load User Data Function
    function loadUserData() {
        $.ajax({
            url: '/api/wallet/user-data',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const user = response.data;
                    $('#wallet-balance').text('₹' + parseFloat(user.wallet_balance || 0).toFixed(2));
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showMessage('Please login to continue', 'error');
                    // Redirect to login page
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            }
        });
    }

    // Load Transactions Function
    function loadTransactions(page = 1) {
        $.ajax({
            url: '/api/wallet/transactions?page=' + page,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const transactions = response.data;
                    renderTransactions(transactions.data);
                    renderPagination(transactions);
                }
            },
            error: function(xhr) {
                $('#transactions-tbody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load transactions</td></tr>');
            }
        });
    }

    // Render Transactions Function
    function renderTransactions(transactions) {
        const tbody = $('#transactions-tbody');
        tbody.empty();

        if (transactions.length === 0) {
            tbody.html('<tr><td colspan="7" class="text-center">No transactions found</td></tr>');
            return;
        }

        transactions.forEach(function(transaction) {
            const date = new Date(transaction.created_at).toLocaleDateString('en-GB') + ' ' + 
                        new Date(transaction.created_at).toLocaleTimeString('en-GB', {hour: '2-digit', minute:'2-digit'});
            
            const typeClass = transaction.type === 'credit' ? 'success' : 'danger';
            const statusClass = transaction.status === 'success' ? 'success' : 
                               (transaction.status === 'failed' ? 'danger' : 'warning');

            const row = `
                <tr>
                    <td>${date}</td>
                    <td>${transaction.transaction_id || 'N/A'}</td>
                    <td><span class="badge badge-${typeClass}">${transaction.type ? transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1) : 'N/A'}</span></td>
                    <td>₹${parseFloat(transaction.amount || 0).toFixed(2)}</td>
                    <td>${transaction.payment_mode ? transaction.payment_mode.charAt(0).toUpperCase() + transaction.payment_mode.slice(1) : 'N/A'}</td>
                    <td><span class="badge badge-${statusClass}">${transaction.status ? transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) : 'N/A'}</span></td>
                    <td>${transaction.description || 'N/A'}</td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Render Pagination Function
    function renderPagination(paginationData) {
        const container = $('#pagination-container');
        container.empty();

        if (paginationData.last_page <= 1) return;

        let paginationHtml = '<nav><ul class="pagination justify-content-center">';

        // Previous button
        if (paginationData.current_page > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${paginationData.current_page - 1}">Previous</a></li>`;
        }

        // Page numbers
        for (let i = 1; i <= paginationData.last_page; i++) {
                        const activeClass = i === paginationData.current_page ? 'active' : '';
            paginationHtml += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }

        // Next button
        if (paginationData.current_page < paginationData.last_page) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${paginationData.current_page + 1}">Next</a></li>`;
        }

        paginationHtml += '</ul></nav>';
        container.html(paginationHtml);

        // Bind pagination click events
        container.find('.page-link').on('click', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadTransactions(page);
        });
    }

    // Show Message Function
    function showMessage(message, type) {
        // Remove existing alerts
        $('#alert-container .alert-temp').remove();
        
        // Create new alert
        const alertClass = type === 'error' ? 'danger' : (type === 'info' ? 'info' : 'success');
        const alertHtml = `
            <div class="alert alert-${alertClass} alert-dismissible alert-temp">
                ${message}
                <button type="button" class="close" onclick="$(this).parent().remove()">&times;</button>
            </div>
        `;
        
        $('#alert-container').prepend(alertHtml);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            $('#alert-container .alert-temp').first().remove();
        }, 5000);
    }

    // Auto-refresh balance every 30 seconds
    setInterval(function() {
        loadUserData();
    }, 2000);
});
</script>

