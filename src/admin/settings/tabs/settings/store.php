<?php
$name = lwpc_get_settings('store', 'name');
$logo = lwpc_get_settings('store', 'logo', 'esc_url', 'https://lokuswp.id/wp-content/uploads/2021/12/lokago.png');
$desc = lwpc_get_settings('store', 'description');

$email    = lwpc_get_settings('store', 'email');
$whatsapp = lwpc_get_settings('store', 'whatsapp');

$address            = lwpc_get_settings('store', 'address');
$country_selected   = lwpc_get_settings('store', 'country');
$state_selected     = lwpc_get_settings('store', 'state', 'intval');
$city_selected      = lwpc_get_settings('store', 'city', 'intval');
$district_selected  = lwpc_get_settings('store', 'district', 'intval');

$latitude           = lwpc_get_settings('store', 'latitude', 'floatval');
$longitude          = lwpc_get_settings('store', 'longitude', 'floatval');

$categories = [
    'digital' => __('Digital Goods', "lwpcommerce"),
    'fashion' => __('Fashion, Apparel', "lwpcommerce"),
    'electronics' => __('Electronics, Computer', "lwpcommerce"),
    'fnb' => __('Food and Drink', "lwpcommerce"),
];

// Get Data Province
$request = wp_remote_get(get_rest_url() . 'lwpcommerce/v1/rajaongkir/province');

if (is_wp_error($request)) {
    return false; // Bail early
}
$body = wp_remote_retrieve_body($request);
$states = json_decode($body)->data;


$request2 = wp_remote_get( get_rest_url() . 'lwpcommerce/v1/rajaongkir/city?province=' . $state_selected);
if (is_wp_error($request2)) {
    return false; // Bail early
}
$body = wp_remote_retrieve_body($request2);
$cities = json_decode($body)->data;

?>

