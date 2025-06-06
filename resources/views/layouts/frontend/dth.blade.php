@include('layouts.header')

<!-- Toast Container for Top Right Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div id="alertToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastBody">
            <!-- Message will be inserted here -->
        </div>
    </div>
</div>

<div class="row" style="max-width: 100%;">
    <div class="container" style="margin-top: 1rem; margin-bottom: 2rem;">
        <div class="d-flex align-items-start user-service-tab">
            <div class="px-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home"
                        role="tab" aria-controls="v-pills-home" aria-selected="true">Recharge</a>
                    <a class="nav-link" id="v-pills-status-tab" data-bs-toggle="pill" href="#v-pills-status"
                        role="tab" aria-controls="v-pills-status" aria-selected="false">Transaction Status</a>
                    <a class="nav-link" id="v-pills-report-tab" data-bs-toggle="pill" href="#v-pills-report"
                        role="tab" aria-controls="v-pills-report" aria-selected="false">Transaction Report</a>
                    <a class="nav-link" id="v-pills-pending-tab" data-bs-toggle="pill" href="#v-pills-pending"
                        role="tab" aria-controls="v-pills-pending" aria-selected="false">Pending Transaction's</a>
                    <a class="nav-link" id="v-pills-failed-tab" data-bs-toggle="pill" href="#v-pills-failed"
                        role="tab" aria-controls="v-pills-failed" aria-selected="false">Failed Transaction's</a>
                </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                @include('layouts.frontend.dth_recharge_tab')

                @include('layouts.frontend.dth_status_tab')

                @include('layouts.frontend.dth_report_tab')

                @include('layouts.frontend.dth_pending_tab')

                @include('layouts.frontend.dth_failed_tab')

            </div>
        </div>
    </div>
</div>

@include('layouts.frontend.dth_scripts')

