<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ccbill.css') }}">

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
</head>
<body>
    <div class="main">
        <header class="header">
            <div class="container-fluid  mb-3">
                <div class="row px-3">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 mart mobile-logo">
                        <a href="#" class="navbar-brand">
                            <img height="65px" src="{{ asset('images/logo.png') }}" />
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 padd">
                        <div class="d-block">
                            <div id="userInfo">
                                <!-- User info will be loaded here -->
                            </div>
                        </div>
                        
                    </div> 
                    <div class="card position-relative overflow-hidden col-lg-3 col-md-12 col-sm-12 col-xs-12 gradient-bg">
                        <div class="text-center">
                            <h5 class="card-title">Customer Care</h5>
                            <h5 class="card-title">Working Hours : 10:00 am to 8:00 pm</h5>
                            <p class="card-text">+91 75675 25559</p>
                            <div class="justify-content-center">
                                  <a href="#" class="text-decoration-none">
                                    <img class="mx-2 invert1" style="height: 25px;" src="/css/assets/home.png" title="Home" alt="Home">
                                  </a>
                                  <img class="mx-2 invert1" style="height: 25px;" src="/css/assets/statement.png" title="MiniStatement" alt="Statement">
                                    <img class="mx-2 invert1" style="height: 25px;" src="/css/assets/download.png" title="Download" alt="Download">
                                    <img class="mx-2 invert1" style="height: 25px;" src="/css/assets/password1.png" title="ChangePassword" alt="Password">
                                    <a href="#" onclick="logout()" class="text-decoration-none">
                                        <img class="mx-2 invert1" style="height: 25px;" src="/css/assets/logout1.png" title="Logout" alt="Logout">
                                    </a>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="blank-box">
                    <marquee behavior="" direction="">Welcome TO Pay Touch!</marquee>
                </div>
                <div class="main-service-section">
                <div class="container-fluid"> 
                    <div class="service-title d-flex justify-content-between align-items-center gap-3 flex-wrap">
                            <h2>{{ ucwords(str_replace(['-', '_'], ' ', Request::segment(1) ?: 'Home')) }}</h2>
                        <div class="text-end">
                            <a href="#" class="d-inline-block">
                                <img height="120px" src="{{ asset('images/bbps.jpg') }}" alt="BBPS Logo" />
                            </a>
                        </div>
                    </div>

                        <div class="row">
                            <!-- <div class="col-auto mt-3"><a href=" {{ route('home')  }}" class="btn btn gradient-bg text-white">Home</a></div> -->
                            <div class="col-auto mt-3"><a href=" {{ route('dth')  }}" class="btn btn gradient-bg text-white">DTH</a></div>
                            <div class="col-auto mt-3"><a href="{{ route('mobile')  }}" class="btn btn gradient-bg text-white">Mobile Recharge</a></div>
                            <div class="col-auto mt-3"><a href="{{ route('fastag') }}" class="btn btn gradient-bg text-white">Fastag Recharges</a></div> 
                             <div class="col-auto mt-3"><a href="{{ route('ccbill') }}" class="btn btn gradient-bg text-white">CC Bill Pay</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Utility Bills</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Reports</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">My Account</a></div> 
                            <div class="col-auto mt-3"><a href="{{ route('wallet') }}" class="btn btn gradient-bg text-white">Load Wallet</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Flight Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Railway Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Hotel Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Bus Booking</a></div>
                        </div>
                        <div class="service-title d-flex justify-content-between align-items-center gap-3 flex-wrap mt-3">
                        <div class="service-prev-btn">
                            @php
                                $pages = [
                                    'dth' => route('dth'),
                                    'mobile' => route('mobile'),
                                    'fastag' => route('fastag'),
                                    'ccbill' => route('ccbill'),
                                    'wallet' => route('wallet')
                                ];
                                $currentRoute = Request::route()->getName();
                                $pageKeys = array_keys($pages);
                                $currentIndex = array_search($currentRoute, $pageKeys);
                                $prevIndex = $currentIndex > 0 ? $currentIndex - 1 : count($pageKeys) - 1;
                                $nextIndex = $currentIndex < count($pageKeys) - 1 ? $currentIndex + 1 : 0;
                                $prevUrl = $currentIndex !== false ? $pages[$pageKeys[$prevIndex]] : '#';
                                $nextUrl = $currentIndex !== false ? $pages[$pageKeys[$nextIndex]] : '#';
                            @endphp
                            <a href="{{ $prevUrl }}" class="btn btn gradient-bg text-white">
                                Previous
                                <i class="fa fa-angle-left me-2"></i>
                            </a>
                        </div>
                        <div class="service-prev-btn">
                            <a href="{{ $nextUrl }}" class="btn btn gradient-bg text-white " >
                                Next
                                <i class="fa fa-angle-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    let walletRefreshInterval;
    let isPageVisible = true;

    $(document).ready(function () {
        // Initial load
        fetchUserInfo();
        
        // Start auto-refresh
        startWalletAutoRefresh();
        
        // Handle page visibility changes
        handlePageVisibility();
    });

    function fetchUserInfo() {
        const token = localStorage.getItem("auth_token");

        if (!token) {
            $("#userInfo").html("<p class='text-danger'>Please login to continue.</p>");
            return;
        }

        $.ajax({
            url: "{{ url('/api/user') }}",
            type: "GET",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token,
                "Content-Type": "application/json"
            },
            success: function (user) {
                updateUserInfo(user);
            },
            error: function (xhr) {
                console.error("Error fetching user:", xhr);
                if (xhr.status === 401) {
                    $("#userInfo").html("<p class='text-danger'>Session expired. Please login again.</p>");
                    // Optionally redirect to login
                    // window.location.href = '/login';
                } else {
                    $("#userInfo").html("<p class='text-danger'>Failed to fetch user info.</p>");
                }
            }
        });
    }

    function updateUserInfo(user) {
        $("#userInfo").html(`
            <div class="container py-2 text-center">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <div class="details">
                        <i class="fa fa-user me-2"></i>
                        <strong>User :</strong><span style="color:black;">&nbsp;<b>${user.id}</b></span>
                    </div>
                    <div class="details">
                        <i class="fa fa-mobile me-2"></i>
                        <strong>Mobile No :</strong> <span style="color:black;">&nbsp; <b>${user.mobile}</b> </span>
                    </div>
                </div>
                <div class="os-box mt-1">
                    <i class="fa fa-life-ring me-2"></i>
                    <strong>O/S :</strong> 
                    <span id="walletBalance" style="color:black; font-weight: 600;">&nbsp;₹${parseFloat(user.wallet_balance || 0).toFixed(2)}</span>
                    <i id="refreshIcon" class="fa fa-sync-alt ms-2" style="font-size: 12px; color: #28a745; cursor: pointer;" 
                       title="Auto-refreshing every 30 seconds" onclick="refreshWalletBalance()"></i>
                </div>
            </div>
        `);
    }

    function refreshWalletBalance() {
        const token = localStorage.getItem("auth_token");
        const refreshIcon = $("#refreshIcon");
        
        if (!token) return;

        // Show spinning animation
        refreshIcon.addClass('fa-spin').css('color', '#007bff');

        $.ajax({
            url: "{{ url('/api/wallet/balance') }}",
            type: "GET",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token,
                "Content-Type": "application/json"
            },
            success: function (response) {
                if (response.success && response.data) {
                    const newBalance = parseFloat(response.data.balance || 0).toFixed(2);
                    const currentBalance = $("#walletBalance").text().replace('₹', '');
                    
                    // Update balance with animation if changed
                    if (newBalance !== currentBalance) {
                        $("#walletBalance").fadeOut(200, function() {
                            $(this).html(`₹${newBalance}`).fadeIn(200);
                        });
                        
                        // Show success color briefly
                        refreshIcon.css('color', '#28a745');
                    }
                }
            },
            error: function (xhr) {
                console.error("Error refreshing wallet balance:", xhr);
                // Show error color briefly
                refreshIcon.css('color', '#dc3545');
            },
            complete: function() {
                // Stop spinning and reset color after a delay
                setTimeout(function() {
                    refreshIcon.removeClass('fa-spin').css('color', '#28a745');
                }, 500);
            }
        });
    }

    function startWalletAutoRefresh() {
        // Clear any existing interval
        if (walletRefreshInterval) {
            clearInterval(walletRefreshInterval);
        }
        
        // Set up auto-refresh every 30 seconds
        walletRefreshInterval = setInterval(function() {
            if (isPageVisible) {
                refreshWalletBalance();
            }
        }, 30000); // 30 seconds
    }

    function stopWalletAutoRefresh() {
        if (walletRefreshInterval) {
            clearInterval(walletRefreshInterval);
            walletRefreshInterval = null;
        }
    }

    function handlePageVisibility() {
        // Handle page visibility changes to pause/resume auto-refresh
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                isPageVisible = false;
                console.log('Page hidden - pausing wallet refresh');
            } else {
                isPageVisible = true;
                console.log('Page visible - resuming wallet refresh');
                // Refresh immediately when page becomes visible
                refreshWalletBalance();
            }
        });

        // Handle window focus/blur events as fallback
        window.addEventListener('focus', function() {
            isPageVisible = true;
            refreshWalletBalance();
        });

                window.addEventListener('blur', function() {
            isPageVisible = false;
        });
    }

    // Global function to manually refresh (can be called from other scripts)
    window.refreshUserWalletBalance = function() {
        refreshWalletBalance();
    };

    // Global function to trigger full user info refresh
    window.refreshUserInfo = function() {
        fetchUserInfo();
    };

    function logout() {
        const token = localStorage.getItem("auth_token");

        // Stop auto-refresh on logout
        stopWalletAutoRefresh();

        $.ajax({
            url: 'http://127.0.0.1:8000/api/logout',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(res) {
                alert(res.message);
                localStorage.removeItem("auth_token");
                window.location.href = '/';
            },
            error: function(xhr) {
                alert('Logout failed');
                console.error(xhr.responseText);
            }
        });
    }

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        stopWalletAutoRefresh();
    });

</script>

</body>
</html>

