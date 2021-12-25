<?php
$name      = "";
$logo      = "https://lokuswp.id/wp-content/uploads/2021/12/lokago.png";
$address   = "";
$countries = "";
$state     = "";
$district  = "";
$districts = "";

$settings = get_option( 'lwpcommerce_store' );
if ( ! $settings ) {
	lwpc_set_settings( 'store', 'name', $name );
	lwpc_set_settings( 'store', 'logo', $logo, 'esc_url_raw' );
	lwpc_set_settings( 'store', 'description', '' );

	lwpc_set_settings( 'store', 'email', '', 'sanitize_email' );
	lwpc_set_settings( 'store', 'whatsapp', '' );

	lwpc_set_settings( 'store', 'address', $address );
	lwpc_set_settings( 'store', 'country', $countries );
	lwpc_set_settings( 'store', 'state', $state, 'intval' );
	lwpc_set_settings( 'store', 'district', $district, 'intval' );
	lwpc_set_settings( 'store', 'districts', $districts, 'intval' );
	lwpc_set_settings( 'store', 'latitude', '', 'floatval' );
	lwpc_set_settings( 'store', 'longitude', '', 'floatval' );
}
$name        = lwpc_get_settings( 'store', 'name' );
$logo        = lwpc_get_settings( 'store', 'logo', 'esc_url', 'https://lokuswp.id/wp-content/uploads/2021/12/lokago.png' );
$description = lwpc_get_settings( 'store', 'description' );

$email    = lwpc_get_settings( 'store', 'email' );
$whatsapp = lwpc_get_settings( 'store', 'whatsapp' );

$address            = lwpc_get_settings( 'store', 'address' );
$country_selected   = lwpc_get_settings( 'store', 'country' );
$state_selected     = lwpc_get_settings( 'store', 'state', 'intval' );
$district_selected  = lwpc_get_settings( 'store', 'district', 'intval' );
$districts_selected = lwpc_get_settings( 'store', 'districts', 'intval' );
$latitude           = lwpc_get_settings( 'store', 'latitude', 'floatval' );
$longitude          = lwpc_get_settings( 'store', 'longitude', 'floatval' );

?>

