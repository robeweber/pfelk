#!/usr/local/bin/php-cgi -f
<?php
require_once("config.inc");
require_once("gwlb.inc");
require_once("interfaces.inc");

$host = gethostname();
$source = "pfconfig";

$iflist = get_configured_interface_with_descr(true);
foreach ($iflist as $ifname => $friendly) {
    $ifinfo =  get_interface_info($ifname);
    $ifstatus = $ifinfo['status'];
    $ifconf = $config['interfaces'][$ifname];
    $ip4addr = get_interface_ip($ifname);
    $ip4subnet = get_interface_subnet($ifname);
    $ip6addr = get_interface_ipv6($ifname);
    $ip6subnet = get_interface_subnetv6($ifname);
    $realif = get_real_interface($ifname);
    $mac = get_interface_mac($realif);

    if (!isset($ifinfo)) {
        $ifinfo = "Unavailable";
    }
    if (strtolower($ifstatus) == "up") {
        $ifstatus = 1;
    }
    if (strtolower($ifstatus) == "active") {
        $ifstatus = 1;
    }
    if (strtolower($ifstatus) == "no carrier") {
        $ifstatus = 0;
    }
    if (strtolower($ifstatus) == "down") {
        $ifstatus = 0;
    }
    if (!isset($ifstatus)) {
        $ifstatus = 2;
    }
    if (!isset($ifconf)) {
        $ifconf = "Unassigned";
    }
    if (!isset($ip4addr)) {
        $ip4addr = "Unassigned";
    }
    if (!isset($ip4subnet)) {
        $ip4subnet = "0";
    }
    if (!isset($ip6addr)) {
        $ip6addr = "Unassigned";
    }
    if (!isset($ip6subnet)) {
        $ip6subnet = "Unassigned";
    }
    if (!isset($realif)) {
        $realif = "Unassigned";
    }
    if (!isset($mac)) {
        $mac = "Unavailable";
    }


    printf(
        "<13>%s %s interfaces: %s,%s,%s,%s,%s,%s,%s,%s %s\n",
        date('M j h:i:s'),
        $host,
        $realif,
        $ip4addr,
        $ip4subnet,
        $ip6addr,
        $ip6subnet,
        $mac,
        $friendly,
        $source,
        $ifstatus
    );
}

$gw_array = return_gateways_array();
//$gw_statuses is not guarranteed to contain the same number of gateways as $gw_array
$gw_statuses = return_gateways_status(true);

$debug = false;

if ($debug) {
    print_r($gw_array);
    print_r($gw_statuses);
}

foreach ($gw_array as $gw => $gateway) {

    //take the name from the $a_gateways list
    $name = $gateway["name"];

    $monitor = $gw_statuses[$gw]["monitorip"];
    $source = $gw_statuses[$gw]["srcip"];
    $delay = $gw_statuses[$gw]["delay"];
    $stddev = $gw_statuses[$gw]["stddev"];
    $loss = $gw_statuses[$gw]["loss"];
    $status = $gw_statuses[$gw]["status"];
    $substatus;

    $interface = $gateway["interface"];
    $friendlyname = $gateway["friendlyiface"]; # This is not the friendly interface name so I'm not using it
    $friendlyifdescr = $gateway["friendlyifdescr"];
    $gwdescr = $gateway["descr"];
    $defaultgw = $gateway['isdefaultgw'];

    if (!isset($monitor)) {
        $monitor = "Unavailable";
    }
    if (!isset($source)) {
        $source = "Unavailable";
    }
    if (!isset($delay)) {
        $delay = "0";
    }
    if (!isset($stddev)) {
        $stddev = "0";
    }
    if (!isset($loss)) {
        $loss = "0";
    }
    if (!isset($status)) {
        $status = "Unavailable";
    }
    if (!isset($interface)) {
        $interface = "Unassigned";
    }
    if (!isset($friendlyname)) {
        $friendlyname = "Unassigned";
    }
    if (!isset($friendlyifdescr)) {
        $friendlyifdescr = "Unassigned";
    }
    if (!isset($gwdescr)) {
        $gwdescr = "Unassigned";
    }

    if (isset($gateway['isdefaultgw'])) {
        $defaultgw = "1";
    } else {
        $defaultgw = "0";
    }

    if (isset($gateway['monitor_disable'])) {
        $monitor = "Unmonitored";
    }

    // Some earlier versions of pfSense do not return substatus
    if (isset($gw_statuses[$gw]["substatus"])) {
        $substatus = $gw_statuses[$gw]["substatus"];
    } else {
        $substatus = "N/A";
    }

    printf(
        "<13>%s %s gateways: %s,%s \"%s\",\"%s\",%s,\"%s\",%s,%s,%s,\"%s\",\"%s\"\n",
        date('M j h:i:s'),
        $host,
        $interface,
        $name, //name is required as it is possible to have 2 gateways on 1 interface.  i.e. WAN_DHCP and WAN_DHCP6
        $monitor,
        $source,
        $defaultgw,
        $gwdescr,
        floatval($delay),
        floatval($stddev),
        floatval($loss),
        $status,
        $substatus
    );
};
?>