<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTouch - Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 550px;
            margin: auto;
        }
        
        .register-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        
        .register-right {
            padding: 50px 40px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .form-floating > label {
            color: #6c757d;
        }
        
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .role-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.9em;
            color: #6c757d;
        }
        
        .role-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8em;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .role-user { background: #e3f2fd; color: #1976d2; }
        .role-employer { background: #e8f5e8; color: #388e3c; }
        .role-admin { background: #fff3e0; color: #f57c00; }

        .btn-outline:hover{
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .register-left {
                padding: 30px 20px;
            }
            .register-right {
                padding: 30px 20px;
            }
        }

        @media (min-width: 768px) {
            .col-md-7 {
                flex: 0 0 auto;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Creating your account...</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="register-container">
                    <!-- <div class="row g-0"> -->
                        
                        
                        <!-- Right Side - Registration Form -->
                        <div class="col-md-7 register-right">
                            <div class="mb-4">
                                <h3 class="text-center mb-1">Create Account</h3>
                                <p class="text-center text-muted">Fill in your details to get started</p>
                            </div>
                            
                            <!-- Alert Messages -->
                            <div id="alertContainer"></div>
                            
                            <!-- Registration Form -->
                            <form id="registerForm">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" required>
                                    <label for="mobile"><i class="fas fa-mobile-alt me-2"></i>Mobile Number</label>
                                    <div class="invalid-feedback" id="mobileError"></div>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                    <div class="invalid-feedback" id="emailError"></div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 position-relative">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                            <div class="invalid-feedback" id="passwordError"></div>
                                            <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer; z-index:2;" id="togglePassword">
                                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 position-relative">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                            <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                                            <div class="invalid-feedback" id="passwordConfirmationError"></div>
                                            <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer; z-index:2;" id="togglePasswordConfirm">
                                                <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                    <div class="invalid-feedback" id="termsError"></div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-register btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>
                            </form>

                            <div class="mt-3 d-flex align-items-center w-100 mb-3" style="column-gap: 10px; outline: none;">
                                <p class="reg-text m-0">Don't have an account?</p>
                                <a href="{{ route('login') }}" class="btn btn-outline p-0">
                                    <i class="fas fa-user me-1"></i>Sign In
                                </a>
                            </div>
                        
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });

            // if (localStorage.getItem('auth_token')) {
            //     window.location.href = '/';
            //     return;
            // }

            // Password toggle functionality
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $('#togglePasswordIcon');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                icon.toggleClass('fa-eye fa-eye-slash');
            });
        
            $('#togglePasswordConfirm').on('click', function() {
                const passwordInput = $('#password_confirmation');
                const icon = $('#togglePasswordConfirmIcon');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                icon.toggleClass('fa-eye fa-eye-slash');
            });

            // Form submission
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                
                clearErrors();
                
                const formData = {
                    mobile: $('#mobile').val().trim(),
                    email: $('#email').val().trim(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                };

                if (!validateForm(formData)) {
                    return;
                }

                showLoading();
                
                $.ajax({
                    url: '{{ url("/api/register") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        hideLoading();
                        
                        if (response.token) {
                            // Store token and user data in localStorage
                            localStorage.setItem('auth_token', response.token);
                            localStorage.setItem('user_data', JSON.stringify(response.user));
                            
                            showAlert('Registration successful! Welcome aboard! Redirecting...', 'success');
                            
                            setTimeout(function() {
                                window.location.href = '/login';
                            }, 200);
                        } else {
                            showAlert('Registration completed, but login failed. Please try signing in.', 'warning');
                            setTimeout(function() {
                                window.location.href = '/login';
                            }, 200);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            displayValidationErrors(errors);
                        } else if (xhr.status === 409) {
                            showAlert('An account with this mobile number or email already exists. Please try signing in.', 'danger');
                        } else {
                            showAlert('Registration failed. Please check your connection and try again.', 'danger');
                        }
                        
                        console.error('Registration error:', error);
                    }
                });
                console.log(url);
            });

            // Form validation
            function validateForm(data) {
                let isValid = true;

                // Mobile validation
                if (!data.mobile || data.mobile.length < 10) {
                    $('#mobile').addClass('is-invalid');
                    $('#mobileError').text('Please enter a valid mobile number (at least 10 digits).');
                    isValid = false;
                }

                // Email validation
                if (!data.email || !isValidEmail(data.email)) {
                    $('#email').addClass('is-invalid');
                    $('#emailError').text('Please enter a valid email address.');
                    isValid = false;
                }

                // Password validation
                if (!data.password || data.password.length < 8) {
                    $('#password').addClass('is-invalid');
                    $('#passwordError').text('Password must be at least 8 characters long.');
                    isValid = false;
                }

                // Password confirmation validation
                if (data.password !== data.password_confirmation) {
                    $('#password_confirmation').addClass('is-invalid');
                    $('#passwordConfirmationError').text('Passwords do not match.');
                    isValid = false;
                }

                // Terms validation
                if (!$('#terms').is(':checked')) {
                    $('#terms').addClass('is-invalid');
                    $('#termsError').text('You must accept the terms and conditions.');
                    isValid = false;
                }

                if (!isValid) {
                    showAlert('Please correct the errors below and try again.', 'danger');
                }

                return isValid;
            }

            // Real-time validation
            $('#mobile').on('blur', function() {
                const mobile = $(this).val().trim();
                if (mobile && (mobile.length < 10 || !/^\+?[\d\s\-\(\)]+$/.test(mobile))) {
                    $(this).addClass('is-invalid');
                    $('#mobileError').text('Please enter a valid mobile number.');
                } else {
                    $(this).removeClass('is-invalid');
                    $('#mobileError').text('');
                }
            });

            $('#email').on('blur', function() {
                const email = $(this).val().trim();
                if (email && !isValidEmail(email)) {
                    $(this).addClass('is-invalid');
                    $('#emailError').text('Please enter a valid email address.');
                } else {
                    $(this).removeClass('is-invalid');
                    $('#emailError').text('');
                }
            });

            $('#password').on('input', function() {
                const password = $(this).val();
                if (password.length > 0 && password.length < 8) {
                    $(this).addClass('is-invalid');
                    $('#passwordError').text('Password must be at least 8 characters long.');
                } else {
                    $(this).removeClass('is-invalid');
                    $('#passwordError').text('');
                    
                    // Check password confirmation match
                    const confirmation = $('#password_confirmation').val();
                    if (confirmation && password !== confirmation) {
                        $('#password_confirmation').addClass('is-invalid');
                        $('#passwordConfirmationError').text('Passwords do not match.');
                    } else if (confirmation) {
                        $('#password_confirmation').removeClass('is-invalid');
                        $('#passwordConfirmationError').text('');
                    }
                }
            });

            $('#password_confirmation').on('input', function() {
                const password = $('#password').val();
                const confirmation = $(this).val();
                
                if (confirmation && password !== confirmation) {
                    $(this).addClass('is-invalid');
                    $('#passwordConfirmationError').text('Passwords do not match.');
                } else {
                    $(this).removeClass('is-invalid');
                    $('#passwordConfirmationError').text('');
                }
            });

            // Utility functions
            function showLoading() {
                $('#loadingOverlay').css('display', 'flex');
            }

            function hideLoading() {
                $('#loadingOverlay').hide();
            }

            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="fas fa-${getAlertIcon(type)} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alertContainer').html(alertHtml);
                
                if (type === 'success') {
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 4000);
                }
            }

            function getAlertIcon(type) {
                const icons = {
                    'success': 'check-circle',
                    'danger': 'exclamation-triangle',
                    'warning': 'exclamation-triangle',
                    'info': 'info-circle'
                };
                return icons[type] || 'info-circle';
            }

            function clearErrors() {
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#alertContainer').html('');
            }

            function displayValidationErrors(errors) {
                $.each(errors, function(field, messages) {
                    const input = $('#' + field);
                    const errorDiv = $('#' + field + 'Error');
                    
                    input.addClass('is-invalid');
                    errorDiv.text(messages[0]);
                });
            }

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Clear errors on input
            $('.form-control, .form-select').on('input change', function() {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('');
                }
            });

            $('#terms').on('change', function() {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $('#termsError').text('');
                }
            });
        });
    </script>
</body>
</html>