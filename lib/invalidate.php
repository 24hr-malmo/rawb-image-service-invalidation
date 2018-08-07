<?php

if ( ! class_exists( 'RAWBImageServiceInvalidation' ) ) {

    $dir = dirname( __FILE__ );
    require $dir . '/../credis/Client.php';

    require_once 'invalidate-settings.php';

    class RAWBImageServiceInvalidation {

        static $version;
        static $singleton;

        protected $CHANNEL = 'images_service_invalidate_image';

        protected $dir;
        protected $plugin_dir;
        protected $content_draft_url;
        protected $init = false;
        protected $short_init = false;
        protected $redis;
        protected $settings_page;

        static function getInstance() {
            if (is_null(RAWBImageServiceInvalidation::$singleton)) {
                throw new Exception('RAWBImageServiceInvalidation not instanciated');
            }
            return RAWBImageServiceInvalidation::$singleton;
        }

        function __construct($dir, $version) {
            RAWBImageServiceInvalidation::$version = $version;
            $this->dir = $dir;
            $this->plugin_dir = basename( $this->dir );
            $this->init();
            RAWBImageServiceInvalidation::$singleton = $this;
        }

        public function init() {

            // Disable double initialization.
            if ( $this->init ) {
                return $this;
            }

            $this->init = true;

            if ( is_plugin_active( 'tcs3/tcS3.php' ) ) {
                add_action('tcS3/uploaded_to_S3', array($this, 'invalidate_file_s3'), 20, 1);
                add_action('tcS3/deleted_from_S3', array($this, 'invalidate_file_s3'), 20, 1);
            } else {
                add_action( 'wp_handle_upload', array(&$this, 'invalidate_file' ), 10, 2);
            }

            $this->settings_page = new RAWBImageServiceInvalidationSettings();

            $this->redis = new Credis_Client($this->settings_page->get_redis_host());;

        }

        public function invalidate_file_s3 ($file_info) {
            $this->redis->publish($this->CHANNEL, '{"image": "/' . $file_info['file'] . '"}');
        }

        public function invalidate_file ($upload, $context) {

            error_log(print_r($upload, true));    
            error_log(print_r($context, true));    

        }

    }

}

