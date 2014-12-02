#kasapi-php

##Manage your All-Inkl account with the KAS API for PHP

All-Inkl.com provides an API for automated access to all your accounts, settings, (sub-)domains, databases, cronjobs, mail accounts, ...
This API is called the *KAS API*. To learn more about it, visit the official [KAS API Documentation](http://kasapi.kasserver.com/dokumentation/phpdoc/).
There are also some [example forms](http://kasapi.kasserver.com/dokumentation/?open=beispiele) to try out.

This is a PHP implementation of the API, which provides simple access to all functions provided by the API.

###Installation

The recommended installation method is to use composer. This software is available at Packagist.

Just add the following line to the "require" section of your composer.json 

```
 "wazaari/kasapi-php": "<version>"
```

Alternatively you can clone the following GIT repository (`git clone https://github.com/wazaari/kasapi-php.git`).

###Usage

Now, we will take a closer look at how this API works.

Whenever you want to use the API, you need to create a KasConfiguration object first. This is done easily:
```
$kasConfiguration = new KasConfiguration($kas_username, $kas_auth_type, $kas_auth_data);
```
Username is quite self explaining. The KAS API allows for different types of authentication. Thus, you need to specify an authentication type and the corresponding authentication data, which could be a hashed password. Have a look at the documentation of all-incl to obtain a list of possible authentication methods.

As an example, assume you want to use "sha1" as authentication method. In this case, kas_auth_type simply would be "sha1", and kas_auth_data should be set to the sha1 hash of your KAS account. Assuming your username is "abcd1234" and your password is "password", the following line would create the correct credential object:
```
$kasConfiguration = new KasConfiguration("abcd1234", "sha1", sha1("password"));
```

This method allows you to authenticate against the KAS API without storing your plain password in a configuration file or database. Next, you need to create an KasApi object to operate on:
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

If you have any feedback, please provide it as comment or issue using GitHub and the URL above..

##Credits

This work is partially based on previous work by info@elias-kuiter.de (https://github.com/ekuiter/kasapi-php) and has been extended by all current functions provided by the all-inkl API. Further, it has been streamlined, some errors and typos have been corrected, and it was changed to allow for composer integration.