Update default values for datetime columns
------------------------------------------
MySQL 8.0.16 broke date(times) because of https://bugs.mysql.com/bug.php?id=93513 .
This results in dates below "1000-01-01" being treated as invalid - so especially
"0000-00-00 00:00:00" is now invalid.
The values have already been updated for new installations - but existing columns
still have "0000-00-00 00:00:00" as the default value. To update them, the update.phps
need to be updated.


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

* Add back a Captcha-like protection - this time GDPR conforming
* * Use for the registration- and contact-forms
* * Possible self-hosted solution: https://mosparo.io/
* * Possible cloud-hosted solution: https://www.hcaptcha.com/
