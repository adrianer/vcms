Package-Upgrades
----------------

### Upgrade following packages:

* bootstrap (v3 -> v5)
* chartjs (v2 -> v4)
* font-awesome (v4 -> v6)
* jquery (v2 -> v3)


### Other packages that are not-up-to-date, hence need upgrades:

* blueimp-file-upload (https://github.com/blueimp/jQuery-File-Upload/)
* hover (https://github.com/IanLunn/Hover/)
* libre-franklin (https://fontsource.org/fonts/libre-franklin + https://www.npmjs.com/package/@fontsource/libre-franklin/)
* phpass (https://www.openwall.com/phpass/ + https://github.com/openwall/phpass/)
* * Stright-forward upgrade to 0.5.4 breaks the login...
* scrollreveal (https://github.com/jlmakes/scrollreveal/)


Add back previously removed features
------------------------------------

* Add back a map on the Contact page - this time GDPR conforming
* * Use https://leafletjs.com/ for that
* * Use the following attributes from the old implementation, as they still do exist on many installations: show_map, map_latitude, map_longitude
* * Implementation hint: this is the commit that removed Google Maps: https://github.com/uwol/vcms/commit/3e27778518910cdd7b37ec2329240a0d3bfb60a1


Add new features
----------------
* Close open photos with ESC
* Navigate between photos with the keyboard arrows
* On the registration page let the user choose the category (e.g. "Ehepartner" or "Verbindungsfreund"), so more confusing cases will be clearer
* * The default should be "Philister"
* Allow registration for a "mailinglist"-only (without any user-login-possibility)
* * The purpose is to allow interested persons to be kept in the email-loop
* Automate the user- and malilinglist-registrations more
* * Instead of the now copy&pasting data from emails, already create the user in the DB, but in a status "inactive-confirmation-needed"
* * For mailinglists, optionally allow complete automation - but only, if the email address has been automatically confirmed


Fix Bugs
--------
* Browser-Back-Button breaking the page on many occasions (e.g. photo-gallery)
* Browser-Refresh-Button creating new login-sessions sometimes
* "Daten -> Personen" view not usable on mobile-vertical-displays (too narrow and not even scrollable horizontally)
* Login Session very short - was longer before
