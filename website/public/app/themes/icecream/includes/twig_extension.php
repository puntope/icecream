<?php
/**
 * Twig extensions
 * https://twig.sensiolabs.org/doc/2.x/advanced.html
 */

use Timber\ImageHelper as TimberImageHelper;
use Timber\Helper as TimberHelper;


class Pellicer_Twig_Extension extends Twig_Extension
{

    const DEFAULT_JPG_QUALITY = 94;
    const DEFAULT_ADJUST = 10;

    public function getName() { return 'Pellicer'; }

    public function getFilters() {
        return array(
            // 'boilerplateExample' => new Twig_Filter_Method($this, 'boilerplateExample'),

            new Twig_SimpleFilter('trimWords', array($this, 'trimWords')),
            new Twig_SimpleFilter('ti', array($this, 'convertToTimberImage')),
            new Twig_SimpleFilter('ati', array($this, 'convertAttachmentToTimberImage')),
            new Twig_SimpleFilter('tp', array($this, 'convertToTimberPost')),
            new Twig_SimpleFilter('tt', array($this, 'convertToTimberTerm')),
            new Twig_SimpleFilter('downsize', array($this, 'downsize')),
            new Twig_SimpleFilter('fit', array($this, 'fit')),
            new Twig_SimpleFilter('srcset', array($this, 'srcset')),
            new Twig_SimpleFilter('autosrcset', array($this, 'autoSrcset')),
            new Twig_SimpleFilter('responsive', array($this, 'responsive')),
            new Twig_SimpleFilter('convertDate', array($this, 'convertDate')),
            new Twig_SimpleFilter('age', array($this, 'age')),
            new Twig_SimpleFilter('slugify', array($this, 'slugify')),
            new Twig_SimpleFilter('hash_hmac', array($this, 'hash_hmac')),
            new Twig_SimpleFilter('get_array_values', array($this, 'get_array_values')),
            new Twig_SimpleFilter('data_attributes', array($this, 'convertArrayToDataAttributes')),
            new Twig_SimpleFilter('convert_to_js_var', array($this, 'outputJsVars')),
            new Twig_SimpleFilter('process_video_oembed', array($this, 'processVideoOEmbed')),
            new Twig_SimpleFilter('get_merged_product_variations', array($this, 'get_merged_product_variations')),
            new Twig_SimpleFilter('hashtag', array($this, 'get_juice_by_hashtag')),

            // Simple Wordpress filters
            // to avoid a lot of calls like `function('esc_attr', 'foobar')`
            new Twig_SimpleFilter('esc_attr', array($this, 'esc_attr')),
            new Twig_SimpleFilter('sanitize_title', array($this, 'sanitize_title')),
            new Twig_SimpleFilter('unescape', array($this, 'unescape')),
            new Twig_SimpleFilter('push', array($this, 'push')),
            new Twig_SimpleFilter('obj_to_array', array($this, 'obj_to_array')),
            new Twig_SimpleFilter('array_column', array($this, 'array_column')),
        );
    }

    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('apply_filters', 'apply_filters'),
            new Twig_SimpleFunction('bia_get_page', 'bia_get_page'),
            new Twig_SimpleFunction('get_permalink', 'get_permalink'),
            new Twig_SimpleFunction('get_term_link', 'get_term_link'),
            new Twig_SimpleFunction('get_woocommerce_currency_symbol', 'get_woocommerce_currency_symbol'),
            new Twig_SimpleFunction('wc_price', 'wc_price'),
            new Twig_SimpleFunction('bia_get_stores', 'bia_get_stores'),
            new Twig_SimpleFunction('wc_get_product', 'wc_get_product'),
            new Twig_SimpleFunction('get_post_meta', 'get_post_meta'),
            new Twig_SimpleFunction('is_string', 'is_string'),
            new Twig_SimpleFunction('wc_get_cart_remove_url', 'wc_get_cart_remove_url'),
            new Twig_SimpleFunction('get_product', array($this, 'get_product')),
            new Twig_SimpleFunction('get_svg', array($this, 'get_svg')),
            new Twig_SimpleFunction('svg', array($this, 'svg')),
            new Twig_SimpleFunction('get_menu_item_post_type', array($this, 'get_menu_item_post_type')),
            new Twig_SimpleFunction('get_field', array($this, 'get_field')),
            new Twig_SimpleFunction('get_excerpt_by_id', array($this, 'get_excerpt_by_id')),
            new Twig_SimpleFunction('get_permalink_by_slug', array($this, 'get_permalink_by_slug')),
            new Twig_SimpleFunction('get_url', array($this, 'get_url')),
            new Twig_SimpleFunction('get_post_url', array($this, 'get_post_url')),
            new Twig_SimpleFunction('get_absolute_url', array($this, 'get_absolute_url')),
            new Twig_SimpleFunction('body_class', array($this, 'body_class')),
            new Twig_SimpleFunction('get_page_parent_slug', array($this, 'get_page_parent_slug')),
            new Twig_SimpleFunction('is_path', array($this, 'is_path')),
            new Twig_SimpleFunction('get_price_range', array($this, 'get_price_range')),
            new Twig_SimpleFunction('get_menu_items_data', array($this, 'get_menu_items_data')),
            // new Twig_SimpleFunction('getIntrinsic', array($this, 'getIntrinsic')),
            new Twig_SimpleFunction('getIntrinsic', 'getIntrinsic'),
            new Twig_SimpleFunction('get_heighest_image', 'get_heighest_image'),
            new Twig_SimpleFunction('colorset', array($this, 'colorset')),
            new Twig_SimpleFunction('get_page', array($this, 'get_page')),
            new Twig_SimpleFunction('get_pagelink', array($this, 'get_pagelink')),
            new Twig_SimpleFunction('is_taxes_included', array($this, 'is_taxes_included')),
            new Twig_SimpleFunction('get_reorder_data', array($this, 'get_reorder_data')),
            new Twig_SimpleFunction('get_familybox', array($this, 'get_familybox')),
            new Twig_SimpleFunction('bia_merge', array($this, 'bia_merge')),
            new Twig_SimpleFunction('product_volume', array($this, 'product_volume')),
            new Twig_SimpleFunction('bia_get_cart_price', array($this, 'bia_get_cart_price')),
            new Twig_SimpleFunction('is_discount_plugin_coupon', array($this, 'is_discount_plugin_coupon')),
            new Twig_SimpleFunction('debug', array($this, 'debug')),

        );
    }

    /**
     * Filters
     * ------------------------------------- */

    public function array_column($array, $key) {
        return array_column($array, $key);
        // return array_map(function($item) {
        //     return $item[$key];
        // }, $array);
    }
    public function trimWords($string, $len = 50, $ellipse = ' &hellip; ') {
        if(!strlen($string)) return '';
        $text = TimberHelper::trim_words($string, $len, false);
        $last = $text[strlen($text) - 1];
        if ( $last != '.') {
            $text .= $ellipse;
        }
        return $text;
    }

    public function convertToTimberImage($image) {
        // return if the image is already a timber image
        if(is_object($image) && get_class($image) == 'Timber\Image') {
            return $image;
        }

        if(is_array($image)) {
            return new TimberImage($image['ID']);
        } elseif(is_string($image)) {
            return new TimberImage($image);
        } else {
            return false;
        }
    }

    public function convertAttachmentToTimberImage($wpPost) {
        if(is_object($wpPost)) {
            $src = wp_get_attachment_url($wpPost->ID);
        } else {
            return false;
        }

        if(!$src) return false;

        return self::convertToTimberImage($src);
    }

    /**
     * convert to BIA TimberPost
     */
    public function convertToTimberPost($wpPost) {
        if(is_array($wpPost)) {
            foreach($wpPost as $key => $post) {
                $wpPost[$key] = new Timber\Post($post);
            }
        } elseif(is_object($wpPost)) {
            $wpPost = new Timber\Post($wpPost->ID);
        } elseif(is_int($wpPost)) {
            $wpPost = new Timber\Post($wpPost);
        } else {
            $wpPost = false;
        }
        return $wpPost;
    }

    public function convertToTimberTerm($wpTerm) {
        return new TimberTerm($wpTerm);
    }

    public function push($array, $item) {
        return $array[] = $item;
    }

    public function obj_to_array($obj) {
        return (array)$obj;
    }

    /**
     * Resized image with asked ratio, limited to the max dimensions or the original file.
     * don't expect to get exact asked dimensions, only ratio is consistent
     *
     * @param $image
     * @param $width
     * @param int $height
     * @param string $crop (default: 'default')
     * @param bool $toJpg (default: false)
     * @param int $jpgQuality (default: DEFAULT_JPG_QUALITY)
     * @param bool $returnArray (default: false)
     * @return array|bool|string
     */
    public function downsize($image, $width, $height = 0, $crop = 'default', $toJpg = false, $jpgQuality = self::DEFAULT_JPG_QUALITY, $returnArray = false){
        if(!is_object($image) || get_class($image) != 'Timber\Image') { return false; }

        // image not in wordpress media, no height, can't resize
        if( $image->height == 0) { return $image; }

        $askedWidth = $width;
        $askedHeight = $height;

        $src = $image->src;

        add_filter( 'jpeg_quality', create_function( '', 'return '.$jpgQuality.';' ) );

        if($toJpg) {
            $filetype = wp_check_filetype($src);
            if($filetype['type'] != 'image/jpeg') {
                $src = TimberImageHelper::img_to_jpg($src);
            }
        }

        // use original ratio if only one dimension given
        if($width == 0 || $height == 0) {
            $askedRatio = $image->aspect();
            // find out missing value based on original ratio
            if($width == 0) {
                $askedWidth = round($height*$askedRatio);
            } else {
                $askedHeight = round($width/$askedRatio);
            }
        } else {
            $askedRatio = $width/$height;
        }

        // constraint resize dimension to original dimensions with asked ratio
        if($image->width < $width) {
            $width = $image->width;
            $height = round($width/$askedRatio);
        } else if($image->height < $height) {
            $height = $image->height;
            $width = round($height*$askedRatio);
        }

        // if size different than original, do the resize
        if($image->width != $width || $image->height != $height) {
            $src = TimberImageHelper::resize($src, $width, $height, $crop);
        }

        remove_filter('jpeg_quality', create_function('', 'return '.$jpgQuality.';'));

        if($returnArray) {
            return array(
                'width' => $askedWidth,
                'height' => $askedHeight,
                'realWidth' => $width,
                'realHeight' => $height,
                'src' => $src
            );
        }
        return $src;
    }

    public function fit($image, $width, $height, $toJpg = false) {
        if(get_class($image) != 'Timber\Image') { return false; }

        $src = $image->src();

        if($toJpg) {
            $filetype = wp_check_filetype($src);
            if($filetype['type'] != 'image/jpeg') {
                $src = TimberImageHelper::img_to_jpg($src);
            }
        }

        $isHorizontal = ($image->width > $image->height);

        if($isHorizontal) {
            if($image->width > $width)
                $src = TimberImageHelper::resize($src, $width);

        } else {
            if($image->height > $height)
                $src = TimberImageHelper::resize($src, 0, $height);
        }

        return $src;
    }

    public function srcset($id, $sizes){

        $output = '';
        $sources = array();
        $tImage = new TimberImage($id);

        foreach ( $sizes as $size ) {
            $resizedImage = self::downsize($tImage, $size[0], $size[1]);

            $sources[] = array($resizedImage, $size[0].'w');
        }

        // stringify
        foreach($sources as $key => $source) {
            $sources[$key] = implode(' ', $source);
        }
        $sources = implode(', ', $sources);

        return $sources;
    }

    public function autoSrcset($wpPost) {

        $img_id = get_post_thumbnail_id($wpPost);

        if (is_numeric($wpPost)) {
            $img_id = $wpPost;
        }

        $ais = wp_get_attachment_image_srcset($img_id);

        return $ais;
    }

    public function responsive($id, $sizes, $encode = true){

        $sources = array();
        $tImage = new TimberImage($id);

        // image not found, stop here
        if($tImage->height == 0) {
            return '[]';
        }

        foreach ( $sizes as $size ) {

            $width = $size;
            $height = 0; // no resize

            if(is_array($size)) {
                $width = $size[0];
                $height = $size[1];
            }

            // get downsized image's array
            $resizedImage = self::downsize($tImage, $width, $height, 'default', false, self::DEFAULT_JPG_QUALITY, true);

            // only return some keys
            $sources[] = array_intersect_key($resizedImage, array_flip(array('width', 'height', 'src')));
        }

        if ($encode) {
            $sources = json_encode($sources);
        }
        return $sources;
    }

    public function convertDate($date, $to, $from = 'Ymd') {
        if(!$date) return '--';

        $date = DateTime::createFromFormat($from, $date);
        return $date->format($to);
    }

    /**
     * Return age based on date of birth
     * @param $date
     * @param string $from
     * @return int|string
     */
    public function age($date, $from = 'Ymd') {
        if(!$date) return '--';

        $date = DateTime::createFromFormat($from, $date);
        $diff = $date->diff(new DateTime('now'));
        return $diff->y;
    }

    public function slugify($slug) {
        // Remove HTML tags
        $slug = preg_replace('/<(.*?)>/u', '', $slug);

        // Remove inner-word punctuation.
        $slug = preg_replace('/[\'"‘’“”]/u', '', $slug);

        // Make it lowercase
        $slug = mb_strtolower($slug, 'UTF-8');

        // Get the "words".  Split on anything that is not a unicode letter or number.
        // Periods are OK too.
        preg_match_all('/[\p{L}\p{N}\.]+/u', $slug, $words);
        $words = $words[0];
        $slug = implode('-', $words);

        return $slug;
    }

    public function hash_hmac($input, $algo, $key) {
        return hash_hmac(
            $algo,
            $input,
            $key
        );
    }

    public function get_array_values($array) {
        if( !empty($array) ) {
            return array_values($array);
        }
    }

    public function convertArrayToDataAttributes($array) {
        $return = '';
        if(is_array($array)) {
            foreach ($array as $key => $value) {
                $return .= ' data-' . $key . '="' . $value . '"';
            }
        }

        return $return;
    }

    public function processVideoOEmbed($video) {
        $videoSource = array();
        if(preg_match('/src="(.+?)"/', $video, $videoSource) === 1) {
            return $videoSource[1];
        }
        else {
            return $video;
        }
    }

    public function outputJsVars($varName, $varContents) {

        foreach ( (array) $varContents as $key => $value ) {
            if ( !is_scalar($value) )
                continue;

            $varContents[$key] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8');
        }

        $jsVar = "var $varName = " . wp_json_encode( $varContents ) . ';' . PHP_EOL;

        return $jsVar;
    }

    /**
     * Wordpress wrapper functions
     * ------------------------------------- */
    public function esc_attr($text) {
        return esc_attr($text);
    }

    public function sanitize_title($text) {
        return sanitize_title($text);
    }

    public function unescape($text) {
        return ucfirst(str_replace('_', ' ', $text));
    }

    /**
     * Functions
     * ------------------------------------- */

     /**
     * Gets svg based on file name
     *
     * @param [type] $id
     * @return void
     */
    public function get_svg($name) {
        if(file_exists( get_template_directory().'/assets/svg/'.$name.'.svg') ) {
            include(get_template_directory().'/assets/svg/'.$name.'.svg');
        }
    }





    /**
     * Debug function
     *
     * @param $var
     * @return void
     */
    public function debug($var) {
        $breakpointme = $var;
    }

    /**
     * Display svg
     * @param $id id based on the name of the SVG files in assets/svg/
     */
    public function svg($id) {
        ?><svg viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
            <use x="0" y="0" href="#<?php echo $id ?>" />
        </svg><?php
    }

    public function get_menu_item_post_type($nav_menu_item) {

        $post = get_post($nav_menu_item->object_id);
        if($post) {
            return $post->post_type;
        } else {
            return null;
        }
    }

    public function get_field($field, $id) {
        return get_field($field, $id);
    }

    public function get_excerpt_by_id($post_id) {
        global $post;
        $save_post = $post;
        $post = get_post($post_id);
        $output = get_the_excerpt();
        $post = $save_post;
        return $output;
    }

    public function get_permalink_by_slug($slug, $language = '') {
        $post = get_page_by_path($slug);
        $permalink = $slug;

        if(isset($post->ID)) {
            $permalink = get_permalink($post->ID);
        }

        return $permalink;
    }

    public function get_url() {
      $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
      $url .= $_SERVER["REQUEST_URI"];
      return $url;
    }

    public function get_post_url($id) {
      $url = get_permalink($id);
      return $url;
    }

    public function get_absolute_url() {
      $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
      $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
      $url .= $_SERVER["REQUEST_URI"];
      return $url;
    }


    public function body_class() {
        $arrClasses = get_body_class();

        return implode(" ",$arrClasses);
    }

    public function get_page_parent_slug($postID) {
        $parents = get_post_ancestors( $postID );
        /* Get the top Level page->ID count base 1, array base 0 so -1 */
        if (empty($parents)) {
            return '';
        }

        $id = $parents[count($parents)-1];
        /* Get the parent and set the $class with the page slug (post_name) */
        $parent = get_post( $id );
        $class = $parent->post_name;

        // $slug = $parent->slug;
        return $class;
    }

    public function is_path($url) {
        $arrPaths = array_values( array_filter( explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ) ) );
        if ( !empty($arrPaths) && $arrPaths[count($arrPaths)-1] == $url ) {
            return true;
        }
        return false;
    }


    /**
     * Display the product price range from prices array
     *
     * @param array $prices
     * @return string
     */
    public function get_price_range($prices) {

        if ( ! current( $prices['price'] ) ) {

            return '<span>'.__("Free", "bia").'</span>';

        } else if( current( $prices['price'] ) === end( $prices['price'] ) ) {

            return '<span>'.wc_price( current( $prices['price'] ) ).'<span>';

        } else {

            reset( $prices['price'] );
            $minPrice = wc_price( current( $prices['price'] ) );
            $maxPrice = wc_price( end( $prices['price'] ) );

            return '<span>'.$minPrice .'&ndash;'. $maxPrice.'<span>';
        }

    }

    /**
     * Menu items data to be used by Javascript
     *
     * @return json object
     */
    public function get_menu_items_data() {
        $menu_items = wp_get_nav_menu_items('Main menu');
        $output = [];

        foreach ($menu_items as $key => $menu_item) {
            $slug = '';
            $url = '';
            $color = colorset($menu_item->object_id)['primary'];
            $has_children = false;

            foreach ($menu_items as $i => $value) {
                if ((int)$value->menu_item_parent == $menu_item->ID) {
                    $has_children = true;
                    break;
                }
            }

            if ($menu_item->object == 'page') {
                $post = get_post($menu_item->object_id);
                $url = get_permalink($post->ID);
                $slug = $post->post_name;
            } else if ($menu_item->object == 'product_cat') {
                $term = get_term($menu_item->object_id, $menu_item->object);
                $slug = $term->slug;
                $url = get_term_link($term);
            }
            // error_log(json_encode($menu_item));

            $output[] = (object)[
                'ID' => (int)$menu_item->ID,
                'item_id' => $post ? (int) $post->ID : (int) $term->ID,
                'name' => $menu_item->title,
                'slug' => $slug,
                'backgroundColor' => $color ? $color : get_field('default_bottom_layer_color', 'option'),
                'url' => $url,
                'parent' => (int)$menu_item->menu_item_parent,
                'hasChildren' => $has_children,
            ];
        }

        return json_encode($output);
    }

    // get page height in padding
    // public function getIntrinsic($image, $options = array()) {
    //     if (!isset($image) || is_null($image)) return;
    //     $paddingTop = 108; // default

    //     if((is_numeric($image) || !is_string($image)) && !isset($options['height'])) {
    //         if (is_numeric($image)) {
    //             $id = $image;
    //         } else if (is_array($image)) {
    //             $id = intval($image['ID']);
    //         } else if (is_object($image->ID)) {
    //             $id = intval($image->ID);
    //         }
    //         if (isset($id)) {
    //             $image = new TimberImage($id);
    //             $sizes = unserialize($image->_wp_attachment_metadata);

    //             if (isset($options['customSize']) && $options['customSize'] == true) {
    //                 if($sizes['width'] > $sizes['height']){
    //                     //landscape
    //                     $paddingTop = 75;
    //                 } else if ($sizes['width'] < $sizes['height']){
    //                     // portrait
    //                     $paddingTop = 133;
    //                 } else {
    //                     // square
    //                     $paddingTop = 100;
    //                 }
    //             } else {
    //                 return ($sizes['height'] / $sizes['width']) * 100;
    //             }
    //         }

    //     } else {
    //         // $imageData = getimagesize($image);
    //         // $paddingTop = ($imageData[1] / $imageData[0]) * 100;

    //         if (isset($options['height'])) {
    //             $paddingTop = $options['height'];
    //         } else {
    //             $paddingTop = 108; // REVIEW: kinda weird value ?
    //         }
    //     }

    //     return $paddingTop;
    // }

    public function getMainColor($slug) {
        get_field('bottom_layer_color', fn('get_page_by_title', 'Shop'));
        return '';
    }

    public function colorset($maybePostID, $term = false) {
        return colorset($maybePostID, $term);
    }

    /**
     * @param $str
     */
    public function get_juice_by_hashtag($str) {
        preg_match_all('/#([^\s]+)/', $str, $matches);
        foreach ($matches[1] as $hashtag) {

            $the_slug = strip_tags($hashtag);
            $args = array(
                'name'        => $the_slug,
                'post_type'   => 'product',
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $juices = get_posts($args);
            if( $juices ) :
                return $juices[0];
            endif;

        }
        return false;
    }

    public function get_merged_product_variations($product) {
        if (!$product) return [];

        if ($product->get_type() == 'product_variation') {
            $parentID = $product->get_parent_id();
            $product = wc_get_product($parentID);
        }

        if (!method_exists($product, 'get_available_variations')) return [];


        $product_variations = $product->get_available_variations();

        $related_products = get_field('related_products', $product->get_id());

        if($related_products) {
            foreach ($related_products as $key => $related) {

                $vars = wc_get_product($related->ID)->get_available_variations();
                $product_variations = array_merge($product_variations, $vars);
            }
        }

        return $product_variations;
    }

    public function get_page($slug) {
        return get_field('page_' . $slug, 'options');
    }

    public function get_pagelink($slug) {
        $page = $this->get_page($slug);
        return $page ? get_permalink($page) : false;
    }

    public function get_product($maybePostID) {
        return BIA_WC()->get_product($maybePostID);
    }

    /**
     * @param bool $order
     *
     * @return bool
     */

    public function is_taxes_included() {

        return ! apply_filters( 'woocommerce_adjust_non_base_location_prices', true );
    }

    public function get_reorder_data($order) {

        /**
         * @var $order WC_Order
         */
        $orgCartItems = $order->get_items();
        $cartItems = [];

        foreach ($orgCartItems as $key => $item) {
            /**
             * @var $item WC_Order_Item_Product
             */

            $isJuice = $item->get_meta('_mnm_container');
            $entry = [];
            $entry['product'] = $item->get_product();
            $entry['post'] = Timber::get_post($item->get_product_id());
            $entry['data'] = $item;
            $entry['price'] = $item->get_subtotal();
            $entry['single_price'] = $item->get_product()->get_price();
            $entry['bia_quantity'] = 6 * $item->get_quantity();
            $entry['quantity'] = $item->get_quantity();
            $entry['isMnM'] = false;
            $entry['isGiftCard'] = $item->get_product()->get_slug() == 'gift_card' ? true : false;

            if ($isJuice) {
                // Is Mix and Match child
                // Move it to its parent
                $cartItems[$isJuice]['items'][] = $entry;
                $cartItems[$isJuice]['price'] += $entry['price'];
            } else {
                $isMnM = $item->get_meta('_mnm_cart_key');
                if ($isMnM) {
                    // Is Mix and Match container
                    $_key = $isMnM;
                    $entry['isMnM'] = true;
                    $entry['items'] = [];
                } else {
                    $_key = $key;
                }
                $cartItems[$_key] = $entry;
            }
        }

        $output = [];
        foreach ($cartItems as $i => $value) {
            $obj = (object) [
                'id' => $value['data']->get_product_id(),
                'isMnM' => $value['isMnM'],
                'isGiftCard' => $value['isGiftCard'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
                'single_price' => $value['single_price'] ? $value['single_price'] : $value['price'] / $value['quantity'],
                'data' => $value['data'],
            ];
            if ($value['isMnM']) {
                foreach ($value['items'] as $y => $product) {
                    // Need to divide by container quantity
                    $obj->products[$product['data']->get_product_id()] = $product['data']->get_quantity() / $value['data']->get_quantity();
                }
            }

            if (!$value['isGiftCard']) {
                $output[] = $obj;
            }
        }

        return $output;
    }

    function get_familybox() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms' => array('family-box'),
                    'field' => 'slug',
                ),
            )
        );
        $family_boxes = Timber::get_posts($args);
        $family_box = get_right_box($family_boxes);

        if (!$family_box) return null;
        $family_box = BIA_WC()->get_product($family_box);
        $variations = $family_box->get_available_variations();

        $output = array(
            'id' => $family_box->get_id(),
        );
        $output['variations'] = array();
        foreach ($variations as $key => $value) {
            $output['variations'][] = (object)[
                'id' => $value['id'],
                'size' => $value['attributes']['attribute_pa_size'],
            ];
        }

        return $output;
    }

    public function bia_merge($org, $new) {
        return (object) array_merge((array) $org, (array) $new);
    }

    public function product_volume($box) {
        $label = '';
        $id = false;
        if (is_object($box) && isset($box->ID)) {
            $id = $box->ID;
        } elseif (method_exists($box, 'get_id')) {
            $id = $box->get_id();
        } elseif(is_array($box)) {
            $id = $box['product_id'];
        }
        $_label = get_field('amount_label', $id);
        if ($_label) {
            $label = $_label;
        }
        if (!$label){
            if (get_field('use_settings_from_another_product', $id)) {
                $another = get_field('another_product', $id);
                $_label = get_field('amount_label', $another);
                if ($_label) {
                    $label = $_label;
                }
            }
        }
        return $label;
        // $contains = get_field('contains', $box->get_id());
        // $amount = 0;
        // if (is_array($contains)) {
        //     foreach ($contains as $key => $value) {
        //         $amount += (int)$value['amount'];
        //     }
        // }
        // return (object)array(
        //     "amount" => $amount,
        //     "items" => $contains,
        // );

    }

    public function bia_get_cart_price($item, $key) {
        return apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $item['data'], $item['quantity'] ), $item, $key );
    }

    public function is_discount_plugin_coupon($coupon) {
        if (!class_exists('FlycartWooDiscountRulesCartRules')) return false;

        $dsc = new FlycartWooDiscountRulesCartRules();
        return $dsc->coupon_code == $coupon;
    }
}
