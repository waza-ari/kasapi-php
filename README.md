#kasapi-php

##Manage your All-Inkl account with the KAS API for PHP

All-Inkl.com provides an API for automated access to all your accounts, settings, (sub-)domains, databases, cronjobs, mail accounts, ...
This API is called the *KAS API*. To learn more about it, visit the official [KAS API Documentation](http://kasapi.kasserver.com/dokumentation/phpdoc/).
There are also some [example forms](http://kasapi.kasserver.com/dokumentation/?open=beispiele) to try out.

This is a PHP implementation of the API, which provides simple access to all functions provided by the API.

###Installation

The recommended installation method is to use Composer. This software is [available at Packagist](https://packagist.org/packages/wazaari/kasapi-php).

Just add the following line to the `require` section of your `composer.json`:

```
 "wazaari/kasapi-php": "dev-master"
```

Alternatively you can clone the following Git repository (`git clone https://github.com/waza-ari/kasapi-php.git`, see below).

###Usage

Now, we will take a closer look at how this API works.

Whenever you want to use the API, you need to create a KasConfiguration object first. This is done easily:
```
$kasConfiguration = new KasConfiguration($username, $authData, $authType);
```
`$username` is quite self explaining. The KAS API allows for different types of authentication. Thus, you need to specify an authentication type and the corresponding authentication data, which could be a hashed password. Have a look at the documentation of All-Inkl to obtain a list of possible authentication methods.

As an example, assume you want to use `sha1` as authentication method. In this case, `$authType` simply would be `sha1`, and `$authData` should be set to the sha1 hash of your KAS account. Assuming your username is `abcd1234` and your password is `password`, the following line would create the correct credential object:
```
$kasConfiguration = new KasConfiguration("abcd1234", sha1("password"), "sha1");
```

This method allows you to authenticate against the KAS API without storing your plain password in a configuration file or database. Next, you need to create an KasApi object to operate on:
```
$kasApi = new KasApi($kasConfiguration);
```
On this object, you can call any API method specified in the [KAS documentation](http://kasapi.kasserver.com/dokumentation/phpdoc/packages/API%20Funktionen.html). Alternatively, you can have a look at the KasApi class.
```
$kasApi->get_databases();
```

Examples from the KasApi class might look like this:

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
$kasApi->get_dns_settings(array(
  'zone_host' => 'example.com.',
  'record_id' => 123
));
```
is perfectly valid. Notice we omitted the `nameserver` parameter, which is optional, but we included the `zone_host`, which is required in every case.

###Usage without Composer

Here's an example of how to use the API if you just `git clone` this repository:
(Place this file in the parent directory of the `src` directory.)
```
<?php
namespace KasApi;
foreach (glob("src/KasApi/*.php") as $filename) {
    require_once $filename; // include kasapi-php
}

$kasConfiguration = new KasConfiguration("abcd1234", sha1("password"), "sha1");
$kasApi = new KasApi($kasConfiguration);

try {
  $kasData = $kasApi->get_domains(); // any API function as described above
  var_dump($kasData); // $kasData is a plain old PHP array
} catch(KasApiException $e) {
  echo $e->getMessage(); // show message on SOAP error
}

?>
```
If you have any feedback, please provide it as comment or issue using GitHub and the URL above.

##Changelog

#### Version 0.5

* Starting from version 0.5, this project is now released under the [MIT license](http://opensource.org/licenses/MIT). A copy can also be found in this repository. Further, the former GPL license has been removed.

#### Version 0.4

* Minor fix to prevent a Warning while fetching parameters from arguments

#### Version 0.3

* Initial version of this library, created by [Elias Kuiter](https://github.com/ekuiter/) and extended by [Daniel Herrmann](https://github.com/waza-ari/).
* Composer support was added.

##Credits

[Elias Kuiter](https://github.com/ekuiter/) created `kasapi-php` to provide an easy way to access All-Inkl's public API.
Credits go to [Daniel Herrmann](https://github.com/waza-ari/) as well for making big extensions to the API (such as streamlining the classes, correcting some errors and adding Composer integration).
Note that the original repository ([ekuiter/kasapi-php](https://github.com/ekuiter/kasapi-php)) is now deprecated, use this repository instead.
