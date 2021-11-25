(function ($) {
    $(document).ready(function () {
        //=============== Datatables ===============//
        const tableReport = $('#reportss').DataTable()

        tableReport.on('draw', function () {
            document.querySelector('.lsdd-screen-loading').style.display = 'none';
        });
    });
})(jQuery)