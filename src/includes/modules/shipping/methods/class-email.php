<?php

namespace LokusWP\Commerce;

use LokusWP\Utils\Log;

if ( ! defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

/**
 * Payment Gateway Class extending from Payment\Gateway Abstraction
 *
 * @link country code https://en.wikipedia.org/wiki/List_of_ISO_3166_country_codes
 * @property string $id
 * @property string $country | WW for WorldWide ( Global ) | ISO 3166-1 alpha-2
 * DB : _lokuswp_options
 *
 * @since 1.0.0
 */
class Email extends Shipping\Gateway
{
    public $id = 'email';

    protected $name = "Email";
    protected $description = "Mengirim produk digital dengan email kepada pembeli";
    protected $logo = LWPC_URL.'src/admin/assets/images/email.png';
    protected $fee = 0;

    public $zone = ['digital'];
    public $package = ['regular' => 'on'];
    public $type = "Kirim Digital";
    public $group = "digital_shipping";
    public $docs_url = ['ID' => '', 'EN' => ''];
    public $country = "ID";

    public function __construct()
    {
        $this->save_as_data();

    }

    // payment management for admin
    public function admin_manage($shipping_id)
    {
        //
    }

    // instruction with output html at receipt page
    public function instruction_html(object $transaction_obj)
    {
    }

    // template text for notification channel sms or whatsapp
    public function notification_text(object $transaction_obj, string $event, string $shipping_id)
    {
    }

    // template html for notification using smtp email
    public function notification_html(object $transaction_obj, string $event, string $shipping_id)
    {
    }

    // template json for notification using webhook services, integromat, zapier or apps. etc
    public function notification_json(object $transaction_obj, string $event)
    {
        return json_encode(["array" => "value"]);
    }
}

Shipping\Manager::register(new Email());
