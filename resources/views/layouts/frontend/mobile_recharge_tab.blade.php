@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTouch | Mobile Recharge</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f9fc;
            margin: 0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
        }
        .recharge-form, .history {
            background: linear-gradient(to bottom right, #c471ed, #f64f59);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin: 10px;
        }
        .recharge-form {
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
            color: #c471ed;
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
            color: #c471ed;
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
            text-align: center;
        }
        .loading {
            color: #f64f59;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Mobile Recharge - PayTouch</h2>

<div class="service-card card mt-3">
    <div class="card-body">
        <div class="d-flex align-items-start user-service-tab">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-recharge-tab" data-bs-toggle="pill"
                    data-bs-target="#v-pills-recharge" type="button" role="tab" aria-controls="v-pills-recharge"
                    aria-selected="true">Mobile Recharge</button>   
            </div>

<div class="container">

    <!-- Recharge Form -->
    <div class="recharge-form">
        <h3>Select Recharge Plan</h3>

        <input type="text" id="mobile" placeholder="Enter Mobile Number" maxlength="10" oninput="validateMobile()" />

        <select id="operator" onchange="loadPlans()">
            <option value="">Select Operator</option>
            <option value="1">Airtel</option>
            <option value="2">Jio</option>
        </select>

        <select id="circle" onchange="loadPlans()">
            <option value="">Select Circle</option>
            <option value="1">Andhra Pradesh</option>
            <option value="2">Delhi</option>
        </select>

        <select id="planType" onchange="loadPlans()">
            <option value="2">All Plans</option>
            <option value="3">Topup</option>
            <option value="17">Special Offer</option>
        </select>

        <div id="plansList" style="margin-top: 20px;">
            <p class="loading">Please select operator & circle to load plans.</p>
        </div>

        <input type="text" id="RechargeAmt" placeholder="Recharge Amount" maxlength="3" />

        <button onclick="recharge()">Recharge Now</button>
    </div>

    <!-- Recharge History -->
    <div class="history">
        <h3>My Recharge History</h3>
        <table>
            <thead>
                <tr>
                    <th>Mobile</th>
                    <th>Operator</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Will be loaded dynamically -->
            </tbody>
        </table>
    </div>

</div>

<script>
function validateMobile() {
    const mobile = document.getElementById("mobile").value;
    document.getElementById("mobile").style.borderColor =
        (mobile.length === 10 && /^[0-9]+$/.test(mobile)) ? "green" : "red";
}

async function loadPlans() {
    const operator = document.getElementById("operator").value;
    const circle = document.getElementById("circle").value;
    const planType = document.getElementById("planType").value;
    const plansList = document.getElementById("plansList");

    if (!operator || !circle) {
        plansList.innerHTML = "<p style='color:red;'>Please select both operator and circle.</p>";
        return;
    }

    plansList.innerHTML = "<p class='loading'>Loading plans...</p>";

    let url = `http://127.0.0.1:8000/api/rechargepending`; 
    if (planType) url += `/${planType}`;

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "X-Mclient": "14"
            }
        });
        const result = await response.json();

        if (result.success && result.data.plans.length > 0) {
            plansList.innerHTML = result.data.plans.map(plan => `
                <div style="border:1px solid #ccc; padding:10px; margin:10px 0; background:#fff; color:#000;">
                    <strong>₹${plan.amount}</strong> - ${plan.planName}<br>
                    <small>${plan.planDescription}</small><br>
                    <em>Validity: ${plan.validity}</em>
                </div>
            `).join("");
        } else {
            plansList.innerHTML = `<p style="color:orange;">${result.message?.text || "No plans available."}</p>`;
        }
    } catch (error) {
        console.error("API Error: ", error);
        plansList.innerHTML = "<p style='color:red;'>Something went wrong while fetching plans.</p>";
    }
}

async function recharge() {
    const mobile = document.getElementById("mobile").value;
    const operator = document.getElementById("operator").value;
    const circle = document.getElementById("circle").value;
    const planType = document.getElementById("planType").value;
    const amount = document.getElementById("RechargeAmt").value;

    if (!mobile || mobile.length !== 10 || !/^[0-9]+$/.test(mobile)) {
        alert("Please enter a valid mobile number.");
        return;
    }
    if (!operator || !circle || !planType || !amount) {
        alert("Please fill all fields including selecting a plan.");
        return;
    }

    alert("Recharge processing (frontend simulation only).\nNo real transaction submitted.");
    // In production, replace this with an actual API POST call
}

function loadRechargeHistory() {
    const tbody = document.querySelector(".history tbody");
    // Simulated data (can be replaced with API fetch)
    const demoData = [
        { mobile: "9876543210", operator: "Jio", type: "Topup", amount: "149", status: "Success", created_at: "2025-06-09 10:15" },
        { mobile: "9876543210", operator: "Airtel", type: "Special", amount: "249", status: "Pending", created_at: "2025-06-08 18:40" }
    ];

    tbody.innerHTML = demoData.map(r => `
        <tr>
            <td>${r.mobile}</td>
            <td>${r.operator}</td>
            <td>${r.type}</td>
            <td>₹${r.amount}</td>
            <td>${r.status}</td>
            <td>${r.created_at}</td>
        </tr>
    `).join('');
}

window.onload = loadRechargeHistory;
</script>

</body>
</html>

 