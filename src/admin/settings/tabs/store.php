<?php
$name      = "";
$logo      = "https://lokuswp.id/wp-content/uploads/2021/12/lokago.png";
$address   = "";
$countries = "";
$state     = "";
$district  = "";

var_dump( get_option( 'lwpcommerce_store' ) );

$settings = get_option( 'lwpcommerce_store' );
if ( ! $settings ) {
	lwpc_set_settings( 'store', 'name', $name );
	lwpc_set_settings( 'store', 'logo', $logo, 'esc_url_raw' );
	lwpc_set_settings( 'store', 'address', $address );
	lwpc_set_settings( 'store', 'country', $countries );
	lwpc_set_settings( 'store', 'state', $state, 'intval' );
	lwpc_set_settings( 'store', 'district', $district, 'intval' );
}

$countries         = [
	[
		'iso2'            => "ID",
		'iso3'            => "IDN",
		'phone'           => "+62",
		'name'            => "Indonesia",
		'currency'        => "IDR",
		'currency_format' => "IDR - Rupiah ( Rp 100.000 )",
	],
	[
		'iso2'            => "MY",
		'iso3'            => "MYS",
		'phone'           => "+60",
		'name'            => "Malaysia",
		'currency'        => "MYR",
		'currency_format' => "MYR - Ringgit ( 100 RM )",
	],
	[
		'iso2'            => "SG",
		'iso3'            => "SGP",
		'phone'           => "+65",
		'name'            => "Singapore",
		'currency'        => "SGD",
		'currency_format' => "SGD - Singapore Dollar ( S$ 10 )",
	],
	[
		'iso2'            => "US",
		'iso3'            => "USA",
		'phone'           => "+1",
		'name'            => "United States",
		'currency'        => "USD",
		'currency_format' => "USD - Dollar ( $15 )",
	]
];
$name              = lwpc_get_settings( 'store', 'name' );
$logo              = lwpc_get_settings( 'store', 'logo', 'esc_url', 'https://lokuswp.id/wp-content/uploads/2021/12/lokago.png' );
$address           = lwpc_get_settings( 'store', 'address' );
$country_selected  = lwpc_get_settings( 'store', 'country' );
$state_selected    = lwpc_get_settings( 'store', 'state', 'intval' );
$district_selected = lwpc_get_settings( 'store', 'district', 'intval' );

$id_states = json_decode( file_get_contents( LWPBB_PATH . 'src/includes/cache/ID-states.json' ) );
$id_cities = json_decode( file_get_contents( LWPBB_PATH . 'src/includes/cache/ID-cities.json' ) );

?>

<section id="settings" class="form-horizontal">
    <form>


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

        <!-- Country -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="country"><?php _e( 'Negara', 'lwpcommerce' ); ?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="country" id="form-country">
                    <!-- lwpcommerce-admin.js : onChange trigger result States -->
                    <option disabled selected><?php _e( 'Pilih Negara', 'lwpcommerce' ) ?></option>
					<?php foreach ( $countries as $key => $country ): ?>
                        <option value="<?php echo $country['iso2']; ?>" <?php echo ( $country_selected == $country['iso2'] ) ? 'selected' : ''; ?>><?php echo $country['name']; ?></option>
					<?php endforeach; ?>
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
                    <option><?php _e( 'Pilih Provinsi', 'lwpcommerce' ); ?></option>
					<?php foreach ( $id_states as $key => $state ): ?>
						<?php if ( ! in_array( $state->province_id, $states ) ): ?>
                            <option value="<?php echo $state->province_id; ?>" <?php echo ( $state_selected == $state->province_id ) ? 'selected' : ''; ?>><?php echo $state->province; ?></option>
							<?php $states[] = $state->province_id; ?>
						<?php endif; ?>
					<?php endforeach; ?>
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
                    <option><?php _e( 'Pilih Kota atau Kabupaten', 'lwpcommerce' ); ?></option>
					<?php foreach ( $id_cities as $key => $city ): ?>
						<?php if ( $city->city_id == $district_selected ): ?>
                            <option value="<?php echo esc_attr( $district_selected ); ?>" selected><?php echo esc_attr( $city->type . ' ' . $city->city_name ); ?></option>
							<?php break; ?>
						<?php endif; ?>
					<?php endforeach; ?>
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
    const id_cities = <?=json_encode( $id_cities )?>;
    const us_cities = <?=file_get_contents( LSDD_PATH . 'includes/cache/US-cities.json' )?>;
    const us_states = <?=file_get_contents( LSDD_PATH . 'includes/cache/US-states.json' )?>;

    (function ($) {
        $('#form-state').on('change', function () {
            const value = $(this).val();
            const initial_countries = $('#form-country').find(":selected").val();
            const district = $('#form-district');
            switch (initial_countries) {
                case 'ID':
                    district.empty();
                    id_cities.forEach((e) => {
                        if (e.province_id === value) {
                            const html = `<option value="${e.city_id}">${e.type} ${e.city_name}</option>`;
                            district.append(html);
                        }
                    })
                    break;
                case 'US':
                    district.empty();
                    us_cities.forEach((e) => {
                        if (e.province_id === value) {
                            const html = `<option value="${e.city_id}">${e.type} ${e.city_name}</option>`;
                            $('#form-district').append(html);
                        }
                    })
                    break;
            }
        })
    })(jQuery)
</script>