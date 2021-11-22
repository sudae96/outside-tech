<?php
defined('ABSPATH') or die("No script kiddies please!");

if ( !class_exists('Front_Class') ) {

	class Front_Class extends Helper_Class {

        function __construct() {
            add_shortcode('event_list', [$this, 'event_list']);

            add_shortcode('event_filter', [$this, 'event_filter']);

            add_shortcode('event_filter_restapi', [$this, 'event_filter_restapi']);

            add_action('wp_ajax_otech_event_filter', array( $this, 'otech_event_filter_ajax' ));
            add_action('wp_ajax_nopriv_otech_event_filter', array( $this, 'otech_event_filter_ajax' ));

            add_action('rest_api_init', [$this, 'wp_rest_user_endpoints']);
        }

        function event_filter_restapi() {
            echo '<div id="rest-api-events-container"></div>';
        }

        function wp_rest_user_endpoints($request) {
            register_rest_route('wp/v3', 'events', array(
                'methods' => 'GET',
                'callback' => [$this,'user_register_endpoint_handler'],
                'permission_callback' => '__return_true'
            ));
        }

        function user_register_endpoint_handler($request = null) {
            return $this->event_list([
                'limit'       => -1,
                'order'       => 'ASC'
            ]);
        }

        public function event_list($atts) {
            global $post;

            $a = shortcode_atts( array(
                'limit'       => -1,
                'order'       => 'ASC'
            ), $atts );

            $args = [
                'post_type' => 'event',
                'post_status' => 'publish',
                'posts_per_page' => $a['limit'],
                'order' => $a['order']
            ];

            if(!empty($atts['type'])) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'event_types',
                        'field' => 'slug',
                        'terms' => $atts['type']
                    ]
                ];
            }

            $query = new WP_Query($args);
            ob_start();
            echo '<ul>';

            if($query->have_posts()):
                while($query->have_posts()) : $query->the_post();
                    $event_data = get_post_meta($post->ID, 'event_data', true);
                    $exp_date = $event_data['event_date'];

                    // Set the current Timezone
                    date_default_timezone_set('Asia/Kathmandu');
                    $today = new DateTime();
                    if($exp_date < $today->format('Y-m-d h:i:s')) {
                        $current_post = get_post(get_the_ID(), 'ARRAY_A');
                        $current_post['post_status'] = 'trash';
                        wp_update_post($current_post);
                    }
                    ?>
                    <li>
                        <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                        <div>Date: <?php echo date_format(date_create($event_data['event_date']), 'jS F'); ?></div>
                        <div>Venue: <?php echo $event_data['venue']; ?></div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                _e('Sorry, nothing to display', 'outside-tech');
            endif;

            echo '</ul>'; 
            return ob_get_clean();     
        }

        public function otech_event_filter_ajax() {
            $data = $_POST['filter_data'];
            parse_str($data, $data_arr);

            $args = [
                'post_type' => 'event',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'order' => 'ASC'
            ];

            if(!empty($data_arr['event_type'])) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'event_types',
                        'field' => 'slug',
                        'terms' => (array)$data_arr['event_type']
                    ]
                ];
            }

            if(!empty($data_arr['event_tag'])) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'event_tags',
                        'field' => 'slug',
                        'terms' => (array)$data_arr['event_tag']
                    ]
                ];
            }

            if(!empty($data_arr['keyword'])) {
                $args['s'] = $data_arr['keyword'];
            }

            $query = new WP_Query($args);

            ob_start();
            echo '<div id="filter-container">';            
            include(get_template_directory() . '/inc/filters.php'); 
            echo '<ul id="events-list-id">';

            if($query->have_posts()):
                while($query->have_posts()) : $query->the_post();
                    $event_data = get_post_meta($post->ID, 'event_data', true);
                    $exp_date = $event_data['event_date'];
                    ?>
                    <li>
                        <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                        <div>Date: <?php echo date_format(date_create($event_data['event_date']), 'jS F'); ?></div>
                        <div>Venue: <?php echo $event_data['venue']; ?></div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                _e('Sorry, nothing to display', 'outside-tech');
            endif;

            echo '</ul></div>'; 

            $html = ob_get_clean();
            echo $html;
            die();
        } 


        public function event_filter($atts) {
            
            global $post;

            $args = [
                'post_type' => 'event',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'order' => 'ASC'
            ];

            $query = new WP_Query($args);

            ob_start();
            echo '<div id="filter-container">';   
            include(get_template_directory() . '/inc/filters.php'); 
            echo '<ul id="events-list-id">';

            if($query->have_posts()):
                while($query->have_posts()) : $query->the_post();
                    $event_data = get_post_meta($post->ID, 'event_data', true);
                    $exp_date = $event_data['event_date'];
                    ?>
                    <li>
                        <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                        <div>Date: <?php echo date_format(date_create($event_data['event_date']), 'jS F'); ?></div>
                        <div>Venue: <?php echo $event_data['venue']; ?></div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                _e('Sorry, nothing to display', 'outside-tech');
            endif;

            echo '</ul></div>'; 

            $html = ob_get_clean();
            return $html;     
        }
    }
    new Front_Class();
}