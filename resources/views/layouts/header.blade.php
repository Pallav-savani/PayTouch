<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    

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
                            <h2>DTH</h2>
                        </div>
                        <div class="row">
                            <div class="col-auto mt-3"><a href=" {{ route('dth')  }}" class="btn btn gradient-bg text-white">DTH</a></div>
                            <div class="col-auto mt-3"><a href="{{ route('mobile')  }}" class="btn btn gradient-bg text-white">Mobile Recharge</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Utility Bills</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Reports</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">My Account</a></div>
                            <div class="col-auto mt-3"><a href="{{ route('ccbill') }}" class="btn btn gradient-bg text-white">CC Bill Pay</a></div>
                            <div class="col-auto mt-3"><a href="{{ route('wallet')  }}" class="btn btn gradient-bg text-white">Load Wallet</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Flight Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Railway Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Hotel Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Bus Booking</a></div>
                            <div class="col-auto mt-3"><a href="#" class="btn btn gradient-bg text-white">Fastag Recharges</a></div> 
                        </div>
                        <div class="service-title d-flex justify-content-between align-items-center gap-3 flex-wrap mt-3">
                        <div class="service-prev-btn">
                            <a href="<?= $prevPageFile ?? '#' ?>" class="btn btn gradient-bg text-white">
                                Previous
                                <i class="fa fa-angle-left me-2"></i>
                            </a>
                        </div>
                        <div class="service-prev-btn">
                            <a href="#" class="btn btn gradient-bg text-white " >
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
        $(document).ready(function () {
            const token = localStorage.getItem("auth_token");

            $.ajax({
                url: "http://127.0.0.1:8000/api/users",
                type: "GET",
                dataType: "json",
               
                headers: {
                    "Authorization": "Bearer " + token,
               "content-type": "application/json"
                },
                
                
                success: function (user) {
                    $("#userInfo").html(`
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="details">
                                            <i class="fa fa-user"></i>
                                            <span>User : ${user.id}</span>
                                        </div>
                                    </td>
                                    <td colspan="8">
                                        <div class="details">
                                            <i class="fa fa-user"></i>
                                            <span>Mobile No. : ${user.mobile}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="details">
                                            <i class="fa fa-briefcase me-2"></i>
                                            <span>Regular : </span>
                                            
                                        </div>
                                    </td>
                                    <td>
                                        <div class="details">
                                            <i class="fa fa-percent me-2"></i>
                                            <span>Disc : </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="details">
                                            <i class="fa fa-life-ring me-2"></i>
                                            <span>O/S : </span>
                                            <span style="color:green;">0.00</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    `);
                },
             
                error: function (xhr) {
                    console.error("Error fetching user:", xhr);
                    $(".userInfo").html("<p class='text-danger'>Failed to fetch user info. Check token.</p>");
                }
            });   
        });

    function logout() {
        const token = localStorage.getItem("auth_token");

        $.ajax({
            url: 'http://127.0.0.1:8000/api/logout',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(res) {
                alert(res.message);
                // Optionally redirect to login page
                window.location.href = '/';
            },
            error: function(xhr) {
                alert('Logout failed');
                console.error(xhr.responseText);
            }
        });
    }
    </script>

</body>
</html>