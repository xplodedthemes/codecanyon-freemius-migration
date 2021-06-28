# CodeCanyon to Freemius License Converter

This plugin enables your CodeCanyon customers to migrate their licenses to Freemius. It creates a shortcode you can use on your website for rendering a form that accepts an email address and a CodeCanyon license key. Upon submission, if everything checks out, the plugin will create a new license on Freemius for the user.

## Configuration

Add the following defines to your `wp-config.php` file:

```
/**
 * Your Freemius developer ID and keys.
 *
 * Where to find it?
 *   1. Sign into the Developer Dashboard.
 *   2. Click on the top-right menu and open "My Profile".
 *   3. You'll find it there under the "Keys" section.
 */
define("FS__DEV_ID", "1234");
define("FS__DEV_PK_APIKEY", "pk_0749284987a9b76b2fcbc357f30ab");
define("FS__DEV_SK_APIKEY", "k_S;LRILhkfM_W5.G_(=P)UCoWu%Qoo");

/**
 * Your Freemius product ID and keys.
 *
 * Where to find it?
 *   4. Switch the context to the product you want to transition its licenses.
 *   5. Go to the "SETTINGS" section (the last menu item on the bottom left).
 *   6. You'll find it there under the "Keys" section.
 */
define("FS__PLUGIN_ID", "1234");
define("FS__PLUGIN_PK_APIKEY", "pk_abcde...");
define("FS__PLUGIN_SK_APIKEY", "sk_abcde...");


/**
 * Your Freemius plan ID.
 *
 * Where to find it?
 *   7. Go to the "PLANS" section.
 *   8. You'll find the plan IDs on the 1st column of the plans table.
 */
define("FS__PLUGIN_PLAN_ID", "5678");

/**
 * Your Freemius plan's pricing ID.
 *
 * Where to find it?
 *   7. Click on the title of the plan you are migrating its licenses.
 *   8. Locate the single-site prices row, the pricing ID is on its right side.
 */
define("FS__PLUGIN_PRICING_ID", "90123");

// The expiration date you'd like to set for the migrated licenses.
define("FS__PLUGIN_EXPIRES_GRACE_PERIOD", '1 year');

// Your CodeCanyon API key and the slug of the CodeCanyon product you are migrating its licenses.
define("CODECANYON_API_KEY", "UNr.....");
define("CODECANYON_SLUG_PLUGIN", "my-plugin");
```

## Adding the license migration form

Create a new page with and with the following shortcode: `[ctf_form]`

## Contribution

This plugin was originally contributed by the awesome team of [NextPress](https://nextpress.co) & [WP Ultimo](https://wpultimo.com/), Arindo Duque and Marcelo Assis, who've built it for migrating WP Ultimo from Gumroad to Freemius.