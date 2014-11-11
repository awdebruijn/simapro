<?php
	if (!class_exists('Timber')){
		add_action( 'admin_notices', function(){
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . admin_url('plugins.php#timber') . '">' . admin_url('plugins.php') . '</a></p></div>';
		});
		return;
	}

	class StarterSite extends TimberSite {

		function __construct(){
			add_theme_support('post-formats');
			add_theme_support('post-thumbnails');
			add_theme_support('menus');
			add_filter('timber_context', array($this, 'add_to_context'));
			add_filter('get_twig', array($this, 'add_to_twig'));
			add_action('init', array($this, 'register_post_types'));
			add_action('init', array($this, 'register_menu_locations'));
			add_action('init', array($this, 'register_taxonomies'));
			add_action('init', array($this, 'register_custom_options_panel'));
			parent::__construct();
		}

		function register_post_types(){
			register_post_type('usps',
				array(
				'labels' => array(
					'name' => _x('USPs', 'post type general name', 'simapro'),
					'singular_name' => _x('USP', 'post type singular name', 'simapro'),
					'add_new' => _x('Add New', 'USP', 'simapro'),
					'add_new_item' => __('Add New USP', 'simapro'),
					'edit_item' => __('Edit USP', 'simapro'),
					'new_item' => __('New USP', 'simapro'),
					'view_item' => __('View USP', 'simapro'),
					'search_items' => __('Search USPs', 'simapro'),
					'not_found' => __('No USPs found', 'simapro'),
					'not_found_in_trash' => __('No USPs found in Trash', 'simapro'),
					'parent' => __('Parent USP', 'simapro'),
				),
				'public' => true,
				'menu_icon' => 'dashicons-megaphone',
				'menu_position' => 5,
				'hierarchical' => true,
				'has_archive' => false,
				'supports' => array('title','editor','thumbnail','excerpt', 'page-attributes'),
				'taxonomies' => array('post_tag'),
				'rewrite' => array('slug' => _x('usps', 'URL slug', 'simapro'), 'with_front' => false)
				)
			);
			register_post_type('testimonials',
				array(
				'labels' => array(
					'name' => _x('Testimonial', 'post type general name', 'simapro'),
					'singular_name' => _x('Testimonial', 'post type singular name', 'simapro'),
					'add_new' => _x('Add New', 'Testimonial', 'simapro'),
					'add_new_item' => __('Add New Testimonial', 'simapro'),
					'edit_item' => __('Edit Testimonial', 'simapro'),
					'new_item' => __('New Testimonial', 'simapro'),
					'view_item' => __('View Testimonial', 'simapro'),
					'search_items' => __('Search Testimonials', 'simapro'),
					'not_found' => __('No Testimonials found', 'simapro'),
					'not_found_in_trash' => __('No Testimonials found in Trash', 'simapro'),
					'parent' => __('Parent Testimonial', 'simapro'),
				),
				'public' => true,
				'menu_icon' => 'dashicons-format-quote',
				'menu_position' => 5,
				'hierarchical' => true,
				'has_archive' => false,
				'supports' => array('title','thumbnail', 'page-attributes'),
				'rewrite' => array('slug' => _x('testimonials', 'URL slug', 'simapro'), 'with_front' => false)
				)
			);
			register_post_type('Support',
				array(
				'labels' => array(
					'name' => _x('Support', 'post type general name', 'simapro'),
					'singular_name' => _x('Support', 'post type singular name', 'simapro'),
					'add_new' => _x('Add New', 'Support', 'simapro'),
					'add_new_item' => __('Add New Support', 'simapro'),
					'edit_item' => __('Edit Support', 'simapro'),
					'new_item' => __('New Support', 'simapro'),
					'view_item' => __('View Support', 'simapro'),
					'search_items' => __('Search Support', 'simapro'),
					'not_found' => __('No Support found', 'simapro'),
					'not_found_in_trash' => __('No Support found in Trash', 'simapro'),
					'parent' => __('Parent Support', 'simapro'),
				),
				'public' => true,
				'menu_icon' => 'dashicons-hammer',
				'menu_position' => 5,
				'hierarchical' => true,
				'has_archive' => false,
				'supports' => array('title','thumbnail', 'page-attributes'),
				'rewrite' => array('slug' => _x('support', 'URL slug', 'simapro'), 'with_front' => false)
				)
			);
		}

		function register_menu_locations(){
			register_nav_menus(
				array(
				'eur_menu' => 'Europe Menu',
				'usa_menu' => 'America Menu',
				'aus_menu' => 'Australia Menu',
				'afr_menu' => 'Africa Menu',
				'asi_menu' => 'Asia Menu',
				'mid_menu' => 'Middle East Menu',
				'other_menu' => 'Others Menu'
				)
			);
		}

		function register_custom_options_panel(){
			if( function_exists('acf_add_options_page') ) {
				$page = acf_add_options_page(array(
					'page_title' 	=> 'Options',
					'menu_title' 	=> 'Options',
					'menu_slug' 	=> 'acf-options',
					'capability' 	=> 'create_users',
					'redirect' 	=> false
				));
			}
		}

		function register_taxonomies(){
			//this is where you can register custom taxonomies
		}

		function add_to_context($context){
			$context['foo'] = 'bar';
			$context['stuff'] = 'I am a value set in your functions.php file';
			$context['notes'] = 'These values are available everytime you call Timber::get_context();';
			//$context['menu'] = new TimberMenu(421);
			$context['site'] = $this;
			return $context;
		}

		function add_to_twig($twig){
			/* this is where you can add your own fuctions to twig */
			$twig->addExtension(new Twig_Extension_StringLoader());
			$twig->addFilter('myfoo', new Twig_Filter_Function('myfoo'));
			return $twig;
		}

	}

	new StarterSite();

	function myfoo($text){
    	$text .= ' bar!';
    	return $text;
	}

	function remove_menus () {
global $menu;
	$restricted = array(__('Posts'),__('Comments'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');

function add_scripts_and_styles(){
	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'bootstrap' );
	wp_deregister_script( 'flexslider' );
	wp_deregister_script( 'site' );

	wp_register_script( 'jquery', "http" . ( $_SERVER['SERVER_PORT'] == 443 ? "s" : "" ) . "://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js", array(), false, true );
	wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), false, true );
	wp_register_script( 'flexslider', get_template_directory_uri() . '/js/flexslider.min.js', array('jquery','bootstrap'), false, true );
	wp_register_script( 'site', get_template_directory_uri() . '/js/site.js', array('jquery','bootstrap','flexslider'), false, true );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap' );
	wp_enqueue_script( 'flexslider' );
	wp_enqueue_script( 'site' );

	wp_localize_script( 'site', 'WPURLS', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action('wp_enqueue_scripts','add_scripts_and_styles');

add_filter( 'wpseo_canonical', '__return_false' );

/*---CUSTOM FIELDS---*/

if( function_exists('register_field_group') ):

register_field_group(array (
	'key' => 'group_540d75278a8c1',
	'title' => 'Introduction Fields',
	'fields' => array (
		array (
			'key' => 'field_540d6f8bdd6b9',
			'label' => 'Reseller Order URL',
			'name' => 'reseller_order_url',
			'prefix' => '',
			'type' => 'url',
			'instructions' => 'Insert link to order form',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
		),
		array (
			'key' => 'field_540d6fc4dd6ba',
			'label' => 'Reseller Order Label',
			'name' => 'reseller_order_label',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Order Now',
			'prepend' => '',
			'append' => '',
			'formatting' => 'none',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54521f136de58',
			'label' => 'Read More Text',
			'name' => 'read_more_text',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Read more text...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '39',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_5432a98da786e',
	'title' => 'Local partner field',
	'fields' => array (
		array (
			'key' => 'field_5432a9ff679fd',
			'label' => 'Button_title',
			'name' => 'button_title',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Button text...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5432aa25679fe',
			'label' => 'Button_URL',
			'name' => 'button_url',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Button URL...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5461e15f513ad',
			'label' => 'Reseller Link Description',
			'name' => 'reseller_name',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Reseller Link Description...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_546227c391246',
			'label' => 'Reseller Menu Button Name',
			'name' => 'reseller_menu_button_name',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Reseller openmenu button name...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '199',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'the_content',
	),
));

register_field_group(array (
	'key' => 'group_54231fe07ebcd',
	'title' => 'Newsletter Hidden Fields',
	'fields' => array (
		array (
			'key' => 'field_5423d71f3c1fc',
			'label' => 'Form action link ',
			'name' => 'form_action_link',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Enter form action link',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54232161c3a7d',
			'label' => 'Hidden field id',
			'name' => 'hidden_field_id',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Enter hidden field id',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5423214fc3a7c',
			'label' => 'Hidden field name',
			'name' => 'hidden_field_name',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Enter hidden field name',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54231ffac3a7b',
			'label' => 'Hidden field value',
			'name' => 'hidden_field_value',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Enter hidden field value',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '104',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'permalink',
		1 => 'the_content',
		2 => 'excerpt',
		3 => 'custom_fields',
		4 => 'discussion',
		5 => 'comments',
		6 => 'revisions',
		7 => 'slug',
		8 => 'author',
		9 => 'format',
		10 => 'page_attributes',
		11 => 'featured_image',
		12 => 'categories',
		13 => 'tags',
		14 => 'send-trackbacks',
	),
));

register_field_group(array (
	'key' => 'group_5422d772f18a2',
	'title' => 'Newsletter Info Text',
	'fields' => array (
		array (
			'key' => 'field_5422d785f6657',
			'label' => 'Newsletter Description',
			'name' => 'info_text',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Receive the latest...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5423cf0da8818',
			'label' => 'Submit button text',
			'name' => 'submit_button_text',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Subscribe',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '104',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'permalink',
		1 => 'the_content',
		2 => 'excerpt',
		3 => 'custom_fields',
		4 => 'discussion',
		5 => 'comments',
		6 => 'revisions',
		7 => 'slug',
		8 => 'author',
		9 => 'format',
		10 => 'page_attributes',
		11 => 'featured_image',
		12 => 'categories',
		13 => 'tags',
		14 => 'send-trackbacks',
	),
));

register_field_group(array (
	'key' => 'group_542315c29f1f0',
	'title' => 'Newsletter Input fields',
	'fields' => array (
		array (
			'key' => 'field_542315d1b654d',
			'label' => 'Form Fields',
			'name' => 'inputfields',
			'prefix' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'min' => 3,
			'max' => 3,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array (
				array (
					'key' => 'field_542315f3b654e',
					'label' => 'Input label for',
					'name' => 'input_labelfor',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_54231603b654f',
					'label' => 'Input label name',
					'name' => 'input_labelname',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_542319da57d87',
					'label' => 'Input placeholder',
					'name' => 'input_placeholder',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Enter placeholder text',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_54231a0157d88',
					'label' => 'Input id',
					'name' => 'input_id',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Enter input id',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_54231a8d57d89',
					'label' => 'Input name',
					'name' => 'input_name',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Enter input name',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_54231ad0cbff4',
					'label' => 'Input type',
					'name' => 'input_type',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Enter input type',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '104',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'permalink',
		1 => 'the_content',
		2 => 'excerpt',
		3 => 'custom_fields',
		4 => 'discussion',
		5 => 'comments',
		6 => 'revisions',
		7 => 'slug',
		8 => 'author',
		9 => 'format',
		10 => 'page_attributes',
		11 => 'featured_image',
		12 => 'categories',
		13 => 'tags',
		14 => 'send-trackbacks',
	),
));

register_field_group(array (
	'key' => 'group_540d77a395a5b',
	'title' => 'Product Fields Default',
	'fields' => array (
		array (
			'key' => 'field_540d77a39989d',
			'label' => 'Try Label',
			'name' => 'try_label',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Try Simapro',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d77a3998a9',
			'label' => 'Try URL',
			'name' => 'try_url',
			'prefix' => '',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
		),
		array (
			'key' => 'field_540d77a3998b6',
			'label' => 'More Label',
			'name' => 'more_label',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Read More',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d77a3998c3',
			'label' => 'More URL',
			'name' => 'more_url',
			'prefix' => '',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '35',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_540d75629ae20',
	'title' => 'Product Pricing Options',
	'fields' => array (
		array (
			'key' => 'field_540d7572accb1',
			'label' => 'Pricing Header',
			'name' => 'pricing_header',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Pricing',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d75c2accb2',
			'label' => 'Pricing Subtitle',
			'name' => 'pricing_subtitle',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'SimaPro offers...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d75e2accb3',
			'label' => 'Pricing Option',
			'name' => 'pricing_option',
			'prefix' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'min' => '',
			'max' => '',
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array (
				array (
					'key' => 'field_540d7617accb4',
					'label' => 'Title',
					'name' => 'option_title',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Licence name',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_540d7647accb5',
					'label' => 'Price',
					'name' => 'option_price',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Price...',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_545232367df03',
					'label' => 'Price Start',
					'name' => 'price_start',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => 'Starting at...',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '35',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_540d77f90f23a',
	'title' => 'Product Specs',
	'fields' => array (
		array (
			'key' => 'field_540d7805deabe',
			'label' => 'Specifications',
			'name' => 'specifications',
			'prefix' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'min' => '',
			'max' => '',
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array (
				array (
					'key' => 'field_540d781adeabf',
					'label' => 'Spec',
					'name' => 'option_spec',
					'prefix' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'column_width' => '',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '35',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_540d7bc757b87',
	'title' => 'Support Fields',
	'fields' => array (
		array (
			'key' => 'field_5416b331beac4',
			'label' => 'Support content',
			'name' => 'support_content',
			'prefix' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Support content',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => 'wpautop',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d7bd046963',
			'label' => 'Support Label',
			'name' => 'support_label',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Contact Sales...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d7bf746964',
			'label' => 'Support URL',
			'name' => 'support_url',
			'prefix' => '',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'support',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_540d79ca3fc9e',
	'title' => 'Testimonial Fields',
	'fields' => array (
		array (
			'key' => 'field_540d79e13b8df',
			'label' => 'Quote',
			'name' => 'quote',
			'prefix' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 4,
			'new_lines' => 'wpautop',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d7a063b8e0',
			'label' => 'Author Name',
			'name' => 'author_name',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Name',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d7a293b8e1',
			'label' => 'Author Description',
			'name' => 'author_description',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Author job title...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'testimonials',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_543be5b6f3a67',
	'title' => 'Theme Options',
	'fields' => array (
		/*array (
			'key' => 'field_543be5c44402b',
			'label' => 'Language',
			'name' => 'language',
			'prefix' => '',
			'type' => 'select',
			'instructions' => 'Select language based upon ISO code (http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)',
			'required' => 0,
			'conditional_logic' => 0,
			'choices' => array (
				'ad' => 'AD',
				'ae' => 'AE',
				'af' => 'AF',
				'ag' => 'AG',
				'ai' => 'AI',
				'al' => 'AL',
				'am' => 'AM',
				'an' => 'AN',
				'ao' => 'AO',
				'ar' => 'AR',
				'as' => 'AS',
				'at' => 'AT',
				'au' => 'AU',
				'aw' => 'AW',
				'az' => 'AZ',
				'ba' => 'BA',
				'bb' => 'BB',
				'bd' => 'BD',
				'be' => 'BE',
				'bf' => 'BF',
				'bg' => 'BG',
				'bh' => 'BH',
				'bi' => 'BI',
				'bj' => 'BJ',
				'bm' => 'BM',
				'bn' => 'BN',
				'bo' => 'BO',
				'br' => 'BR',
				'bs' => 'BS',
				'bt' => 'BT',
				'bv' => 'BV',
				'bw' => 'BW',
				'by' => 'BY',
				'bz' => 'BZ',
				'ca' => 'CA',
				'catalonia' => 'CATALONIA',
				'cd' => 'CD',
				'cf' => 'CF',
				'cg' => 'CG',
				'ch' => 'CH',
				'ci' => 'CI',
				'ck' => 'CK',
				'cl' => 'CL',
				'cm' => 'CM',
				'cn' => 'CN',
				'co' => 'CO',
				'cr' => 'CR',
				'cu' => 'CU',
				'cv' => 'CV',
				'cw' => 'CW',
				'cy' => 'CY',
				'cz' => 'CZ',
				'de' => 'DE',
				'dj' => 'DJ',
				'dk' => 'DK',
				'dm' => 'DM',
				'do' => 'DO',
				'dz' => 'DZ',
				'ec' => 'EC',
				'ee' => 'EE',
				'eg' => 'EG',
				'eh' => 'EH',
				'england' => 'ENGLAND',
				'er' => 'ER',
				'es' => 'ES',
				'et' => 'ET',
				'eu' => 'EU',
				'fi' => 'FI',
				'fj' => 'FJ',
				'fk' => 'FK',
				'fm' => 'FM',
				'fo' => 'FO',
				'fr' => 'FR',
				'ga' => 'GA',
				'gb' => 'GB',
				'gd' => 'GD',
				'ge' => 'GE',
				'gf' => 'GF',
				'gg' => 'GG',
				'gh' => 'GH',
				'gi' => 'GI',
				'gl' => 'GL',
				'gm' => 'GM',
				'gn' => 'GN',
				'gp' => 'GP',
				'gq' => 'GQ',
				'gr' => 'GR',
				'gs' => 'GS',
				'gt' => 'GT',
				'gu' => 'GU',
				'gw' => 'GW',
				'gy' => 'GY',
				'hk' => 'HK',
				'hm' => 'HM',
				'hn' => 'HN',
				'hr' => 'HR',
				'ht' => 'HT',
				'hu' => 'HU',
				'ic' => 'IC',
				'id' => 'ID',
				'ie' => 'IE',
				'il' => 'IL',
				'im' => 'IM',
				'in' => 'IN',
				'io' => 'IO',
				'iq' => 'IQ',
				'ir' => 'IR',
				'is' => 'IS',
				'it' => 'IT',
				'je' => 'JE',
				'jm' => 'JM',
				'jo' => 'JO',
				'jp' => 'JP',
				'ke' => 'KE',
				'kg' => 'KG',
				'kh' => 'KH',
				'ki' => 'KI',
				'km' => 'KM',
				'kn' => 'KN',
				'kp' => 'KP',
				'kr' => 'KR',
				'kurdistan' => 'KURDISTAN',
				'kw' => 'KW',
				'ky' => 'KY',
				'kz' => 'KZ',
				'la' => 'LA',
				'lb' => 'LB',
				'lc' => 'LC',
				'li' => 'LI',
				'lk' => 'LK',
				'lr' => 'LR',
				'ls' => 'LS',
				'lt' => 'LT',
				'lu' => 'LU',
				'lv' => 'LV',
				'ly' => 'LY',
				'ma' => 'MA',
				'mc' => 'MC',
				'md' => 'MD',
				'me' => 'ME',
				'mg' => 'MG',
				'mh' => 'MH',
				'mk' => 'MK',
				'ml' => 'ML',
				'mm' => 'MM',
				'mn' => 'MN',
				'mo' => 'MO',
				'mp' => 'MP',
				'mq' => 'MQ',
				'mr' => 'MR',
				'ms' => 'MS',
				'mt' => 'MT',
				'mu' => 'MU',
				'mv' => 'MV',
				'mw' => 'MW',
				'mx' => 'MX',
				'my' => 'MY',
				'mz' => 'MZ',
				'na' => 'NA',
				'nc' => 'NC',
				'ne' => 'NE',
				'nf' => 'NF',
				'ng' => 'NG',
				'ni' => 'NI',
				'nl' => 'NL',
				'no' => 'NO',
				'np' => 'NP',
				'nr' => 'NR',
				'nu' => 'NU',
				'nz' => 'NZ',
				'om' => 'OM',
				'pa' => 'PA',
				'pe' => 'PE',
				'pf' => 'PF',
				'pg' => 'PG',
				'ph' => 'PH',
				'pk' => 'PK',
				'pl' => 'PL',
				'pm' => 'PM',
				'pn' => 'PN',
				'pr' => 'PR',
				'ps' => 'PS',
				'pt' => 'PT',
				'pw' => 'PW',
				'py' => 'PY',
				'qa' => 'QA',
				're' => 'RE',
				'ro' => 'RO',
				'rs' => 'RS',
				'ru' => 'RU',
				'rw' => 'RW',
				'sa' => 'SA',
				'sb' => 'SB',
				'sc' => 'SC',
				'scotland' => 'SCOTLAND',
				'sd' => 'SD',
				'se' => 'SE',
				'sg' => 'SG',
				'sh' => 'SH',
				'si' => 'SI',
				'sk' => 'SK',
				'sl' => 'SL',
				'sm' => 'SM',
				'sn' => 'SN',
				'so' => 'SO',
				'somaliland' => 'SOMALILAND',
				'sr' => 'SR',
				'ss' => 'SS',
				'st' => 'ST',
				'sv' => 'SV',
				'sx' => 'SX',
				'sy' => 'SY',
				'sz' => 'SZ',
				'tc' => 'TC',
				'td' => 'TD',
				'tf' => 'TF',
				'tg' => 'TG',
				'th' => 'TH',
				'tj' => 'TJ',
				'tk' => 'TK',
				'tl' => 'TL',
				'tm' => 'TM',
				'tn' => 'TN',
				'to' => 'TO',
				'tr' => 'TR',
				'tt' => 'TT',
				'tv' => 'TV',
				'tw' => 'TW',
				'tz' => 'TZ',
				'ua' => 'UA',
				'ug' => 'UG',
				'um' => 'UM',
				'us' => 'US',
				'uy' => 'UY',
				'uz' => 'UZ',
				'va' => 'VA',
				'vc' => 'VC',
				've' => 'VE',
				'vg' => 'VG',
				'vi' => 'VI',
				'vn' => 'VN',
				'vu' => 'VU',
				'wales' => 'WALES',
				'wf' => 'WF',
				'ws' => 'WS',
				'ye' => 'YE',
				'yt' => 'YT',
				'za' => 'ZA',
				'zanzibar' => 'ZANZIBAR',
				'zm' => 'ZM',
				'zw' => 'ZW',
			),
			'default_value' => array (
				'gb' => 'GB',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),*/
		array (
			'key' => 'field_543d3d742c775',
			'label' => 'Language name',
			'name' => 'language_name',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'language name..',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5447776397cce',
			'label' => 'Google Analytics Code',
			'name' => 'google_analytics_code',
			'prefix' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Insert your google analytics code here...',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_544777a097ccf',
			'label' => 'Canonical link',
			'name' => 'canonical_link',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54522ddf6e6eb',
			'label' => 'USP Title',
			'name' => 'usp_title',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54523d6306188',
			'label' => 'Main Menu Item 1',
			'name' => 'main_menu_item_1',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Features (translation)',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54523e0dba2bb',
			'label' => 'Main Menu Item 2',
			'name' => 'main_menu_item_2',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'About SimaPro (translation)',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_54523e72ba2bc',
			'label' => 'Main Menu Item 3',
			'name' => 'main_menu_item_3',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Contact (translation)',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_5459f72076a0d',
			'label' => 'Footer text',
			'name' => 'footer_text',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'The text in the footer...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

register_field_group(array (
	'key' => 'group_540d78cc59b7f',
	'title' => 'Try Now Fields',
	'fields' => array (
		array (
			'key' => 'field_540d78e2e3486',
			'label' => 'Button Label',
			'name' => 'button_label',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Try Now...',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d78f6e3487',
			'label' => 'Button URL',
			'name' => 'button_url',
			'prefix' => '',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '47',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'the_content',
		1 => 'excerpt',
		2 => 'custom_fields',
		3 => 'discussion',
		4 => 'comments',
		5 => 'revisions',
		6 => 'slug',
		7 => 'featured_image',
		8 => 'send-trackbacks',
	),
));

register_field_group(array (
	'key' => 'group_540d75279435d',
	'title' => 'USPs Fields',
	'fields' => array (
		array (
			'key' => 'field_540d70ccc4d3e',
			'label' => 'Link Label',
			'name' => 'link_label',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'Call to action...',
			'prepend' => '',
			'append' => '',
			'formatting' => 'none',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_540d70f7c4d3f',
			'label' => 'Link URL',
			'name' => 'link_url',
			'prefix' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'default_value' => '',
			'placeholder' => 'http://',
			'prepend' => '',
			'append' => '',
			'formatting' => 'none',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'usps',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
	),
));

endif;
