// ----- PRELOADER -----
const PRELOADER = `
<div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center py-5">
    <div class="spinner-grow spinner-grow-lg text-secondary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h6 class="mt-2">Please wait...</h6>
</div>`;
// ----- END PRELOADER -----


// ----- SHOW TOAST -----
function showToast(type = 'success', text = '') {
    if (type == 'success') {
        new bs5.Toast({
            body: `
            <div class="d-flex gap-3">
                <img class="rounded-circle" width="30" height="30" src="/assets/img/modal/success.png">
                <div>
                    <h4 class="mb-0">SUCCESS</h4>
                    <p>
                        ${text}
                    </p>
                </div>
            </div>`,
            className: `border-0 bg-success text-white`,
            btnCloseWhite: true,
        }).show()
    } else if (type == 'danger') {
        new bs5.Toast({
            body: `
            <div class="d-flex gap-3">
                <img class="rounded-circle" width="30" height="30" src="/assets/img/modal/error.png">
                <div>
                    <h4 class="mb-0">Error</h4>
                    <p>
                        ${text}
                    </p>
                </div>
            </div>`,
            className: `border-0 bg-danger text-white`,
            btnCloseWhite: true,
        }).show()
    }

}
// ----- END SHOW TOAST -----


// ----- INITIALIZE SELECT2 -----
function initSelect2() {
    $(`[select2]`).select2();
}
// ----- END INITIALIZE SELECT2 -----


// ----- INITIALIZE DATERANGEPICKER -----
function initDateRangePicker() {
    $(`[daterangepicker]`).daterangepicker({
        opens: 'left',
        showDropdowns: true,
        locale: {
            format: 'MMMM DD, YYYY'
        }
    }, function(start, end, label) {
        console.log(start.format('YYYY-MM-DD'));
        console.log(end.format('YYYY-MM-DD'));
    });
}
// ----- END INITIALIZE DATERANGEPICKER -----



$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ----- INIT ALL -----
    function initAll() {
        initSelect2(); // Initialize select2
        initDateRangePicker();
    }
    initAll();
    // ----- END INIT ALL -----

})