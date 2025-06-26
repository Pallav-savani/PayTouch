@include('layouts.header')
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CC Bill Pay</title>
    <link rel="stylesheet" href="{{ asset('css/ccbill.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>
<body><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CC Bill Pay</title>
    <link rel="stylesheet" href="{{ asset('css/ccbill.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
    <!-- Your page content here -->
</body>
</html>

<div class="row" style="max-width: 100%;">
    <div class="container my-4">
        <div class="d-flex align-items-start user-service-tab">
            <!-- Left Tab Navigation -->
            <div class="px-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-cc-fetch-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cc-fetch"
                        type="button" role="tab" aria-controls="v-pills-cc-fetch" aria-selected="false">FETCH BILL</button>

                    <button class="nav-link" id="v-pills-cc-complaint-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cc-complaint"
                        type="button" role="tab" aria-controls="v-pills-cc-complaint" aria-selected="false">Register Complaint</button>

                    <button class="nav-link" id="v-pills-cc-complaint-status-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cc-complaint-status"
                        type="button" role="tab" aria-controls="v-pills-cc-complaint-status" aria-selected="false">Complaint Status</button>

                    <button class="nav-link" id="v-pills-cc-txn-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cc-txn-history"
                        type="button" role="tab" aria-controls="v-pills-cc-txn-history" aria-selected="false">Transaction History</button>

                    <button class="nav-link" id="v-pills-cc-sms-r-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cc-sms-r"
                        type="button" role="tab" aria-controls="v-pills-cc-sms-r" aria-selected="false">SMS Receipt</button>
                </div>
            </div>

            <div class="tab-content w-100" id="v-pills-tabContent">

                @include('layouts.frontend.cc_fetch')

                @include('layouts.frontend.cc_complaint')

                @include('layouts.frontend.cc_complaint_status')

                @include('layouts.frontend.cc_txn_history')

                @include('layouts.frontend.cc_sms_receipt')

            </div>
        </div>
    </div>
</div>
</body>
</html>

@include('layouts.frontend.ccbill_script')

