<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTouch - Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: whitesmoke;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            margin: auto;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="70" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }
        
        .login-left > * {
            position: relative;
            z-index: 1;
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-forgot {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-forgot:hover {
            color: #764ba2;
            text-decoration: underline;
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
            border: none;
        }
        
        .welcome-icon {
            font-size: 4rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }
        
        .feature-list li {
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
        }
        
        .feature-list i {
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .social-login {
            margin: 1.5rem 0;
        }
        
        .btn-social {
            border: 2px solid #e9ecef;
            background: white;
            color: #6c757d;
            transition: all 0.3s ease;
            margin: 0.25rem;
        }
        
        .btn-social:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .btn-google:hover {
            border-color: #db4437;
            color: #db4437;
        }
        
        .btn-linkedin:hover {
            border-color: #0077b5;
            color: #0077b5;
        }
        .btn-outline:hover{
            text-decoration: underline;
        }
        
        .divider {
            position: relative;
            margin: 2rem 0;
            text-align: center;
            color: #6c757d;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 5;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .form-floating {
            position: relative;
        }
        
        @media (max-width: 768px) {
            .login-left {
                padding: 30px 20px;
            }
            .login-right {
                padding: 30px 20px;
            }
            .welcome-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }
        }

        @media (min-width: 768px) {
            .col-md-7 {
                flex: 0 0 auto;
                width: 100%;
            }
        }
        
        /* Animation for form elements */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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
            <p class="mt-3">Signing you in...</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-container">
                    <!-- <div class="row g-0"> -->
                        <!-- Left Side - Welcome -->

                        
                        <!-- Right Side - Login Form -->
                        <div class="col-md-7 login-right slide-in-right">
                            <div class="mb-4 fade-in">
                                <h3 class="text-center mb-1">Sign In</h3>
                                <p class="text-center text-muted">Enter your credentials to access your account</p>
                            </div>
                            
                            <!-- Alert Messages -->
                            <div id="alertContainer"></div>
                            
                            <!-- Login Form -->
                            <form id="loginForm" class="fade-in">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="9847589564" required maxlength="12">
                                    <label for="mobile"><i class="fas fa-mobile-alt me-2"></i>Mobile Number</label>
                                    <div class="invalid-feedback" id="mobileError"></div>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                    <button type="button" class="password-toggle" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback" id="passwordError"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="#" class="btn-forgot" id="forgotPassword">
                                            <i class="fas fa-key me-1"></i>Forgot Password?
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-login btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 d-flex align-items-center column-gap-2 w-100" style="justify-content: space-between; outline: none;">
                                <p class="mb-2">Don't have an account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline">
                                    <i class="fas fa-user-plus me-1"></i>Create Account
                                </a>
                            </div>
                            
                            <!-- Social Login -->
                            <div class="social-login fade-in">
                                <div class="divider">
                                    <span>Or continue with</span>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-social btn-google w-100" id="googleLogin">
                                            <i class="fab fa-google me-2"></i>Google
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-social btn-linkedin w-100" id="linkedinLogin">
                                            <i class="fab fa-linkedin me-2"></i>LinkedIn
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4 fade-in">
                                <a href="{{ route('index') }}" class="text-decoration-none">
                                    <i class="fas fa-home me-1"></i>Back to Home
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

            // Check if user is already logged in
            // if (localStorage.getItem('auth_token')) {
            //     window.location.href = 'welcome';
            //     return;
            // }

            // Password toggle functionality
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Format mobile number input
            $('#mobile').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-digits
                if (value.length > 12) {
                    value = value.substring(0, 12);
                }
                $(this).val(value);
                
                // Remove error state if typing
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $('#mobileError').text('');
                }
            });

            // Login form submission
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                clearErrors();
                
                const formData = {
                    mobile: $('#mobile').val().trim(),
                    password: $('#password').val(),
                    remember: $('#remember').is(':checked')
                };

                if (!validateForm(formData)) {
                    return;
                }

                showLoading();

                $.ajax({
                    url: '/api/login',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        hideLoading();
                        
                        if (response.token && response.user) {
                            localStorage.setItem('auth_token', response.token);
                            localStorage.setItem('user_data', JSON.stringify(response.user));
                            
                            showAlert(`Welcome back! Redirecting...`, 'success');
                            
                            setTimeout(function() {
                                window.location.href = '/kycVerification';
                            }, 1500);
                        } else {
                            showAlert('Login successful, but session data is incomplete. Please try again.', 'warning');
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            displayValidationErrors(errors);
                        } else if (xhr.status === 401) {
                            showAlert('Invalid mobile number or password. Please check your credentials and try again.', 'danger');
                            $('#loginForm').addClass('animate__animated animate__headShake');
                            setTimeout(() => {
                                $('#loginForm').removeClass('animate__animated animate__headShake');
                            }, 1000);
                        } else if (xhr.status === 429) {
                            showAlert('Too many login attempts. Please wait a few minutes before trying again.', 'warning');
                        } else {
                            showAlert('Login failed. Please check your connection and try again.', 'danger');
                        }
                        
                        console.error('Login error:', xhr.responseJSON || error);
                    }
                });
            });

            // Form validation
            function validateForm(data) {
                let isValid = true;

                if (!data.mobile || !isValidMobile(data.mobile)) {
                    $('#mobile').addClass('is-invalid');
                    $('#mobileError').text('Please enter a valid 10-digit mobile number.');
                    isValid = false;
                }

                if (!data.password || data.password.length < 1) {
                    $('#password').addClass('is-invalid');
                    $('#passwordError').text('Password is required.');
                    isValid = false;
                }

                if (!isValid) {
                    showAlert('Please correct the errors below and try again.', 'danger');
                }

                return isValid;
            }

            // Mobile number validation on blur
            $('#mobile').on('blur', function() {
                const mobile = $(this).val().trim();
                if (mobile && !isValidMobile(mobile)) {
                    $(this).addClass('is-invalid');
                    $('#mobileError').text('Please enter a valid 10-digit mobile number.');
                } else if (mobile && isValidMobile(mobile)) {
                    $(this).removeClass('is-invalid');
                    $('#mobileError').text('');
                }
            });

            // Password validation on input
            $('#password').on('input', function() {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $('#passwordError').text('');
                }
            });

            // Social login buttons
            $('#googleLogin').on('click', function() {
                showAlert('Google login will be implemented soon!', 'info');
            });

            $('#linkedinLogin').on('click', function() {
                showAlert('LinkedIn login will be implemented soon!', 'info');
            });

            $('#forgotPassword').on('click', function(e) {
                e.preventDefault();
                showAlert('Forgot password functionality will be implemented soon!', 'info');
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
                
                if (type === 'success' || type === 'info') {
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
                $('.form-control').removeClass('is-invalid');
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

            function isValidMobile(mobile) {
                // Accept 10-12 digit mobile numbers
                const mobileRegex = /^[0-9]{10,12}$/;
                return mobileRegex.test(mobile);
            }

            // Remove validation errors on input
            $('.form-control').on('input', function() {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('');
                }
            });

            // Focus effects for form controls
            $('.form-control').on('focus', function() {
                $(this).parent().addClass('focused');
            });

            $('.form-control').on('blur', function() {
                if (!$(this).val()) {
                    $(this).parent().removeClass('focused');
                }
            });

            // Button ripple effect
            $('.btn').on('click', function(e) {
                const button = $(this);
                const circle = $('<span class="ripple"></span>');
                const diameter = Math.max(button.outerWidth(), button.outerHeight());
                const radius = diameter / 2;

                circle.css({
                    width: diameter,
                    height: diameter,
                    left: e.pageX - button.offset().left - radius,
                    top: e.pageY - button.offset().top - radius
                }).addClass('ripple');

                button.append(circle);

                setTimeout(() => {
                    circle.remove();
                }, 600);
            });
        });
    </script>
</body>
</html>