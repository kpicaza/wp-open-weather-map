<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/kpicaza
 * @since      1.0.0
 *
 * @package    Wp_Open_Weather
 * @subpackage Wp_Open_Weather/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="openweather_options" action="options.php">
        <?php
        //Grab all options
        $options = get_option($this->plugin_name);

        // Cleanup
        $api_key = isset($options['api_key']) ? $options['api_key'] : '';
        $city = isset($options['city']) ? $options['city'] : '';
        ?>

        <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>        

        <fieldset>
            <label for="<?php echo $this->plugin_name; ?>-api_key">
                <span><?php esc_attr_e('Display weather form', $this->plugin_name); ?></span>
            </label>
            <input type="text" class="<?php echo $this->plugin_name; ?>-city" id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[city]" placeholder="Erandio.Es" value="<?php echo $city; ?>"  />
            </label>
        </fieldset>

        <fieldset>
            <label for="<?php echo $this->plugin_name; ?>-api_key">
                <span><?php esc_attr_e('Open Weather Map API Keys', $this->plugin_name); ?></span>
            </label>
            <input type="text" class="<?php echo $this->plugin_name; ?>-api_key" id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[api_key]" placeholder="API secret" value="<?php echo $api_key; ?>"  />
            </label>
        </fieldset>

        <?php submit_button(__('Save all changes'), 'primary', 'submit', TRUE); ?>

    </form>
</div>