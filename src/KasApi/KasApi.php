<?php

namespace KasApi;
use SoapFault;

/**
 * Calls the KAS API.
 * Ensures that the given API functions and parameters are valid
 *
 * @package KasApi
 */
class KasApi {
    /**
     * KasConfiguration object
     *
     * @var KasConfiguration
     */
    protected $kasConfiguration;

    /**
     * @var String kasFloodDelay return value of last API call
     */
    protected $kasFloodDelay;

    /**
     * @return String
     */
    public function getKasFloodDelay() {
        return $this->kasFloodDelay;
    }

    // contains all API functions and their parameters, adjust if the KAS API was updated
    /**
     * Contains every API function and its parameters.
     * Adjust if the KAS API is updated; ! means required parameter
     *
     * @var array
     */
    protected $functions = array(
        'get_accountressources' => '', // sorry for the typo, All-Inkl's fault :)
        'get_accounts' => 'account_login',
        'get_accountsettings' => '',
        'get_server_information' => '',
        'get_cronjobs' => 'cronjob_id',
        'get_databases' => 'database_login',
        'get_ddnsusers' => 'ddns_login',
        'get_directoryprotection' => 'directory_path',
        'get_dns_settings' => 'zone_host!, nameserver, record_id',
        'get_domains' => 'domain_name',
        'get_topleveldomains' => '',
        'get_ftpusers' => 'ftp_login',
        'get_mailaccounts' => 'mail_login',
        'get_mailstandardfilter' => '',
        'get_mailforwards' => 'mail_forward',
        'get_mailinglists' => 'mailinglist_name',
        'get_sambausers' => 'samba_login',
        'get_softwareinstall' => 'software_id',
        'get_space' => 'show_subaccounts, show_details',
        'get_space_usage' => 'directory!',
        'get_traffic' => 'year', 'month',
        'get_subdomains' => 'subdomain_name',
        'add_account' => 'account_kas_password!, account_ftp_password!, account_comment, account_contact_mail, hostname_art,
                                  hostname_part1, hostname_part2, max_account, max_domain, max_subdomain, max_webspace, max_mail_account,
                                  max_mail_forward, max_mailinglist, max_database, max_ftpuser, max_sambauser, max_cronjobs, inst_htaccess,
                                  inst_fpse, kas_access_forbidden, inst_software, logging, dns_settings, show_password',
        'add_cronjob' => 'protocol!, http_url!, cronjob_comment!, minute!, hour!, day_of_month!, month!, day_of_week!, http_user,
                                  http_password, mail_adress, mail_condition, is_active', // another typo D:
        'add_database' => 'database_password!, database_comment!',
        'add_ddnsuser' => 'dyndns_comment!, dyndns_password!, dyndns_zone!, dyndns_label!, dyndns_target_ip!',
        'add_directoryprotection' => 'directory_user!, directory_path!, directory_password!, directory_authname!',
        'add_dns_settings' => 'zone_host!, nameserver, record_type!, record_name!, record_data!, record_aux!',
        'add_domain' => 'domain_name!, domain_tld!, domain_path, redirect_status, ssl_proxy, statistic_version, statistic_language, php_version',
        'add_ftpuser' => 'ftp_password!, ftp_comment!, ftp_path, ftp_permission_read, ftp_permission_write, ftp_permission_list,
                                  ftp_virus_clamav',
        'delete_account' => 'account_login!',
        'update_account' => 'account_login!, account_kas_password!, max_account, max_domain, max_subdomain, max_webspace, max_mail_account, max_mail_forward, max_mailinglist, max_database, max_ftpuser, max_sambauser, max_cronjobs, inst_htaccess, inst_fpse, kas_access_forbidden, show_password, inst_software, logging, dns_settings, account_comment, account_contact_mail',
        'update_accountsettings' => 'account_password, show_password, logging, account_comment, account_contact_mail',
        'update_superusersettings' => 'account_login!, ssh_access, ssh_keys',
        'update_chown' => 'chown_path!, chown_user!, recursive!',
        'delete_cronjob' => 'cronjob_id!',
        'update_cronjob' => 'cronjob_id!, protocol, http_url, cronjob_comment, minute, hour, day_of_month, month, day_of_week, http_user, http_password, mail_adress, mail_condition, is_active',
        'delete_database' => 'database_login!',
        'update_database' => 'database_login!, database_new_password, database_comment',
        'delete_ddnsuser' => 'dyndns_login!',
        'update_ddnsuser' => 'dyndns_login!, dyndns_password, dyndns_comment',
        'delete_directoryprotection' => 'directory_user!, directory_path!',
        'update_directoryprotection' => 'directory_user!, directory_path!, directory_password!, directory_authname!',
        'delete_dns_settings' => 'record_id!, nameserver',
        'reset_dns_settings' => 'zone_host!, nameserver',
        'update_dns_settings' => 'record_id!, nameserver, record_name, record_data, record_aux',
        'delete_domain' => 'domain_name!',
        'update_domain' => 'domain_name!, domain_path, redirect_status, ssl_proxy, statistic_version, statistic_language, php_version',
        'delete_ftpuser' => 'ftp_login!',
        'update_ftpuser' => 'ftp_login!, ftp_path, ftp_new_password, ftp_comment, ftp_permission_read, ftp_permission_write, ftp_permission_list, ftp_virus_clamav',
        'add_mailaccount' => 'mail_password!, local_part!, domain_part!, responder, mail_responder_content_type, mail_responder_displayname, responder_text, copy_adress, mail_sender_alias, mail_xlist_enabled, mail_xlist_sent, mail_xlist_drafts, mail_xlist_trash, mail_xlist_spam',
        'delete_mailaccount' => 'mail_login!',
        'update_mailaccount' => 'mail_login!, mail_new_password, responder, mail_responder_content_type, mail_responder_displayname, responder_text, copy_adress, is_active, mail_sender_alias, mail_xlist_enabled, mail_xlist_sent, mail_xlist_drafts, mail_xlist_trash, mail_xlist_spam',
        'add_mailstandardfilter' => 'mail_login!, filter!',
        'delete_mailstandardfilter' => 'mail_login!',
        'add_mailforward' => 'local_part!, domain_part!, target_N',
        'delete_mailforward' => 'mail_forward!',
        'update_mailforward' => 'mail_forward!, target_N',
        'add_mailinglist' => 'mailinglist_name!, mailinglist_domain!, mailinglist_password',
        'delete_mailinglist' => 'mailinglist_name!',
        'update_mailinglist' => 'mailinglist_name!, subscriber, restrict_post, config, is_active',
        'add_sambauser' => 'samba_path!, samba_new_password!, samba_comment',
        'delete_sambauser' => 'samba_login!',
        'update_sambauser' => 'samba_login!, samba_path, samba_new_password, samba_comment',
        'add_session' => 'session_lifetime!, session_update_lifetime!',
        'add_softwareinstall' => 'software_id!, software_database!, software_hostname!, software_path!, software_admin_mail!, software_admin_user, software_admin_pass, language, software_team',
        'add_subdomain' => 'subdomain_name!, domain_name!, subdomain_path, redirect_status, ssl_proxy, statistic_version, statistic_language, php_version',
        'delete_subdomain' => 'subdomain_name!',
        'update_subdomain' => 'subdomain_name!, subdomain_path, redirect_status, ssl_proxy, statistic_version, statistic_language, php_version',
        'update_ssl' => 'hostname!, ssl_certificate_sni_key!, ssl_certificate_sni_crt!, ssl_certificate_is_active, ssl_certificate_sni_csr, ssl_certificate_sni_bundle',
        'add_symlink' => 'symlink_target!, symlink_name!',
    );

