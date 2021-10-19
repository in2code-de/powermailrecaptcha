# powermailrecaptcha

## Captcha field for TYPO3 powermail to prevent spam

This extension adds Google Recaptcha or any Recaptcha based captcha provider field as a spamshield in TYPO3 Powermail

Here's a exemple with Google Recaptcha
![Example form with a google recaptcha](Documentation/Images/frontend.png "Example form with a google recaptcha")

## Dependencies

* powermail >= 8.0

## Installation

- Just install this extension via composer `composer require in2code/powermailrecaptcha`
- Register your domain to your captcha provider. ([see Providers section](#Providers))
- Add sitekey and secretkey to TypoScript Constants (see example below)
- Ensure that spamshield is enabled (see below)
- Add a field of Type Captcha to your powermail form
- Have fun

Example for TypoScript Constants : _(Google Recaptcha test key)_

```typo3_typoscript
plugin.tx_powermailrecaptcha.sitekey = 6LdsBBUTAAAAAKMhI67inzeAvzBh5JdRRxlCwbTz
plugin.tx_powermailrecaptcha.secretkey = 6LdsBBUTAAAAAKMhaaaainzeAvzBh5JdRRxlCwbyy
```

## Common pitfalls and best practice

spamshield must be enabled in powermail (TypoScript setup):

```typo3_typoscript
plugin.tx_powermail.settings.setup.spamshield._enable = 1
```

Keep up to date if powermail recognize spam (TypoScript setup):

```typo3_typoscript
# Get an email if spam was recognized
plugin.tx_powermail.settings.setup.spamshield.email = spamreceiver@yourdomain.de

# Write to a logfile when spam was recognized
plugin.tx_powermail.settings.setup.spamshield.logfileLocation = typo3temp/logs/powermailSpam.log
```

## Providers

This extension can handle any Google Recaptcha based captcha APIs

| Name                       | Provider Name        | Provider URL                            |
| -------------------------- | -------------------- | --------------------------------------- |
| Google Recaptcha (default) | recaptcha            | https://www.google.com/recaptcha/about/ |
| hCaptcha                   | hcaptcha             | https://www.hcaptcha.com/               |

### Switching between providers

If provider is empty it will take the default **recaptcha**

```typo3_typoscript
plugin.tx_powermailrecaptcha.provider = provider
```

### Adding a provider

- add a provider in ext_typoscript_constants.txt

```typo3_typoscript
plugin.tx_powermailrecaptcha {
    provider {
        verificationuri = https://provider.com/siteverify
        verificationresponsename = provider-captcha-response
        javascript = https://provider.com/api.js
        htmlclass = provider-captcha
    }
}
```

- add a condition in ext_typoscript_setup.txt

```typo3_typoscript
["{$plugin.tx_powermailrecaptcha.provider}" == "provider"]
    plugin.tx_powermail.settings.setup {
        captcha {
            javascript = {$plugin.tx_powermailrecaptcha.provider.javascript}
            htmlclass = {$plugin.tx_powermailrecaptcha.provider.htmlclass}
        }

        spamshield.methods.10.configuration {
            verificationuri = {$plugin.tx_powermailrecaptcha.provider.verificationuri}
            verificationresponsename = {$plugin.tx_powermailrecaptcha.provider.verificationresponsename}
        }
    }
[END]
```

## Changelog

| Version    | Date       | Description                                                                                                  |
| ---------- | ---------- | ------------------------------------------------------------------------------------------------------------ |
| 5.0.4      | 2021-10-19 | Add Recaptcha based providers handling                                                                       |
| 5.0.3      | 2021-09-25 | Fix typo in ter-release.yml file                                                                             |
| 5.0.2      | 2021-09-09 | Add extension key to composer.json                                                                           |
| 5.0.1      | 2020-12-03 | Add TYPO3 dependency to ext_emconf.php to make TER upload happy                                              |
| 5.0.0      | 2020-12-03 | Update dependencies for powermail 8.x                                                                        |
| 4.0.0      | 2018-11-21 | Update dependencies for powermail 7.x                                                                        |
| 3.0.0      | 2018-07-13 | Update dependencies for powermail 6.x                                                                        |
| 2.0.0      | 2018-02-14 | Update dependencies for powermail 5.x                                                                        |
| 1.1.0      | 2017-11-04 | Update dependencies for powermail 4.x                                                                        |
| 1.0.1      | 2016-08-06 | Activate check only if form has a recaptcha field and not every time, some more stuff in the manual          |
| 1.0.0      | 2016-08-06 | Initial upload - have fun                                                                                    |
