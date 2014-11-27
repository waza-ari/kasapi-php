<?php

namespace KasApi;

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
   * Adjust if the KAS API is updated; ! means required parameter
   *
   * @var array
   */
  private $functions = array(
    'get_accountressources'   => '', // sorry for the typo, All-Inkl's fault :)
    'get_accounts'            => 'account_login',
    'get_accountsettings'     => '',
    'get_server_information'  => '',
    'get_cronjobs'            => 'cronjob_id',
    'get_databases'           => 'database_login',
    'get_ddnsusers'           => 'ddns_login',
    'get_directoryprotection' => 'directory_path',
    'get_dns_settings'        => 'zone_host!, nameserver, record_id',
    'get_domains'             => 'domain_name',
    'get_topleveldomains'     => '',
    'get_ftpusers'            => 'ftp_login',
    'get_mailaccounts'        => 'mail_login',
    'get_mailstandardfilter'  => '',
    'get_mailforwards'        => 'mail_forward',
    'get_mailinglists'        => 'mailinglist_name',
    'get_sambausers'          => 'samba_login',
    'get_softwareinstall'     => 'software_id',
    'get_space'               => 'show_subaccounts, show_details',
    'get_traffic'             => 'year', 'month',
    'get_subdomains'          => 'subdomain_name',
    'add_account'             => 'account_kas_password!, account_ftp_password!, account_comment, account_contact_mail, hostname_art,
                                  hostname_part1, hostname_part2, max_account, max_domain, max_subdomain, max_webspace, max_mail_account,
                                  max_mail_forward, max_mailinglist, max_database, max_ftpuser, max_sambauser, max_cronjobs, inst_htaccess,
                                  inst_fpse, kas_access_forbidden, inst_software, logging, dns_settings, show_password',
    'add_cronjob'             => 'protocol!, http_url!, cronjob_comment!, minute!, hour!, day_of_month!, month!, day_of_week!, http_user,
                                  http_password, mail_adress, mail_condition, is_active', // another typo D:
    'add_database'            => 'database_password!, database_comment!',
    'add_ddnsuser'            => 'dyndns_comment!, dyndns_password!, dyndns_zone!, dyndns_label!, dyndns_target_ip!',
    'add_directoryprotection' => 'directory_user!, directory_path!, directory_password!, directory_authname!',
    'add_dns_settings'        => 'zone_host!, nameserver, record_type!, record_name!, record_data!, record_aux!',
    'add_domain'              => 'domain_name!, domain_tld!, domain_path, redirect_status, ssl_proxy, statistic_version, statistic_language',
    'add_ftpuser'             => 'ftp_password!, ftp_comment!, ftp_path, ftp_permission_read, ftp_permission_write, ftp_permission_list,
                                  ftp_virus_clamav',
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
   * Whether a parameter is required
   *
   * @param string $param
   * @return boolean
   * @author Elias Kuiter
   */
  private function paramIsRequired($param) {
    return ends_with($param, '!') ? true : false;
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
   * Returns an array of allowed parameters for an API function
   *
   * @param string $function
   * @return void
   * @author Elias Kuiter
   */
  private function allowedParams($function) {
    $params = explode(',', $this->functions[$function]);
    array_walk($params, create_function('&$val', '$val = trim($val);'));
    return $params;
  }

  /**
   * Returns an array of required parameters for an API function
   *
   * @param string $function
   * @return void
   * @author Elias Kuiter
   */
  private function requiredParams($function) {
    $params = explode(',', $this->functions[$function]);
    array_walk($params, create_function('&$val', '$val = trim($val);'));
    $required_params = array();
    foreach ($params as $param)
      if ($this->paramIsRequired($param))
        $required_params[] = str_replace('!', '', $param);
    return $required_params;
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
    $allowed_params = $this->allowedParams($function);
    return in_array("$param!", $allowed_params) || in_array($param, $allowed_params);
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
    $params = $this->requiredParams($function);
    foreach ($params as $param)
      if (!array_key_exists($param, $given_params))
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