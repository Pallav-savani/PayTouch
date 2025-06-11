<?php
function getActiveClass($section)
{
  // Default section is home
  $current_section = '';

  // Check if a section is in the URL as a query parameter
  if (isset($_GET['section'])) {
    $current_section = $_GET['section'];
  }

  // Return the "active" class if the current section matches the section
  return $current_section === $section ? 'active' : '';
}


// country code api start
$url = "https://restcountries.com/v3.1/all";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$countries = json_decode($response, true);
$countryCodes = [];

if (is_array($countries)) {
    foreach ($countries as $country) {
        // Safely access nested array elements
        $name = isset($country['name']['common']) ? $country['name']['common'] : '';
        
        $code = '';
        if (isset($country['idd']['root']) && isset($country['idd']['suffixes'][0])) {
            $code = $country['idd']['root'] . $country['idd']['suffixes'][0];
        } elseif (isset($country['idd']['root'])) {
            $code = $country['idd']['root'];
        }
        
        $flag = isset($country['flags']['png']) ? $country['flags']['png'] : '';
        $shortCode = isset($country['cca2']) ? $country['cca2'] : '';
        
        if ($code && $flag && $shortCode) {
            $countryCodes[] = [
                'name' => $name,
                'code' => $code,
                'flag' => $flag,
                'shortCode' => $shortCode
            ];
        }
    }
    
    // Sort country codes by name
    usort($countryCodes, function ($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
}
// country code api end



?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="images\transference.png">
  <title> WELCOME TO PayTouch WEB APP</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <!-- custom CSS -->
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/responsive.css" rel="stylesheet" type="text/css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
  <!-- MDB -->
</head>

<body>

  <!-- Navbar Start -->
 

  <nav class="navbar navbar-expand-lg sticky-top"  style="background-color:white;" aria-label="Offcanvas navbar large">
    <div class="container">
      <div class="m-0 p-0 logo-main">
        <div class="d-flex justify-content-center logo countdown">
          <a class="text-decoration-none" href="index.php">
            <img class="navbar-brand p-0" height="65px" src="images\Adobe Express - file.png" />
          </a>
        </div>
        <a class="text-align-center m-0 text-decoration-none" href="index.php"><span class="unikpay fs-5">SHREE
          </span><span class="websol">fintech Solutions Pvt. Ltd.</span></a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end text-bg-light" tabindex="-1" id="offcanvasNavbar2" aria-labelledby="offcanvasNavbar2Label">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbar2Label">PayTouch</h5>
          <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav fs-6 justify-content-center flex-grow-1 px-5 gap-3">
            <li class="nav-item">
              <a class="nav-link <?= getActiveClass('') ?> px-3" href="?section=#"><i class="fa fa-home me-2"></i>Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= getActiveClass('about') ?> px-3" href="?section=about#about"><i class="fa fa-user me-2"></i>About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= getActiveClass('services') ?> px-3" href="?section=services#services"><i class="fa fa-cog me-2"></i>Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= getActiveClass('contactus') ?> px-3" href="?section=contactus#contactus"><i class="fa fa-phone me-2"></i>Contact Us</a>
            </li>
            
          </ul>
          <ul class="navbar-nav gap-2">
            
            <Button class="btn btn-outline-warning rounded-pill my-1 card-b d-flex justify-content-center align-items-center"  type="button"><a href="{{ route('login') }}">Sign In</a>
              <img class="h-75 ms-2" src="images/svg/Add User.svg" alt="">
              <!-- <i class="fas fa-sign-in ms-2 fa-lg"></i> -->
            </Button>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Navbar End -->


  <!-- MAIN PAGE -->

  <!-- Hero Section -->
  <div class="bg-lightorange main-banner">
    <!-- <img class="position-absolute mobile-img" src="images\slider3.png" width="100%" height="647px" /> -->
    <div class="container py-5">
      <div class="row justify-content-between h-100">
        <div class="col-lg-6 wow slideInLeft order-lg-1 order-md-2 oder-sm-2 order-2 row align-items-center">
          <div class="landing-text">
            <h3 class="text-light text-black">
              Easy Way to <br class="br-none" />make Online
            </h3>
            <h1 class="unikpay">PAYMENTS <br class="br-none" /><span class="text-black">and </span> SECURE</h1>
            <p>Pay and get paid in real-time with zero fees. Secure, streamlined, and super fast.</p>
            <!--<Button class="btn btn-outline-warning rounded-pill my-1 card-b d-flex justify-content-center align-items-center text-black" data-bs-target="#loginModal" data-bs-toggle="modal" type="button">-->
            <!--  <img class="me-2" src="images/svg/Download.svg" alt="">Download App</Button>-->
            <div class="ac-div d-flex pt-4 gap-3">
              <div class="d-flex">
                <img class="me-2 h-75 mt-2" src="images/svg/Achievement.svg" alt="">
                <p class="mb-0">Years <br class="br-none" /> of experience</p>
              </div>
              <div class="vr"></div>
              <div class="d-flex">
                <img class="me-2 h-75 mt-2" src="images/svg/Customer.svg" alt="">
                <p class="mb-0">Satisfide and <br class="br-none" /> happy customurs</p>
              </div>
            </div>
          </div>
  
        </div>
        <div class="col-lg-6 wow slideInRight order-lg-2 order-md-1 oder-sm-1 order-1 m-img row justify-content-center">
          <img class="" src="images/svg/Landing Image.svg" />
        </div>
      </div>
    </div>
  </div>
  <!-- Hero Section -->

  <!-- ABOUT SECTION -->
  <div id="about" class="container">

    <div class="row align-items-center">
      <div class="col-lg-6 wow slideInLeft">
        <div class="about-us-img">
          <img src="images\aboutpage2.png" />
        </div>
      </div>
      <div class="col-lg-6 wow slideInRight">
        <div class="">
          <h1 class="d-flex justify-content-left column-gap-3">
            <p class="unikpay">SHREE</p>
            <p class="websol">FinTech Solutions Private Limited</p>
          </h1>
          <div class="font-sol  ">
            <p>SHREE Fintech Solutions Private Limited is India’s one of the leading recharge & bill payment site, designed for customers
              convenience that delivers quick and safe online transaction portal to the customers across the world.</p>

            <p><span class="unikpay">SHREE</span> <span class="websol">FinTech Solutions Private Limited</span> is a secure service
              provider who is focused on developing a strong technical core to make
              your experience with PayTouch FinTech Solution more secure and reliable.</p>
          </div>
        </div>
        <div class=" row align-items-center">
          <div class="col-lg-6">
            <div class="card rounded-4 mb-3 card-b">
              <img src="images\user 1.png" class="rounded-circle bg-white border mx-auto mt-3 p-2" width="58px" height="58px" alt="...">
              <div class="card-body text-white text-center">
                <h5 class="card-title head-title fs-4">No Hidden Charges</h5>
                <p class="card-cont cont">Just Sign Up and Complete your minimum KYC and you are done. No hidden
                  charges.</p>
              </div>
            </div>
            <div class="card rounded-4 card-b">
              <div class="rounded-circle bg-white border mx-auto mt-3 p-2" style="width: 58px; height: 58px;">
                <img src="images\revenue.png" style=" height: 35px; width: 35px;" alt="...">
              </div>
              <div class="card-body text-white text-center">
                <h5 class="card-title head-title fs-4"> Increase Revenue</h5>
                <p class="card-cont cont">Join <span class="unikpay">SHREE</span> <span class="websol">FinTech
                    Solutions Private Limited</span> and offer more services to your customers and earn
                  maximum commissions.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card rounded-4 mb-3 card-b">
              <img src="images\services.png" class="rounded-circle bg-white border mx-auto mt-3 p-2" width="58px" height="58px" alt="...">
              <div class="card-body text-white text-center">
                <h5 class="card-title head-title fs-4">Expand Services</h5>
                <p class="card-cont cont">All the tools that help you build your business are available in one
                  app.
                  Create a PayTouch Account to get started and extend services to your loyal customers.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ABOUT SECTION -->

  <!-- service SECTION -->
  <div id="services" class="py-5 text-white bg-lightblue" style="height: 336px;">
    <div class="container">
      <div class="d-flex justify-content-center py-4">
        <div class="text-center">
          <h1 class="wow fadeIn"><span class="colorh1">SHREE</span><span class="colorh2"> FinTech Solution Services</span>
          </h1>
          </br>
          <h6>
            <p class="lh-base fs-5 wow card-cont fadeIn cont-w600">We provide Services like Recharge & Bill Payment,
              </br>
              Utility Payments, Settlement.</p>
            </h5>
        </div>
      </div>
    </div>
  </div>
  <div class=" container pay-sec" style="margin-top: -100px;">
    <ul class="d-flex flex-wrap justify-content-center text-black text-center service-box p-0 ">
      <li class="text-decoration-none wow fadeInUp" style="padding: 15px; flex: 0 0 20%;">
        <div class="box bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/DTH.svg" alt="">
          </span>
          <p class="fs-6 head-title">Recharges Telecom/DTH</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 20%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Bill Payments.svg" alt="">
          </span>
          <p class="fs-6 head-title mb-0">Bill</p>
          <p class="fs-6 head-title mb-0">Payments</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInUp" style="padding: 15px; flex: 0 0 20%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Utilities.svg" alt="">
          </span>
          <p class="fs-6 head-title mb-0">Utility</p>
          <p class="fs-6 head-title mb-0">Payments</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 20%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Airline Booking.svg" alt="">
          </span>
          <p class="fs-6 head-title">Railways/Airline Bookings</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInUp" style="padding: 15px; flex: 0 0 20%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/White Label Solution.svg" alt="">
          </span>
          <p class="fs-6 head-title">White-Label Solutions</p>
        </div>
      </li>
     <!-- <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 20%;">
        <!-- <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <!-- <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <!-- <img height="50px" class="m-auto" src="images/svg/Aadhar Payments.svg" alt="">
          </span> -->
          <!-- <p class="fs-6 head-title">AePS</p>
        </div>-->
      <!--</li>-->
      <li class="text-decoration-none big-box wow  fadeInUp" style="padding: 15px; flex: 0 0 30%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Web App.svg" alt="">
          </span>
          <p class="fs-6 head-title">Utility Payment Solution on Mobile Apps & Website</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 20%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px;">
          <span class="icon rounded-circle  d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Insurance.svg" alt="">
          </span>
          <p class="fs-6 head-title">Insurance</p>
        </div>
      </li>
    </ul>
    <!-- <div class="d-flex justify-content-center py-4 powered-sec wow fadeInDown">
      <h2>
        Powered by
      </h2>
      <div class="d-flex p-img">
        <img src="images/sbi.png" class="my-auto mx-3" height="35px" alt="">
        <img src="images/hdfc.png" class="my-auto mx-3" height="35px" alt="">

      </div>
    </div> -->
  </div>
  <!-- service SECTION -->

  <!-- Portfolio section -->
  <section class="main-portfolio">
    <div class="mt-3 text-white">
      <div class="container">
        <div class="my-4 row align-items-center">
          <div class="col-lg-6 wow slideInLeft">
            <div class="p-img-sec">
              <img class="img-fluid fadeInLeft" src="images/20240420_120409.png" width="550px" height="400px" alt="">
            </div>
          </div>
          <div class="col-lg-6 p-4 wow card-cont slideInRight">
            <h3 class="mb-3">
              <span class="colorh1">SHREE</span> <span class="colorh2">FinTech Solutions Private Limited</span> make your recharge & bill
              payment simple advantageous.
            </h3>
            <p>We are aimed to serve you in such way that you don’t find need to go in hustle and stand in a long queue
              for your prepaid recharge & bill payments.</p>
            <p>with its exciting discount coupons, cashback and much more. So register yourself with Payin and enjoy our
              services.</p>
          </div>
        </div>
      </div>
      <div class="bg-lightblue">
        <div class="container">
          <div class="my-4 row align-items-center">
            <div class="col-lg-6 p-4 wow card-cont slideInLeft">
              <h3 class="mb-3">
                We aspire to provide services in a way that <span class="unikpay">SHREE</span> <span class="websol">FinTech
                  Solutions Private Limited</span> become your optimal choice for everything.
              </h3>
              <p>To work beyond the bounds to meet customer’s expectations of services in order to exceed our offerings
                by
                introducing new products & services based on customers demand.</p>
              <p>Our aim is to conspire with endless merchants & service providers to serve our customers endless
                services
                at their fingertips.</p>
              <p>We intend to create experiences which make us customer’s first choice in online market with our best
                customer services possible.</p>
            </div>
            <div class="col-lg-6 order-md-1 order-sm-1 order-1 wow slideInRight">
              <div class="p-img-sec"><img class="" src="images/chokri2.png" alt=""></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class=" py-5 mt-5 text-white" style="height: 336px;">
      <div class="d-flex justify-content-center py-4">
        <div class="text-center container">
          <h1 class="wow head-title fadeIn"><span class="unikpay">Why</span> Choose Us?</h1>
          </br>
          <h6>
            <p class="lh-base fs-5 wow card-cont fadeIn cont-w600">“ We aspire to provide services in a way that Payin
              </br>
              become your optimal choice for everything.”
            </p>
            </h5>
        </div>
      </div>
    </div>
    <div class="container pay-sec" style="margin-top: -100px;">
      <ul class="d-flex flex-wrap justify-content-center text-black text-center px-0 payment-main">
        <!-- <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 33.3333%;">
          <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
            <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
              <img height="50px" class="m-auto" src="images/svg/Aadhar Payments.svg" alt="">
            </span>
            <h5 class="mb-3 head-title">Aadhar Payments</h5>
            <p class="card-text2 cont">Aadhaar Payment is AEPS (Aadhaar Enabled Payment System) powered Web Solution
              payment
              service that enables cash withdrawl using Aadhaar.</p>
          </div>
        </li> -->
        <li class="text-decoration-none wow  fadeInUp" style="padding: 15px; flex: 0 0 33.3333%;">
          <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
            <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
              <img height="50px" class="m-auto" src="images/svg/Bill Payments.svg" alt="">
            </span>
            <h5 class="mb-3 head-title">Recharge & Bill Payment</h5>
            <p class="card-text2 cont">Online Mobile (Prepaid, Postpaid) Recharge, DTH Recharge & Bill Payments.
              Recharge
              Jio, Airtel, Vodafone, Idea, Tata Sky, Dish Tv & more.</p>
          </div>
        </li>
        <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 33.3333%;">
          <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
            <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
              <img height="50px" class="m-auto" src="images/svg/Utilities.svg" alt="">
            </span>
            <h5 class="mb-3 head-title">Utility Payments</h5>
            <p class="card-text2 cont">Collect cash from customers and pay their electricity bill, water bill, gas bill,
              and
              phone bills using PayTouch.</p>
          </div>
        </li>
      </ul>
      <ul class="d-flex flex-wrap justify-content-center text-black text-center px-0 payment-main">
        <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 33.3333%;">
          <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
            <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
              <img height="50px" class="m-auto" src="images\QR Code.png" alt="">
            </span>
            <h5 class="mb-3 head-title">Qr Collection service provider</h5>
            <p class="card-text2 cont">"Effortless Virtual Payment Acceptance! 🚀 Easily generate your payment platform with minimal documents and a quick process. Collect virtual cash seamlessly using QR—simple, fast, and hassle-free!"</p>
          </div>
        </li>
        <!--<li class="text-decoration-none wow  fadeInUp" style="padding: 15px; flex: 0 0 33.3333%;">
        <!--  <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
            <!--<span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
              <!--<img height="50px" class="m-auto" src="images\transference.png" alt="">-->
            <!--</span>-->
            <!--<h5 class="mb-3 head-title">Domestic Money Transfer Services</h5>-->
            <!--<p class="card-text2 cont">"Send money anywhere in India quickly and securely. Our hassle-free Domestic Money Transfer services ensure speed and reliability. Experience seamless transactions with complete peace of mind!".</p>-->
          <!--</div>-->
        <!--</li>-->
        <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 33.3333%;">
          <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
            <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
              <img height="50px" class="m-auto" src="images\ticket.png" alt="">
            </span>
            <h5 class="mb-3 head-title">All Types of Booking Service provider</h5>
            <p class="card-text2 cont">"Book travel, tickets, and more with ease. Our hassle-free booking services ensure quick and reliable confirmations. Enjoy a seamless experience for all your booking needs!".</p>
          </div>
        </li>
      </ul>
    </div>
  </section>
  <!-- Portfolio section -->

  <!-- Contact Us section -->
  <div id="contactus" class="py-5 mt-5 text-white " style="height: 336px;">
    <div class="d-flex justify-content-center py-4">
      <div class="text-center">
        <h1 class="wow head-title fadeIn"><span class="unikpay">Contact</span> Us</h1>
        </br>
        <h6>
          <p class="lh-base fs-5 wow card-cont fadeIn cont-w600">“ We aspire to provide services in a way that Payin
            </br>
            become your optimal choice for everything.”
          </p>
          </h5>
      </div>
    </div>
  </div>
  <div class="container pay-sec" style="margin-top: -100px;">
    <ul class="d-flex flex-wrap justify-content-center text-black text-center px-0 cn-info">
      <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 33.3333%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
          <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Location.svg" alt="">
          </span>
          <h5 class="mb-3 head-title">ADDRESS</h5>
          <p class="card-text2 fw-bold">Register Office:-Plot No 2/11,Mani Estate,Mavdi Plot Road, Rajkot, Gujarat-360004.</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInUp" style="padding: 15px; flex: 0 0 33.3333%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
          <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Email.svg" alt="">
          </span>
          <h5 class="mb-3 head-title">EMAIL</h5>
          <p class="card-text2 fw-bold">admin@paytouch.in</p>
        </div>
      </li>
      <li class="text-decoration-none wow  fadeInDown" style="padding: 15px; flex: 0 0 33.3333%;">
        <div class="box card-cont bg-white h-100 shadow rounded-4" style="padding: 30px 20px;">
          <span class="icon rounded-circle d-flex justify-content-center align-item-center" style="height: 85px; width: 85px; margin: 0 auto 15px;">
            <img height="50px" class="m-auto" src="images/svg/Call.svg" alt="">
          </span>
          <h5 class="mb-3 head-title">CONTACTS</h5>
          <p class="card-text2 fw-bold">+91 75675 25559</p>
        </div>
      </li>
    </ul>
  </div>
  <div class="container contact_form my-5 bg-lightorange rounded-4">
    <div class="row justify-content-center gap-5 py-2">
      <form method="post" action="#" class="col-lg-6 row wow fadeInUp p-3">
        <div class="col-md-12 card-cont text-white">
          <h2 class="head-title">Just say Hello !</h2>
          <p class="card-cont">Let us know more about you!</p>
        </div>
        <div class="col-md-12 mb-3">
          <label for="fullname" class="form-label cont-w600">Full Name<span class="error" title="Required!">*</span>
            :</label>
          <input type="text" class="form-control" id="fullname" placeholder="Full Name" required>
        </div>
        <div class="col-md-6">
          <label for="emailaddress" class="form-label cont-w600">Email address<span class="error" title="Required!">*</span> :</label>
          <input type="email" class="form-control" id="emailaddress" placeholder="example@gmail.com" required>
        </div>
        <div class="col-md-6">
          <label for="mobile" class="form-label cont-w600">Mobile No. :</label>
          <div class="input-group mb-3">
            <button class="btn btn-outline-secondary dropdown-toggle bg-white px-2 d-flex align-items-center border-1" style="border-top-left-radius: .5rem !important; border-bottom-left-radius: .5rem !important;" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="selected-country">Country
              <img class="flags mx-2 rounded" src="" alt="">
            </button>
            <ul class="dropdown-menu custom-scroll" style="height: 200px;" id="country-list">
              <?php foreach ($countryCodes as $country) : ?>
                <li>
                  <a class="dropdown-item country-item d-flex justify-content-evenly" href="#" title="<?php echo htmlspecialchars($country['name']); ?>" data-code="<?php echo htmlspecialchars($country['code']); ?>" data-flag="<?php echo htmlspecialchars($country['flag']); ?>" data-shortcode="<?php echo htmlspecialchars($country['shortCode']); ?>"><?php echo htmlspecialchars($country['code']); ?>
                    <img class="flags ms-2 rounded" src="<?php echo htmlspecialchars($country['flag']); ?>" alt="">
                    <?php echo htmlspecialchars($country['shortCode']); ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
            <input type="text" class="form-control h-100" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;" id="mobile" placeholder="Mobile Number">
          </div>
        </div>
        <div class="col-md-12 mb-3">
          <label for="message" class="form-label cont-w600">Message :</label>
          <textarea class="form-control" id="message" placeholder="Type your message here…" style="height: 100px;" rows="3"></textarea>
        </div>
        <div class="">
          <button class="btn btn-outline-warning rounded-pill card-b px-4 card-b">Submit</button>
        </div>
      </form>
      <div class="col-lg-4 row wow fadeInUp p-3 align-items-center">
        <div class="">
          <h2 class="head-title cont-w600">Contact Us</h2>
          <p class="card-cont"><img height="25px" class="me-2" src="images/svg/Location.svg" alt=""><B>SHREE FinTech 
            Solution,<br />
            Corporate Office:-<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Shop-2,Mani Estate,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PL-4, 2/10 Mavdi Plot,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mavdi,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rajkot, Gujarat-360004
            <br />
            Register Office:-<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mani Estate,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  2/11 Mavdi Plot,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mavdi Plot Road,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rajkot, Gujarat-360004</B></p>
          <p class="card-cont"><img height="25px" class="me-2" src="images/svg/call.svg" alt="">+91 75675 25557</p>
          <p class="card-cont"><img height="25px" class="me-2" src="images/svg/Email.svg" alt=""><a href="">admin@paytouch.in</a></p>
          <p class="card-cont"><img height="25px" class="me-2" src="images/svg/Whatsapp.svg" alt="">+91 75675 25557</p>
          <h4 class="mb-3 head-title cont-w600">Stay Connected With Us</h4>
          <div class="d-flex gap-3 justify-content-left ft-social">
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fa-brands fa-x-twitter fa-xl" style="color: #171b20;"></i>
            </a>
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fab fa-facebook-f fa-xl" style="color: #1877F2;"></i>
            </a>
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fab fa-instagram fa-xl"></i>
            </a>
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fab fa-youtube fa-xl" style="color: #FF0000;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Contact Us section -->

  <!-- map -->
  <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7384.689068807199!2d70.78949059476926!3d22.264935097787948!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959ca60a24529d5%3A0x26cf74e972c212!2sUmiya%20Chowk%2C%20Rajkot%2C%20Gujarat%20360004!5e0!3m2!1sen!2sin!4v1713520901713!5m2!1sen!2sin" width="100%" height="350" style="border:0;" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="wow  fadeIn"></iframe>-->
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3692.0511845815927!2d70.79545256190187!3d22.27605086576737!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959cb2b1ba8c1af%3A0xadded1cf7549be6c!2sMahavir%20Chowk!5e0!3m2!1sen!2sin!4v1740033839018!5m2!1sen!2sin" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="wow  fadeIn"></iframe>
  <!-- map -->

  <!-- Footer section -->
  <div id="Footer" class="py-5 bg-lightorange">
    <div class="container d-flex text-red gap-4">
      <div class="row justify-content-between">
        <div class="col-lg-3 col-md-6 col-sm-12 col-12 mb-3  wow slideInLeft">
          <div class=" ft-cn ">
            <img class="navbar-brand p-0 wow slideInLeft" height="65px" src="images\Adobe Express - file.png" />
            <h5 class="wow  fadeInRightBig"><span class="unikpay">SHREE</span> <span class="text-blue">FinTech Solutions Private Limited</span> </h5>
            </br>
            <p class="card-text"><span class="unikpay">SHREE</span> <span class="text-red">FinTech Solutions Private Limited</span>
              is India’s one of the leading recharge & bill payment site, designed
              for customers convenience. We are aimed to serve you in such way that you don’t find need to go in hustle
              and stand in a long queue for your prepaid recharge & bill payments</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 col-12 mb-3 wow slideInLeft">
          <div class="">
            <h3 class="head-title text-red">USEFUL LINKS</h3>
            </br>
            <li class="card-text mb-2"><a class="nav-link <?= getActiveClass('') ?> px-3" href="?section=#"><i class="fa fa-home me-2"></i>Home</a></li>
            <li class="card-text mb-2"><a class="nav-link <?= getActiveClass('services') ?> px-3" href="?section=services#services"><i class="fa fa-cog me-2"></i>About Us</a></li>
            <li class="card-text mb-2"><a href="https://www.paytouch.in/data-retention-policies.html">Data Retention policy</a></li>
            <li class="card-text mb-2"><a href="https://www.paytouch.in/privacy-policy.html">Privacy policy</a></li>
            <li class="card-text mb-2"><a href="https://www.paytouch.in/termsandcondition.html">Terms & Conditions</a> </li>
            <li class="card-text mb-2"><a href="https://www.paytouch.in/refund-policy.html">Refund and cancel policy</a></li>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 col-12 mb-3 wow slideInRight">
          <div class="ft-cn">
            <h3 class="head-title text-red">Social Media's</h3>
            </br>
            <h4 class="mb-3 head-title cont-w600">Stay Connected With Us</h4>
          <div class="d-flex gap-3 justify-content-left ft-social">
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fa-brands fa-x-twitter fa-xl" style="color: #171b20;"></i>
            </a>
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fab fa-facebook-f fa-xl" style="color: #1877F2;"></i>
            </a>
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fab fa-instagram fa-xl"></i>
            </a>
            <a class="bg-white rounded-circle d-flex justify-content-center align-items-center" href="#">
              <i class="fab fa-youtube fa-xl" style="color: #FF0000;"></i>
            </a>
          </div>
        
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-dark text-white py-3">
    <div class="container d-flex justify-content-start h-100">
      <h6 class="card-text my-auto wow slideInLeft">© 2024 Copyright SHREE FinTech Solutions Private Limited. All Rights Reserved 2025.</h6>
    </div>
  </div>
  <!-- Footer section -->


  <!-- Login Modal -->

  <div class="modal fade" id="loginModal" aria-labelledby="loginModalLabel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <img src="images\logotrans.png" height="50" width="50" class="rounded" />
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="main_div my-5 mx-4 bg-white">
            <form id="one" method="POST" action="utilityuser/utilityuser/authentication2.php">
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="name" name="username1" class="form-control form-control-lg" pattern="[A-Za-z]+" required />
                <label class="form-label" for="name">Username</label>
              </div>
              <div data-mdb-input-init class="form-outline mb-2">
                <input class="form-control form-control-lg" type="password" id="pass" name="password1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" minlength="8" maxlength="12" required />
                <label class="form-label" for="pass">Password</label>

              </div>
              <div class="mb-4 d-flex">
                <input type="checkbox" id="showPassword" />
                <label for="showPassword" class="ms-2">show paasword</label>
              </div>
              <div class="align-item-center d-flex justify-content-center gap-4 mt-3">
                <div class="inputbox">
                  <button type="submit" href="utilityuser/login.php" class="btn btn-outline-warning rounded-pill colorh2">Login</button>
                </div>
                
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Modal -->

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
  <script>
    new WOW().init();
  </script>

  <!-- Hide & Show Password -->
  <script>
    var passwordField = document.getElementById("pass");
    var showPasswordCheckbox = document.getElementById("showPassword");

    showPasswordCheckbox.addEventListener("change", function() {
      if (showPasswordCheckbox.checked) {
        passwordField.type = "text"
      } else {
        passwordField.type = "password";
      }
    });
  </script>
  <!-- Hide & Show Password -->
   
  <script>
    // JavaScript to handle dropdown selection and input changes
    document.addEventListener('DOMContentLoaded', (event) => {
      const countryItems = document.querySelectorAll('.country-item');
      const selectedCountryButton = document.getElementById('selected-country');
      const mobileInput = document.getElementById('mobile');
      const defaultCountryCode = '+91'; // Country code for India

      // Function to update selected country based on input value
      function updateSelectedCountry(code) {
        let found = false;
        countryItems.forEach(item => {
          const itemCode = item.getAttribute('data-code');
          const name = item.getAttribute('data-name');
          if (code === itemCode) {
            const flag = item.getAttribute('data-flag');
            const shortCode = item.getAttribute('data-shortcode');
            selectedCountryButton.textContent = `${shortCode}`;
            selectedCountryButton.innerHTML += `<img class="flags mx-2 rounded" src="${flag}" alt="">`;
            found = true;
            return; // Exit loop early
          }
        });

        if (!found) {
          selectedCountryButton.textContent = 'Country not available';
          selectedCountryButton.innerHTML = 'Unavailable';
        }
      }

      // Function to set India as default
      function setDefaultCountry() {
        updateSelectedCountry(defaultCountryCode);
        mobileInput.value = defaultCountryCode;
      }

      // Event listener for dropdown item clicks
      countryItems.forEach(item => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          const code = item.getAttribute('data-code');
          const flag = item.getAttribute('data-flag');
          const shortCode = item.getAttribute('data-shortcode');
          selectedCountryButton.textContent = `${shortCode} `;
          selectedCountryButton.innerHTML += `<img class="flags mx-2 rounded" src="${flag}" alt="">`;
          mobileInput.value = code;
        });
      });

      // Event listener for input changes
      mobileInput.addEventListener('input', (e) => {
        const inputValue = e.target.value.trim();
        updateSelectedCountry(inputValue);
      });

      // Set India as default when page loads
      setDefaultCountry();
    });
  </script>


  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>