<section id="settings" class="form-horizontal">
    <form>

        <!--Profil Toko-->
        <div class="divider" data-content="Profil Toko"></div>
        <!-- Name -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="name"><?php _e( 'Nama', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="text" class="form-input" name="name" placeholder="Toko Merah" value="<?php echo $name; ?>"/>
            </div>
        </div>

        <!-- Logo -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="logo"><?php _e( 'Logo', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <img style="width:75px;" src="<?php echo $logo; ?>"/>
                <input class="form-input" type="text" style="display:none;" name="logo">
                <input type="button" value="<?php _e( 'Pilih Gambar', 'lwpcommerce' ); ?>" class="lwp_admin_upload btn col-12">
            </div>
        </div>

        <!-- Description -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="description"><?php _e( 'Deskripsi', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <textarea class="form-input" name="description" placeholder="Toko keren" rows="3"><?php echo $description; ?></textarea>
            </div>
        </div>

        <!--Kontak Toko-->
        <div class="divider" data-content="Kontak Toko"></div>

        <!-- Email -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="email"><?php _e( 'Email', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="email" class="form-input" name="email" placeholder="jhonDoe@gmail.com" value="<?php echo $email; ?>"/>
            </div>
        </div>

        <!-- Whatsapp -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="whatsapp"><?php _e( 'Whatsapp', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="number" class="form-input" name="whatsapp" placeholder="08126876418" value="<?php echo $whatsapp; ?>"/>
            </div>
        </div>

        <!--Lokasi Toko-->
        <div class="divider" data-content="Lokasi Toko"></div>

        <!-- Country -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="country"><?php _e( 'Negara', 'lwpcommerce' ); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="country" id="form-country">
                    <!-- lwpcommerce-admin.js : onChange trigger result States -->
                    <option name="indonesia">Indonesia</option>
                </select>
            </div>
        </div>

        <!-- State -->
		<?php $states = array(); ?>
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="state"><?php _e( 'Provinsi', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <select class="form-select" name="state" id="form-state">
                    <option value="<?= $state_selected ?? '' ?>"><?php _e( 'Pilih Provinsi', 'lwpcommerce' ); ?></option>
                </select>
            </div>
        </div>

        <!-- District -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="district"><?php _e( 'Kota/Kabupaten', 'lwpcommerce' ); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="district" id="form-district">
                    <option value="<?= $district_selected ?? '' ?>"><?php _e( 'Pilih Kota atau Kabupaten', 'lwpcommerce' ); ?></option>
                </select>
            </div>
        </div>

        <!-- Districts -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="districts"><?php _e( 'Kecamatan', 'lwpcommerce' ); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="districts" id="form-districts">
                    <option value="<?= $districts_selected ?? '' ?>"><?php _e( 'Pilih Kecamatan', 'lwpcommerce' ); ?></option>
                </select>
            </div>
        </div>

        <!-- Address -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="address"><?php _e( 'Alamat', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <textarea class="form-input" name="address" placeholder="Jl.Jendral Sudirman no 40. 15560" rows="3"><?php echo $address; ?></textarea>
            </div>
        </div>

        <!-- Koordinat -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="koordinat"><?php _e( 'Koordinat', 'lwpcommerce' ); ?></label>
            </div>
            <div class="col-2" style="margin-right: 0.2rem">
                <input type="number" class="form-input" name="latitude" placeholder="-6.123671823" value="<?php echo $latitude; ?>"/>
            </div>
            <div class="col-2" style="margin-left: 0.2rem">
                <input type="number" class="form-input" name="longitude" placeholder="126.128635" value="<?php echo $longitude; ?>"/>
            </div>
            <div class="col-2" style="margin-left: 1rem">
                <button class="btn get-koordinat">Get Koordinat</button>
            </div>
        </div>


        <!-- form checkbox control -->
        <!-- <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox">
            <i class="form-icon"></i> <?php _e( 'I agree to contribute organization data for better experience and mapping organization donation.' ) ?> what we collect. show @4.2.0
          </label>
        </div> -->

        <br>
        <button class="btn btn-primary" id="lwpc_store_settings_save" style="width:120px"><?php _e( 'Simpan', 'lwpcommerce' ); ?></button>
    </form>
</section>

<script>
    const apiWilayahIndonesia = 'http://www.emsifa.com/api-wilayah-indonesia/api';
    const stateId = document.querySelector('#form-state').value;
    const districtId = document.querySelector('#form-district').value;
    const districtsId = document.querySelector('#form-districts').value;

    fetch(`${apiWilayahIndonesia}/provinces.json`)
        .then(response => response.json())
        .then(provinces => {
            provinces.forEach(province => {
                if (province.id === stateId) {
                    document.querySelector('#form-state').innerHTML = `<option value="${province.id}">${province.name}</option>`;
                }
                let option = document.createElement('option');
                option.value = province.id;
                option.innerHTML = province.name;
                document.getElementById('form-state').appendChild(option);
            });
        });

    if (districtId) {
        fetch(`${apiWilayahIndonesia}/regencies/${stateId}.json`)
            .then(response => response.json())
            .then(regencies => {
                regencies.forEach(regency => {
                    if (regency.id === districtId) {
                        document.querySelector('#form-district').innerHTML = `<option value="${regency.id}">${regency.name}</option>`;
                    }
                    let option = document.createElement('option');
                    option.value = regency.id;
                    option.innerHTML = regency.name;
                    document.getElementById('form-district').appendChild(option);
                });
            });
    }

    if (districtsId) {
        fetch(`${apiWilayahIndonesia}/districts/${districtId}.json`)
            .then(response => response.json())
            .then(districts => {
                districts.forEach(district => {
                    if (district.id === districtsId) {
                        document.querySelector('#form-districts').innerHTML = `<option value="${district.id}">${district.name}</option>`;
                    }
                    let option = document.createElement('option');
                    option.value = district.id;
                    option.innerHTML = district.name;
                    document.getElementById('form-districts').appendChild(option);
                });
            });
    }

    document.querySelector('#form-state').addEventListener('change', function () {
        const id_province = this.value;
        const cities = document.querySelector('#form-district');
        cities.innerHTML = '';
        fetch(`${apiWilayahIndonesia}/regencies/${id_province}.json`)
            .then(response => response.json())
            .then(district => {
                district.forEach(district => {
                    let option = document.createElement('option');
                    option.value = district.id;
                    option.innerHTML = district.name;
                    document.getElementById('form-district').appendChild(option);
                });
            });
    });

    document.querySelector('#form-district').addEventListener('change', function () {
        const id_district = this.value;
        const districts = document.querySelector('#form-districts');
        districts.innerHTML = '';
        fetch(`${apiWilayahIndonesia}/districts/${id_district}.json`)
            .then(response => response.json())
            .then(district => {
                district.forEach(district => {
                    let option = document.createElement('option');
                    option.value = district.id;
                    option.innerHTML = district.name;
                    document.getElementById('form-districts').appendChild(option);
                });
            });
    });

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

    window.addEventListener('load', function () {
        if (!lat && !lon) {
            getLocation();
        }
    })

    document.querySelector('.get-koordinat').addEventListener('click', function (e) {
        e.preventDefault();
        getLocation();
    });

</script>