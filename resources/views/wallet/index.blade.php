@extends('layouts.header')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>My Wallet</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Wallet Balance</h5>
                                    <h3>₹{{ number_format($user->wallet_balance ?? 0, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Add Money to Wallet</h5>
                                    <form action="{{ route('wallet.add-money') }}" method="POST">
                                        @csrf
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
                            <form id="payment-form" action="{{ route('wallet.process-payment') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" 
                                           placeholder="Enter payment amount" min="0" required>
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
                                <table class="table">
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
                                    <tbody>
                                    @if(isset($transactions))
                                        @forelse($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $transaction->transaction_id }}</td>
                                            <td>
                                                <span class="badge badge-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td>₹{{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ ucfirst($transaction->payment_mode) }}</td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $transaction->status === 'success' ? 'success' : 
                                                    ($transaction->status === 'failed' ? 'danger' : 'warning') 
                                                }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $transaction->description }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No transactions found</td>
                                        </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No transactions found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($transactions))
                                {{ $transactions->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = this.querySelector('input[name="_token"]').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';
    
    fetch('{{ route("wallet.process-payment") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.redirect_url) {
                showMessage('Redirecting to payment gateway...', 'info');
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                showMessage(data.message || 'Payment processed successfully', 'success');
                setTimeout(() => location.reload(), 2000);
            }
        } else {
            showMessage(data.message || 'Payment failed', 'error');
        }
    })
    .catch(error => {
        console.error('Payment error:', error);
        showMessage('Payment processing failed. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

// Helper function to show messages
function showMessage(message, type) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-temp');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : (type === 'info' ? 'info' : 'success')} alert-dismissible alert-temp`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Insert at the top of the card body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Auto-redirect payment handler
class PaymentHandler {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            const paymentForms = document.querySelectorAll('.payment-form');
            paymentForms.forEach(form => {
                form.addEventListener('submit', this.handlePaymentSubmit.bind(this));
            });
        });
    }

    async handlePaymentSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        try {
            const formData = new FormData(form);
            const csrfToken = form.querySelector('input[name="_token"]')?.value;
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON response');
            }
            
            const data = await response.json();
            
            if (data.success) {
                if (data.redirect_url) {
                    // Show redirect message
                    this.showMessage('Redirecting to payment gateway...', 'info');
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1000);
                } else {
                    this.showMessage(data.message || 'Payment processed successfully', 'success');
                    setTimeout(() => location.reload(), 2000);
                }
            } else {
                this.showMessage(data.message || 'Payment failed', 'error');
            }
            
        } catch (error) {
            console.error('Payment error:', error);
            this.showMessage('Payment processing failed. Please try again.', 'error');
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
    
    showMessage(message, type) {
        // Use the global showMessage function
        showMessage(message, type);
    }
}

// Initialize payment handler
new PaymentHandler();
</script>
