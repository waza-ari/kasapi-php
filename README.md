#kasapi-php

##Manage your All-Inkl account with the KAS API for PHP

All-Inkl.com provides an API for automated access to all your accounts, settings, (sub-)domains, databases, cronjobs, mail accounts, ...
This API is called the *KAS API*. To learn more about it, visit the official [KAS API Documentation](http://kasapi.kasserver.com/dokumentation/phpdoc/).
There are also some [example forms](http://kasapi.kasserver.com/dokumentation/?open=beispiele) to try out.

This is a PHP implementation of the API, which provides simple access to all functions provided by the API.

###Getting started

The recommended installation method is to use composer. This software is available at Packagist.

Just add the following line to the "require" section of your composer.json 

```
 "wazaari/kasapi-php": "<version>"
```

Alternatively you can clone the following GIT repository (`git clone https://github.com/wazaari/kasapi-php.git`).

You only need to adjust a few settings for your needs:
- `$kas_username`: insert your All-Inkl username here
- `$kas_password`: and your All-Inkl password

TODO: Include example of how to use it.

###A closer look

Now, we will take a closer look at how this API works.

Whenever you want to use the API, you need to create a KasConfiguration object first. This is done easily:
```
$kasConfiguration = new KasConfiguration($kas_username, $kas_password, $session_lifetime, $session_update_lifetime);
```
Username and password are self-explaining. The lifetime determines how long the API authentication is valid. It is specified in seconds, and may be maximal 30 minutes (1800 seconds). The parameter update_lifetime is a boolean, noted as string ('Y' or 'N'), indicating whether the authentication refreshes on expiration. I recommend to set these values on 1800 and 'Y'.

Then you create an KasApi object to operate on:
```
$kasApi = new KasApi($kas_configuration);
```
On this object, you can call any API method specified in the KAS documentation. Alternatively, you can have a look at the KasApi class.
```
$api->get_databases();
```

Examples from the KasApi class might look like this

```
private $functions = array(
  [...]
  'get_dns_settings'        => 'zone_host!, nameserver, record_id',
  'get_domains'             => 'domain_name',
  'get_topleveldomains'     => '',
  'get_ftpusers'            => 'ftp_login',
  'get_mailaccounts'        => 'mail_login',
  [...]
);
```
This array specifies which API functions you may call and which parameters to pass. The `!` suffix means that this parameter is required and has to be specified (e.g. `zone_host!`), all other parameters are optional (e.g. `domain_name`).

So if you look at `get_dns_settings` above, you see that a call like
```
$api->get_dns_settings(array(
  'zone_host' => 'example.com.',
  'record_id' => 123
));
```
is perfectly valid. Notice we omitted the `nameserver` parameter, which is optional, but we included the `zone_host`, which is required in every case.

If you have any feedback, please provide it as comment or issue using GitHub and the URL above. I will recommend as soon as possible.

##Credits

This work is partially based on previous work by info@elias-kuiter.de (https://github.com/ekuiter/kasapi-php) and has been extended by all current functions provided by the all-inkl API. Further, it has been streamlined, some errors and typos have been corrected, and it was changed to allow for composer integration.