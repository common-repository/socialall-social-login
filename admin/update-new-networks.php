<?php
if (!defined('ABSPATH'))
    exit;

$new_nets = array('instagram', 'amazon', 'ebay', 'yahoo', 'weibo', 'twitch', 'steam');
$nets = array();
foreach ($this->api_settings['networks'] as $network)
    $nets[] = $network['code'];

$new_nets = array_diff($new_nets, $nets);

foreach ($new_nets as $net)
    $this->api_settings['networks'][] = array('name' => ucfirst($net), 'code' => $net, 'enable' => '0');

if (!empty($new_nets))
    update_option(SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS, $this->api_settings);
