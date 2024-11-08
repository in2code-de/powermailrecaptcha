# powermailrecaptcha

## Google recaptcha for TYPO3 powermail to prevent spam

![Example form with a google recaptcha](Documentation/Images/frontend.png "Example form with a google recaptcha")


## Dependencies

* powermail 8.x, 9.x, 10.x, 11.x, 12.x
* TYPO3 Version 10, 11 & 12
* Google recaptcha V2 (https://developers.google.com/recaptcha/docs/v2)

## Installation

- Just install this extension via composer `composer require in2code/powermailrecaptcha`
- Register your domain to www.google.com/recaptcha/ (registration direct link: https://g.co/recaptcha/v3 - reCAPTCHA, Version 2)
- Add sitekey and secretkey to TypoScript Constants (see example below)
- Ensure that spamshield is enabled (see below)
- Add a field of Type Google Recaptcha to your powermail form
- Have fun

Example for TypoScript Constants:

```
plugin.tx_powermailrecaptcha.sitekey = 6LdsBBUTAAAAAKMhI67inzeAvzBh5JdRRxlCwbTz
plugin.tx_powermailrecaptcha.secretkey = 6LdsBBUTAAAAAKMhaaaainzeAvzBh5JdRRxlCwbyy
```

## Common pitfalls and best practice

spamshield must be enabled in powermail (TypoScript setup):

```
plugin.tx_powermail.settings.setup.spamshield._enable = 1
```

Keep up to date if powermail recognize spam (TypoScript setup):

```
# Get an email if spam was recognized
plugin.tx_powermail.settings.setup.spamshield.email = spamreceiver@yourdomain.de

# Write to a logfile when spam was recognized
plugin.tx_powermail.settings.setup.spamshield.logfileLocation = typo3temp/logs/powermailSpam.log
```

## Early Access Programm for TYPO3 13 support

:information_source: **TYPO3 13 compatibility**
> See [EAP page (DE)](https://www.in2code.de/agentur/typo3-extensions/early-access-programm/) or
> [EAP page (EN)](https://www.in2code.de/en/agency/typo3-extensions/early-access-program/) for more information how
> to get access to a TYPO3 13 version

## Changelog

| Version | Date       | Description                                                                                         |
|---------|------------|-----------------------------------------------------------------------------------------------------|
| 13.0.0   | tbd       | TYPO3 v13 compatibility - planned                                                                   |
| 5.2.2   | 2024-08-25 | Bugfix to add valid actions - thx to speters                                                        |
| 5.2.1   | 2024-03-30 | Support for Powermail 12                                                                            |
| 5.2.0   | 2023-12-31 | Support for TYPO3 12                                                                                |
| 5.1.0   | 2023-05-13 | Support for TYPO3 11 / Fix typo in ter-release.yml file                                             |
| 5.0.3   | 2021-09-25 | Fix typo in ter-release.yml file                                                                    |
| 5.0.2   | 2021-09-09 | Add extension key to composer.json                                                                  |
| 5.0.1   | 2020-12-03 | Add TYPO3 dependency to ext_emconf.php to make TER upload happy                                     |
| 5.0.0   | 2020-12-03 | Update dependencies for powermail 8.x                                                               |
| 4.0.0   | 2018-11-21 | Update dependencies for powermail 7.x                                                               |
| 3.0.0   | 2018-07-13 | Update dependencies for powermail 6.x                                                               |
| 2.0.0   | 2018-02-14 | Update dependencies for powermail 5.x                                                               |
| 1.1.0   | 2017-11-04 | Update dependencies for powermail 4.x                                                               |
| 1.0.1   | 2016-08-06 | Activate check only if form has a recaptcha field and not every time, some more stuff in the manual |
| 1.0.0   | 2016-08-06 | Initial upload - have fun                                                                           |

# Development

Environment is included. Set it up via:
* DDEV start
* Composer install
* ddev import-db .project/db.sql.gz

Made for TYPO3 12 / powermail 12