    /**
     * Sets KasConfiguration
     *
     * @param object $kas_configuration
     */
    function __construct($kas_configuration) {
        $this->kasConfiguration = $kas_configuration;
    }

    /**
     * Calls an API function with parameters.
     * Does not validate function name or parameters
     *
     * @param string $function
     * @param array $params
     * @throws KasApiException
     * @return string
     */
    protected function call($function, $params = array()) {
        try {
            $data = array('KasUser' => $this->kasConfiguration->_username,
                'KasAuthType' => $this->kasConfiguration->_authType,
                'KasAuthData' => $this->kasConfiguration->_authData,
                'KasRequestType' => $function,
                'KasRequestParams' => $params);
            $kasSoapClient = new KasSoapClient($this->kasConfiguration->wsdl_api);
            $client = $kasSoapClient->getInstance();
            $result = $client->KasApi(json_encode($data));
            $this->kasFloodDelay = $result['Response']['KasFloodDelay'];
            return $result['Response']['ReturnInfo'];
        } catch (SoapFault $fault) {
            throw new KasApiException('Unable to execute SOAP call '.$function.': '.(isset($fault->faultstring) ? $fault->faultstring : ""), (isset($fault->faultcode) ? $fault->faultcode : ""), (isset($fault->faultstring) ? $fault->faultstring : ""), (isset($fault->faultfactor) ? $fault->faultfactor : ""), (isset($fault->detail) ? $fault->detail : ""));
        }
    }

    /**
     * Whether an API function exists
     *
     * @param string $function
     * @return boolean
     */
    protected function functionExists($function) {
        return array_key_exists($function, $this->functions) ? true : false;
    }

    /**
     * Whether a parameter is required
     *
     * @param string $param
     * @return boolean
     */
    protected function paramIsRequired($param) {
        return substr($param, -1) === "!" ? true : false;
    }

    /**
     * Gets parameters from a function argument list
     *
     * @param array $arguments
     * @return array
     */
    protected function getParamsFromArguments($arguments) {
        return isset($arguments[0]) ? $arguments[0] : array();
    }

    /**
     * Returns an array of allowed parameters for an API function
     *
     * @param string $function
     * @return string[]
     */
    protected function allowedParams($function) {
        $params = explode(',', $this->functions[$function]);
        return array_map('trim', $params);
    }

    /**
     * Returns an array of required parameters for an API function
     *
     * @param string $function
     * @return String[]
     */
    protected function requiredParams($function) {
        $params = array_map('trim', explode(',', $this->functions[$function]));
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
     */
    protected function paramIsAllowed($param, $function) {
        $allowed_params = $this->allowedParams($function);
        return in_array("$param!", $allowed_params) || in_array($param, $allowed_params) || (preg_match('/_[0-9]+$/', $param) && in_array("target_N", $allowed_params) && strpos($param,'target_') !== false);
    }

    /**
     * Ensures that the given parameters contain every required parameter.
     * Also ensures there are no unnecessary parameters
     *
     * @param string $function
     * @param array $given_params
     * @throws KasApiException
     * @return void
     */
    protected function ensureFunctionParams($function, $given_params) {

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
     * @throws KasApiException
     * @return Object
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
