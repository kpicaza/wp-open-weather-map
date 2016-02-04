<?php

/**
 * Adds Foo_Widget widget.
 */
class Open_Weather_Widget extends WP_Widget
{

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $plugin_name    The ID of this plugin.
         */
        private $plugin_name;

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
                parent::__construct(
                        'open_weather_widget', // Base ID
                        __('Weather in Erandio, Bizkaia', 'text_domain'), // Name
                        array('description' => __('Witget than showns actual weather from Erandio, Bizkaia', 'text_domain'),) // Args
                );

                $this->plugin_name = 'wp-open-weather';
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance)
        {

                $options = get_option($this->plugin_name);

                $data = $this->makeCall($options['api_key']);

                echo $args['before_widget'];
                if (!empty($instance['title'])) {
                        echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
                }

                if (null !== $data) {
                        echo __($data->main . ': ' . $data->description, 'text_domain');
                        echo $args['after_widget'];
                }
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
                $title = !empty($instance['title']) ? $instance['title'] : __('Weather in Erandio, Bizkaia', 'text_domain');
                ?>
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
                </p>
                <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
                $instance = array();
                $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';

                return $instance;
        }

        protected function makeCall($apikey)
        {

                $locale = $this->getLocale(get_locale());

                $response = wp_remote_get(sprintf('http://api.openweathermap.org/data/2.5/weather?q=city=Erandio.Sp&APPID=%1$s&lang=%2$s', $apikey, $locale));
                $data = json_decode(wp_remote_retrieve_body($response));

                if (!isset($data->weather) || null === $data->weather[0]) {
                        return;
                }

                return $data->weather[0];
        }

        protected function getLocale($locale)
        {

                // Open map wheather available lang keys.
                $langs = array('en', 'ru', 'it', 'es', 'uk', 'de', 'pt', 'ro', 'pl', 'fi', 'nl', 'fr', 'bg', 'sv', 'tr', 'hr', 'ca');
                $current = substr($locale, 0, 2);
                if (!in_array($current, $langs)) {
                        return 'en';
                }

                return $langs[array_search($current, $langs)];
        }

}

// class Foo_Widget