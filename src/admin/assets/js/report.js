(function ($) {
    $(document).ready(function () {
        //=============== Datatables ===============//
        const tableReport = $('#reports').DataTable()

        tableReport.on('draw', function () {
            document.querySelector('.lsdd-screen-loading').style.display = 'none';
        });
    });
})(jQuery)