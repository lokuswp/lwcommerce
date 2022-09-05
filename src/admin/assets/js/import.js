"use strict";

jQuery(document).ready(function ($) {

    $("#dialog").dialog({
        autoOpen: false,
        resizable: false,
        width: 'auto',
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        buttons: {
            "Import": handleImport
        }
    });

    $('.button div.import').on('click', function (e) {
        e.preventDefault();
        $("#dialog").dialog("open");
    })

    const csvUpload = $('#csv-upload');

    csvUpload.on('change', function () {
        checkFileExtension($(this))
        $(this).siblings('span').text($(this)[0].files[0].name);
    })

    const checkFileExtension = (el) => {
        const fileExtension = el.val().split('.').pop().toLowerCase();
        if (fileExtension !== 'csv') {
            alert('File format must be .csv');
            return false;
        }

        return true;
    }

    function handleImport() {
        if (!checkFileExtension(csvUpload)) return;

        const fileData = csvUpload.prop('files')[0];
        const formData = new FormData();
        formData.append('file', fileData);
        formData.append('action', 'lwc_import_product');
        formData.append('security', lwc_product.ajax_nonce);

        $.ajax({
            url: lwc_product.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (!response.success) {
                    alert(response.data);
                    return;
                }
                alert(`Success import ${response.data} product`);
                location.reload();
            }
        }).fail(function () {
            alert('Please check your internet connection');
        });
    }
})