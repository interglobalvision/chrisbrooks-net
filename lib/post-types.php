<?php
// Menu icons for Custom Post Types
function add_menu_icons_styles(){
?>

<style>
#adminmenu .menu-icon-project div.wp-menu-image:before {
    content: '\f498';
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );


//Register Custom Post Types
add_action( 'init', 'register_cpt_project' );

function register_cpt_project() {

    $labels = array(
        'name' => _x( 'Projects', 'project' ),
        'singular_name' => _x( 'Project', 'project' ),
        'add_new' => _x( 'Add New', 'project' ),
        'add_new_item' => _x( 'Add New Project', 'project' ),
        'edit_item' => _x( 'Edit Project', 'project' ),
        'new_item' => _x( 'New Project', 'project' ),
        'view_item' => _x( 'View Project', 'project' ),
        'search_items' => _x( 'Search Projects', 'project' ),
        'not_found' => _x( 'No projects found', 'project' ),
        'not_found_in_trash' => _x( 'No projects found in Trash', 'project' ),
        'parent_item_colon' => _x( 'Parent Project:', 'project' ),
        'menu_name' => _x( 'Projects', 'project' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title', 'thumbnail' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,

        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'project', $args );
}

add_action( 'init', 'register_cpt_photograph' );

function register_cpt_photograph() {

    $labels = array(
        'name' => _x( 'Photographs', 'photograph' ),
        'singular_name' => _x( 'Photograph', 'photograph' ),
        'add_new' => _x( 'Add New', 'photograph' ),
        'add_new_item' => _x( 'Add New Photograph', 'photograph' ),
        'edit_item' => _x( 'Edit Photograph', 'photograph' ),
        'new_item' => _x( 'New Photograph', 'photograph' ),
        'view_item' => _x( 'View Photograph', 'photograph' ),
        'search_items' => _x( 'Search Photographs', 'photograph' ),
        'not_found' => _x( 'No photographs found', 'photograph' ),
        'not_found_in_trash' => _x( 'No photographs found in Trash', 'photograph' ),
        'parent_item_colon' => _x( 'Parent Photograph:', 'photograph' ),
        'menu_name' => _x( 'Photographs', 'photograph' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title', 'thumbnail' ),
        'taxonomies' => array( 'post_tag' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,

        'show_in_nav_menus' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'photograph', $args );
}

add_action( 'init', 'register_cpt_spread' );

function register_cpt_spread() {

    $labels = array(
        'name' => _x( 'Spreads', 'spread' ),
        'singular_name' => _x( 'Spread', 'spread' ),
        'add_new' => _x( 'Add New', 'spread' ),
        'add_new_item' => _x( 'Add New Spread', 'spread' ),
        'edit_item' => _x( 'Edit Spread', 'spread' ),
        'new_item' => _x( 'New Spread', 'spread' ),
        'view_item' => _x( 'View Spread', 'spread' ),
        'search_items' => _x( 'Search Spreads', 'spread' ),
        'not_found' => _x( 'No Spreads found', 'spread' ),
        'not_found_in_trash' => _x( 'No Spreads found in Trash', 'spread' ),
        'parent_item_colon' => _x( 'Parent Spread:', 'spread' ),
        'menu_name' => _x( 'Spreads', 'spread' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,

        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'spread', $args );
}
