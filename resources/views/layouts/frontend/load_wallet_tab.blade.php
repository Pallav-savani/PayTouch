<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Load Wallet - PayTouch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .wallet-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .amount-btn {
            border: 2px solid #e9ecef;
            background: #f8f9fa;
            color: #495057;
            transition: all 0.3s ease;
        }
        .amount-btn:hover, .amount-btn.active {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }
        .payment-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        .loading {
            display: none;
        }
        .alert {
            margin-top: 1rem;
        }
        .transaction-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: #fff;
        }
        .status-success { color: #28a745; }
        .status-pending { color: #ffc107; }
        .status-failed { color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="wallet-container">
            <div class="text-center mb-4">
                <i class="fas fa-wallet fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold">Load Wallet</h2>
                <p class="text-muted">Add money to your PayTouch wallet</p>
            </div>

            <!-- Quick Amount Selection -->
            <div class="mb-4">
                <label class="form-label fw-bold">Quick Select Amount</label>
                <div class="row g-2">
                    <div class="col-4">
                        <button class="btn amount-btn w-100" data-amount="100">₹100</button>
                    </div>
                    <div class="col-4">
                        <button class="btn amount-btn w-100" data-amount="500">₹500</button>
                    </div>
                    <div class="col-4">
                        <button class="btn amount-btn w-100" data-amount="1000">₹1000</button>
                    </div>
                    <div class="col-4">
                        <button class="btn amount-btn w-100" data-amount="2000">₹2000</button>
                    </div>
                    <div class="col-4">
                        <button class="btn amount-btn w-100" data-amount="5000">₹5000</button>
                    </div>
                    <div class="col-4">
                        <button class="btn amount-btn w-100" data-amount="10000">₹10000</button>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" class="payment-form">
                <div class="mb-3">
                    <label for="amount" class="form-label fw-bold">
                        <i class="fas fa-rupee-sign me-2"></i>Enter Amount
                    </label>
                    <input type="number" class="form-control form-control-lg" id="amount" name="amount" 
                           placeholder="Enter amount" min="1" max="50000" required>
                    <div class="form-text">Minimum: ₹1, Maximum: ₹50,000</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">
                        <i class="fas fa-comment me-2"></i>Description (Optional)
                    </label>
                    <input type="text" class="form-control" id="description" name="description" 
                           placeholder="Add a note for this transaction">
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded">
                        <span class="fw-bold">Total Amount:</span>
                        <span class="h4 text-primary mb-0" id="totalAmount">₹0</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <span class="normal-text">
                        <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                    </span>
                    <span class="loading">
                        <i class="fas fa-spinner fa-spin me-2"></i>Processing...
                    </span>
                </button>
            </form>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Recent Transactions -->
            <div class="mt-5">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-history me-2"></i>Recent Transactions
                </h5>
                <div id="recentTransactions">
                    <div class="text-center text-muted">
                        <i class="fas fa-spinner fa-spin"></i> Loading transactions...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const totalAmountDisplay = document.getElementById('totalAmount');
            const paymentForm = document.getElementById('paymentForm');
            const alertContainer = document.getElementById('alertContainer');
            const amountButtons = document.querySelectorAll('.amount-btn');
            const submitButton = paymentForm.querySelector('button[type="submit"]');

            // Set CSRF token for all AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Quick amount selection
            amountButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const amount = this.dataset.amount;
                    amountInput.value = amount;
                    updateTotalAmount();
                    
                    // Update active state
                    amountButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Update total amount display
            amountInput.addEventListener('input', updateTotalAmount);

            function updateTotalAmount() {
                const amount = parseFloat(amountInput.value) || 0;
                totalAmountDisplay.textContent = `₹${amount.toLocaleString()}`;
            }

            // Form submission
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const amount = parseFloat(amountInput.value);
                const description = document.getElementById('description').value;

                // Validation
                if (!amount || amount < 1) {
                    showAlert('Please enter a valid amount', 'danger');
                    return;
                }

                if (amount > 50000) {
                    showAlert('Amount cannot exceed ₹50,000', 'danger');
                    return;
                }

                // Show loading state
                toggleLoading(true);

                // Prepare form data
                const formData = {
                    amount: amount,
                    description: description,
                    user_id: 1 // Replace with actual user ID from session/auth
                };

                // Make API call
                fetch('/api/payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    toggleLoading(false);
                    
                    if (data.success) {
                        showAlert('Payment initiated successfully!', 'success');
                        paymentForm.reset();
                        updateTotalAmount();
                        loadRecentTransactions();
                        
                        // Remove active state from amount buttons
                        amountButtons.forEach(btn => btn.classList.remove('active'));
                    } else {
                        showAlert(data.message || 'Payment failed. Please try again.', 'danger');
                    }
                })
                .catch(error => {
                    toggleLoading(false);
                    console.error('Error:', error);
                    showAlert('An error occurred. Please try again.', 'danger');
                });
            });

            // Toggle loading state
            function toggleLoading(isLoading) {
                const normalText = submitButton.querySelector('.normal-text');
                const loadingText = submitButton.querySelector('.loading');
                
                if (isLoading) {
                    normalText.style.display = 'none';
                    loadingText.style.display = 'inline';
                    submitButton.disabled = true;
                } else {
                    normalText.style.display = 'inline';
                    loadingText.style.display = 'none';
                    submitButton.disabled = false;
                }
            }

            // Show alert messages
            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                alertContainer.innerHTML = alertHtml;

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }

            // Load recent transactions
            function loadRecentTransactions() {
                fetch('/api/payments', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const transactionsContainer = document.getElementById('recentTransactions');
                    
                    if (data.success && data.data.length > 0) {
                        const recentTransactions = data.data.slice(0, 5); // Show only last 5
                        let transactionsHtml = '';
                        
                        recentTransactions.forEach(transaction => {
                            const statusClass = getStatusClass(transaction.statuscode);
                            const statusIcon = getStatusIcon(transaction.statuscode);
                            const date = new Date(transaction.created_at).toLocaleDateString();
                            
                            transactionsHtml += `
                                <div class="transaction-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">₹${parseFloat(transaction.amount).toLocaleString()}</div>
                                            <small class="text-muted">${transaction.orderid}</small>
                                            ${transaction.description ? `<div class="small text-muted">${transaction.description}</div>` : ''}
                                        </div>
                                        <div class="text-end">
                                            <div class="${statusClass}">
                                                <i class="fas fa-${statusIcon} me-1"></i>
                                                ${transaction.statuscode}
                                            </div>
                                            <small class="text-muted">${date}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        transactionsContainer.innerHTML = transactionsHtml;
                    } else {
                        transactionsContainer.innerHTML = `
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-receipt fa-2x mb-2"></i>
                                <div>No transactions found</div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading transactions:', error);
                    document.getElementById('recentTransactions').innerHTML = `
                        <div class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Failed to load transactions
                        </div>
                    `;
                });
            }

            // Get status class for styling
            function getStatusClass(status) {
                switch(status?.toUpperCase()) {
                    case 'SUCCESS':
                    case 'COMPLETED':
                        return 'status-success';
                    case 'PENDING':
                    case 'PROCESSING':
                        return 'status-pending';
                    case 'FAILED':
                    case 'ERROR':
                        return 'status-failed';
                    default:
                        return 'status-pending';
                }
            }

            // Get status icon
            function getStatusIcon(status) {
                switch(status?.toUpperCase()) {
                    case 'SUCCESS':
                    case 'COMPLETED':
                        return 'check-circle';
                    case 'PENDING':
                    case 'PROCESSING':
                        return 'clock';
                    case 'FAILED':
                    case 'ERROR':
                        return 'times-circle';
                    default:
                        return 'clock';
                }
            }

            // Load recent transactions on page load
            loadRecentTransactions();
        });
    </script>
</body>
</html>
