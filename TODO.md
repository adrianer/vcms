TODO
----

Packages
--------

### Upgrade following packages:

* chartjs (v2 -> v4)
* font-awesome (v4 -> v6)


### Other packages that are not-up-to-date, hence need upgrades:

* blueimp-file-upload ( blueimp/jquery-file-upload )
* hover (https://github.com/IanLunn/Hover/)
* libre-franklin (https://fontsource.org/fonts/libre-franklin + https://www.npmjs.com/package/@fontsource/libre-franklin/)
* phpass (https://www.openwall.com/phpass/ + https://github.com/openwall/phpass/)
  * Straight-forward upgrade to 0.5.4 breaks the login...
  * Alternative: switch to bordoni/phpass
* scrollreveal (https://github.com/jlmakes/scrollreveal/)


### Remove the need of patched external packages

* Find another way to use fontawesome-webfont with MPDF instead of patching MPDF


### Remove the external packages from the repository (use composer instead)

* Requires finding alternatives to patching packages (e.g. MPDF)


### Bundle Leaflet.js with the project so that it does not need to be loaded from CDN


### Stop loading JS files that the user does not need

* Load Leaflet.js from CDN only if the map was activated
* Load HCaptcha from CDN only if HCaptcha was activated


Add new features
----------------
* Navigate between photos with the keyboard arrows
* On the registration page let the user choose the category (e.g. "Ehepartner" or "Verbindungsfreund"), so more confusing cases will be clearer
* * The default should be "Philister"
* Allow registration for a "mailinglist"-only (without any user-login-possibility)
  * The purpose is to allow interested persons to be kept in the email-loop
* Automate the user- and malilinglist-registrations more
  * Instead of the now copy&pasting data from emails, already create the user in the DB, but in a status "inactive-confirmation-needed"
  * For mailinglists, optionally allow complete automation - but only, if the email address has been automatically confirmed


Fix Bugs
--------
* Browser-Back-Button breaking the page on many occasions (e.g. photo-gallery)
* Browser-Refresh-Button creating new login-sessions sometimes
* Login Session very short - was longer before
