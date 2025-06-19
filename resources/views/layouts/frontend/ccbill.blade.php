@include('layouts.header')
<style>

/* Form Elements */
form {
  max-width: 600px;
  margin: 0 auto;
  padding: 0 20px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: 600;
  margin-bottom: 8px;
}

input[type="text"],
input[type="number"],
input[type="email"],
input[type="date"],
select,
textarea {
  width: 100%;
  padding: 10px 12px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box;
  transition: border 0.3s ease;
  margin-bottom: 12px;
}

input:focus,
select:focus,
textarea:focus {
  border-color: #0066cc;
  outline: none;
}

/* Buttons */
button,
input[type="submit"] {
  background-color: #003399;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  font-size: 15px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

/* Tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  overflow-x: auto;
}

table thead {
  background-color: #003399;
  color: #ffffff;
}

table th,
table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
}

table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Status Badges */
.status-success {
  color: green;
  font-weight: bold;
}

.status-failed {
  color: red;
  font-weight: bold;
}

.img-box{
  width: 100%;
  display: flex;
  justify-content: flex-end;
}
.img-box-sms{
  width: 33%;
  display: flex;
  justify-content: flex-end;
}
.right-logo {
  width: 150px;
  display: block;
}
.receipt{
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.sms_recpt_logo{
  width: 250px;
}
.logo{
  width: 100px;
}

.status-pending {
  color: orange;
  font-weight: bold;
}

#ccFetchBillForm{
  max-width: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin: 0;
  border-bottom: 1px solid #d4d4d4;
  padding-bottom: 20px;
}

.form-group{
  width: 50%;
}


/* Responsive Design */
@media screen and (max-width: 992px) {
  .right-logo {
    margin: 10px auto;
    display: block;
  }

  nav button {
    flex: 1 1 50%;
    text-align: center;
  }

  .tab-content {
    padding: 20px 15px;
  }
}

@media screen and (max-width: 768px) {
  nav {
    flex-direction: column;
  }

  nav button {
    flex: 1 1 100%;
  }


  form {
    padding: 0 10px;
  }


  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
    width: 100%;
  }

  table thead {
    display: none;
  }

  table tr {
    margin-bottom: 15px;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    background-color: #fff;
  }

  table td {
    position: relative;
    padding-left: 50%;
    text-align: left;
  }

  table td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    font-weight: bold;
    color: #555;
  }
}

    table {
              width: 51%;
              border-collapse: collapse;
              margin-top:0px ;
              font-size: 14px;
              border: none;
              
    }
</style>

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

@include('layouts.frontend.ccbill_script')
