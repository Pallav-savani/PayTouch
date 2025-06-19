<?php
$activePage = 'fasttag';
include __DIR__ . '../../component/header.php';

$username = $_SESSION['user'] ?? '';
?>

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
            background-color: #f4f9fc;
            margin: 0;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
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
    </style>
</head>
<body>

<h2>FASTag Recharge - PayTouch</h2>

<div class="service-card card mt-3"> 
    <div class="d-flex align-items-start user-service-tab">
        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active left-space" id="v-pills-recharge-tab" data-bs-toggle="pill"
                data-bs-target="#v-pills-recharge" type="button" role="tab" aria-controls="v-pills-recharge"
                aria-selected="true">Fastag Recharge</button>
        </div>
    </div>
</div>

<div class="container">
    <!-- FASTag Form -->
    <div class="fastag-form">
        <h3>Recharge Your FASTag</h3>

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

        <?php if ($username): ?>
            <p>Welcome, <?= htmlspecialchars($username); ?>!</p>
            <?php
            $host = 'srv1000.hstgr.io';
            $db_name = 'u589796865_paytouch';
            $db_user = 'u589796865_utilityuser';
            $db_pass = 'Sfspl@2024';

            try {
                $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM fastag_recharges WHERE username = ? ORDER BY created_at DESC");
                $stmt->execute([$username]);
                $recharges = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
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
                <tbody>
                <?php if (count($recharges)): ?>
                    <?php foreach ($recharges as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['vehicle']) ?></td>
                            <td><?= htmlspecialchars($r['bank']) ?></td>
                            <td>₹<?= htmlspecialchars($r['amount']) ?></td>
                            <td><?= htmlspecialchars($r['status']) ?></td>
                            <td><?= htmlspecialchars($r['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No FASTag recharge history found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?php } catch (Exception $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            } ?>
        <?php else: ?>
            <p>Please log in to view history.</p>
        <?php endif; ?>
    </div>
</div>

<script>
async function rechargeFastag() {
    const vehicle = document.getElementById("vehicleNumber").value.trim();
    const bank = document.getElementById("bank").value;
    const amount = document.getElementById("amount").value.trim();

    if (!vehicle || !bank || !amount || isNaN(amount)) {
        alert("Please fill all fields correctly.");
        return;
    }

    const response = await fetch('fastag_process.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ vehicle, bank, amount })
    });

    const result = await response.json();
    if (result.success) {
        alert("FASTag Recharge Successful!");
        location.reload();
    } else {
        alert("Recharge Failed: " + result.message);
    }
}
</script>

</body>
</html>

<?php include __DIR__ . '../../component/footer.php'; ?>
