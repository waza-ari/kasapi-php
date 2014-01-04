<?php

require_once 'kas.inc.php';

// ======= Configuration values =======

$kas_username = 'abcdefgh'; // your KAS username
$kas_password = '12345678'; // your KAS password
$session_lifetime = 30 * 60; // lifetime for the KAS Session, maximum 30 minutes
$session_update_lifetime = 'Y'; // Y means the auth token is refreshed on every request, N is contrary

$session_name = 'sessid'; // name for the PHP session cookie
$session_scope = '.example.com'; // scope in which the PHP session is valid (the dot . is for all sub-domains)

// =======  Configuration end   =======

// starts a session
init_session($session_name, $session_scope);

// creates a KasConfiguration object
$kas_configuration = new KasConfiguration($kas_username, $kas_password, $session_lifetime, $session_update_lifetime);

// creates a KasApi object
$api = new KasApi($kas_configuration);

// example calls to the KAS API
// ============================
// These examples are only getting lists, but there are also API calls to add, delete and update.
// Additional parameters are specified through an array: array('parameter_name' => 'parameter_value').

// Learn about which parameters to use in KasApi.class.php in the $functions array.
// Learn more about the KAS API in general: http://kasapi.kasserver.com/dokumentation/phpdoc/

// To try the examples, just remove the comment sign # in front of the API call.

print_r(
  array(
    #$api->get_accountressources(),
    #$api->get_accounts(),
    #$api->get_accountsettings(),
    #$api->get_server_information(),
    #$api->get_cronjobs(),
    #$api->get_databases(),
    #$api->get_ddnsusers(),
    #$api->get_directoryprotection(),
    #$api->get_dns_settings(array('zone_host' => 'example.com.')),
    #$api->get_domains(),
    #$api->get_topleveldomains(),
    #$api->get_ftpusers(),
    #$api->get_mailaccounts(),
    #$api->get_mailstandardfilter(),
    #$api->get_mailforwards(),
    #$api->get_mailinglists(),
    #$api->get_sambausers(),
    #$api->get_softwareinstall(),
    #$api->get_space(),
    #$api->get_traffic(),
    #$api->get_subdomains(),
  )
);