<?php

/**
 * Calls the KAS API.
 * Ensures that the given API functions and parameters are valid
 *
 * @package default
 * @author Elias Kuiter
 */
class KasApi {
  /**
   * KasConfiguration object
   *
   * @var object
   */
  private $kas_configuration;
  
  // contains all API functions and their parameters, adjust if the KAS API was updated
  /**
   * Contains every API function and its parameters.
   * Adjust if the KAS API is updated; opt means optional parameter
   *
   * @var array
   */
  private $functions = array(
    'get_accountressources'   => array(), // sorry for the typo, All-Inkl's fault :)
    'get_accounts'            => array('account_login:opt'),
    'get_accountsettings'     => array(),
    'get_server_information'  => array(),
    'get_cronjobs'            => array('cronjob_id:opt'),
    'get_databases'           => array('database_login:opt'),
    'get_ddnsusers'           => array('ddns_login:opt'),
    'get_directoryprotection' => array('directory_path:opt'),
    'get_dns_settings'        => array('zone_host', 'nameserver:opt', 'record_id:opt'),
    'get_domains'             => array('domain_name:opt'),
    'get_topleveldomains'     => array(),
    'get_ftpusers'            => array('ftp_login:opt'),
    'get_mailaccounts'        => array('mail_login:opt'),
    'get_mailstandardfilter'  => array(),
    'get_mailforwards'        => array('mail_forward:opt'),
    'get_mailinglists'        => array('mailinglist_name:opt'),
    'get_sambausers'          => array('samba_login:opt'),
    'get_softwareinstall'     => array('software_id:opt'),
    'get_space'               => array('show_subaccounts:opt', 'show_details:opt'),
    'get_traffic'             => array('year:opt', 'month:opt'),
    'get_subdomains'          => array('subdomain_name:opt'),
  );
  
  /**
   * Sets KasConfiguration
   *
   * @param object $kas_configuration 
   * @author Elias Kuiter
   */
  function __construct($kas_configuration) {
    $this->kas_configuration = $kas_configuration;
  }
  
  // calls a given API function (but does not validate the function name and parameters)
  // e.g.: KasApi::call('get_domains');
  /**
   * Calls an API function with parameters.
   * Does not validate function name or parameters
   *
   * @param string $function 
   * @param array $params
   * @return string
   * @author Elias Kuiter
   */
  private function call($function, $params = array()) {
    $data = array('KasUser' => $this->kas_configuration->username,
                  'KasAuthType' => 'session',
                  'KasAuthData' => (new KasAuthToken($this->kas_configuration))->get(),
                  'KasRequestType' => $function,
                  'KasRequestParams' => $params);
    $client = (new KasApiSoapClient($this->kas_configuration))->get();
    return $client->KasApi(json_encode($data))['Response']['ReturnInfo'];
  }

  /**
   * Whether an API function exists
   *
   * @param string $function 
   * @return boolean
   * @author Elias Kuiter
   */
  private function functionExists($function) {
    return array_key_exists($function, $this->functions) ? true : false;
  }
  
  /**
   * Whether a parameter is optional
   *
   * @param string $param 
   * @return boolean
   * @author Elias Kuiter
   */
  private function paramIsOptional($param) {
    return ends_with($param, ':opt') ? true : false;
  }
  
  /**
   * Gets parameters from a function argument list
   *
   * @param array $arguments 
   * @return array
   * @author Elias Kuiter
   */
  private function getParamsFromArguments($arguments) {
    return $arguments[0] ? $arguments[0] : array();
  }
  
  /**
   * Whether the parameter is optional or is in the given params
   *
   * @param string $param 
   * @param array $given_params 
   * @return boolean
   * @author Elias Kuiter
   */
  private function containsParam($param, $given_params) {
    return $this->paramIsOptional($param) || array_key_exists($param, $given_params);
  }
  
  /**
   * Whether the given parameter is neither required nor optional
   *
   * @param string $param 
   * @param string $function 
   * @return boolean
   * @author Elias Kuiter
   */
  private function paramIsAllowed($param, $function) {
    $allowed_params = $this->functions[$function];
    return in_array($param, $allowed_params) || in_array("$param:opt", $allowed_params);
  }

  /**
   * Ensures that the given parameters contain every required parameter.
   * Also ensures there are no unnecessary parameters
   *
   * @param string $function 
   * @param array $given_params 
   * @return void
   * @author Elias Kuiter
   */
  private function ensureFunctionParams($function, $given_params) {
    
    // ensure every required param is there
    foreach ($this->functions[$function] as $param)
      if (!$this->containsParam($param, $given_params))
        throw new KasApiException("API parameter '$param' not given");
    
    // ensure every given param is allowed
    foreach ($given_params as $param => $value)
      if (!$this->paramIsAllowed($param, $function))
        throw new KasApiException("API parameter '$param' may not be used");
  }  
  
  /**
   * Is called whenever an API call is requested, then validates and executes the call.
   * e.g.: KasApi::get_domains();
   * or: KasApi::get_dns_settings(array('zone_host' => 'example.com.'));
   * $functions describes which functions may be called and what params are valid
   *
   * @param string $name 
   * @param string $arguments 
   * @return void
   * @author Elias Kuiter
   */
  public function __call($name, $arguments) {
    if ($this->functionExists($name)) {
      $params = $this->getParamsFromArguments($arguments);
      $this->ensureFunctionParams($name, $params);
      return $this->call($name, $params);
    } else
      throw new KasApiException("API function '$name' does not exist");
  }
}

/**
 * Exception for KAS API concerns
 *
 * @package default
 * @author Elias Kuiter
 */
class KasApiException extends Exception {}