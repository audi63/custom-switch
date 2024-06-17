=== WP Custom Switch ===
Contributors: johancoffigniez
Tags: wp custom switch, wp, custom, qwitch, button, price, options
Donate link: https://www.paypal.me/johancoffigniez
Requires at least: 4.6
Tested up to: 6.5.4
Stable tag: 0.0.0

WP Custom Switch, Plugin Worpress pour créer des boutons switch personnalisables et administrables facilement.
Testé sur la version 6.5.4 de Wordpress.


== Description ==
Plugin Worpress pour créer des boutons switch personnalisables et administrables facilement. Ils peuvent être placé partout, et même clonés pour avoir le mêmeétat d'un bouton sur des pages différentes.

== Installation ==
1. unzip
2. upload to wp-content/plugin
3. Go to your dashboard to activate it
4. have fun!

== Frequently Asked Questions ==
= No more question ? =

Use the Gutenberg block
   Peut être un jour...

Or use the old Shortcode :

1. Create a page with the name you want.
2. Add the Shortcode [custom_switch id="votre_shortcode_id"]
3. Add parameters ( label-on, label-off, button-on, button-off, label-position="after" or label-position="before" )

Example [custom_switch id="5" label-on="ON" label-off="OFF" button-on="wp-content/plugins/wp-custom-switch/assets/images/default/button_on.svg" button-off="wp-content/plugins/wp-custom-switch/assets/images/default/button_off.svg" label-position="after"]

3. That\'s all!

== Screenshots ==
1. Default custom label and images   ![Screenshot 1](./screenshots/1.png)
2. Shortcode form                    ![Screenshot 2](./screenshots/2.png)
3. Shortcode listing                 ![Screenshot 3](./screenshots/3.png)
4. Edit and delete shortcode         ![Screenshot 4](./screenshots/4.png)
5. Others screenshots                ![Screenshot 5](./screenshots/5.png)


== Changelog ==
= 1.0.1 = 17 jun 2024
* happy Father's Day for yesterday ;)
* Add security for change only by admin
* Code reorganisation

= 1.0.1 = 17 jun 2024
* Correction mineure - Ajout du nom de l'auteur

= 1.0.0 = 17 jun 2024
* initial version

Arborescence :

wp-custom-switch/
|-- assets/
|   |-- css/
|   |   |-- custom-button-style.css
|   |-- js/
|       |-- custom-button-script.js
|   |-- images/
|       |-- default/
|           |-- button-on.svg
|           |-- button-off.svg
|-- includes/
|   |-- class-wp-custom-switch.php
|   |-- admin/
|       |-- class-wp-custom-switch-admin.php
|-- wp-custom-switch.php
