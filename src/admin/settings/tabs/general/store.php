<?php
/*****************************************
 * Free Shipping
 * Shipping Method for Free Shipping without condition
 *
 * @since 0.1.0
 *****************************************
 */
$name = lwp_get_settings( 'lwcommerce', 'store', 'name' );
$logo = lwp_get_settings( 'lwcommerce', 'store', 'logo', 'esc_url', LWC_URL . 'src/admin/assets/images/lwcommerce.png' );
$desc = lwp_get_settings( 'lwcommerce', 'store', 'description' );

$email    = lwp_get_settings( 'lwcommerce', 'store', 'email' );
$whatsapp = lwp_get_settings( 'lwcommerce', 'store', 'whatsapp' );

$address           = lwp_get_settings( 'lwcommerce', 'store', 'address' );
$country_selected  = lwp_get_settings( 'lwcommerce', 'store', 'country' );
$state_selected    = lwp_get_settings( 'lwcommerce', 'store', 'state', 'intval' );
$city_selected     = lwp_get_settings( 'lwcommerce', 'store', 'city', 'intval' );
$district_selected = lwp_get_settings( 'lwcommerce', 'store', 'district', 'intval' );

$latitude  = lwp_get_settings( 'lwcommerce', 'store', 'latitude', 'floatval' );
$longitude = lwp_get_settings( 'lwcommerce', 'store', 'longitude', 'floatval' );

$categories = [
	'digital'     => __( 'Digital Goods', "lwcommerce" ),
	'fashion'     => __( 'Fashion, Apparel', "lwcommerce" ),
	'electronics' => __( 'Electronics, Computer', "lwcommerce" ),
	'fnb'         => __( 'Food and Drink', "lwcommerce" ),
];

// Get Data Province
//$get_states = lwp_get_remote_json( get_rest_url() . 'lwcommerce/v1/rajaongkir/province', [], 'lwcommerce_states', WEEK_IN_SECONDS );
//$states     = $get_states->data ?? [];
//
//$state_selected = $state_selected == 0 ? 3 : $state_selected;
//$get_cities     = lwp_get_remote_json( get_rest_url() . 'lwcommerce/v1/rajaongkir/city?province=' . $state_selected, [], 'lwcommerce_cities_' . $state_selected, WEEK_IN_SECONDS );
//$cities         = $get_cities->data ?? [];
?>


<style>
    #settings .form-input,
    #settings .btn {
        max-width: 500px;
        display: block;
    }
