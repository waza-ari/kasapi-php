<?php

namespace KasApi;

/**
 * Contains configuration values for the KAS API
 *
 * @package default
 * @author Elias Kuiter
 */
class KasConfiguration {

    /**
     * KAS username
     *
     * @var string
     */
    public $username;

    /**
     * KAS password
     *
     * @var string
     */
    public $password;

    /**
     * Lifetime of a KAS session.
     * maximum is 30 minutes
     *
     * @var string
     */
    public $session_lifetime;

    /**
     * Whether the KAS session should be renewed on every request.
     * Y = token is refreshed on every request, N = contrary
     *
     * @var string
     */
    public $session_update_lifetime;

    /**
     * WSDL file for KAS Authentication
     *
     * @var string
     */
    public $wsdl_auth = 'https://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl';

    /**
     * WSDL file for KAS API
     *
     * @var string
     */
    public $wsdl_api = 'https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl';

    /**
     * Creates a new Configuration object with the given parameters
     *
     * @param string $username
     * @param string $password
     * @param string $session_lifetime
     * @param string $session_update_lifetime
     * @author Elias Kuiter
     */
    function __construct($username, $password, $session_lifetime, $session_update_lifetime) {
        $this->username = $username;
        $this->password = $password;
        $this->session_lifetime = $session_lifetime;
        $this->session_update_lifetime = $session_update_lifetime;
    }
}