<div class="tab-pane fade" id="v-pills-cc-complaint" role="tabpanel"
    aria-labelledby="v-pills-cc-complaint-tab" tabindex="0">
    <div class="container-fluid">
        <div class="row mt-4 align-items-center justify-content-between">

            <!-- Complaint Form Column (Left Side) -->
            <div class="col-md-4">
                <h2 class="mb-4 formobile">Complaint Registration</h2>

                <!-- Option 1 -->
                <form class="mb-4">
                    <div class="mb-3 row">
                        <label for="mNumber" class="form-label">Mobile No:</label>
                        <input type="text" class="form-control" id="mNumber" name="mn" placeholder="Mobile Number" required>
                    </div>
                    <div class="mb-3 row">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Complaint</button>
                </form>

                <!-- Option 2 -->
                <h4 class="mb-4  ">Option 2: Transaction Reference</h4>
                <form class="mb-4">
                    <div class="mb-3">
                        <label for="txnRef" class="form-label">Transaction Ref ID:</label>
                        <input type="text" class="form-control" id="txnRef" placeholder="Transaction Ref ID">
                    </div>
                    <button type="submit" class="btn btn-primary">Register Complaint</button>
                </form>
            </div>

            <!-- Logo Column (Right Side) -->
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <img src="images/bbps.jpg" alt="BBPS Logo" style="width: 150px;" class="img-fluid" />
            </div>

        </div>
    </div>
</div>
