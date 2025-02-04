<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;
$context['form'] = get_field('form', $timber_post->post_id);
$context['heading'] = get_field('heading', $timber_post->post_id);
$context['text'] = get_field('text', $timber_post->post_id);
$context['cta'] = get_field('cta', $timber_post->post_id);
$context['hubspot_form'] = get_field('hubspot_form', $timber_post->post_id);
$context['hubspot_portal'] = get_field('hubspot_portal', $timber_post->post_id);

$context['icecreams'] = new Timber\PostQuery(array(
	'post_type' => 'icecream',
	'posts_per_page'  => 3,
	'order' => 'ASC'
));


Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
