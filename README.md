#kasapi-php

##Manage your All-Inkl account with the KAS API for PHP

All-Inkl.com provides an API for automated access to all your accounts, settings, (sub-)domains, databases, cronjobs, mail accounts, ...
This API is called the *KAS API*. To learn more about it, visit the official [KAS API Documentation](http://kasapi.kasserver.com/dokumentation/phpdoc/).
There are also some [example forms](http://kasapi.kasserver.com/dokumentation/?open=beispiele) to try out.

This is a PHP implementation of the API, though other programming languages are possible too; in fact every language in which you can issue SOAP requests.

###Getting started

To start using the KAS API for PHP, clone this repository (`git clone https://github.com/ekuiter/kasapi-php.git`) and take a look at `example.php`.

You only need to adjust a few settings for your needs:
- `$kas_username`: insert your All-Inkl username here
- `$kas_password`: and your All-Inkl password

Read the explanations and make yourself familiar with the typical api call: `$api->get_domains();`
So, to try it out, just remove some comments from the example API calls, upload the PHP files to your server and browse to `example.php`.

Congratulations! You made your first steps with the KAS API.

###A closer look

Now, we will take a closer look at how this works. You don't have to read this if the given example calls serve your needs.

Whenever you want to use the API, you need to create a KasConfiguration object first. This is done easily:

`$kas_configuration = new KasConfiguration($kas_username, $kas_password, $session_lifetime, $session_update_lifetime);`

Username and password are self-explaining. The lifetime determines how long the API authentication lasts. It is specified in seconds, and may be maximal 30 minutes (1800 seconds). The update_lifetime is a boolean, noted as string ('Y' or 'N'), indicating whether the authentication refreshes on expiration. I recommend to set these values on 1800 and 'Y'.

Then you create an KasApi object to operate on:

`$api = new KasApi($kas_configuration);`

On this object, you can call any API method specified in KasApi.class.php (more on this later):

`$api->get_databases();`

Now I'll explain what each file in this repository is for:
- `examples.php`: You know this file already, it contains the first steps.
- `kas.inc.php`: You need to `require_once` this file in your application. It also contains functions for exception and session handling.
- `KasConfiguration.class.php`: This is a simple class that contains your configuration values.
- `KasSoapClient.class.php`: We use the PHP `SoapClient` with WSDL to connect to the API. This class is for creating these clients.
- `KasAuthToken.class.php`: This class takes care of authentication through a `SoapClient`.
- `KasApi.class.php`: Finally, the most interesting class, which does the actual API calling based upon an array called `$functions`:
  ```
  private $functions = array(
      'get_accountressources'   => array(),
      'get_accounts'            => array('account_login:opt'),
      ...
      'get_traffic'             => array('year:opt', 'month:opt'),
      'get_subdomains'          => array('subdomain_name:opt'),
    );
  ```
  This array specifies which API functions you may call and which parameters to pass. The `:opt` suffix means that this parameter is optional and does not have to be specified.

So if there's an entry

`'get_dns_settings'        => array('zone_host', 'nameserver:opt', 'record_id:opt'),`

a call like
```
$api->get_dns_settings(array(
  'zone_host' => 'example.com.',
  'record_id' => 123
));
```
is perfectly valid. Notice we omitted the `nameserver` parameter, which is optional, but we included the `zone_host`, which is required in every case.

(At the moment, this repository only supports the `get_xyz` API calls. This will change soon, and the `add`, `update` and `delete` calls will be implemented. You are free to add them yourself; in that case, please contribute your changes.)

That's it! You now know how to use the API and how it works, so start writing an awesome application on top of this.

Good luck, and please [give me feedback](mailto:info@elias-kuiter.de) if you have improvement suggestions!