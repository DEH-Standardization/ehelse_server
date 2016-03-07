## php-login-advanced

A simple, but secure PHP login script. Similar to minimal version, but much more features: PDO, Register, login, logout, email verification, password reset, edit user data, gravatars, captchas, remember me / stay logged in cookies,
"remember me" supports parallel login from multiple devices, login with email, i18n/internationalization,
mail sending via PHPMailer (SMTP or PHP's mail() function/linux sendmail). Uses the ultra-modern & future-proof PHP 5.5. BLOWFISH hashing/salting functions (includes the official PHP 5.3 & PHP 5.4 compatibility pack, which makes those
functions available in those versions too).

This script was originally part of the "php-login project", a collection of 4 different login scripts made in the 2012-2013 PHP era to give especially beginners and security-inexperienced users a set of basic auth functions that fitted the most modern password hashing standards possible. You know, this was the time when even major companies like SONY and LinkedIn used horrible outdated MD5-hashing for their passwords (or even saved everything in plain text) and when the big PHP frameworks didn't have proper user auth solution out-of-the-box.

[![Support the project](_installation/banner-host1plus.png)](https://affiliates.host1plus.com/ref/devmetal/36f4d828.html)

Find the other versions here:

**One-file version** (not maintained anymore)
Full login script in one file. Uses a one-file SQLite database (no MySQL needed) and PDO: Register, login, logout.
https://github.com/panique/php-login-one-file

**Minimal version** (not maintained anymore)
All the basic functions in a clean file structure, uses MySQL and mysqli. Register, login, logout.
https://github.com/panique/php-login-minimal

**Advanced version** (not maintained anymore)
Similar to the minimal version, but full of features. Uses PDO, Captchas, mail sending via SMTP and much more.
https://github.com/panique/php-login-advanced

**HUGE (professional version)** 
Quite professional MVC framework structure, useful for real applications. Additional features like: URL rewriting, mail sending via PHPMailer (SMTP or PHP's mail() function/linux sendmail), user profile pages, public user profiles, gravatars and local avatars, account upgrade/downgrade etc., OAuth2, Composer integration, etc.
https://github.com/panique/huge

## Requirements

- PHP 5.3.7+
- MySQL 5 database (please use a modern version of MySQL (5.5, 5.6, 5.7) as very old versions have a exotic bug that
[makes PDO injections possible](http://stackoverflow.com/q/134099/1114320).
- activated PHP's GD graphic functions (the tutorial shows how)
- enabled OpenSSL module (the tutorial shows how)
- this version uses mail sending, so you need to have an **SMTP mail sending account** somewhere OR you know how to get
 **linux's sendmail** etc. to run. As it's nearly impossible to send real mails with PHP's mail() function (due to
 anti-spam blocking of nearly every major mail provider in the world) you should really use SMTP mail sending.

## Installation (quick setup)

* 1. create database *login* and table *users* via the SQL statements in the `_installation` folder.
* 2. in `config/config.php`, change mySQL user and password (*DB_USER* and *DB_PASS*).
* 3. in `config/config.php`, change *COOKIE_DOMAIN* to your domain name (and don't forget to put the dot in front of the domain!)
* 4. in `config/config.php`, change *COOKIE_SECRET_KEY* to a random string. this will make your cookies more secure
* 5. change the URL part of EMAIL_PASSWORDRESET_URL and EMAIL_VERIFICATION_URL in `config/config.php` to your URL! You need to provide the URL of your project here to link to your project from within
verification/password reset mails.
* 6. as this version uses email sending, you'll need to a) provide an SMTP account in the config OR b) install a mail server tool on your server.
Using a real SMTP provider (like [SMTP2GO](http://www.smtp2go.com/?s=devmetal) etc.) is highly recommended. Sending emails manually via mail() is something for hardcore admins.
Usually mails sent via mail() will never reach the receiver. Please also don't try weird Gmail setups, this can fail to a lot of reasons.
Get professional and send mails like mail should be sent. It's extremely cheap and works.

- To enable OpenSSL, do `sudo apt-get install openssl` (and restart the apache via `sudo service apache2 restart`)
- To enable PHP's GD graphic functions, do `sudo apt-get install php5-gd` (and restart the apache via `sudo service apache2 restart`)

## Installation (very detailed setup)

A very detailed guideline on how to install the script
[here in this blog post](http://www.dev-metal.com/install-php-login-nets-2-advanced-login-script-ubuntu/).

## Troubleshooting & useful stuff

Please use a real SMTP provider for sending mail. Using something like gmail.com or even trying to send mails via
mail() will bring you into a lot of problems (unless you really really know what you are doing). Sending mails is a
huge topic. But if you still want to use Gmail: Gmail is very popular as an SMTP mail sending service and would
work for smaller projects, but sometimes gmail.com will not send mails anymore, usually because of:

1. "SMTP Connect error": PHPMailer says "smtp login failed", but login is correct: Gmail.com thinks you are a spammer. You'll need to
"unlock" your application for gmail.com by logging into your gmail account via your browser, go to http://www.google.com/accounts/DisplayUnlockCaptcha
and then, within the next 10minutes, send an email via your app. Gmail will then white-list your app server.
Have a look here for full explanaition: https://support.google.com/mail/answer/14257?p=client_login&rd=1

2. "SMTP data quota exceeded": gmail blocks you because you have sent more than 500 mails per day (?) or because your users have provided
 too much fake email addresses. The only way to get around this is renting professional SMTP mail sending, prices are okay, 10.000 mails for $5.

## Security notice

This script comes with a handy .htaccess in the views folder that denies direct access to the files within the folder
(so that people cannot render the views directly). However, these .htaccess files only work if you have set
`AllowOverride` to `All` in your apache vhost configs. There are lots of tutorials on the web on how to do this.

## How this script works

If you look into the code and at the file/folder-structure everything should be self-explaining.

## Useful links

- [How to use PDO](http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)
- [A little guideline on how to use the PHP 5.5 password hashing functions and its "library plugin" based PHP 5.3 & 5.4 implementation](http://www.dev-metal.com/use-php-5-5-password-hashing-functions/)
- [How to setup latest version of PHP 5.5 on Ubuntu 12.04 LTS](http://www.dev-metal.com/how-to-setup-latest-version-of-php-5-5-on-ubuntu-12-04-lts/). Same for Debian 7.0 / 7.1:
- [How to setup latest version of PHP 5.5 on Debian Wheezy 7.0/7.1 (and how to fix the GPG key error)](http://www.dev-metal.com/setup-latest-version-php-5-5-debian-wheezy-7-07-1-fix-gpg-key-error/)
- [Notes on password & hashing salting in upcoming PHP versions (PHP 5.5.x & 5.6 etc.)](https://github.com/panique/php-login/wiki/Notes-on-password-&-hashing-salting-in-upcoming-PHP-versions-%28PHP-5.5.x-&-5.6-etc.%29)
- [Some basic "benchmarks" of all PHP hash/salt algorithms](https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)

## License

Licensed under [MIT](http://www.opensource.org/licenses/mit-license.php). You can use this script for free for any
private or commercial projects.

## Contribute

This script is not developed any further due to End of Lifetime, so please only commit bugfixes, not new features, no new translations or similar. If you really want to develop on this project then please fork it and commit to your version. Feel free to do with this project whatever you want.

## Support

If you think this script is useful and saves you a lot of work, then think about getting your next server from
[Host1Plus](https://affiliates.host1plus.com/ref/devmetal/36f4d828.html). Thanks! :)

## My blog

Also have a look on my blog if you like: **[Dev Metal](http://www.dev-metal.com)**.
