<?php 
$name = "";
$logo = "";
$address = "";
$countries = [];
?>

<section id="settings" class="form-horizontal">
    <form>
 
    
        <!-- Name -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="institution_name"><?php _e('Nama', 'lwponation');?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="text" class="form-input" name="institution_name" placeholder="Yayasan Indonesia" value="<?php echo $name; ?>"/>
            </div>
        </div>

        <!-- Logo -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="institution_logo"><?php _e('Logo', 'lwponation');?></label>
            </div>
            <div class="col-5 col-sm-12">
                <img style="width:75px;" src="<?php echo $logo; ?>"/>
                <input class="form-input" type="text" style="display:none;" name="institution_logo">
                <input type="button" value="<?php _e('Pilih Gambar', 'lwponation');?>" class="lwp_admin_upload btn col-12">
            </div>
        </div>

        <!-- Country -->
        <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="institution_country"><?php _e('Negara', 'lwponation');?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="institution_country" id="form-country">
                    <!-- lwponation-admin.js : onChange trigger result States -->
                    <option disabled selected><?php _e('Select a country', 'lwponation')?></option>
                    <?php foreach ($countries as $key => $country): ?>
                        <option value="<?php echo $country['iso2']; ?>" <?php echo ($country_selected == $country['iso2']) ? 'selected' : ''; ?>><?php echo $country['name']; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div> 

        <!-- State -->
        <?php $states = array();?>
        <!-- <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="institution_state"><?php _e('Provinsi', 'lwponation');?></label>
            </div>
            <div class="col-5 col-sm-12">
                <select class="form-select" name="institution_state" id="form-state">
                    <option><?php _e('Pilih Provinsi', 'lwponation');?></option>
                    <?php foreach ($id_states as $key => $state): ?>
                        <?php if (!in_array($state->province_id, $states)): ?>
                            <option value="<?php echo $state->province_id; ?>" <?php echo ($state_selected == $state->province_id) ? 'selected' : ''; ?>><?php echo $state->province; ?></option>
                            <?php array_push($states, $state->province_id);?>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
            </div>
        </div> -->

        <!-- District -->
        <!-- <div class="form-group hidden">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="institution_district"><?php _e('Kota/Kabupaten', 'lwponation');?></label>
            </div>

            <div class="col-5 col-sm-12">
                <select class="form-select" name="institution_district" id="form-distric">
                    <option><?php _e('Pilih Kota atau Kabupaten', 'lwponation');?></option>
                    <?php foreach ($id_cities as $key => $city): ?>
                        <?php if ($city->city_id == $district_selected): ?>
                            <option value="<?php echo esc_attr($district_selected); ?>" selected><?php echo esc_attr($city->type . ' ' . $city->city_name); ?></option>
                            <?php break;?>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
            </div>
        </div> -->

        <!-- Address -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="institution_address"><?php _e('Alamat', 'lwponation');?></label>
            </div>
            <div class="col-5 col-sm-12">
                <textarea class="form-input" name="institution_address" placeholder="Jl.Jendral Sudirman no 40. 15560" rows="3"><?php echo $address; ?></textarea>
            </div>
        </div>


        <!-- form checkbox control -->
        <!-- <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox">
            <i class="form-icon"></i> <?php _e('I agree to contribute organization data for better experience and mapping organization donation.')?> what we collect. show @4.2.0
          </label>
        </div> -->

        <br>
        <button class="btn btn-primary" id="lwp_institution_settings_save" style="width:120px"><?php _e('Simpan', 'lwponation');?></button>
    </form>
</section>

<script>
    var id_cities = <?=json_encode($id_cities)?>;
    var us_cities = <?=file_get_contents(LSDD_PATH . 'includes/cache/US-cities.json')?>;
    var us_states = <?=file_get_contents(LSDD_PATH . 'includes/cache/US-states.json')?>;

    (function($) {
        $('#form-state').on('change', function() {
            var value = $(this).val();
            var intital_countries = $('#form-country').find(":selected").val();
            switch (intital_countries) {
                case 'ID':
                    $('#form-distric').empty();
                    id_cities.forEach((e) => {
                        if (e.province_id === value) {
                            var html = `<option value="${e.city_id}">${e.type} ${e.city_name}</option>`;
                            $('#form-distric').append(html);
                        }
                    })
                    break;
                case 'US':
                    $('#form-distric').empty();
                    us_cities.forEach((e) => {
                        if (e.province_id === value) {
                            var html = `<option value="${e.city_id}">${e.type} ${e.city_name}</option>`;
                            $('#form-distric').append(html);
                        }
                    })
                    break;
            }
        })
    })(jQuery)
</script>