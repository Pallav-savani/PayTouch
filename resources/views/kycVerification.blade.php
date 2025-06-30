<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to PayTouch - KYC Verification</title>
        <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
            <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
</head>

<header class="kyc-header">
    <nav class="one navbar navbar-expand-lg sticky-top justify-content-center" aria-label="Offcanvas navbar large">
        <!-- <div class="d-flex"> -->
        <div class="m-0 p-0 logo-main mx-auto">
            <div class="d-flex justify-content-center logo countdown">
                <a class="text-decoration-none" href="index.php">
                    <img height="65px" src="{{ asset('images/logo.png') }}" />
                </a>
            </div>
            <a class="text-align-center m-0 text-decoration-none" href="#"><span class="spcolor">PayTouch
                </span><span class="spcolor2">Web Solution </span></a>
        </div>
    </nav>
</header>

<br>
<div class="container">
    <!-- Success/Error Messages -->
    <div id="alert-container"></div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="text-center" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Processing your request...</p>
    </div>

    <!-- KYC Form Container -->
    <div id="kyc-form-container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">KYC Information Form</h4>
            </div>
            <div class="card-body">
                <form id="kyc-form" class="row g-3" style="border-style: groove; padding: 20px; margin-bottom:5px;">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mobile No <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="mobile_no" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Member Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="member_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Birth Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="birth_date" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Age <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="age" min="18" max="100" required readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Home Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="home_address" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">City Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="city_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pan Card No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pan_card_no" 
                               pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Please enter valid PAN number (e.g., ABCDE1234F)" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Aadhaar No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="aadhaar_no" 
                               pattern="[0-9]{12}" title="Please enter valid 12-digit Aadhaar number" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">GST No</label>
                        <input type="text" class="form-control" name="gst_no">
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-3" id="submit-btn">
                            <span class="spinner-border spinner-border-sm me-2" style="display: none;"></span>
                            Submit KYC Info
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    const token = localStorage.getItem('auth_token');
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...(token ? { 'Authorization': 'Bearer ' + token } : {})
        }
    });

    // --- KYC Status Check and Redirect ---
    let kycRedirected = false;
    $.ajax({
        url: "{{ url('/api/kyc') }}",
        method: 'GET',
        success: function(response) {
            // Only redirect if not already on /welcome and not already redirected
            if (response && response.redirect && !kycRedirected) {
                kycRedirected = true;
                if (window.location.pathname !== '/welcome') {
                    window.location.href = response.redirect;
                }
                return;
            }
            // Optionally, you can pre-fill form fields with response.kyc_data if needed
        },
        error: function(xhr) {
            showError("KYC not displayed")
        }
    });

    // Bind form submission
    $('#kyc-form').on('submit', handleFormSubmission);
    
    // Auto-calculate age from birth date
    $('input[name="birth_date"]').on('change', calculateAge);
    
    // Format PAN card input
    $('input[name="pan_card_no"]').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    function handleFormSubmission(e) {
        e.preventDefault();
        
        const formData = {};
        const form = $(this);
        
        // Collect form data
        form.find('input, select, textarea').each(function() {
            const field = $(this);
            if (field.attr('name')) {
                formData[field.attr('name')] = field.val();
            }
        });
        
        // Validate required fields
        if (!validateForm(formData)) {
            return;
        }
        
        showSubmitLoading();
        
        $.ajax({
            url: "{{ url('/api/kyc') }}",
            method: 'POST',
            data: JSON.stringify(formData),
            success: function(response) {
                hideSubmitLoading();
                if (response.success) {
                    showSuccess('KYC information submitted successfully!');
                    setTimeout(() => {
                        showKycCompleted();
                        window.location.href = '/welcome';
                    }, 4000);
                } else {
                    showError('Failed to submit KYC information');
                }
            },
            error: function(xhr) {
                hideSubmitLoading();
                handleAjaxError(xhr);
            }
        });
    }

    function validateForm(data) {
        const requiredFields = ['mobile_no', 'member_name', 'birth_date', 'age', 
                              'home_address', 'city_name', 'email', 'pan_card_no', 'aadhaar_no'];
        
        for (let field of requiredFields) {
            if (!data[field] || data[field].trim() === '') {
                showError(`Please fill in the ${field.replace('_', ' ')} field`);
                $(`input[name="${field}"], select[name="${field}"], textarea[name="${field}"]`).focus();
                return false;
            }
        }
        
        // Validate PAN format
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (!panRegex.test(data.pan_card_no)) {
            showError('Please enter a valid PAN number (e.g., ABCDE1234F)');
            $('input[name="pan_card_no"]').focus();
            return false;
        }
        
        // Validate Aadhaar format
        const aadhaarRegex = /^[0-9]{12}$/;
        if (!aadhaarRegex.test(data.aadhaar_no)) {
            showError('Please enter a valid 12-digit Aadhaar number');
            $('input[name="aadhaar_no"]').focus();
            return false;
        }
        
        return true;
    }

    function calculateAge() {
        const birthDate = new Date($(this).val());
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        $('input[name="age"]').val(age);
    }

    function showKycCompleted() {
        $('#kyc-form-container').html(`
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success">
                        <h4 class="alert-heading">KYC Completed!</h4>
                        <p>You have successfully submitted your KYC information.</p>
                        <hr>
                        <p class="mb-0">Your information has been processed successfully.</p>
                    </div>
                </div>
            </div>
        `);
    }

    function showSubmitLoading() {
        const btn = $('#submit-btn');
        btn.prop('disabled', true);
        btn.find('.spinner-border').show();
        btn.find('span:last').text('Submitting...');
    }

    function hideSubmitLoading() {
        const btn = $('#submit-btn');
        btn.prop('disabled', false);
        btn.find('.spinner-border').hide();
        btn.find('span:last').text('Submit KYC Info');
    }

    function showSuccess(message) {
        const alert = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alert-container').html(alert);
        scrollToTop();
    }

    function showError(message) {
        const alert = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alert-container').html(alert);
        scrollToTop();
    }

    function handleAjaxError(xhr) {
        let message = 'An error occurred. Please try again.';
        
        if (xhr.responseJSON) {
            if (xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseJSON.errors) {
                // Handle validation errors
                const errors = xhr.responseJSON.errors;
                const errorMessages = [];
                
                for (let field in errors) {
                    errorMessages.push(...errors[field]);
                }
                
                message = errorMessages.join('<br>');
            }
        } else if (xhr.status === 401) {
            message = 'You are not authorized. Please login again.';
        } else if (xhr.status === 403) {
            message = 'Access denied. You do not have permission to perform this action.';
        } else if (xhr.status === 422) {
            message = 'Please check your input data and try again.';
        } else if (xhr.status === 500) {     
            message = 'Server error. Please try again later.';
        }
        
        showError(message);
    }

    function scrollToTop() {
        $('html, body').animate({
            scrollTop: $('#alert-container').offset().top - 20
        }, 500);
    }
});
</script>