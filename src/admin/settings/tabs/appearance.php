<?php

namespace LokusWP\Commerce;

$jne              = new POST_Indonesia();
$jne->service     = 'YES';
$jne->destination = '456';
$jne->weight      = '1';

var_dump( $jne->get_cost() );