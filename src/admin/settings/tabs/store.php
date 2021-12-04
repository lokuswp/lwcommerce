<?php

// File dummy
// if (file_exists(LSDD_PATH . 'admin/assets/addons.json')) {
//     $json_file = file_get_contents(LSDD_PATH . 'admin/assets/addons.json');
// }
// $addons = json_decode($json_file ?? '') ?? '';

?>
<div class="columns">
    <div class="column col-12">
        <div class="filter">
            <input class="filter-tag d-hide" id="tag-0" type="radio" name="filter-radio" hidden="" checked="">
            <input class="filter-tag d-hide" id="tag-1" type="radio" name="filter-radio" hidden="">
            <input class="filter-tag d-hide" id="tag-2" type="radio" name="filter-radio" hidden="">
            <input class="filter-tag d-hide" id="tag-3" type="radio" name="filter-radio" hidden="">
            <input class="filter-tag d-hide" id="tag-4" type="radio" name="filter-radio" hidden="">
            <div class="filter-nav">
                <label class="chip" for="tag-0"><?php _e('Semua', 'lsddonation'); ?></label>
                <label class="chip" for="tag-3"><?php _e('Notifikasi', 'lsddonation'); ?></label>
                <label class="chip" for="tag-1"><?php _e('Pembayaran ', 'lsddonation'); ?></label>
                <label class="chip" for="tag-2"><?php _e('Galang Dana', 'lsddonation'); ?></label>
            </div>
            <div class="filter-body columns">

             

            </div>
        </div>
    </div>
</div>

<style>
    .card {
        padding: 0;
        border: none;
    }
</style>

<script>
    (function($) {
        'use strict';

        $(document).ready(function() {
            function actionAddon(data) {
                return $.ajax({
                    url: lsdd_admin.ajax_url,
                    method: 'POST',
                    data: {
                        action: 'lsdd_admin_action_addon',
                        data: data,
                        security: lsdd_admin.ajax_nonce,
                    },
                    success: (res) => {
                        console.log(res)
                        if (!res.success) {
                            actionAddon(data); // Recursive function
                        } else {
                            console.log(res.data)
                            location.reload();
                        }
                    },
                })
            }

            $('.enable-addon').on('click', function() {
                const that = $(this);
                that.addClass("loading");
                const data = {
                    'basename': that.siblings('.basename').val(),
                    'plugin_name': that.siblings('.pluginname').val(),
                    'action': 'enable',
                };
                // that.addClass('loading');
                actionAddon(data).fail(() => alert('please check your internet connection!'));
            })
            $('.disable-addon').on('click', function() {
                const that = $(this);
                const data = {
                    'basename': that.siblings('.basename').val(),
                    'plugin_name': that.siblings('.pluginname').val(),
                    'action': 'disable',
                };
                that.addClass('loading');
                actionAddon(data).fail(() => alert('please check your internet connection!'));
            })
        });
    })(jQuery)
</script>