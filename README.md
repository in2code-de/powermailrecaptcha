# powermailrecaptcha

## Google recaptcha for TYPO3 powermail 3.x and higher to prevent spam

<img src="https://box.everhelper.me/attachment/542050/34726531-a4d7-4620-9d70-39b6cb4c519e/262407-Fi3TkpDpR25HHyRV/screen.png" />


## Changelog

- 1.0.1 Activate check only if form has a recaptcha field and not every time, some more stuff in the manual
- 1.0.0 Initial upload - have fun


## Installation

- Just install this extension (oldschool or via composer)
- Clear caches
- Register your domain to www.google.com/recaptcha/
- Add sitekey and secretkey to TypoScript Constants (see example below)
- Add a field of Type Google Recaptcha to your powermail form
- Have fun

Example for TypoScript Constants:
```
plugin.tx_powermailrecaptcha.sitekey = 6LdsBBUTAAAAAKMhI67inzeAvzBh5JdRRxlCwbTz
plugin.tx_powermailrecaptcha.secretkey = 6LdsBBUTAAAAAKMhaaaainzeAvzBh5JdRRxlCwbyy
```

## Notes and best practice

Be sure to have spamshield enabled in powermail (TypoScript setup):

```
plugin.tx_powermail.settings.setup.spamshield._enable = 1
```

Keep up to date if powermail recognize spam (TypoScript setup):
```
# Get an email if spam was recognized
plugin.tx_powermail.settings.setup.spamshield.email = alex@in2code.de

# Write to a logfile when spam was recognized
plugin.tx_powermail.settings.setup.spamshield.logfileLocation = typo3temp/logs/powermailSpam.log
```
