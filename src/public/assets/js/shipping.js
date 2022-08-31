(function ($) {
    'use strict'

    // On Ready
    document.addEventListener("DOMContentLoaded", function () {

        let urlCurrent = window.location.pathname.split('/');
        var states = document.getElementById("states");

        if (urlCurrent[2] != 'trx' && states.length) {
            /*****************************************
             * Get Provinces List
             * Request Provinces List from RajaOngkir API
             *
             * @since 0.1.0
             *****************************************
             */
            jQuery.ajax({
                url: lokuswp.rest_url + "lwcommerce/v1/rajaongkir/province",
                type: 'GET',
                success: function (response) {

                    if (response.data && typeof (states) != 'undefined' && states != null) {
                        for (var i = 0; i < response.data.length; i++) {
                            var ele = document.createElement("option");
                            ele.value = response.data[i].province_id;
                            ele.innerHTML = response.data[i].province;
                            states.appendChild(ele);
                        }
                        // $(this).find('#states').get(0).remove();
                    }

                    // Set Extras fot take away
                    lwpCheckout.setExtra("shipping", "Biaya Pengiriman", 'take-away', 0, "+", "fixed", "subtotal");
                    lwpCheckout.setExtraField("shipping", {
                        provider: 'takeaway',
                        service: 'ambil ditempat',
                        courier: 'Take Away',
                        destination: '-',
                        weight: '0',
                    });

                    // Render Summary
                    lwpRender.trxExtras().trxTotal();

                },
                error: function (data) {
                    $(document).snackbar('Tidak dapat mengambil data dari server, Silahkan Coba Lagi');
                    console.log(data);
                }
            });

        }
    });

    /*****************************************
     * User Change State in Shipping Section
     * Getting Cities Based on Selected State
     *
     * @since 0.1.0
     *****************************************
     */
    $(document).on('change', '#lwcommerce-shipping #states', function (e) {
        let state = $(this).find(":selected").val();

        jQuery.ajax({
            url: lokuswp.rest_url + "lwcommerce/v1/rajaongkir/city?province=" + state,
            type: 'GET',
            success: function (response) {

                if (response.data) {
                    $('#cities').empty();
                    for (var i = 0; i < response.data.length; i++) {
                        var ele = document.createElement("option");
                        ele.value = response.data[i].city_id;
                        ele.innerHTML = response.data[i].type + ' ' + response.data[i].city_name;
                        document.getElementById("cities").appendChild(ele);
                    }
                    // $(this).find('#cities').get(0).remove();
                }

            },
            error: function (data) {
                $(document).snackbar('Tidak dapat mengambil data dari server, Silahkan Coba Lagi');
                console.log(data);
            }
        });

    });


    /*****************************************
     * User Change City in Shipping Section
     * Getting Shipping Services Based on Selected City
     *
     * @since 0.1.0
     *****************************************
     */
    $(document).on('change', '#lwcommerce-shipping #cities', function (e) {
        const shippingServiceElement = $('#lwcommerce-shipping-services');
        shippingServiceElement.children().remove();
        shippingServiceElement.addClass('loading loading-lg');

        let destination = $('#cities').find(":selected").val();
        let cart_uuid = lokusCookie.get("lokuswp_cart_session");


        // Request to REST API
        jQuery.ajax({
            url: lokuswp.ajax_wp,
            type: 'POST',
            data: {
                action: 'lwcommerce_get_shipping_services',
                destination: destination,
                cart_uuid: cart_uuid,
            },
            success: function (response) {
                if (response.success) {
                    // Formatting Struct and Data
                    let shippingStruct = jQuery('#struct-shipping-services').html();

                    let rawData = {};
                    rawData = response.data;

                    let shippingData = {
                        'shippingServices': rawData
                    };

                    // Formatting Currency
                    shippingData.currencyFormat = function () {
                        return function (val, render) {
                            return lwpCurrencyFormat(true, render(val));
                        };
                    }

                    // Rendering with Mustache
                    jQuery("#lwcommerce-shipping-services").html(Mustache.to_html(shippingStruct, shippingData));

                    shippingServiceElement.removeClass('loading loading-lg');

                    // Saving to Local with Cache
                    // lokusCookie.set("lokuswp_shipping_list", JSON.stringify(shippingData), 1); // 1 Day Expired
                }
            },
            error: function (data) {
                if (data.responseJSON.message !== "" && typeof data.responseJSON.message !== "undefined") {
                    $(document).snackbar(data.responseJSON.message);
                    return;
                }
                $(document).snackbar('Tidak dapat mengambil data dari server, Silahkan Coba Lagi');
                console.log(data);
            }
        })

    });

    /*****************************************
     * User Choose Shipping Service
     * Adding Shipping Cost to Summary
     *
     * @since 0.1.0
     *****************************************
     */
    $(document).on('change', 'input[name="shipping_channel"]', function (e) {
        e.preventDefault();
        console.log("User Choose or Change Shipping Channel");

        let title = $(this).attr("title");
        let service = $(this).attr('service');
        let cost = $(this).attr('cost');
        let id = $(this).attr('id');

        // Ser Extras
        lwpCheckout.setExtra("shipping", "Biaya Pengiriman", title + " - " + service, cost, "+", "fixed", "subtotal");
        lwpCheckout.setExtraField("shipping", {
            provider: id.split('-').slice(0, -1).join('-'),
            service: service,
            courier: title,
            destination: $('#cities').find(":selected").val(),
            address: $('#shipping_address').val(),
            weight: 20,
        });

        // Render Summary
        lwpRender.trxExtras().trxTotal();
    });


    /*****************************************
     * User Choose Shipping Service
     * Adding Shipping Cost to Summary
     *
     * @since 0.1.0
     *****************************************
     */
    $(document).on('change', 'input[name="shipping_type"]', function (e) {
        e.preventDefault();
        console.log("User Choose or Change Shipping Type");

        let id = $(this).attr("id");

        if (id == "shipping") {
            $("#address-field").css("display", "flex");
        } else {
            $("#address-field, #lwcommerce-shipping-services").css("display", "none");
            lwpRender.trxExtras().trxTotal();
        }

    });

    /*****************************************
     * When User Click on Continue Button
     * Verify Shipping Section is Valid
     *
     * @since 0.1.0
     *****************************************
     */
    $(document).on('click', '#lwc-verify-shipping', function (e) {
        e.preventDefault();

        //Shipping Checking
        swiperTabsContent.slideTo(2);
    });

})(jQuery);