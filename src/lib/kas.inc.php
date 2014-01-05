<?php

require_once 'KasConfiguration.class.php';
require_once 'KasSoapClient.class.php';
require_once 'KasAuthToken.class.php';
require_once 'KasApi.class.php';

/**
 * Handles SoapFaults and forwards all other exceptions
 *
 * @param string $e 
 * @return void
 * @author Elias Kuiter
 */
function exception_handler($e) {
  switch (get_class($e)) {
    case 'SoapFault':
      trigger_error("SoapFault: Code: $e->faultcode, 
                     Message: $e->faultstring, 
                     Actor: $e->faultactor, 
                     Details: $e->detail", E_USER_ERROR);
    break;
    case 'KasApiException':
      trigger_error('KAS API: ' . $e->getMessage(), E_USER_ERROR);
    break;
    default:
      trigger_error($e);
  }
}

/**
 * Sets PHP configuration values and starts a session.
 * You may want to adjust the configuration values
 *
 * @return void
 * @author Elias Kuiter
 */
function init_session($name, $scope) {
  set_exception_handler('exception_handler');
  ini_set('memory_limit', -1);
  ini_set('session.use_cookies', 1);
  ini_set('session.use_only_cookies', 1);
  ini_set('session.use_trans_sid', 0);
  ini_set('session.cookie_domain', $scope);
  session_name($name);
  session_start();
}

/**
 * Whether a haystack string ends with a needle string
 *
 * @param string $haystack 
 * @param string $needle 
 * @return boolean
 * @author Elias Kuiter
 */  
function ends_with($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}