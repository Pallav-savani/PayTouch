  @include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utility Bills</title>
    <link rel="stylesheet" href="/css/utility.css">
</head>
<body>
  <div class="service-card card mt-3">
    <div class="card-body">
        <div class="d-flex align-items-start user-service-tab">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-billpay-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-billpay" type="button" role="tab" aria-controls="v-pills-billpay"
                    aria-selected="true">Bill Payment</button>
                <button class="nav-link" id="v-pills-payment-status-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-payment-status" type="button" role="tab"
                    aria-controls="v-pills-payment-status" aria-selected="false">Payment Status</button>
                <button class="nav-link" id="v-pills-payment-reports-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-payment-reports" type="button" role="tab"
                    aria-controls="v-pills-payment-reports" aria-selected="false">Payment Reports</button>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Bill Payment Tab -->
                <div class="tab-pane fade show active" id="v-pills-billpay" role="tabpanel"
                    aria-labelledby="v-pills-billpay-tab" tabindex="0">
                    <div class="container-fluid pad">
                        <div class="row martop d-flex">
                            <div id="entry" class="col-md-4">
                                <div class="row formobile">
                                    <h4>Bill Payment</h4>
                                </div>
                                <div class="row service">
                                    <div id="divService" class="martop10">
                                        <form method="post">
                                            <div class="mb-3">
                                                <label class="form-label">Select Service</label>
                                                <select name="service" id="cmbService" class="form-select operator" required onchange="handleServiceChange(this.value)">
                                                    <option value="">-- Select Service --</option>
                                                    <option value="MobileBill">Mobile Bill</option>
                                                    <option value="GasBill">Gas Bill</option>
                                                    <option value="InternetBill">Internet Bill</option>
                                                    <option value="ElectricityBill">Electricity Bill</option>
                                                    <option value="WaterBill">Water Bill</option>
                                                    <option value="EMI">EMI</option>
                                                    <option value="PostpaidBill">Postpaid Bill</option>
                                                    <option value="EducationFees">Education Fees</option>
                                                    <option value="CableTV">Cable TV</option>
                                                    <option value="MunicipalBill">Municipal Bill</option>
                                                    <option value="LPGGas">LPG Gas</option>
                                                    <option value="CreditCard">Credit Card</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Sub-Service</label>
                                                <select name="subservice" id="cmbSubService" class="form-select sub-service" onchange="handleSubServiceChange()" required>
                                                    <option value="">-- Select Sub-Service --</option>
                                                </select>
                                            </div>

                                            <!-- Mobile Bill Fields -->
                                            <div id="MobileBillFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile Number</label>
                                                    <input type="text" id="mobile" name="mobile_number" class="form-control"   placeholder="Enter the Mobile Number">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" id="amount" name="mobile_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Gas Bill Fields -->
                                            <div id="GasBillFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Customer ID</label>
                                                    <input type="text" id="mobile" name="gas_mobile_number" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" id="amount" name="gas_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Internet Bill Fields -->
                                            <div id="InternetBillFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Customer ID</label>
                                                    <input type="text" id="mobile" name="internet_mobile_number" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" id="amount" name="internet_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Electricity Bill Fields -->
                                            <div id="ElectricityBillFields" class="service-fields d-none">
                                            <div class="mb-3">
                                                    <label class="form-label">Customer Number</label>
                                                    <input type="text" id="mobile" name="electricity_consumer_number" class="form-control">
                                                </div>    
                                            <div class="mb-3">
                                                    <label class="form-label">Mobile Number</label>
                                                    <input type="text" id="mobile" name="electricity_mobile_number" class="form-control"   placeholder="Enter the Amount">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" id="amount" name="electricity_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Water Bill Fields -->
                                            <div id="WaterBillFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="number" name="water_mobile_number" class="form-control" maxlength="10" pattern="\d{10}" placeholder="Enter the Consumer Number">
                                                </div> 
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="number" name="water_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- EMI Fields -->
                                            <div id="EMIFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="number" name="emi_mobile_number" class="form-control" maxlength="10" pattern="\d{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="number" name="emi_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Postpaid Bill Fields -->
                                            <div id="PostpaidBillFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="number" name="postpaid_mobile_number" class="form-control" maxlength="10" pattern="\d{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="number" name="postpaid_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Education Fees Fields -->
                                            <div id="EducationFeesFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="number" name="education_mobile_number" class="form-control" maxlength="10" pattern="\d{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="number" name="education_amount" class="form-control" placeholder="Entert the Amount">
                                                </div>
                                            </div>

                                            <!-- Cable TV Fields -->
                                            <div id="CableTVFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="text" name="cabletv_mobile_number" class="form-control" maxlength="10" pattern="\d{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" name="cabletv_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Municipal Bill Fields -->
                                            <div id="MunicipalBillFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="number" name="municipal_mobile_number" class="form-control" maxlength="10" pattern="\d{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" name="municipal_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- LPG Gas Fields -->
                                            <div id="LPGGasFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="text" name="lpggas_mobile_number" class="form-control" maxlength="10" pattern="\d{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" name="lpggas_amount" class="form-control" placeholder="Enter the Amount">
                                                </div>
                                            </div>

                                            <!-- Credit Card Fields -->
                                            <div id="CreditCardFields" class="service-fields d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Consumer Number</label>
                                                    <input type="text" name="creditcard_mobile_number" class="form-control" maxlength="10" pattern="\d{10}" placeholder="">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="text" name="creditcard_amount" class="form-control">
                                                </div>
                                            </div>

                                            <button type="submit" name="submit" class="col-12 btn gradient-bg mt-2 text-white">Verify Bill</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="img-cent col-md-2">
                                <img class="img-fluid" src="images/bbps.jpg" width="140px" alt="Payment Gateway Logo">
                            </div>

                            <!-- Bill Payments Table -->
                            <div class="col-md-12 my-3">
                                <div id="divLastTrn">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Trn No</th>
                                                <th>Trn Date</th>
                                                <th>Service Name</th>
                                                <th>Mobile No</th>
                                                <th>Amount</th>
                                                <th>Transaction ID</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status Tab -->
                <div class="tab-pane fade" id="v-pills-payment-status" role="tabpanel" aria-labelledby="v-pills-payment-status-tab" tabindex="0">
                    <div class="container">
                        <div class="row">
                            <div id="entry" class="col-md-12">
                                <div class="formobile">
                                    <h4>TRANSACTION STATUS</h4>
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <form class="form px-4 py-2" method="post" action="" style="border-style: groove;">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Mobile No:</label>
                                        <input type="text" name="check_mobile_number" class="form-control" placeholder="Enter the Mobile Number" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Transaction ID:</label>
                                        <input type="text" name="check_transaction_id" class="form-control" placeholder="Enter Transaction ID" required>
                                    </div>
                                    <button type="submit" name="check_status" class="btn btn-primary col-md-2">Check Status</button>
                                    <button type="reset" class="btn btn-success col-md-2">Reset</button>
                                </form>
                            </div>
                            <!-- Display Transaction Status -->
                            <div class="col-md-12 my-3">
                                <?php if (isset($_POST['check_status']) && $status_result): ?>
                                    <?php if (mysqli_num_rows($status_result) > 0): ?>
                                        <h5>Transaction Details</h5>
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Trn No</th>
                                                    <th>Trn Date</th>
                                                    <th>Service Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Amount</th>
                                                    <th>Transaction ID</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_array($status_result)): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['service']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p class="text-danger">No transaction found for the provided Mobile Number and Transaction ID.</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

             

        <!-- Payment Reports Tab -->
        <div class="tab-pane fade" id="v-pills-payment-reports" role="tabpanel" aria-labelledby="v-pills-payment-reports-tab" tabindex="0">
            <div class="container pad">
                <div class="row martop">
                    <div id="entry" class="col-md-12" style="text-align: center;">
                        <div class="formobile">
                            <h4>TRANSACTION REPORT</h4>
                        </div>
                    </div>
                    <div class="col-md-12 my-2">
                        <form class="form px-4 py-2" method="post" action="" style="border-style: groove;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Service:</label>
                                <select name="service" id="cmbService" class="form-select">
                                    <option value="">-- All Services --</option>
                                    <option value="MobileBill">Mobile Bill</option>
                                    <option value="GasBill">Gas Bill</option>
                                    <option value="InternetBill">Internet Bill</option>
                                    <option value="ElectricityBill">Electricity Bill</option>
                                    <option value="WaterBill">Water Bill</option>
                                    <option value="EMI">EMI</option>
                                    <option value="PostpaidBill">Postpaid Bill</option>
                                    <option value="EducationFees">Education Fees</option>
                                    <option value="CableTV">Cable TV</option>
                                    <option value="MunicipalBill">Municipal Bill</option>
                                    <option value="LPGGas">LPG Gas</option>
                                    <option value="CreditCard">Credit Card</option>
                                    <!-- Add more specific services as needed -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <select name="status" id="cmbStatus" class="form-select">
                                    <option value="">-- All Statuses --</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Success">Success</option>
                                    <option value="Failed">Failed</option>
                                    <option value="Refund">Refund</option>
                                    <option value="Hold">Hold</option>
                                </select>
                            </div>
                            <div class="d-flex">
                                <div class="mb-3 col-md-3 me-5">
                                    <label class="form-label fw-bold">From:</label>
                                    <input type="date" name="from_date" class="form-control" required>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">To:</label>
                                    <input type="date" name="to_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mobile No:</label>
                                <input type="text" name="customer_no" class="form-control" placeholder="Mobile No">
                            </div>
                            <button type="submit" name="show_report" class="btn btn-primary col-md-2">Show</button>
                            <button type="reset" class="btn btn-success col-md-2">Cancel</button>
                        </form>
                    </div>
                    <!-- Display Transaction Report -->
                    <div class="col-md-12 my-3">
                        <?php
                        if (isset($_POST['show_report'])) {
                            $service = $_POST['service'] ?? '';
                            $status = $_POST['status'] ?? '';
                            $from_date = $_POST['from_date'] ?? '';
                            $to_date = $_POST['to_date'] ?? '';
                            $customer_no = $_POST['customer_no'] ?? '';

                            // Build the SQL query dynamically
                            $query = "SELECT * FROM bill_payments WHERE 1=1";
                            $params = [];
                            $types = '';

                            if (!empty($service)) {
                                $query .= " AND service = ?";
                                $params[] = $service;
                                $types .= 's';
                            }
                            if (!empty($status)) {
                                $query .= " AND status = ?";
                                $params[] = $status;
                                $types .= 's';
                            }
                            if (!empty($from_date)) {
                                $query .= " AND DATE(created_at) >= ?";
                                $params[] = $from_date;
                                $types .= 's';
                            }
                            if (!empty($to_date)) {
                                $query .= " AND DATE(created_at) <= ?";
                                $params[] = $to_date;
                                $types .= 's';
                            }
                            if (!empty($customer_no)) {
                                $query .= " AND mobile_number = ?";
                                $params[] = $customer_no;
                                $types .= 's';
                            }

                            $query .= " ORDER BY created_at Asc";

                            // Prepare and execute the query
                            $stmt = $con->prepare($query);
                            if (!empty($params)) {
                                $stmt->bind_param($types, ...$params);
                            }
                            $stmt->execute();
                            $report_result = $stmt->get_result();

                            if (mysqli_num_rows($report_result) > 0) {
                                echo '<h5>Transaction Report</h5>';
                                echo '<table class="table table-bordered table-striped table-hover">';
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th>Trn No</th>';
                                echo '<th>Trn Date</th>';
                                echo '<th>Service Name</th>';
                                echo '<th>Mobile No</th>';
                                echo '<th>Amount</th>';
                                echo '<th>Transaction ID</th>';
                                echo '<th>Status</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                while ($row = mysqli_fetch_array($report_result)) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['service']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['transaction_id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';
                            } else {
                                echo '<p class="text-danger">No transactions found for the selected criteria.</p>';
                            }
                            $stmt->close();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
 

<script>
const subservices = {
MobileBill:[
    "Airtel", "Airtel Payments Bank NETC FASTag", "AU Bank Fastag", "Axis Bank", "Bandhan Bank Fastag", "Bank Of Baroda", "Bank of Maharashtra FASTag", "Bajaj Finance Limited Fastag", "BSNL",
    "Canara Bank Fastag", "Equitas FASTag Recharge", "Federal Bank - FASTag", "HDFC Bank", "ICICI Bank", "IDBI Bank FASTag", "IDFC FIRST Bank - FasTag", "Indian Bank Fastag", "Indian Highways Management Company Ltd-Indusind FASTag",
    "Indusind Bank", "IOB FASTag", "Jammu and Kashmir Bank Fastag", "Jio", "Jio Fi (Not Required)", "Karnataka Bank Fastag", "Kotak Mahindra Bank", "Livquik Technology India Private Limited",
    "MTNL", "Paul Merchants ( Not Required)", "South Indian Bank Fastag", "State Bank of India FASTag", "Transcorp International Limited", "Transaction Analyst FASTag(Not Required)", "UCO Bank FASTag",
    "Union Bank of India FASTag", "VI", "Yes Bank FASTag"
],
GasBill: [
  "Aavantika Gas Ltd", "Adani Gas", "AGP CGD India Pvt Ltd", "AGP City Gas Pvt Ltd", "Assam Gas Company Limited", "Bhagyanagar Gas Limited", "Bengal Gas Company Limited", "Central U.P. Gas Limited",
  "Charotar Gas Sahakari Mandali Ltd", "Gail Gas Limited", "GAIL India", "GasBill", "Goa Natural Gas", "Godavari Gas Pvt Ltd", "Green Gas Limited(GGL)", "Gujarat Gas Company Limited",
  "HP Oil Gas Private Limited", "Haryana City Gas", "Haryana City Gas Distribution Bhiwadi Ltd", "Hindustan Petroleum Corporation Ltd-Piped Gas", "Indian Oil Corporation Ltd-Piped Gas",
  "Indian Oil-Adani Gas Private Limited", "Indraprastha Gas", "IRM Energy Private Limited", "Mahanagar Gas Limited", "Maharashtra Natural Gas Limited", "Megha Gas", "Naveriya Gas Pvt Ltd",
  "Purba Bharati Gas Pvt Ltd", "Rajasthan State Gas Limited", "Sabarmati Gas Limited (SGL)", "Think Gas Pvt Ltd", "Torrent Gas", "Tripura Natural Gas", "Unique Central Piped Gases Pvt Ltd (UCPGPL)",
  "Vadodara Gas Limited"
],
InternetBill:[
  "777 Network Broadband", "AAA Internet Services Pvt Ltd", "ACT BroadBand", "AIR Internet", "AITSPL", "ANI Broadband", "ANI Network Pvt Ltd", "ANU Broadband", "APB Broadband", "Aeronet Online Services Private Limited",
  "Air Internet", "AirConnect", "AirJaldi - Rural Broadband", "AirServices", "Airgenie Communications", "Airnet Networks", "Airtel Broadband", "Alliance Broadband Services Pvt. Ltd.",
  "Anupama Cable And Internet Service", "Aparna Star TV Network", "Apsara Communications", "ASIANET Broadband (ASIANET) (Not Required)", "ATSPL", "Auriga", "BACBPL", "B Fibernet", "BCN Digital",
  "B-Fi Networks", "BSNL Broadband", "BSB Network", "Balaji Broadband", "Baroda Broadband", "Benvar", "Bharti Hexacom Limited", "Bhima Riddhi Broadband Private Limited", "Bijis Internet Private Limited",
  "Broadband 24X7","CATLA Broadband", "CNC Broadband", "Charotar Broadband", "Chakdaha Cable And Broadband Pvt Ltd", "Cherrinet", "Clicknet Communication", "Cloudlasers Broadband", "Cloudsky Superfast Broadband & Services Pvt Ltd",
  "Comcast Broadband Services", "Comway Broadband", "Compliance Broadband (CBPL)", "Confiar Broadband", "Confiar Partner (Not Required)", "Correl Internet", "Crystalclear Network", "Cyber Broadband",
  "DVPL BB", "DSCN", "DHL Fibernet", "DVR Broadband Services", "Daksh Telecom", "Dainik Savera Net", "Deco Broadband", "Den Broadband", "Deshkal Networks", "Digital World", "Digiway Net",
  "Dreamnet Gigafiber", "DWAN Supports Private Ltd", "Easy Net", "Earthlink Net", "Ecreado Network Solutions Private Limited", "Eknath","ELL Fibernet", "Esto Broadband Private Ltd", "Esto Media Private Limited",
  "Ethernet Xpress", "Excell Broadband", "Express Wire", "Extreme Broadband", "FABNET", "FiberX", "Ficus Telecom Pvt Ltd", "Flash Fibernet", "Frontline Internet Services", "Future Connect Broadband",
  "Future Netsanchar Limited", "Fusionnet Web Services Private Limited", "GEFO Fibernet", "GRB infotech", "GSR Broadband", "GTPL KCBPL Broadband Pvt Ltd", "Galactic Internet", "Galaxynet",
  "Gangotri Telenet Pvt Ltd", "Garuda Groups", "Gateway Networks", "GenZ Broadband", "Gloriosa Infotel", "Globnet Broadband", "Goodwill Broadband", "Grey Sky Internet", "Gulbarga Mega Speed",
  "Hathway", "Hasten", "Hi Reach Broadband", "Hi Tech Broadband", "Hightec Network Solutions (OPC) Private Limited", "Hightech Broadband Services Pvt Ltd", "Hybrid Internet", "Hybrid Network",
  "Hydranet Broadband", "IBPL", "ION", "ISLAND BROADBAND", "I Net Broadband", "I Com Broadband Service", "Igen Networks", "Infinet", "Infonet Comm Enterprises Pvt Ltd", "Infinity Fibernet",
  "Inet Fiber", "Indinet Service Pvt Ltd", "Inri Communications Pvt. Ltd", "Instalinks", "Jeetu Broadband", "Jabbar Network", "Juweriyah Networks (Jeebr)", "Jtel", "KRP Fibernet", "Khatore It Solutions Private Limited",
  "Khetan Telecommunications Pvt Ltd", "Kingnet", "Kings Broadband", "Krishiinet Infocom Pvt Ltd", "Limras Eronet", "Link4data Broadband (Not Required)", "Linkio Fibernet", "Linktel Broadband",
  "Linkway Broadband", "Logon Networks Pvt Ltd", "Lotus Broadband", "MS Broadband", "MS Networks", "MTNL Delhi Broadband","Maa Durga Cable Broadband", "Manas Broadband", "Maruthi Net Cable",
  "Maya Electronice And Internet Service", "Meganet", "Megasurf Broadband", "Megasoft Broadband", "Megatel Networks Private Limited", "Meghlink", "Metanet", "Microsacn Infocommtech Pvt. Ltd.",
  "Mnet Broadband", "More Wifi", "Multicraft Digital Technologies Private Limited", "My Internet Zone", "N4U  Broadband", "NGC IT Works", "NPR Broadband Services", "NSB Networks Broadband",
  "NSPL","NSSI Fibernet", "NTL Broadband","Net 9 Fibernet Private Limited", "NetPlus Fiber Broadband Palakollu", "Netplus Broadband", "NetSanchar Internet", "Netstra", "Ne Line", "Nexen Broadband",
  "Nextra Broadband", "Nikki Internet Services","Nirav Infoway Pvt Ltd", "Nitro Broadband", "Ncore Creative Technologies Private Limited", "National Broadband Network", "Nageshwar Broadband",
  "Nandbalaji Connecting Zone Private Limited", "ODiGiTEL Broadband", "OXYNET", "Omnet", "One Broadband", "One touch express", "Orange Broadband", "Orange Infocom Pvt Ltd", "Padmesh Broadband Pvt Ltd",
  "PeerCast", "Pegasuswave Pvt Ltd", "Pink Broadband", "Pioneer Elabs Limited", "Plex Broadband", "Pol Fibernet Private Limited", "Praction Networks", "Quest Consultancy", "Quicknet", "Radius Broadband",
  "Radius Broadband Services", "RailWire Broadband", "Rainbow Communications India Pvt Ltd", "Rapidmove Broadband Services Private Limited", "Rapidnet", "Raze Networks", "Rcom Networks",
  "RDS NET", "RK Internet", "RPS Fibernet", "RVR Digital", "Raghavendra Network Services", "Raju Net", "Reisnet Broadband Pvt ltd", "Renu Broadband", "Rida Online", "Roarnet Broadband Private Limited",
  "Royal Fibernet", "Royal Networks", "SCCNET", "SGK Broadband", "SRI LAKSHMI NETWORKS PRIVATE LIMITED", "SR NET KAVALI", "SS Cablenet", "SS INTERNET", "ST Broadband biller", "SNS Internet Services Private Limited",
  "Samaira Infotech Pvt. Ltd", "Samiksha Network Solutions", "Satellite Netcom Private Limited", "Satsky", "Shark Broadband", "Shine Broadband", "Shiv Shakti Computers", "Shrikshetra Networks Private Limited",
  "Siliguri Internet And Cable Pvt Ltd", "Singh Televentures","Siti Vision Data (Not Required)", "Skynet", "Skynet Fiber Broadband", "Skynet Internet Broadband Pvt Ltd", "Skynet Wireless",
  "Skyair Internet", "Skyway Telecom", "Skyworld Infotech", "Softnet Digital", "Speed Hex Tele Communications Pvt Ltd", "Speednet Broadband", "Speednet Unique Network", "Speedsy", "SpectraNet Broadband",
  "Spider Broadband", "Spiderlink Networks Pvt Ltd", "Spidernet Broadband", "SpotNet Connected", "Sri Vijayalaksmhi Digital Vision", "SS Broadband (Not Required)", "Starnetworks", "Starlings Broadband",
  "Stromnet Broadband", "Subhnet", "Sun Broadband And Data Services Pvt Ltd", "Super Sonic Broadband Private Limited", "Supernetplus", "Suraj Networks", "SVS Broadband (Not Required)",
  "Swifttele Enterprises Private Limited", "Syncbroad Networks Pvt Ltd", "Syncevo Broadband Scify", "T-Way Networks", "TATA PLAY FIBER (Not Required)", "TIC FIBER", "TSK Giga Fibber", "Tact Communication Pvt Ltd",
  "Telex Broadband", "Threesa", "Timbl Broadband", "Treelink Broadband", "Trisha Enterprises", "Trunet Broadband", "UCN Fibernet Pvt Ltd", "UNM Broadband Service", "U Tele Services Pvt Ltd",
  "Udupi Fastnet", "Ufibernet", "Unique Broadband Service Private Limited", "Varsha Fibernet", "VCC Broadband", "VCN Fibernet", "VELoxr Telecom Pvt Ltd", "VILCOM", "Vayu Online Pvt Ltd",
  "Vision Hi Speed", "Weebo NetworksPt", "Weone Broadband", "Way2Net IT Services Pvt Ltd", "WANDOOR MULTIVERSE PVT LTD", "Willaegis", "Win Communications", "Winux Communications", "Wish Net Pvt Ltd",
  "World Phone Internet Services Pvt Ltd", "Worldnet Broadband", "Yash Instant Online India Private Limited (Demandpay)", "You Broadband (Not Required)", "Zapbytes Fibernet", "Zeta Telecom",
  "Zyetek Stream" 
],

ElectricityBill: [
    "Adani Electricity Mumbai Limited", "Ajmer Vidyut Vitran Nigam Ltd", "Andhra Pradesh Central Power Distribution Corporation Limited", "Assam Power Distribution Company Ltd (NON-RAPDR)",
    "Assam Power Distribution Company Ltd- Smart Prepaid Recharge", "Avyanna Aviation Private Limited", "Avyanna Aviation Private Limited (Not Required)", "B.E.S.T Mumbai", "Bangalore Electricity Supply",
    "BES Rajdhani Power Limited", "BES Yamuna Power Limited", "BES Rajdhani Prepaid Meter Recharge", "BES Yamuna Prepaid Meter Recharge", "Bharatpur Electricity Services Ltd. (BESL)",
    "Bikaner Electricity Supply Limited (BkESL)", "Calcutta Electricity Supply Co. Ltd.", "CESU, Odisha", "Chamundeshwari Electricity Supply Corp Ltd (CESCOM)", "Chandigarh Electricity Department",
    "Chhattisgarh Electricity Board", "Dakshin Gujarat Vij Company Ltd", "Dakshin Haryana Bijli Vitran Nigam (DHBVN)", "Dakshinanchal Vidyut Vitran Nigam Limited (DVVNL)(Postpaid and Smart Prepaid Meter Recharge)",
    "Dadra and Nagar Haveli and Daman and Diu Power Distribution Corporation Limited", "Department of Power, Government of Arunachal Pradesh", "Department of Power, Government of Arunachal Pradesh - Prepaid",
    "Department of Power, Nagaland", "Gift Power Company Limited", "Goa Electricity Department", "Government of Puducherry Electricity Department", "Gulbarga Electricity Supply Company Limited",
    "HESCOM (Hubli Electricity Supply Company Ltd)", "Himachal Pradesh Electricity", "Hukkeri Rural Electric CoOperative Society Ltd", "India Power Corporation Limited (IPCL)",
    "Jaipur Vidyut Vitran Nigam Ltd", "Jammu and Kashmir Power Development Department", "Jammu Power Distribution Corporation (JPDCL)", "Jamshedpur Utilities and Services Company",
    "Jharkhand Bijli Vitran Nigam Limited (JBVNL)", "Jharkhand Bijli Vitran Nigam Limited - Prepaid Meter Recharge", "Jharkhand Bijli Vitran Nigam Limited - Prepaid Meter Recharge (Not Required)",
    "Kanpur Electricity Supply Company Ltd", "Kannan Devan Hills Plantations Company Private Limited", "Kerala State Electricity Board Ltd. (KSEBL)", "Kiara Residency RWA", "Kiara Residency RWA (Not Required)",
    "Kinesco Power and Utilities Pvt Ltd", "Kota Electricity Distribution Limited (KEDL)", "Ladakh Power Development Department (LPDD)", "Lakshadweep Electricity Department", "M.P. Madhya Kshetra Vidyut Vitaran - RURAL",
    "M.P. Madhya Kshetra Vidyut Vitaran - URBAN", "M.P. Paschim Kshetra Vidyut Vitaran", "M.P. Poorv Kshetra Vidyut Vitaran – RURAL", "Madhya Gujarat Vij Company Ltd", "Madhyanchal Vidyut Vitran Nigam Limited (MVVNL)(Postpaid and Smart Prepaid Meter Recharge)",
    "Maharashtra State Electricity Distribution Co Ltd (MSEB Mumbai)", "Manipur State Power Distribution Company Limited", "MePDCL Smart Prepaid Meter Recharge", "Meghalaya Power Distribution Cor. Ltd",
    "Mangalore Electricity Supply Co. Ltd (MESCOM)", "Mangalore Electricity Supply Company LTD (Non RAPDR)", "New Delhi Municipal Council (NDMC) - Electricity", "Noida Power Company Limited",
    "North Bihar Power",  "Northern Power Distribution Company of Telangana Limited",  "NESCO, Odisha",  "Paschim Gujarat Vij Company Ltd", "Paschimachal Vidyut Vitran Nigam Limited",
    "Power and Electricity Department - Mizoram", "Punjab State Power Corporation Ltd (PSPCL)", "Purvanchal Vidyut Vitran Nigam Limited(PUVVNL)(Postpaid and Smart Prepaid Meter Recharge)",
    "Sikkim Power – RURAL", "Southern Bihar Power", "Southern Power Distribution Company of Telangana Limited", "SOUTHCO, Odisha", "Tamil Nadu Electricity Board (TNEB)", "Tata Power Delhi Distribution Ltd",
    "Tata Power Mumbai", "Thrissur Corporation Electricity Department", "Torrent power", "TP Ajmer Distribution Ltd (TPADL)", "TP Renewables Microgrid Ltd.", "TP Southen Odisha Distribution Ltd-Smart Prepaid Meter Recharge",
    "TP Western Odisha Distribution Ltd-Smart Prepaid Meter Recharge", "TTD Electricity", "Uttar Gujarat Vij Company Ltd", "Uttar Haryana Bijli Vitran Nigam (UHBVN)", "Uttarakhand Power Corporation Limited",
    "Vaghani Energy Limited",  "WESCO Utility",  "West Bengal Electricity Prepaid",  "West Bengal State Electricity Distribution Co. Ltd", "Zen Spire", "Zen Spire (Not Required)" 
],

WaterBill: [
    "Bangalore Water Supply and Sewerage Board", "Chandrapur Municipal Corporation", "Chennai Metropolitan Water Supply And Sewerage Board", "City Municipal Council –Ilkal", "DDA (Narela Zone)",
    "DDA - (East Zone)", "Delhi Development Authority (DDA) - Water", "Delhi Jal Board", "GRAM PANCHAYAT WANGI - WATER", "Grampanchayat Aitawade Khurd Water", "Grampanchayat Ambegaon",
    "Grampanchayat Halondi Nal Paani Puravatha", "Grampanchayat Hingangaon Budruk", "Grampanchayat Kheradewangi", "Grampanchayat Nevari", "Greater Warangal Municipal Corporation – Water",
    "Haryana Urban Development Authority", "Hubli Dharwad Municipal Corporation Water", "Hyderabad Metropolitan Water Supply and Sewerage Board", "Jejuri Nagarparishad", "Jammu Kashmir Water Billing-JKPHE Jammu",
    "Jammu Kashmir Water Billing-JKPHE Kashmir", "Kerala Water Authority (KWA)", "Madhya Pradesh Urban Administration and Development - Water", "Maharashtra Jeevan Pradhikaran 105Vrr Amravati",
    "Maharashtra Jeevan Pradhikaran 156Vrr Daryapur", "Maharashtra Jeevan Pradhikaran Amravati Urban", "Maharashtra Jeevan Pradhikaran Anjangaon", "Maharashtra Jeevan Pradikaran 79Vrr Anjangaon",
    "Maharashtra Jeevan Pradikaran Daryapur", "MCGM Water Department", "Mira Bhayander Municipal Corporation-Water", "Municipal Corporation Chandigarh", "Municipal Corporation Ludhiana – Water",
    "Municipal Corporation of Gurugram", "Mysuruvani Vilas Water Works 24X7", "Mysuru Citi Corporation", "Nagar Nigam Aligarh- water", "New Delhi Municipal Council (NDMC) - Water",
    "Odisha Municipal Payments - Water Tax", "Port Blair Municipal Council - Water", "Public Health Engineering Department - Rajasthan", "Public Health Engineering Department, Haryana",
    "Public Works Department (PWD), Goa", "Puducherry Public Health Division PWD", "Pune Municipal Corporation Water", "Punjab Municipal Corporations Councils Sewerage", "Punjab Municipal Corporations/Councils",
    "Ranchi Municipal Corporation", "Shimla Jal Prabandhan", "Shivamogga City Corporation - Water Tax", "Surat Municipal Corporation", "Talegaon Dabhade Nagar Parishad - Water Payments",
    "Thane Municipal Corporation Water Tax", "Tirumala Tirupati Devasthanam (TTD) Water", "Ujjain Nagar Nigam – PHED", "Uttarakhand Jal Sansthan", "Vadiyeraibag Gp Water Tax", "Vasai Virar Municipal Corporation - Water",
    "Vatva Industrial Estate Infrastructure Development Ltd", "Vijayapura Water Board" 
],

EMI: [
    "121 Finance Private Limited", "Achiievers Finance", "Adani Capital Pvt Ltd", "Adani Housing Finance", "Adarsh Laxmi Nidhi", "Aditya Birla Finance Ltd. (ABFL)", "Aditya Birla Housing Finance Limited",
    "Agriwise Finserv Limited", "Aham Housing Finance Private Limited", "Ajeevak Nidhi Limited", "Alfastar India Nidhi Limited", "Alleywell Finserve Nidhi Limited", "Altum Credo Home Finance",
    "Amarpadma Credits Pvt Ltd", "Ambit Finvest Pvt Ltd", "Amrit Malwa Capital Limited", "Amritversha Nidhi Limited", "Ananya Vikash Nidhi Limited", "Ankur Trade Links Pvt Ltd",
    "Annapurna Finance Private Limited-MFI", "Annapurna Finance Private Limited-MSME", "Anscor Capital And Investment Pvt Ltd", "APAC Financial Services Pvt Ltd", "Aptus Finance India Private Limited",
    "Aptus Value Housing Finance India Limited", "Aris Capital Pvt Limited", "Aristan Finance", "Arthan Finance Pvt Ltd", "Arthmate Financing India Private Limited", "Art Housing Finance (India) Limited",
    "Arohan Financial Services Ltd", "Aryabharat Digital Nighi Ltd", "ASA International India Microfinance Limited", "Ascend Capital", "Ashv Finance", "AU Bank Loan Repayment", "Avail",
    "Avanse Financial Services Ltd", "Axis Bank Limited - Retail Loan", "Axis Bank Limited-Microfinance", "Axis Bank Ltd - MCA", "Axis Bank Ltd-Digital Loan", "Axis Finance Limited",
    "Ayaan Finserve India Private LTD", "AAVAS FINANCIERS LIMITED", "Baid Leasing and Finance", "Bajaj Auto Credit Limited", "Bajaj Auto Finance", "Bajaj Finance", "Bajaj Finance Limited Agent Collection",
    "Bajaj Finance Ltd - Corporate agent", "Bajaj Housing Finance Limited", "Babasaheb Deshmukh Sahakari Bank Limited Atpadi", "Bazaari Finance", "Belstar Microfinance Limited", "BERAR Finance Limited (Not Required)",
    "Bhala Finance Private Limited", "Bhgala Livelihood Finserv Limited",  "Blackbuck Finserve (Not Required)","Boundparivar Loan", "Bussan Auto Finance India Pvt Ltd", "BWDA Finance Ltd (Not Required)",
    "Can Fin Homes Ltd", "Capri Global Capital Limited", "Capri Global Housing Finance", "Capri Global MSME", "Capital India Finance Limited", "Capital India Home Loans Limited", "Capital Trust Limited",
    "Care India Finvest Limited", "Cars24 Financial Services Private Limited", "CASHe", "Cashtree Finance", "Centrum Microcredit Limited", "Chachyot Nidhi", "Chaitanya Godavari Grameena Bank",
    "Chaitanya India Fin Credit Pvt Ltd", "Cholamandalam Investment and Finance Company Limited", "Choice Finserv Pvt Ltd", "Clix", "CNH Industrial Capital Pvt. Ltd.", "Credit One Payments Solutions Pvt Ltd",
    "Credit Saison", "Credit Wise Capital", "CreditAccess Grameen - Microfinance", "CreditAccess Grameen - Retail Finance", "Credin", "Criss Financial Holdings Ltd", "CSL Finance",
    "Dadhich Finserv Pvt Ltd", "DCB Bank Loan Repayment", "DCBS Loan", "Deccan Finance Limited", "Del Capital Private Limited", "Dev Finance", "Dhansansar Nidhi Limited", "Dhanmax Finance Private Limited",
    "Digamber Capfin Limited", "Diwakar Tracom Private Limited", "DMI Finance", "DMI Housing Finance Pvt. Ltd.", "Dooars India Nidhi Limited", "Dvara Kshetriya Gramin Financials Private Limited",
    "Easy Home Finance Limited", "EDC Limited", "Eduvanz Financing Pvt. Ltd.", "Ekagrata Finance", "Electronica Finance Limited", "Emerald Finance",  "EMGEE MUTHOOT NIDHI LTD", "Equitas SFB – Microfinance Loan",
    "Equitas Small Finance Bank - Retail Loan", "ESAF Small Finance Bank (Micro Loans)", "ESAF Small Finance Bank (Retails Loans)", "Esco Elettil Nidhi Limited", "Ess Kay Fincorp Limited (Sk Finance)",
    "EZFINANZE", "Faircent", "Fasttrack Housing Finance Ltd.", "Federal Bank Loan Repayment", "FeeMonk", "Fincare Small Finance Bank", "Fin Coopers Capital Private Limited", "Finazone Micro Services Foundation",
    "Finnable", "Finova Capital Private Ltd", "Five Star Business Finance", "FlexiLoans", "Flexsalary", "Fortune Credit Capital Limited", "Fortune Integrated Assets Finance Ltd", "Fullerton India Credit Company Limited",
    "Fullerton India Housing Finance Limited", "Fusion Finance Ltd MFI",  "Fusion Micro Finance Ltd.", "GEO BROS MUTHOOT NIDHI LTD",  "Girnar Capital (Formerly Khemlani Finance)", "Gobrapota Asharalo Nidhi Limited",
    "Goldline Finance Private Limited (Capital Now)", "G U Financial Services Pvt Ltd", "HDB Financial Services Limited", "HDFC Bank Retail Assets", "Hedge Finance Ltd", "Hero FinCorp Ltd",
    "Hero Housing Finance Ltd", "HGNL Nidhi Limited", "Hiranandani Financial Services Pvt Ltd", "Hindon Mercantile Limited - Mufin", "Hinduja Housing Finance Limited", "Hinduja Leyland Finance",
    "HMT Finance Pvt Ltd", "HMKA Nidhi Ltd", "Home Credit India Finance Pvt. Ltd", "Home First Finance Company India Limited", "i2iFunding", "ICICI Bank Ltd - Loans", "ICICI BANK - Interest Repayment Loans",
    "IDF Financial Services Private Limited", "IFL Housing Finance Ltd Gold Loan", "IFL Housing Finance Ltd Home Loan", "IIFL Finance Limited", "IIFL Home Finance", "IIFL Samasta Finance Ltd - Retail Loans",
    "InCred", "Indel Money Limited", "India Home Loan Limited", "India Shelter Finance Corporation Limited", "Indiabulls Housing Finance Limited", "Indian Bank Loan EMI", "Indostar Capital Finance Limited - CV",
    "Indostar Capital Finance Limited - SME",  "Indostar Home Finance Private Limited", "INDUSIND BANK - CFD", "Infinity Fincorp Solutions Pvt Ltd", "Jain Autofin", "Jain Motor Finmart", 
    "Janakalyan Financial Services Private Limited", "Jana Small Finance Bank", "Jassi Hire Purchase Limited", "Javana Nidhi Limited", "Jeevan Dhara", "JM Financial Home Loans Ltd.",
    "John Deere Financial India Private Limited", "Kamal Autofinance Pvt Ltd", "Kamal Finserve Private Limited", "Kanakadurga Finance Limited", "Kanakadurga Finance Limited - Gold Loans",
    "Kannattu Finance Nidhi Ltd", "Kannattu Fingold Finance Pvt Ltd", "Karnataka Vikas Grameena Bank Loan Repayment", "Karpagam Hire Purchase And Finance Pvt Ltd", "Keertana Finserv Pvt Ltd",
    "Keshaw Microfinance", "KIFS Housing Finance Ltd", "Kinara Capital", "Kissht", "KLM Axiva Finvest Limited", "Kogta Financial India Limited", "Kosamattam Finance Ltd",
    "Kotak Mahindra Bank Ltd.-Loans", "Kotak Mahindra Prime Limited", "KPS MICRO SERVICES FOUNDATION", "Kusalava Finance Limited", "L and T Financial Services", "Laxmi India Finleasecap Private Limited",
    "Lendingkart Finance Limited", "Light Microfinance Private Limited", "Loan2Wheels", "LoanBaba", "LoanFront", "LoanTap Credit Products Private Limited", "Loanzen Finance Pvt Ltd",
    "Loksuvidha", "Lord Krishna Financial Services", "M S Fincap Pvt Ltd", "Maben Nidhi", "Maben Nidhi Gold Loans (Not Required)", "Mahaveer Finance India Limited", "Mahindra and Mahindra Financial Services Limited", "Mahindra Finance Consumer Loans",
    "Mahindra Rural Housing Finance", "Manappuram Asset Finance Ltd Micro Finance", "Manappuram Asset Finance Ltd Mortgage Loan", "Manappuram Asset Finance Ltd Two Wheeler Loan", "Manappuram Asset Finance Ltd Vehicle Loan",
    "Manappuram Finance Limited", "Manappuram Finance Limited-Vehicle Loan", "Manappuram Home Finance", "Mangal Credit and Fincorp Limited", "Mangal Vehicle Finance Pvt Ltd", "Mangala Infin Ltd",
    "Maitreya Capital and Business Services Private Limited", "MAS Financial Services Limited", "Mas Rural Housing and Mortgage Finance Ltd", "Maudrikrashi Nidhi Limited", "Maxvalue Credits And Investments Ltd",
    "Megha Holdings Pvt Ltd", "Mentor Home Loans India Limited", "Mere Apne Micro Finance", "Mere Apne Nidhi", "Metro Samrudhi Nidhi Limited", "Metrocity Finance Pvt Ltd", "Mgm Financiers",
    "Midland Microfin Ltd", "Mintifi Finserve Private Limited", "Mitron Capital", "MDFC Financiers Pvt Ltd", "Moneyboxx", "Moneyplus Financial Services Pvt Ltd", "MoneyTap", "Moneywise Financial Services Private Limited",
    "Monedo Financial Services Pvt Ltd", "Motilal Oswal Home Finance","Mufin Green Finance Limited", "Muthoot BL SME", "Muthoot Capital Services Ltd", "Muthoot Finance", "Muthoot Finance InstaPL",
    "Muthoot Finance-Personal Loan", "Muthoot Fincorp Ltd", "Muthoot Homefin Limited", "Muthoot Housing Finance Company Limited", "Muthoot M George Nidhi Ltd", "Muthoot Microfin Limited",
    "Muthoot Money", "Muthoot Money - Gold Loan", "Muthoot Vehicle And Asset Finance Limited", "Muthoot Vehicle And Asset Finance Limited Gold Loan", "NABFINS", "Nagar Nigam Aligarh - Municipality",
    "Namdev Finvest Pvt Ltd", "Namra Finance", "Navi Loans", "Netafim Agricultural Financing Agency Pvt. Ltd.", "Nidhilakshmi Finance", "NM Finance", "Novelty Finance Ltd", "OHMYLOAN",
    "OMLP2P.COM", "Oroboro", "Oxyzo Financial Services Pvt Ltd", "Pahal Finance IL/SL", "Pahal Financial Services Pvt Ltd", "Paisa Dukan-Borrower EMI", "Paisabuddy Finance Pvt Ltd", "Paisalo Digital Limited",
    "Parkosian Nidhi Limited", "Perfect Capital Services Ltd", "Perfect Finsec Pvt. Ltd.", "Piramal Finance", "Pink City Fincap Pvt Ltd", "Pooja Finelease", "Poonawalla Fincorp Ltd",
    "Poonawalla Housing Finance Ltd", "Prayas Financial Services Private Limited", "Protium", "Punjab National Bank", "R K Bansal Finance Private Limited", "Rahimatpur Sahakari Bank Ltd",
    "Ramaiah Capital Pvt Ltd", "Rander Peoples Co Operative Bank Ltd", "RBA Finance Pvt Ltd", "Reliance Credits India Limited", "Religare Health Insurance Co Ltd.", "Repco Micro Finance",
    "Richline Finance Ltd", "Ring", "Riyanjali Nidhi Limited", "RMK Fincorp Pvt Ltd", "Rupee Circle", "RupeeRedee", "Rupitol Finance Pvt Ltd", "S V Creditline Limited", "Sadbhav Mutual Benefit Nidhi",
    "Samasta Microfinance Limited", "Samavesh Finserve Private Limited", "Samraddh Bestwin Micro Finance Association", "Sampournasamuh M F", "Samrat Motor Finance Ltd", "Samunnati Financial Intermediation and Services Private Limited",
    "Sanritik Nidhi Limited", "Saraswat Bank - Loan Repayment", "Sarvjan India Fintech Private Limited", "Save Microfinance Private Limited", "SBFC Finance Private Limited", "Secureind Nidhi",
    "Setia Auto Finance Pvt Ltd", "Sewa Grih Rin Limited", "SG Royal Capital Pvt Ltd", "Shaipal Nidhi Limited", "Shalibhadra Finance Limited", "Share India Fincap Pvt Ltd", "Shine Blue Hire Purchase Ltd.",
    "Shivalik Small Finance Bank Ltd", "Shivaya Capital Private Limited", "Shri Ram Finance Corporation Pvt Ltd", "Shriram Housing Finance Limited", "Shriram Transport Finance Company Limited",
    "Singhi Finance Private Limited", "Singularity Creditworld Private Limited", "Smile Microfinance Limited", "SMEcorner", "SM Square Credit Services Private Limited", "Snapmint", "Sonata Finance",
    "Spandana Rural And Urban Development Organisation", "Spandana Sphoorty Financial Ltd", "Speel Finance Company Private Limited (Pocketly)", "SRG Fincap Pvt Ltd", "SRG Housing Finance Limited",
    "STREE NIDHI - TELANGANA", "Strr Nidhi Limited", "StuCred", "Subhlakshmi Finance Pvt. Ltd", "Sundaram Finance Limited", "Suneet Finman Private Limited", "Supra Pacific Financial Services Ltd",
    "Suryoday Small Finance Bank", "Svakarma Finance Private Limited", "Svatantra Micro Housing Finance Corporation Limited", "Svatantra Microfin Private Limited", "Swadha Finlease Services",
    "Tata Capital Financial Services Limited", "Tata Capital Housing Finance Limited", "Tata Motors Finance Limited", "Techfino Capital Pvt Ltd", "Thazhayil Nidhi Ltd", "Three65 Financial Services Pvt Ltd",
    "Tiffany Finance Private Limited", "Toyota Financial Services (Not Required)", "Transwarranty Finance Limited", "Treedha Finance Private Limited", "Trickle Flood Technologies Pvt Ltd",
    "Trishiv Technology Nidhi Limited", "TVS Credit", "UCO Bank Loan",  "Ujjivan Small Finance Bank",  "Ujjwal Mudra Benefits Nidhi Limited",  "Ummeed Housing Finance Pvt. Ltd.", "Unigold Finance",
    "Union Bank of India-Loans", "Unity Small Finance Bank", "Unnayan Bharat Finance Corporation Private Limited", "Upkaar Micro Finance", "Utkarsh Bank Loan Repayment", "Vastu Finserve India Private Limited",
    "Vedika Credit Capital Limited", "Velicham Finance", "Veritas Finance", "VFS Capital Limited", "VFS Capital Ltd - MSME Loans", "Vinayaka Capsec Pvt Ltd", "Visionfund", "Vistaar Financial Services Private Limited",
    "We Pay Finance Pvt Ltd", "Wheelsemi Pvt Ltd", "Wonder Home Finance Limited",  "X10 Financial Services Limited", "Yes Bank Ltd - Loan Payment", "Yogakshemam Loans Ltd", "Ziniya Nidhi Limited" , "Ziploan" 
],

PostpaidBill:[
  "ACNS Pvt Ltd", "Adigital", "Airtel", "Airtel Landline", "Amber Online Services", "ARIHANT NETWORK", "AT Broadband", "BSNL", "BSNL Landline - Corporate", "BSNL Landline - Individual",
  "Cloud ISP", "Connect BroadBand (Not Required)", "Crystal Broadband", "Eway FiberNet", "Feathers", "Fiber Power Connects Private Limited", "Gtech Broadband", "Gtech Partner (Not Required)",
  "IMPERIUM DIGITAL NETWORK PRIVATE LIMITED", "IRRA Internet Service Private Limited", "Jio (Not Required)", "Kerala Vision Broadband Pvt Ltd", "Kord Broadband Services Pvt Ltd", "MANOJAVA BROADBAND PRIVATE LTD",
  "MM Networks", "MTNL Mumbai", "MTNL Mumbai Lease Circuit", "One Click", "Orange Fibernet and TV", "QNet", "Rajesh Digital and Datacom Private Limited", "Reach Broadband",
  "REALTEL", "SG Broadband internet Pvt Ltd", "Smart Net India Pvt Ltd", "SR Broadband", "Tata TeleServices (CDMA)(Not Required)", "TJ Broadband Network Pvt Ltd", "Tikona",
  "Vision Fibernet", "Vodafone Idea Postpaid", "Wave Fiber", "Wiwanet Solution Pvt Ltd", "Xpress Fiber Pvt Ltd" 
], 

EducationFees:$billers = ["Akshaya Patra Foundation (Not Required)",  "Anand Jivan Foundation (Not Required)",  "Apeksha Humanitarian Rights and Social Welfare Foundations (Not Required)",
    "Ashadeep Association (Not Required)", "Bal Raksha Bharat (Not Required)",  "Child Help Foundation (Not Required)",  "Dean Foundation (Not Required)",  "Devdaitywa Welfare Foundation (Not Required)",
    "Education Billers", "Global Life Tree Foundation (Not Required)", "Green India Trust (Not Required)", "HARE KRISHNA MOVEMENT AHMEDABAD (Not Required)", "Human Welfare Charitable Trust (Not Required)",
    "Institute for Rural Development (IRD) (Not Required)", "Iskcon Pune (Not Required)", "ISKCON Hubballi-Dharwad (Not Required)", "Jesus Kings Temple Foundations and Charitable Trust (Not Required)",
    "Kalyanam Karoti (Not Required)", "Ketto Foundation (Not Required)", "Mahathobhara Shri Mangaladevi Temple (Not Required)", "Pakhar Sankul Solapur (Not Required)", "Prerna Parivar Welfare Foundation (Not Required)",
    "Sardardham (Not Required)", "Shree Kalki Dham Nirman Trust (Not Required)", "Shree Kashi Vishwanath Mandir Trust (Not Required)", "Shri Amarnathji Shrine Board (Not Required)",
    "Shri Krishnayan Desi Gauraksha Avom Golok Dham Seva Samiti (Not Required)", "Shri Mata Vaishno Devi Shrine Board (Not Required)", "Shri Ram Janmbhoomi Teerth Kshetra (Not Required)",
    "Shri Uttaradi Math (Not Required)", "Shirdi Sai Baba Temple Society (Not Required)", "Shravanabelagola Digambar Jain Matha Institutions Managing Committee Trust R (Not Required)",
    "Smile Foundation (Not Required)",  "Soor Shyam Seva Sansthan (Not Required)",  "South Gujarat Medical Education And Research Centre (Not Required)",  "Sri Banashankari Temple (Not Required)",
    "Sri Pratyaksha Charitable Trust (Not Required)",  "Sri Rajarajeswara Temple (Not Required)",  "Sri Trichambaram Sreekrishna Temple (Not Required)",  "The Gowd Saraswat Brahimin Mandal Thane (Not Required)",
    "Trulyhelp Trust (Not Required)",  "Vedachala Seva Trust (Not Required)",  "Vidiyal Society Sathyamangalam (Not Required)", "Vishva Manav Ruhani Aadhyatmik Kendra (Not Required)" 
],

CableTV:[
  "777 Network Broadband", "AAA Internet Services Pvt Ltd", "ACT BroadBand", "ACT Cable TV", "Adaptive Networks", "Air Internet", "AirConnect", "Airgenie Communications", "AirJaldi - Rural Broadband",
  "Airnet Networks", "Airnetz", "Airtel Broadband", "Aitspl", "Alka Vishwadarshan", "Alliance Broadband Services Pvt. Ltd.", "Amrita Cable Network", "ANI Broadband", "ANI Network Pvt Ltd",
  "Anthariksha Fiber", "Anu Broadband", "Anupama Cable And Internet Service", "Aparna Star TV Network", "Apex", "Apple Fibernet", "Apsara Communications", "Aryan Cable Network", "ASIANET Broadband (ASIANET) (Not Required)",
  "Asianet Digital", "Ayyappa Swamy Siti Cable Network Badvel", "Ayman Internet", "B Fibernet", "BACBPL", "Badanganj Cable Network", "Balaji Broadband", "Bapi Electric And Cable Network",
 "Baroda Broadband", "BCTN Broadband", "Benvar", "B-Fi Networks", "Bharti Hexacom Limited", "Bhima Riddhi Broadband Private Limited", "Bijis Internet Private Limited", "Bsb Network",
 "BSNL Broadband", "Cable Guy", "CATV DIGITAL", "Catla Broadband", "CCNDS Cable", "Chakdaha Cable And Broadband Pvt Ltd", "Channel Vision Cable TV Network", "Charotar Broadband", "Cherrinet",
 "Clicknet Communication", "Cloudlasers Broadband", "Cloudsky Superfast Broadband & Services Pvt Ltd", "CNC Broadband", "Cochin Cable Vision", "Comcast Broadband Services", "Compliance Broadband (CBPL)",
 "Comway Broadband", "Confiar Broadband", "Confiar Partner (Not Required)", "Correl Internet", "Crystalclear Network", "Cyber Broadband", "Dainik Savera Net", "Daksh Telecom", "Deenet Services Private Limited",
  "Deetech Cable Network", "Den Broadband", "Deshkal Networks", "Devi Sat Vision Cable TV Network", "Digital World", "Digiway Net", "Dishergarh Cable TV", "DJio Net", "Dreamland Cables",
  "Dreamnet Gigafiber", "DSCN", "DVPL BB", "DVR Broadband Services", "DWAN Supports Private Ltd", "Earthlink Net", "Easy Net", "Ecreado Network Solutions Private Limited", "Eknath", "ELL Fibernet",
  "Ethernet Xpress", "Esto Broadband Private Ltd", "Esto Media Private Limited", "Excell Broadband", "Express Wire", "Extreme Broadband", "FABNET", "FiberX", "Ficus Telecom Pvt Ltd", "Flash Fibernet",
  "Frontline Internet Services", "Fusionnet Web Services Private Limited", "Future Connect Broadband", "Future Netsanchar Limited", "Galactic Internet", "Galaxynet", "Gangotri Telenet Pvt Ltd",
  "Garuda Groups", "Gateway Networks", "GBPL", "GBPS Networks Pvt Ltd", "GEFO Fibernet", "G Link Fibernet", "Globnet Broadband", "Gloriosa Infotel", "Golden Cable Network", "Goodwill Broadband",
  "GRB infotech", "GSR Broadband", "GTPL Hathway Limited", "GTPL KCBPL Broadband Pvt Ltd", "Gulbarga Mega Speed", "Hasten", "Hathway", "Haur Cable Network", "Hi Reach Broadband", "Hi Tech Broadband",
  "High Range Communication", "Hightec Network Solutions (OPC) Private Limited", "Hightech Broadband Services Pvt Ltd", "Hybrid Internet", "Hybrid Network", "I Com Broadband Service",
  "IBPL", "Igen Networks", "Incable Digital TV (Not Required)", "INDigital (Not Required)", "Infinet", "Infinity Fibernet", "Infonet Comm Enterprises Pvt Ltd", "Inet Fiber", "I Net Broadband",
  "Instalinks", "Ion", "ISLAND BROADBAND", "Isoinet Integrated Solutions", "Jabbar Network", "Jemari Cable Darshan", "Jeetu Broadband", "Jhanjra Cable And Broadband Service", "Jtel",
  "Juweriyah Networks (Jeebr)", "Karnet Broadband", "Khatore It Solutions Private Limited", "Khetan Telecommunications Pvt Ltd", "Kingnet", "Kings Broadband", "Krp Fibernet", "Laxmimata Cable Network",
  "Limras Eronet", "Link4data Broadband (Not Required)", "Linkio Fibernet", "Linktel Broadband", "Linkway Broadband", "Logon Networks Pvt Ltd", "Lotus Broadband", "Lugvalley Digital Cable Network",
  "M M Communication", "Maa Durga Cable Broadband", "Maa Durga Cable Broadband Service", "Maa Sidheswari Enterprises", "Maa Tara Network", "Manas Broadband", "Maruthi Net Cable", "Mathurapur Cable Network",
 "Maya Electronice And Internet Service", "MB Net", "Megasoft Broadband", "Megasurf Broadband", "Megatel Networks Private Limited", "Megnet", "Meghlink", "Metro Cast Network India Pvt Ltd",
 "Microsacn Infocommtech Pvt. Ltd.", "Mnet Broadband", "Mobiezy", "Modina Cable Network", "Mondal Cable TV Network", "More Wifi", "MS Broadband", "MS Networks", "MTNL Delhi Broadband",
 "Multicraft Digital Technologies Private Limited", "My Internet Zone", "N4U Broadband", "Nageshwar Broadband", "Nandbalaji Connecting Zone Private Limited", "National Broadband Network",
  "Net 9 Fibernet Private Limited", "NetPlus Fiber Broadband Palakollu", "Netplus Broadband", "Netsanchar Internet", "Netstra", "Netway Infotech Private Limited", "New Airnet Cable", "New Samananta Cable",
  "Nexen Broadband", "Nextra Broadband", "NGC IT Works", "Nikki Internet Services", "Nirav Infoway Pvt Ltd", "Niss Fibernet", "Nitro Broadband", "NPR Broadband Services", "NSB Networks Broadband",
  "NSPL", "ODiGiTEL Broadband", "Omnet", "One Broadband", "One touch express", "Orange Broadband", "Orange Infocom Pvt Ltd", "OXYNET", "Padmesh Broadband Pvt Ltd", "Paglachandi Cable Network",
  "Pal Cable Network", "Parbati Cable Network", "Paymytv – Den", "Paymytv – Hathway", "Pegasuswave Pvt Ltd", "PeerCast", "Pink Broadband", "Pioneer Elabs Limited", "Plassey Cable Network",
 "Plex Broadband", "Pol Fibernet Private Limited", "Poochakal Cable Vision", "Praction Networks", "Quadplay", "Quest Consultancy", "Quicknet", "Radius Broadband Services", "Raghavendra Network Services",
 "RailWire Broadband", "Rainbow Communications India Pvt Ltd", "Rapidmove Broadband Services Private Limited","Rapidnet", "Raze Networks", "Rcom Networks", "RDS NET", "Renu Broadband",
  "Reisnet Broadband Pvt Ltd", "Rida Online", "RK Internet", "Roarnet Broadband Private Limited", "Royal Fibernet", "Royal Networks", "RVR Digital", "Sakthi Cable TV", "Samaira Infotech Pvt. Ltd",
  "Samiksha Network Solutions", "Sangli Media Communication", "Santishree Cable", "Satellite Cable Communication", "Satellite Netcom Private Limited", "Satsky", "SBASS", "SBR Telecom",
  "SCCNET", "See Cable Tv", "Seyon Teleinfra", "SGS Broadband", "Shark Broadband", "Shine Broadband", "Shiv Shakti Computers", "Shrikshetra Networks Private Limited", "Siliguri Internet And Cable Pvt Ltd",
  "Singh Televentures", "Siti Vision Data (Not Required)", "Skynet", "Skynet Fiber Broadband", "Skynet Internet Broadband Pvt Ltd", "Skynet Wireless", "S M Giganet Services Private Limited",
 "SNDC BROADBAND", "SNS Internet Services Private Limited", "Softnet Digital", "Speed Hex Tele Communications Pvt Ltd", "Speednet Broadband", "Speednet Unique Network", "Spider Broadband",
 "Spiderlink Networks Pvt Ltd", "Spidernet Broadband", "SpotNet Connected", "Sri Sai Communication and Internet Private Limited (Not Required)", "SRI LAKSHMI NETWORKS PRIVATE LIMITED",
  "Sri Vijayalaksmhi Digital Vision", "SR NET KAVALI", "SS Broadband (Not Required)", "SS Cablenet", "SS INTERNET", "Ssky Conneect Private Limited", "ST Broadband biller", "STAR SAMIR CABLE NETWORK",
  "Starlings Broadband", "Starnetworks", "Stromnet Broadband", "Sun Broadband And Data Services Pvt Ltd", "Sun Cable Network", "Sunny Cable", "Super Sonic Broadband Private Limited", "Supernetplus",
 "Suraj Networks", "SVS Broadband (Not Required)", "Sweety Cable", "Swifttele Enterprises Private Limited", "Syncbroad Networks Pvt Ltd", "Tact Communication Pvt Ltd", "Tarapith Cable Link",
 "TATA PLAY FIBER (Not Required)", "TCN Digital", "Telex Broadband", "Threesa", "TIC FIBER", "Timbl Broadband", "Treelink Broadband", "Trisha Enterprises", "Trunet Broadband", "TSK Giga Fibber",
  "TTN BroadBand", "U Tele Services Pvt Ltd", "UCN Cable", "UCN Fibernet Pvt Ltd", "Udupi Fastnet", "Ufibernet", "Unique Broadband Service Private Limited", "UNM Broadband Service",
  "Varsha Fibernet", "Vayu Online Pvt Ltd", "VCN Fibernet", "VCC Broadband", "Vdigital", "Veloxr Telecom Pvt Ltd", "Victory Digital Network Pvt Ltd", "Vijayalakshmi Net Services Private Limited",
  "VILCOM", "Vision Hi Speed", "WANDOOR MULTIVERSE PVT LTD", "Way2Net IT Services Pvt Ltd", "Weebo NetworksPt", "Weone Broadband", "Willaegis", "Win Communications", "Winux Communications",
  "Wish Net Pvt Ltd", "World Phone Internet Services Pvt Ltd", "Worldnet Broadband", "Yash Instant Online India Private Limited (Demandpay)", "You Broadband (Not Required)", "Zapbytes Fibernet",
 "Zita Telecom", "Zyetek Stream" 
],
  
MunicipalBill: [
  "Agartala Municipal Corporation", "Ajmer Nagar Nigam", "Bicholim Municipal council", "Bicholim Municipal council Trade License", "Canacona Municipal council", "Canacona Municipal Council Trade License",
  "Commissioner and Director of Municipal Administration Hyderbad, Telangana", "Corporation of City Panaji", "Corporation of City Panaji Trade License", "Cuncolim Municipal council",
  "Cuncolim Municipal Council Trade License", "Curchorem Cacora Municipal council", "Curchorem Cacora Municipal Council Trade License", "Davangere Citi Municipal Corporation", "Dewas Municipal Corporation",
  "Directorate of Land Revenue and Settlement Dept - Mizoram", "Directorate of Municipal Administration Karnataka", "Gram Panchayat Dhamner", "GRAM PANCHAYAT WANGI", "Grampanchayat Aitawade Khurd",
  "Grampanchayat Ambegaon", "Grampanchayat Halondi Gram Nidhi", "Grampanchayat Hingangaon Budruk", "Grampanchayat Kheradewangi", "Grampanchayat Nevari", "Gramin Nalpani Yojana Grampanchayat Shiye",
  "Greater Chennai Corporation", "Greater Hyderabad Municipal Corporation", "Gulbarga City Corporation", "Hubli-Dharwad Municipal Corporation", "Jejuri Nagarparishad", "Kolkata Municipal Corporation",
  "Kolkata Municipal Corporation - Market Regular Demand", "Kolkata Municipal Corporation-Trade license", "Kolhapur Municipal corporation- Property tax", "Kolhapur Municipal Corporation - Water Tax",
 "Lucknow Nagar Nigam", "Madhya Pradesh Urban (e-Nagarpalika) - Property", "Mangalore Municipal Corporation", "Margao Municipal Council", "Margao Municipal Council Trade License", "Mhapsa Municipal Council",
  "Mhapsa Municipal Council Trade License", "Minicipal Corporation - Meerut", "Mira Bhayander Municipal Corporation", "Mormugao Municipal Council", "Mormugao Municipal Council Trade License", "Municipal Corporation Bhopal",
  "Municipal Corporation Of The City Of Chandrapur", "Municipal Corporation Rohtak", "Municipal Corporation Shimla", "Nagar Nigam Agra", "Nagar Nigam Firozabad", "Nagar Nigam Jhansi", "Nagar Nigam Saharanpur",
  "Nagar Palika Jaitaran", "Nagar Palika Parishad Lalitpur", "Nagar Palika Parishad Muzaffarnagar", "Nagar Palika Parishad Sitapur, UP", "Nagar Palika Palia Kalan", "Nagar Parishad Beawar",
  "Nagar Parishad Yavatmal", "NAGARPALIKA PARISAD KHODA GHAZIABAD", "Odisha Municipal Payments - Property Tax", "Orange Retail Finance India Pvt Ltd", "Pachgaon Grampanchayat", "Patna Municipal Corporation",
  "Pernem Municipal council", "Pernem Municipal council Trade License", "Pimpri Chinchwad Municipal Corporation (PCMC)", "Ponda Municipal Council Trade License", "Port Blair Municipal Council",
  "Prayagraj Nagar Nigam - Property", "Puducherry Urban Development Agency(LAD)-Property Tax", "Pune Municipal Corporation - Property Tax", "Quepem Municipal council", "Quepem Municipal council Trade License",
  "Raha Municipal Corporation", "Raipur Municipal Corporation", "Rajkot Municipal Corporation", "RDPR - KARNATAKA - BAPUJI SEVA KENDRA", "Sanguem Municipal council", "Sanguem Municipal Council Trade License",
  "Sankhali Municipal council", "Sankhali Municipal council Trade License", "Shivamogga City Corporation", "SILIGURI JALPAIGURI DEVELOPMENT AUTHORITY", "Solapur Municipal Corporation",
 "Talegaon Dabhade Nagar Parishad - Municipal Taxes and Services Payments", "Tamil Nadu Civil Supplies And Customer Protection Dept", "Tamilnadu Urban eSevai Municipal Taxes",
  "Tamilnadu Urban eSevai Service Charges", "Tarapith Rampurhat Development Authority(TRDA)", "Thane Municipal Corporation Property Tax", "UDD Uttarakhand", "Ulhasnagar Municipal Corporation",
  "Vadiyeraibag Grampanchayat", "Vadodara Municipal Corporation Property Tax", "Valpoi Municipal council", "Valpoi Municipal council Trade License", "Varanasi Nagar Nigam Property Tax", 
  "Vasai Virar Municipal Corporation - Property" 
],
  
LPGGas: ["Bharat Gas (BPCL) - Commercial" , "Bharat Petroleum Corporation Limited (BPCL)"  ,  "Hindustan Petroleum Corporation Ltd (HPCL)" , "Indane Gas (Indian Oil)"],
SubscriptionFees:[""],   
CreditCard:["Credit Card Bill Payment"]


};

function handleServiceChange(service) {
    const subDropdown = document.getElementById("cmbSubService");
    subDropdown.innerHTML = `<option value="">-- Select Sub-Service --</option>`;
    if (subservices[service]) {
        subservices[service].forEach(sub => {
            const opt = document.createElement("option");
            opt.value = sub;
            opt.textContent = sub;
            subDropdown.appendChild(opt);
        });
    }

    // Hide all service fields
    document.querySelectorAll(".service-fields").forEach(div => div.classList.add("d-none"));

    // Show selected service fields
    const selectedDiv = document.getElementById(service + "Fields");
    if (selectedDiv) {
        selectedDiv.classList.remove("d-none");
    }
}

 
  
//   document.querySelectorAll('.form-control').forEach(function (input) {
//     input.addEventListener('input', function () {
//       // Remove all non-digit characters
//       this.value = this.value.replace(/\D/g, '');

//       // Optional: Enforce max length in case someone pastes longer input
//       const maxLength = this.getAttribute('maxlength');
//       if (maxLength && this.value.length > maxLength) {
//         this.value = this.value.slice(0, maxLength);
//       }
//     });
//   });

</script>
</body>
</html> 