<?php
/**
 * Add tabs to LSDDonation settings.
 *
 * @param string $tab_slug
 * @param string $tab_title
 * @param callable $function
 * @return void
 */
function lwpc_add_tab_settings( string $tab_slug, string $tab_title, $function = ''){
    global $lwpc_admin_tabs;

    $lwpc_admin_tabs = is_array($lwpc_admin_tabs) ? $lwpc_admin_tabs : array();

    // Make sure menu not overridable
    if( !in_array( $tab_slug, $lwpc_admin_tabs ) ){
        add_filter('lwpcommerce/admin/tabs', function( $tab_lists ) use ( $tab_slug, $tab_title, $function ){
            $tab = array();
            $tab[$tab_slug] = $tab_title;
            return array_reverse( array_merge( $tab,  array_reverse( $tab_lists ) ) );
        });
        add_action( "lwpcommerce/admin/tabs/{$tab_slug}", $function );
        array_push( $lwpc_admin_tabs, $tab_slug );
    }
}

/**
 * Remove tabas from LSDDonation Settings.
 *
 * @param string $tab_slug
 * @return void
 */
function lwpc_remove_tab_settings( string $tab_slug ){
    global $lwpc_admin_tabs;
    
    $lwpc_admin_tabs = is_array($lwpc_admin_tabs) ? $lwpc_admin_tabs : array();

    add_filter('lwpcommerce/admin/tabs', function( $tab_lists ) use ( $tab_slug ){
        unset( $tab_lists[$tab_slug] );
        return $tab_lists;
    });
}

/**
 * Listing tabs
 *
 * @return void
 */
function lwpc_tab_lists(){
    $default = array();
    if( has_filter('lwpcommerce/admin/tabs') ) {
        $lists = apply_filters( 'lwpcommerce/admin/tabs', $default );
    }
    return $lists;
}

/**
 * Default Admin Tabs
 */
lwpc_add_tab_settings( 'settings', __('Settings', 'lwpcommerce'), function () {
    require_once 'tabs/settings.php';
});

lwpc_add_tab_settings( 'store', __('Store', 'lwpcommerce'), function () {
    require_once 'tabs/store.php';
});

lwpc_add_tab_settings( 'appearance', __('Appearance', 'lwpcommerce'), function () {
    require_once 'tabs/appearance.php';
});

lwpc_add_tab_settings( 'shipping', __('Shipping', 'lwpcommerce'), function () {
    require_once 'tabs/shipping.php';
});

lwpc_add_tab_settings( 'extensions', __('Extensions', 'lwpcommerce'), function () {
    require_once 'tabs/extensions.php';
});
// ?>