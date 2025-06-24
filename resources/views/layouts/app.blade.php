<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PayTouch - Web App</title>
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
<body>
    <!-- Loading overlay for authentication check -->
    <div id="authLoadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); z-index: 9999; justify-content: center; align-items: center;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Verifying authentication...</p>
        </div>
    </div>

    <div id="mainContent" style="display: none;">
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
            <div class="position-absolute end-0 me-3"> 
                <a href="#" onclick="logout()" class="text-decoration-none">
                    <button type="button" class="btn btn-outline-danger px-3">
                        <i class="fa-solid fa-power-off fa-xl"></i>
                    </button>
                     
                </a>
            </div>
            
        </nav>

        <!-- Content section where child views will be included -->
        <main id="pageContent">
            @yield('content')
        </main>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Check authentication first
        checkAuthentication();
    });

    function checkAuthentication() {
        const token = localStorage.getItem("auth_token");

        // Show loading overlay
        showAuthLoading();

        if (!token) {
            // No token found, redirect to login
            redirectToLogin("No authentication token found");
            return;
        }

        // Verify token with server
        $.ajax({
            url: "{{ url('/api/user') }}",
            type: "GET",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token,
                "Content-Type": "application/json"
            },
            success: function (user) {
                // Token is valid, show main content
                hideAuthLoading();
                showMainContent();
                console.log("User authenticated successfully:", user);
            },
            error: function (xhr) {
                console.error("Authentication failed:", xhr);
                hideAuthLoading();
                
                if (xhr.status === 401 || xhr.status === 403) {
                    // Token is invalid or expired
                    localStorage.removeItem("auth_token");
                    localStorage.removeItem("user_data");
                    redirectToLogin("Session expired or invalid");
                } else if (xhr.status === 0) {
                    // Network error
                    redirectToLogin("Network error. Please check your connection");
                } else {
                    // Other server errors
                    redirectToLogin("Server error. Please try again later");
                }
            }
        });
    }

    function showAuthLoading() {
        $("#authLoadingOverlay").css('display', 'flex');
        $("#mainContent").hide();
    }

    function hideAuthLoading() {
        $("#authLoadingOverlay").hide();
    }

    function showMainContent() {
        $("#mainContent").show();
    }

    function redirectToLogin(reason = "Authentication required") {
        console.log("Redirecting to login:", reason);
        
        // Show redirect message
        $("#authLoadingOverlay").html(`
            <div class="text-center">
                <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Redirecting...</span>
                </div>
                <p class="mt-3 text-warning">${reason}</p>
                <p class="text-muted">Redirecting to login page...</p>
            </div>
        `).css('display', 'flex');
        
        // Redirect after a short delay
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 2000);
    }

    function logout() {
        const token = localStorage.getItem("auth_token");

        if (!token) {
            // If no token, just redirect to login
            window.location.href = "{{ route('login') }}";
            return;
        }

        // Show loading state
        showAuthLoading();

        $.ajax({
            url: '{{ url("/api/logout") }}',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            success: function(res) {
                console.log("Logout successful:", res);
                localStorage.removeItem("auth_token");
                localStorage.removeItem("user_data");
                
                // Show success message
                $("#authLoadingOverlay").html(`
                    <div class="text-center">
                        <div class="text-success mb-3">
                            <i class="fas fa-check-circle" style="font-size: 3rem;"></i>
                        </div>
                        <p class="text-success">${res.message || 'Logged out successfully'}</p>
                        <p class="text-muted">Redirecting to login page...</p>
                    </div>
                `).css('display', 'flex');
                
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}";
                }, 1500);
            },
            error: function(xhr) {
                console.error('Logout error:', xhr);
                
                // Even if logout API fails, clear local storage and redirect
                localStorage.removeItem("auth_token");
                localStorage.removeItem("user_data");
                
                $("#authLoadingOverlay").html(`
                    <div class="text-center">
                        <div class="text-warning mb-3">
                            <i class="fas fa-exclamation-triangle" style="font-size: 3rem;"></i>
                        </div>
                        <p class="text-warning">Logout completed with warnings</p>
                        <p class="text-muted">Redirecting to login page...</p>
                    </div>
                `).css('display', 'flex');
                
                setTimeout(function() {
                    window.location.href = "{{ route('login') }}";
                }, 1500);
            }
        });
    }

    // Handle token expiration globally for this layout
    $(document).ajaxError(function(event, xhr, settings) {
        if (xhr.status === 401 || xhr.status === 403) {
            // Token expired or unauthorized
            localStorage.removeItem("auth_token");
            localStorage.removeItem("user_data");
            
            // Don't redirect if it's already a login page request
            if (!settings.url.includes('/login') && !settings.url.includes('/api/login')) {
                console.log("Session expired during AJAX request");
                redirectToLogin("Session expired during request");
            }
        }
    });

    // Handle page visibility to re-check authentication when page becomes visible
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible, verify token is still valid
            const token = localStorage.getItem("auth_token");
            if (token) {
                // Quick token validation
                $.ajax({
                    url: "{{ url('/api/user') }}",
                    type: "GET",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Content-Type": "application/json"
                    },
                    error: function(xhr) {
                        if (xhr.status === 401 || xhr.status === 403) {
                            localStorage.removeItem("auth_token");
                            localStorage.removeItem("user_data");
                            redirectToLogin("Session expired while away");
                        }
                    }
                });
            }
        }
    });

    // Prevent back button after logout
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Page was loaded from cache (back button)
            const token = localStorage.getItem("auth_token");
            if (!token) {
                window.location.href = "{{ route('login') }}";
            }
        }
    });

    // Global function to check authentication (can be called from child views)
    window.checkUserAuthentication = function() {
        return localStorage.getItem("auth_token") !== null;
    };

    // Global function to get current user token
    window.getUserToken = function() {
        return localStorage.getItem("auth_token");
    };

    // Global function to force re-authentication check
    window.reCheckAuthentication = function() {
        checkAuthentication();
    };

</script>

</body>
</html>
