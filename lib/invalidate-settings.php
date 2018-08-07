<?php

    class RAWBImageServiceInvalidationSettings {

        private $options;

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        }

		public function get_redis_host() {
            $value = get_option( 'rawb_isi_redis_host' );
            if ($value === '' || !isset($value)) {
                return 'redis';
            }
			return $value;
		}

        public function add_plugin_page() {
            add_options_page('RAWB Image Service', 'RAWB Image Service settings', 'administrator', __FILE__, array( &$this, 'create_admin_page'));
            add_action( 'admin_init', array( $this, 'register_settings' ) );
        }

        public function create_admin_page() {

            $this->options = get_option( 'rawb_isi_redis_host' );
?>
        <div class="wrap">
            <h1>RAWB Image Service Invalidation Settings</h1>
            <form method="post" action="options.php">
<?php
            // This prints out all hidden setting fields
            settings_fields( 'rawb_isi_options_group' );
            do_settings_sections( 'rawb_isi_settings' );
            submit_button();
?>
            </form>
        </div>
<?php
        }

        public function register_settings() {        

			if (!get_option('rawb_isi_redis_host')) {
				add_option('rawb_isi_redis_host');
			}

            register_setting( 'rawb_isi_options_group', 'rawb_isi_redis_host', array( $this, 'sanitize' ) );

            add_settings_section(
                'rawb_isi_settings_section', 'Post types settings', array( $this, 'print_redis_host_info' ), 'rawb_isi_settings'
            );  

			add_settings_field(
				'rawb_isi_settings', 'Redis host:', array( $this, 'redis_host_callback'), 'rawb_isi_settings', 'rawb_isi_settings_section' 
			);      

		}

		public function sanitize( $input ) {
			$new_input = array();
			if( isset( $input['id_number'] ) )
				$new_input['id_number'] = absint( $input['id_number'] );

			if( isset( $input['title'] ) )
				$new_input['title'] = sanitize_text_field( $input['title'] );

			return $input;
		}

		public function print_redis_host_info() {
			print 'Enter redis host. Defaults to "redis":';
		}

		public function redis_host_callback() {

			$value = get_option( 'rawb_isi_redis_host' );
            echo "<div><input placeholder=\"redis\" style=\"width: 50%;\" name=\"rawb_isi_redis_host\" value=\"$value\"/></div>";

		}

	}