</style>
<section id="settings" class="form-horizontal">
    <form>

        <!-- Name -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="name"><?php _e( 'Name', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <input type="text" class="form-input" name="name" placeholder="Lokus Store"
                       value="<?php echo $name; ?>"/>
            </div>
        </div>

        <!-- Logo -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="logo"><?php _e( 'Logo', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <img style="width:75px;" src="<?php echo $logo; ?>"/>
                <input class="form-input " style="display: none" type="text" name="logo">
                <input type="button" value="<?php _e( 'Choose Image', 'lwcommerce' ); ?>"
                       class="lokuswp-admin-upload btn col-12">
            </div>
        </div>

        <!-- Description -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="description"><?php _e( 'Description', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <input type="text" class="form-input" name="description"
                       placeholder="<?php _e( "The best online shop ever", "lwcommerce" ); ?>"
                       value="<?php echo $desc; ?>"/>
            </div>
        </div>

        <!-- Category -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="category"><?php _e( 'Category', 'lwcommerce' ); ?></label>
            </div>

            <div class="col-9 col-sm-12">
                <select class="form-select" name="category" id="form-category">
					<?php foreach ( $categories as $key => $value ) : ?>
                        <option value="<?= $key ?? '' ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Contact -->
        <div class="divider" data-content="<?php _e( 'Store Contact', 'lwcommerce' ); ?>"></div>

        <!-- Email -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="email"><?php _e( 'Email', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <input type="email" class="form-input" name="email" placeholder="lokuswp@gmail.com"
                       value="<?php echo $email; ?>"/>
            </div>
        </div>

        <!-- Whatsapp -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="whatsapp"><?php _e( 'Whatsapp', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <input type="number" class="form-input" name="whatsapp" placeholder="08126876418"
                       value="<?php echo $whatsapp; ?>"/>
            </div>
        </div>

        <!-- Location -->
        <div class="divider" data-content="<?php _e( 'Store Location', 'lwcommerce' ); ?>"></div>

        <!-- Country -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="country"><?php _e( 'Country', 'lwcommerce' ); ?></label>
            </div>

            <div class="col-9 col-sm-12">
                <select class="form-select" name="country" id="form-country">
                    <!-- lwcommerce-admin.js : onChange trigger result States -->
                    <option name="indonesia">Indonesia</option>
                </select>
            </div>
        </div>

        <!-- State -->
        <!--        <div class="form-group hidden">-->
        <!--            <div class="col-3 col-sm-12">-->
        <!--                <label class="form-label" for="state">-->
		<?php //_e( 'State', 'lwcommerce' ); ?><!--</label>-->
        <!--            </div>-->
        <!--            <div class="col-9 col-sm-12">-->
        <!--                <select class="form-select" name="state" id="form-state">-->
        <!--                    <option value="--><? //= $state_selected ?? '' ?><!--">-->
		<?php //_e( 'Choose your state', 'lwcommerce' ); ?><!--</option>-->
        <!--					--><?php //foreach ( $states as $key => $state ) : ?>
        <!--                        <option value="--><?php //echo $state->province_id; ?><!--" -->
		<?php //echo ( $state->province_id == $state_selected ) ? 'selected' : ''; ?><?php //echo $state->province; ?><!--</option>-->
        <!--					--><?php //endforeach; ?>
        <!--                </select>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- District -->
        <!--        <div class="form-group hidden">-->
        <!--            <div class="col-3 col-sm-12">-->
        <!--                <label class="form-label" for="city">-->
		<?php //_e( 'City', 'lwcommerce' ); ?><!--</label>-->
        <!--            </div>-->
        <!--
		<!--            <div class="col-9 col-sm-12">-->
        <!--                <select class="form-select" name="city" id="form-city">-->
        <!--                    <option value="--><? //= $city_selected ?? '' ?><!--">-->
		<?php //_e( 'Choose your city', 'lwcommerce' ); ?><!--</option>-->
        <!--					--><?php //foreach ( $cities as $key => $city ) : ?>
        <!--                        <option value="--><?php //echo $city->city_id; ?><!--" -->
		<?php //echo ( $city->city_id == $city_selected ) ? 'selected' : ''; ?><?php //echo $city->type . ' ' . $city->city_name; ?><!--</option>-->
        <!--					--><?php //endforeach; ?>
        <!--                </select>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- Address -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="address"><?php _e( 'Address', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <textarea class="form-input" name="address" placeholder="Jl.Jendral Sudirman no 40. 15560"
                          rows="3"><?php echo $address; ?></textarea>
            </div>
        </div>

        <!-- Coordinate -->
        <!-- <div class="form-group lwp-hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="coordinate">
					<?php _e( 'Coordinate', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-2" style="margin-right: 0.2rem">
                <input type="number" class="form-input" name="latitude" placeholder="-6.123671823"
                       value="<?php echo $latitude; ?>"/>
            </div>
            <div class="col-2" style="margin-left: 0.2rem">
                <input type="number" class="form-input" name="longitude" placeholder="126.128635"
                       value="<?php echo $longitude; ?>"/>
            </div>
            <div class="col-2" style="margin-left: 1rem">
                <button class="btn get-coordinat">Get Coordinate</button>
            </div>
        </div> -->

        <!-- form checkbox control -->
        <!-- <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox">
            <i class="form-icon"></i> <?php //_e( 'I agree to contribute organization data for better experience and mapping store.' ) ?> what we collect. show @4.2.0
          </label>
        </div> -->

        <br>
        <button class="btn btn-primary w-120" id="lwc-setting-store-save"><?php _e( 'Save', 'lwcommerce' ); ?></button>
    </form>
</section>

<script>
    //const apiRajaOngkir = '<?php //echo get_rest_url() . 'lwcommerce/v1/rajaongkir'; ?>//';
    //const stateId = document.querySelector('#form-state').value;
    //
    //// Get City based on Province
    //document.querySelector('#form-state').addEventListener('change', function () {
    //    let stateId = this.value;
    //    let option = document.createElement('option');
    //    option.innerHTML = '<?php //_e( "Getting Data", "lwcommerce" ); ?>//...';
    //    let city = document.querySelector('#form-city');
    //    city.innerHTML = '';
    //    city.appendChild(option); // Cleaning the city select
    //
    //    fetch(`${apiRajaOngkir}/city?province=${stateId}`)
    //        .then(res => res.json())
    //        .then(result => {
    //            if (result.status === 'success') {
    //                console.log(result)
    //                result.data.forEach(city => {
    //                    let option = document.createElement('option');
    //                    option.value = city.city_id;
    //                    option.innerHTML = city.type + ' ' + city.city_name;
    //                    document.querySelector('#form-city').appendChild(option);
    //                });
    //                document.querySelector('#form-city option:first-child').remove();
    //            } else {
    //                console.log(data.message);
    //            }
    //        });
    //});


    // const lat = document.querySelector('input[name="latitude"]').value;
    // const lon = document.querySelector('input[name="longitude"]').value;
    //
    // const showPosition = (position) => {
    //     const lat = position.coords.latitude;
    //     const lon = position.coords.longitude;
    //     document.querySelector('input[name="latitude"]').value = lat;
    //     document.querySelector('input[name="longitude"]').value = lon;
    // }
    //
    // const getLocation = () => {
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition(showPosition);
    //     } else {
    //         alert('Geolocation is not supported by this browser.');
    //     }
    // }
    //
    // window.addEventListener('load', function() {
    //     if (!lat && !lon) {
    //         getLocation();
    //     }
    // })
    //
    // document.querySelector('.get-coordinate').addEventListener('click', function(e) {
    //     e.preventDefault();
    //     getLocation();
    // });
</script>