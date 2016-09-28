<?php
/*
Plugin Name: Team Members
Plugin URI: http://wp.tutsplus.com/
Description: Declares a plugin that will create a custom post type displaying movie reviews.
Version: 1.0
Author: Soumitra Chakraborty
Author URI: http://wp.tutsplus.com/
License: GPLv2
*/
add_action( 'init', 'create_team_member' );
function create_team_member() {
    register_post_type( 'team_members',
        array(
            'labels' => array(
                'name' => 'Team Members',
                'singular_name' => 'Team Member',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Team Member',
                'edit' => 'Edit',
                'edit_item' => 'Edit Team Member',
                'new_item' => 'New Team Member',
                'view' => 'View',
                'view_item' => 'View Team Member',
                'search_items' => 'Search Team Member',
                'not_found' => 'No Team Members found',
                'not_found_in_trash' => 'No Team Members found in Trash',
                'parent' => 'Parent Team Member'
                ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( '/image.ico', __FILE__ ),
            'has_archive' => true
            )
);
}

add_filter('rwmb_meta_boxes', 'team_members_register_meta_boxes');
function team_members_register_meta_boxes($meta_boxes){
    $meta_boxes[] = array(
        'id'         => 'team_members',
        'title'      => __( 'Other Information', 'textdomain' ),
        'post_types' => 'team_members',
        'context'    => 'normal',
        'priority'   => 'high',
        'fields' => array(
            array(
                'name'  => __( 'Position', 'textdomain' ),
                'desc'  => 'Format: First Last',
                'id'    => 'tmposition',
                'type'  => 'text',
                //'clone' => true
                ),
            array(
                'name'  => __( 'Email', 'textdomain' ),
                'desc'  => 'Format: example@gmail.com',
                'id'    => 'tmemail',
                'type'  => 'email',
                //'clone' => true
                ),
            array(
                'name'  => __( 'Phone', 'textdomain' ),
                'desc'  => 'Format: 081259234092',
                'id'    => 'tmphone',
                'type'  => 'text',
                //'clone' => true
                ),
            array(
                'name'  => __( 'Website', 'textdomain' ),
                'desc'  => 'Format: www.example.com',
                'id'    => 'tmwebsite',
                'type'  => 'text',
                //'clone' => true
                ),
            array(
                'name'  => __( 'Image', 'textdomain' ),
                'desc'  => 'Format: *JPG/*JPEG',
                'id'    => 'tmimage',
                'max_file_uploads'=>1,
                'type'  => 'image_advanced',
                //'clone' => true
                ),
            ),
    ); return $meta_boxes;
}


function display_team_member($atts){
    $attributes = shortcode_atts(
        array(
            'phone' => '',
            'email' => '',
            'website' => '',
            ), 
        $atts
    );
    
    $post_type = array( 'post_type' => 'team_members', );
    $result = new WP_Query( $post_type);
    ?>

    <div class="row" style="background:#00BFFF">
    <?php
    $i=0;
    while ( $result->have_posts() ) : $result->the_post();
    $position = get_post_meta( get_the_ID(), 'tmposition', true );
    $email = get_post_meta( get_the_ID(), 'tmemail', true );
    $phone = get_post_meta( get_the_ID(), 'tmphone', true );
    $website = get_post_meta( get_the_ID(), 'tmwebsite', true );
    $id_image = get_post_meta( get_the_ID(), 'tmimage', true ); 
    
    $temp_image = wp_get_attachment_image_src( $id_image, array('200', '200') );
    ?>

    <div style="color:white" >
        <img src="<?php echo $temp_image[0];?>" align="middle">
        
        <h3><center><?php echo the_title();?></center></h3>
        <h4><center><?php echo $position;?></center></h4>
        <p style="text-align:justify"><?php echo the_content();?></p>
        <?php
            if ($attributes['email'] == 'show') {
                echo "<p><center>".$email."</center></p>";
                        
            }
            if($attributes['phone']=='show'){
                echo "<p><center>".$phone."</center></p>";          
            }
            if($attributes['website']=='show'){
                echo "<p><center>".$website."</center></p>";            
            }
        ?>
    </div>
    <?php
    $i++;
    if ($i%3 == 0) echo '</div><div class="row" style="background:#00BFFF">';
    endwhile;
    ?>
    </div>
    <?php
}
add_shortcode('shortcode_display_team_member', 'display_team_member');
?>