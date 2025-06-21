@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PayTouch | FASTag Recharge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .left-space {
            margin-left: 15px;
            padding-left: 15px;
        }

        .user-service-tab {
            width: 100%;
        }

        .nav-pills {
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            /* background-color: #f4f9fc; */
            margin: 0;
        }

        .container1 {
            display: flex;
            flex-wrap: wrap;
            padding-left: 200px;
            width: 80%;
            height:400px;
        }

        .fastag-form, .history {
            background: linear-gradient(to bottom right, #43cea2, #185a9d);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin: 10px;
        }

        .fastag-form {
            flex: 1;
            min-width: 300px;
        }

        .history {
            flex: 2;
            background: white;
            color: black;
            overflow-x: auto;
            min-width: 400px;
        }

        h2 {
            text-align: center;
            color: #185a9d;
        }

        h3 {
            text-align: center;
        }

        input, select {
            padding: 10px;
            width: 90%;
            margin-bottom: 10px;
            border: none;
            border-radius: 6px;
        }

        button {
            padding: 10px 20px;
            background-color: white;
            color: #185a9d;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .history p {
            margin-bottom: 15px;
        }
        .c1{
            text-align:left;
        }
    </style>
</head>
<body> 
<div class="service-card card mt-3">
        <div class="d-flex align-items-start user-service-tab">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active left-space" id="v-pills-recharge-tab"
                    type="button" role="tab" aria-selected="true">FASTag Recharge</button>
            </div>
        </div>
    

    <div class="container1">
        <!-- FASTag Form -->
        <div class="fastag-form">
            <h3 class="c1">Recharge Your FASTag</h3>

            <input type="text" id="vehicleNumber" placeholder="Enter Vehicle Number (e.g. KA01AB1234)" />

            <select id="bank">      
                <option value="">Select FASTag Provider</option>
                <option value="icici">ICICI Bank</option>
                <option value="sbi">SBI Bank</option>
                <option value="hdfc">HDFC Bank</option>
                <option value="axis">Axis Bank</option>
            </select>

            <input type="number" id="amount" placeholder="Enter Recharge Amount" />

            <button onclick="rechargeFastag()">Recharge Now</button>
        </div>

        <!-- Recharge History -->
        <div class="history">
            <h3>FASTag Recharge History</h3>
            <p>Welcome, <strong>demo_user</strong>!</p>
            <table>
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Bank</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="historyTable">
                    <tr>
                        <td>KA01AB1234</td>
                        <td>ICICI Bank</td>
                        <td>₹500</td>
                        <td>Success</td>
                        <td>2025-06-19 12:34:56</td>
                    </tr>
                    <!-- New rows will be added by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function rechargeFastag() {
    const vehicle = document.getElementById("vehicleNumber").value.trim();
    const bank = document.getElementById("bank").value;
    const amount = document.getElementById("amount").value.trim();

    if (!vehicle || !bank || !amount || isNaN(amount)) {
        alert("Please fill all fields correctly.");
        return;
    }

    // Simulate successful recharge
    alert("FASTag Recharge Successful!");

    // Add to dummy history table
    const table = document.getElementById("historyTable");
    const row = table.insertRow(1);
    row.innerHTML = `
        <td>${vehicle}</td>
        <td>${bank.toUpperCase()} Bank</td>
        <td>₹${amount}</td>
        <td>Success</td>
        <td>${new Date().toLocaleString()}</td>
    `;

    // Reset form
    document.getElementById("vehicleNumber").value = "";
    document.getElementById("bank").value = ""; 
    document.getElementById("amount").value = "";
}
</script>

</body>
</html>
