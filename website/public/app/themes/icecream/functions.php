<?php



if (!class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });

    add_filter('template_include', function ($template) {
        return get_stylesheet_directory() . '/static/no-timber.html';
    });

    return;
}

Timber::$dirname = ['templates', 'views'];

class StarterSite extends TimberSite
{
    public function __construct()
    {
        add_theme_support('post-formats');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);
        add_filter('timber_context', [$this, 'add_to_context']);
        add_filter('get_twig', [$this, 'add_to_twig']);
        add_filter('clean_url', [$this, 'async_scripts']);
        add_action('init', [$this, 'init']);
        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_action('wp_enqueue_scripts', [$this, 'add_theme_scripts']);

        parent::__construct();
    }

    public function init()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title' => 'Options',
                'menu_title' => 'Options',
                'menu_slug' => 'options',
                'capability' => 'edit_posts',
                'redirect' => false
            ]);
        }

        $this->register_post_types();
        $this->register_taxonomies();

        include_once "includes/shortcodes.php";
        include_once "includes/twig_extension.php";
        include_once "includes/filters.php";

    }

    // Async load
    public function async_scripts($url)
    {
        if (strpos($url, '#asyncload') === false) {
            return $url;
        } elseif (is_admin()) {
            return str_replace('#asyncload', '', $url);
        } else {
            return str_replace('#asyncload', '', $url) . "' defer async='async";
        }
    }

    public function register_post_types()
    {
		
			$labels = array(
				'name'               => __( 'Icecreams', 'post type general name', 'icecream' ),
				'singular_name'      => __( 'Icecream', 'post type singular name', 'icecream' ),
				'menu_name'          => __( 'Icecreams', 'admin menu', 'icecream' ),
				'name_admin_bar'     => __( 'Icecreams', 'add new on admin bar', 'icecream' ),
				'add_new'            => __( 'Add New', 'Icecream', 'icecream' ),
				'add_new_item'       => __( 'Add New Icecream', 'icecream' ),
				'new_item'           => __( 'New Icecream', 'icecream' ),
				'edit_item'          => __( 'Edit Icecream', 'icecream' ),
				'view_item'          => __( 'View Icecream', 'icecream' ),
				'all_items'          => __( 'All Icecream', 'icecream' ),
				'search_items'       => __( 'Search Icecreams', 'icecream' ),
				'parent_item_colon'  => __( 'Parent Icecream:', 'icecream' ),
				'not_found'          => __( 'No Icecream found.', 'icecream' ),
				'not_found_in_trash' => __( 'No Icecream found in Trash.', 'icecream' ),
			);

		$args = array(
			'labels'        => $labels,
			'description'   => __('Icecreams', 'icecream'),
			'public'        => true,
			'menu_position' => 25,
			'supports'      => array('title', 'editor', 'thumbnail', 'revisions', 'excerpt'),
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-buddicons-topics',
			'rewrite'       => array(
				'slug'       => 'icecream', 'with_front' => false
			)
		);

		register_post_type('icecream', $args);
    }

    public function register_taxonomies()
    {
        //this is where you can register custom taxonomies
    }

    public function add_to_context($context)
    {
        $context['theme'] = 'icecream';
        $context['foo'] = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::get_context();';

        $context['menu'] = new TimberMenu('main');

		 $context['env'] = getenv('WP_ENV');
         //$context['menu_topbar'] = new TimberMenu('topbar');
         $context['site'] = $this;
         $context['options'] = get_fields('options');
         return $context;

     }

     public function add_to_twig($twig)
     {
         /* this is where you can add your own functions to twig */
        $twig->addExtension(new Twig_Extension_StringLoader());
        $twig->addExtension( new Pellicer_Twig_Extension() );
        return $twig;
    }

    public function add_theme_scripts()
    {

    	if (getenv('WP_ENV') === 'production') {
			$file = 'main.min.js';
		} else {
			$file = 'main.js';
		}


        //if ( !is_admin() ) wp_deregister_script('jquery');
        wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/' . $file . '#asyncload', 1.1, true);

    }



}

new StarterSite();
