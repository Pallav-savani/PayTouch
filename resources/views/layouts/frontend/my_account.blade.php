 @include('layouts.header')


<div class="service-card card mt-3">
    <div class="card-body">
        <div class="d-flex align-items-start user-service-tab">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                 
                <button class="nav-link active" id="v-pills-account-info-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-account-info" type="button" role="tab" aria-controls="v-pills-account-info"
                    aria-selected="true">Account Info</button> 
                <button class="nav-link" id="v-pills-complaint-register-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-complaint-register" type="button" role="tab"
                    aria-controls="v-pills-complaint-register" aria-selected="true">Complaint Register</button>
                <button class="nav-link" id="v-pills-complaint-status-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-complaint-status" type="button" role="tab"
                    aria-controls="v-pills-complaint-status" aria-selected="true">Complaint Status</button>
                <button class="nav-link" id="v-pills-redeem-discount-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-redeem-discount" type="button" role="tab"
                    aria-controls="v-pills-redeem-discount" aria-selected="true">Redeem Discount</button>
            </div>


            <div class="tab-content" id="v-pills-tabContent">
 
                <!-- account info -->
                <div class="tab-pane fade active" id="v-pills-account-info" role="tabpanel"
                    aria-labelledby="v-pills-account-info-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>MY ACCOUNT</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <div class="row justify-content-center mt-2">
                                <form class="row form-horizontal" method="post" action="#"
                                    style="border-style: groove; margin-bottom:5px;">
                                    <label for="Member ID" class="col-md-2 col-form-label fw-bold">Member ID</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member iD" value="">66</span>
                                    </div>
                                    <label for="Member No" class="col-md-2 col-form-label fw-bold">Member No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member no" value="">100002</span>
                                    </div>
                                    <label for="Member Code" class="col-md-2 col-form-label fw-bold">Member Code</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member code" value="">rt54</span>
                                    </div>
                                    <label for="Mobile No" class="col-md-2 col-form-label fw-bold">Mobile No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member no" value="">1234567890</span>
                                    </div>
                                    <label for="Firm Name" class="col-md-2 col-form-label fw-bold">Firm Name</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="firm name" value="">SHREE DOCS</span>
                                    </div>
                                    <label for="Member Name" class="col-md-2 col-form-label fw-bold">Member Name</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="member name" value="">Kishanbhai
                                            Gujara</span>
                                    </div>
                                    <label for="Birth Date" class="col-md-2 col-form-label fw-bold">Birth Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="birth date" value="">01/01/1998</span>
                                    </div>
                                    <label for="Age" class="col-md-2 col-form-label fw-bold">Age</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="age" value="">26</span>
                                    </div>
                                    <label for="Firm Address" class="col-md-2 col-form-label fw-bold">Firm
                                        Address</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="firm address" value="">Ring Road, Patel
                                            Vihar, Rajkot</span>
                                    </div>
                                    <label for="Home Address" class="col-md-2 col-form-label fw-bold">Home
                                        Address</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="home address" value="">Vraj Villa, 186,
                                            chandra park, Near Big Bazar, 150 feet Ring Road, Rajkot - 360005</span>
                                    </div>
                                    <label for="City Name" class="col-md-2 col-form-label fw-bold">City Name</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="city name" value="">RAJKOT [Taluka:
                                            RAJKOT, District: RAJKOT, State: GUJARAT]</span>
                                    </div>
                                    <label for="Email" class="col-md-2 col-form-label fw-bold">Email</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="email"
                                            value="">shreehari84@gmail.com</span>
                                    </div>
                                    <label for="Status" class="col-md-2 col-form-label fw-bold">Status</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" style="color:green;" id="status"
                                            value="">Active</span>
                                    </div>
                                    <label for="Discount Pattern" class="col-md-2 col-form-label fw-bold">Discount
                                        Pattern</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="discount pattern" value="">DEMO
                                            RT(0%)</span>
                                    </div>
                                    <label for="Pan Card No" class="col-md-2 col-form-label fw-bold">Pan Card No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="pan card no" value="">alrpk5822g</span>
                                    </div>
                                    <label for="Aadhhar No" class="col-md-2 col-form-label fw-bold">Aadhhar No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="aadhhar no"
                                            value="">691178081688</span>
                                    </div>
                                    <label for="GST No" class="col-md-2 col-form-label fw-bold">GST No</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="GST no" value=""></span>
                                    </div>
                                    <label for="Registration Date" class="col-md-2 col-form-label fw-bold">Registration
                                        Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="registration date" value="">31/01/2024
                                            6:32:28 PM</span>
                                    </div>
                                    <label for="Activation Date" class="col-md-2 col-form-label fw-bold">Activation
                                        Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="activation date" value="">Not
                                            Activated</span>
                                    </div>
                                    <label for="Password Change Date" class="col-md-2 col-form-label fw-bold">Password
                                        Change Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="password change date" value="">-</span>
                                    </div>
                                    <label for="Last Topup Date" class="col-md-2 col-form-label fw-bold">Last Topup
                                        Date</label>
                                    <div class="col-sm-4">
                                        <span class="form-control-plaintext" id="last topup date" value="">09/05/2024
                                            3:56:09 PM</span>
                                    </div>
                                    <label for="Balance" class="col-md-2 col-form-label fw-bold">Balance</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="balance" value="">50701.97 [ Rupees
                                            Fifty Thousand Seven Hundred One and Paise Ninety Seven Only]</span>
                                    </div>
                                    <label for="DMR Balance" class="col-md-2 col-form-label fw-bold">DMR Balance</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="DMR balance" value="">N/A</span>
                                    </div>
                                    <label for="Discount" class="col-md-2 col-form-label fw-bold">Discount</label>
                                    <div class="col-sm-10">
                                        <span class="form-control-plaintext" id="discount" value="">0.00 [ Rupees
                                            Only]</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- account info --> 

                <!-- Complaint Register -->
                <div class="tab-pane fade" id="v-pills-complaint-register" role="tabpanel"
                    aria-labelledby="v-pills-complaint-register-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>REGISTER COMPLAIN</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <form class="row justify-content-center mt-2" method="post" action="#"
                                style="border-style: groove; margin-bottom:5px;">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Complaint Type</label>
                                    <select type="text" class="form-select" required>
                                        <option selected="selected" value="0">-- Select State --</option>
                                        <option value="MOBILE">MOBILE</option>
                                        <option value="DTH">DTH</option>
                                        <option value="BILL PAY">BILL PAY</option>
                                        <option value="BUS">BUS</option>
                                        <option value="RAILWAY">RAILWAY</option>
                                        <option value="CINEMA">CINEMA</option>
                                        <option value="DMR">DMR</option>
                                        <option value="SERVICE">SERVICE</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" Placeholder="subject" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label class="form-label">Error Screenshot</label>
                                    <input type="file" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="cancel" class="btn btn-primary w-25">Submit</button>
                                    <button type="cancel" class="btn btn-success w-25">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- Complaint Register -->

                <!-- Complaint Status -->
                <div class="tab-pane fade" id="v-pills-complaint-status" role="tabpanel"
                    aria-labelledby="v-pills-complaint-status-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>COMPLAIN STATUS</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <form class="row justify-content-center mt-2" method="post" action="#"
                                style="border-style: groove; margin-bottom:5px;">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Subject</label>
                                    <input type="text" class="form-control" Placeholder="subject" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Complain Date</label>
                                    <input type="date" class="form-control" Placeholder="subject" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Status</label>
                                    <select type="text" class="form-select" required>
                                        <option selected="selected" value="0">-- Select State --</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Under Process">Under Process</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="cancel" class="btn btn-primary w-25">Show</button>
                                    <button type="cancel" class="btn btn-success w-25">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- Complaint Status -->

                <!-- Redeem Discount -->
                <div class="tab-pane fade" id="v-pills-redeem-discount" role="tabpanel"
                    aria-labelledby="v-pills-redeem-discount-tab" tabindex="0">
                    <section class="myaccount">
                        <div class="container">
                            <div class="row formobile">
                                <h4>REDEEM Discount</h4>
                            </div>
                        </div>
                    </section>
                    <section class="acc-detail">
                        <div class="container">
                            <form class="row justify-content-center mt-2" method="post" action="#"
                                style="border-style: groove; margin-bottom:5px;">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Available</label>
                                    <input type="text" class="form-control" Placeholder="Available" value="0" readonly>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Redeem</label>
                                    <input type="text" class="form-control" Placeholder="redeem" value="0" readonly>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <button type="cancel" class="btn btn-primary">Submit</button>
                                    <button type="cancel" class="btn btn-success">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- Redeem Discount -->

            </div>

        </div>
    </div>
</div>
 