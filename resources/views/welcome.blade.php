<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome to PayTouch - Web App</title>
        <!-- Fonts -->

    </head>
<body>
    @include('layouts.app');

        <section class="content-wrapper mb-4">
        <div class="container">
            <div class="user">
                <div class="row mt-4 justify-content-center">
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/DTH.png" alt="DTH image"
                                    style="height:50px" />
                                <a class="text-white" href="{{ route('dth') }}">
                                    <h5 class="card-title mt-2 fw-bold">DTH</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/recharge.png" alt="Mobile Recharge image"
                                    style="height:50px" />
                                <a class="text-white" href="{{ route('mobile') }}">
                                    <h5 class="card-title mt-2 fw-bold">Mobile Recharge</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/UBILL.png" alt="UBILL image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/utilityBills.php">
                                    <h5 class="card-title mt-2 fw-bold">UTILITY BILLS</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/REPORTS.png" alt="Reports image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/reports.php">
                                    <h5 class="card-title mt-2 fw-bold">REPORTS</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/MYACCOUNT.png" alt="My Account image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/myAccount.php">
                                    <h5 class="card-title mt-2 fw-bold">MY ACCOUNT</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/PSDMR.png" alt="PS DMR image"-->
                    <!--                style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/psDmr.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">PS DMR</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/CCBILLPAY.png" alt="CC Bill Pay image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/ccBillPay.php">
                                    <h5 class="card-title mt-2 fw-bold">CC BILL PAY</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/INSTANTPAY.png"-->
                    <!--                alt="InstantPay AEPS image" style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/instantPayAeps.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">INSTENTPAY APES</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/LOADWALLET.png" alt="Load Wallet image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/loadWallet.php">
                                    <h5 class="card-title mt-2 fw-bold">LOAD WALLET</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/QUIKPAYOUT.png" alt="Quick Payout image"-->
                    <!--                style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/quickPayout.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">QUICK PAYOUT</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/PAYOUT.png" alt="Payout image"-->
                    <!--                style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/payOut.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">PAYOUT</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/pos.png" alt="POS image"-->
                    <!--                style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/pos.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">POS</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/flight.png" alt="Flight Booking image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/flight.php">
                                    <h5 class="card-title mt-2 fw-bold">Flight Booking</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/railway.png" alt="Railway Booking image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/railway.php">
                                    <h5 class="card-title mt-2 fw-bold">Railway Booking</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/hotel.png" alt="Hotel Booking image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/hotel.php">
                                    <h5 class="card-title mt-2 fw-bold">Hotel Booking</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/bus.png" alt="BUS Booking image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/bus.php">
                                    <h5 class="card-title mt-2 fw-bold">BUS Booking</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/cmr.png" alt="CMR image"-->
                    <!--                style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/cmr.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">CMR</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-lg-4 mb-4 col-sm-6 ">-->
                    <!--    <div-->
                    <!--        class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">-->
                    <!--        <div class="card-body text-center">-->
                    <!--            <img class="invert1" src="../images/img/cms.png" alt="CMS image"-->
                    <!--                style="height:50px" />-->
                    <!--            <a class="text-white" href="Pages/cms.php">-->
                    <!--                <h5 class="card-title mt-2 fw-bold">CMS</h5>-->
                    <!--            </a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/fastag.png" alt="FASTag Recharge image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/fastag.php">
                                    <h5 class="card-title mt-2 fw-bold">FASTag Recharge</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div
                            class="card card-shine position-relative overflow-hidden gradient-bg border border-primary">
                            <div class="card-body text-center">
                                <img class="invert1" src="../images/img/insurance.png" alt="LIC image"
                                    style="height:50px" />
                                <a class="text-white" href="Pages/lic.php">
                                    <h5 class="card-title mt-2 fw-bold">LIC</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-4 mb-4 col-sm-6 ">
                        <div class=" ">
                            <div class="card-body">
                                <img class="invert1" src="{{ asset('images/bbps.jpg') }}" alt="BBPS image"
                                    style="height:135px "/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