<section id="settings" class="form-horizontal">
    <form>

        <!-- Name -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="name"><?php _e('Name', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="text" class="form-input" name="name" placeholder="Lokus Store" value="<?php echo $name; ?>" />
            </div>
        </div>

        <!-- Logo -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="logo"><?php _e('Logo', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <img style="width:75px;" src="<?php echo $logo; ?>" />
                <input class="form-input lwp-hidden" type="text" name="logo">
                <input type="button" value="<?php _e('Choose Image', 'lwpcommerce'); ?>" class="lokuswp-admin-upload btn col-12">
            </div>
        </div>

        <!-- Description -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="description"><?php _e('Description', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="text" class="form-input" name="description" placeholder="<?php _e("The best online shop ever", "lwpcommerce"); ?>" value="<?php echo $desc; ?>" />
            </div>
        </div>

        <!-- Category -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="category"><?php _e('Category', 'lwpcommerce'); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="category" id="form-category">
                    <?php foreach ($categories as $key => $value) : ?>
                        <option value="<?= $key ?? '' ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!--Kontak Toko-->
        <div class="divider" data-content="<?php _e('Store Contact', 'lwpcommerce'); ?>"></div>

        <!-- Email -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="email"><?php _e('Email', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="email" class="form-input" name="email" placeholder="lokuswp@gmail.com" value="<?php echo $email; ?>" />
            </div>
        </div>

        <!-- Whatsapp -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="whatsapp"><?php _e('Whatsapp', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="number" class="form-input" name="whatsapp" placeholder="08126876418" value="<?php echo $whatsapp; ?>" />
            </div>
        </div>

        <!--Lokasi Toko-->
        <div class="divider" data-content="<?php _e('Store Location', 'lwpcommerce'); ?>"></div>

        <!-- Country -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="country"><?php _e('Country', 'lwpcommerce'); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="country" id="form-country">
                    <!-- lwpcommerce-admin.js : onChange trigger result States -->
                    <option name="indonesia">Indonesia</option>
                </select>
            </div>
        </div>

        <!-- State -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="state"><?php _e('State', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <select class="form-select" name="state" id="form-state">
                    <option value="<?= $state_selected ?? '' ?>"><?php _e('Choose your state', 'lwpcommerce'); ?></option>
                    <?php foreach ($states as $key => $state) : ?>
                        <option value="<?php echo $state->province_id; ?>" <?php echo ($state->province_id == $state_selected) ? 'selected' : ''; ?>><?php echo $state->province; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- District -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="city"><?php _e('City', 'lwpcommerce'); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="city">
                    <option value="<?= $city_selected ?? '' ?>"><?php _e('Choose your city', 'lwpcommerce'); ?></option>
                    <?php foreach ($cities as $key => $city) : ?>
                        <option value="<?php echo $city->city_id; ?>" <?php echo ($city->city_id == $city_selected) ? 'selected' : ''; ?>><?php echo $city->type . ' ' . $city->city_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Address -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="address"><?php _e('Address', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <textarea class="form-input" name="address" placeholder="Jl.Jendral Sudirman no 40. 15560" rows="3"><?php echo $address; ?></textarea>
            </div>
        </div>

        <!-- Koordinat -->
        <div class="form-group lwp-hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="coordinat"><?php _e('Coordinat', 'lwpcommerce'); ?></label>
            </div>
            <div class="col-2" style="margin-right: 0.2rem">
                <input type="number" class="form-input" name="latitude" placeholder="-6.123671823" value="<?php echo $latitude; ?>" />
            </div>
            <div class="col-2" style="margin-left: 0.2rem">
                <input type="number" class="form-input" name="longitude" placeholder="126.128635" value="<?php echo $longitude; ?>" />
            </div>
            <div class="col-2" style="margin-left: 1rem">
                <button class="btn get-coordinat">Get Koordinat</button>
            </div>
        </div>


        <!-- form checkbox control -->
        <!-- <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox">
            <i class="form-icon"></i> <?php _e('I agree to contribute organization data for better experience and mapping organization donation.') ?> what we collect. show @4.2.0
          </label>
        </div> -->

        <br>
        <button class="btn btn-primary w-120" id="lwpc-setting-store-save"><?php _e('Save', 'lwpcommerce'); ?></button>
    </form>
</section>

<script>
    // const apiWilayahIndonesia = 'http://www.emsifa.com/api-wilayah-indonesia/api';
    // const apiRajaOngkir = 'http://lwp.local/wp-json/lokuswp/v1/rajaongkir';
    // const stateId = document.querySelector('#form-state').value;
    // const districtId = document.querySelector('#form-district').value;
    // // const districtsId = document.querySelector('#form-districts').value;

    // // get province
    // fetch(`${apiRajaOngkir}/province`)
    //     .then(res => res.json())
    //     .then(data => {
    //         if (data.status === 'success') {
    //             data.data.forEach(province => {
    //                 if (province.province_id === stateId) {
    //                     document.querySelector('#form-state').innerHTML = `<option value="${province.province_id}">${province.province}</option>`;
    //                 }
    //                 let option = document.createElement('option');
    //                 option.value = province.province_id;
    //                 option.innerHTML = province.province;
    //                 document.querySelector('#form-state').appendChild(option);
    //             });
    //         } else {
    //             console.log(data.message);
    //         }
    //     });

    // if (districtId) {
    //     fetch(`${apiRajaOngkir}/city?province=${stateId}`)
    //         .then(res => res.json())
    //         .then(data => {
    //             if (data.status === 'success') {
    //                 data.data.forEach(city => {
    //                     if (city.city_id === districtId) {
    //                         document.querySelector('#form-district').innerHTML = `<option value="${city.city_id}">${city.city_name} (${city.type})</option>`;
    //                     }
    //                     let option = document.createElement('option');
    //                     option.value = city.city_id;
    //                     option.innerHTML = city.city_name + ' (' + city.type + ')';
    //                     document.querySelector('#form-district').appendChild(option);
    //                 });
    //             } else {
    //                 console.log(data.message);
    //             }
    //         });
    // }

    // // get city
    // document.querySelector('#form-state').addEventListener('change', function() {
    //     let stateId = this.value;
    //     let district = document.querySelector('#form-district');
    //     district.innerHTML = '';
    //     fetch(`${apiRajaOngkir}/city?province=${stateId}`)
    //         .then(res => res.json())
    //         .then(data => {
    //             if (data.status === 'success') {
    //                 data.data.forEach(city => {
    //                     let option = document.createElement('option');
    //                     option.value = city.city_id;
    //                     option.innerHTML = city.city_name + ' (' + city.type + ')';
    //                     district.appendChild(option);
    //                 });
    //             } else {
    //                 console.log(data.message);
    //             }
    //         });
    // });


    // fetch(`${apiWilayahIndonesia}/provinces.json`)
    //     .then(response => response.json())
    //     .then(provinces => {
    //         provinces.forEach(province => {
    //             if (province.id === stateId) {
    //                 document.querySelector('#form-state').innerHTML = `<option value="${province.id}">${province.name}</option>`;
    //             }
    //             let option = document.createElement('option');
    //             option.value = province.id;
    //             option.innerHTML = province.name;
    //             document.getElementById('form-state').appendChild(option);
    //         });
    //     });
    //
    // if (districtId) {
    //     fetch(`${apiWilayahIndonesia}/regencies/${stateId}.json`)
    //         .then(response => response.json())
    //         .then(regencies => {
    //             regencies.forEach(regency => {
    //                 if (regency.id === districtId) {
    //                     document.querySelector('#form-district').innerHTML = `<option value="${regency.id}">${regency.name}</option>`;
    //                 }
    //                 let option = document.createElement('option');
    //                 option.value = regency.id;
    //                 option.innerHTML = regency.name;
    //                 document.getElementById('form-district').appendChild(option);
    //             });
    //         });
    // }
    //
    // if (districtsId) {
    //     fetch(`${apiWilayahIndonesia}/districts/${districtId}.json`)
    //         .then(response => response.json())
    //         .then(districts => {
    //             districts.forEach(district => {
    //                 if (district.id === districtsId) {
    //                     document.querySelector('#form-districts').innerHTML = `<option value="${district.id}">${district.name}</option>`;
    //                 }
    //                 let option = document.createElement('option');
    //                 option.value = district.id;
    //                 option.innerHTML = district.name;
    //                 document.getElementById('form-districts').appendChild(option);
    //             });
    //         });
    // }
    //
    // document.querySelector('#form-state').addEventListener('change', function () {
    //     const id_province = this.value;
    //     const cities = document.querySelector('#form-district');
    //     cities.innerHTML = '';
    //     fetch(`${apiWilayahIndonesia}/regencies/${id_province}.json`)
    //         .then(response => response.json())
    //         .then(district => {
    //             district.forEach(district => {
    //                 let option = document.createElement('option');
    //                 option.value = district.id;
    //                 option.innerHTML = district.name;
    //                 document.getElementById('form-district').appendChild(option);
    //             });
    //         });
    // });
    //
    // document.querySelector('#form-district').addEventListener('change', function () {
    //     const id_district = this.value;
    //     const districts = document.querySelector('#form-districts');
    //     districts.innerHTML = '';
    //     fetch(`${apiWilayahIndonesia}/districts/${id_district}.json`)
    //         .then(response => response.json())
    //         .then(district => {
    //             district.forEach(district => {
    //                 let option = document.createElement('option');
    //                 option.value = district.id;
    //                 option.innerHTML = district.name;
    //                 document.getElementById('form-districts').appendChild(option);
    //             });
    //         });
    // });

    const lat = document.querySelector('input[name="latitude"]').value;
    const lon = document.querySelector('input[name="longitude"]').value;

    const showPosition = (position) => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        document.querySelector('input[name="latitude"]').value = lat;
        document.querySelector('input[name="longitude"]').value = lon;
    }

    const getLocation = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    window.addEventListener('load', function() {
        if (!lat && !lon) {
            getLocation();
        }
    })

    document.querySelector('.get-coordinat').addEventListener('click', function(e) {
        e.preventDefault();
        getLocation();
    });
</script>