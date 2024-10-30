<?php
/**
 * Saves meta tags in database.
 * 
 * @since 2.0.0
 */


defined('ABSPATH') || die();


class DPMT_Save_Tags {
 
    /**
     * Saves meta tags of a particular WP item.
     * 
     * @param array $list_of_meta_tags List of all editable meta tags.
     * @param array $data WP item data to process. Includes item type, ID and meta tag values.
     */
    public static function save( $list_of_meta_tags, $data ){

        // validate type
        if ( ! post_type_exists($data['dpmt_type']) && ! taxonomy_exists($data['dpmt_type']) ){
            return;
        }

        $type = $data['dpmt_id'] == 'front' ? 'frontpage' : $data['dpmt_type'];

        $url_fields = [ 
            'dpmt_og_audio',
            'dpmt_og_image',
            'dpmt_og_video',
            'dpmt_og_url',
            'dpmt_twitter_image',
            'dpmt_twitter_player_stream'
        ];


        // get list of all possible meta tags and walk them
        foreach ( $list_of_meta_tags as $group => $item ){

            foreach ( $item['fields'] as $tag => $field ){
                
                $key = $field['variable'];


                // sanitize data
                if ( $key == 'dpmt_custom' ){

                    $allowed_html = array(
                        'meta' => array(
                            'name' => array(),
                            'property' => array(),
                            'http-equiv' => array(),
                            'content' => array()
                        )
                    );
                    
                    $value = wp_kses( $data[$field['variable']], $allowed_html );

                }else{

                    if ( in_array($field['variable'], $url_fields) ){

                        $value = sanitize_text_field( $data[$field['variable']] );

                    }else{

                        $value = sanitize_text_field( $data[$field['variable']] );

                    }

                }

                
                // switch statement to handle saving of $key / $value based on $type
                switch (true){

                    case $type == 'category':
                    case $type == 'tag':
                    case taxonomy_exists( $type ):
                        update_term_meta( $data['dpmt_id'], $key, $value );

                        break;


                    case $type == 'author':
                        update_user_meta( $data['dpmt_id'], $key, $value );

                        break;


                    case $type == 'frontpage':
                        update_option( 'dpmt_frontpage_'. $key, $value );

                        break;


                    case post_type_exists( $type ):
                        update_post_meta( $data['dpmt_id'], $key, $value );

                        break;


                    default:

                        break;

                }

            }

        }
        
    }



    /**
     * Bulk action to clear all tags or set them autopilot (one meta tag group at a time).
     * 
     * @param string $action Action to take: delete or set autopilot.
     * @param array $list_of_meta_tags List of all editable meta tags.
     * @param string $wp_object_type WP item type that will be updated. E.g.: post, page, etc.
     * @param string $meta_tag_group Meta tag group that will be updated.
     */    
    public static function bulk( $action, $list_of_meta_tags, $wp_object_type, $meta_tag_group ){

        // only delete or autopilot actions are allowed this time
        if ( ! in_array( $action, array('delete', 'autopilot') ) ){
            return;
        }


        // validate type
        if ( ! post_type_exists($wp_object_type) && ! taxonomy_exists($wp_object_type) ){
            return;
        }


        // we can't set custom meta tags to autopilot
        if ( $meta_tag_group == 'custom' ){
            return;
        }


        // get list of all possible meta tags in that group      
        $fields_to_update = [];  
        foreach ( $list_of_meta_tags as $group => $item ){

            if ( $item['var'] == $meta_tag_group ){

                foreach( $item['fields'] as $k => $v ){

                    $fields_to_update[] = $v['variable'];

                }

                break;

            }

        }


        // get all items of a wp object type and set meta tags to "auto"        
        $items = DPMT_Retrieve_List::get_list( $wp_object_type );

        if ( ! empty($items['list']) && is_array($items) ){
            
            foreach($items['list'] as $item){

                // frontpage
                if ( $item['id'] == 'front' ){

                    foreach ( $fields_to_update as $field ){

                        if ( $action == 'autopilot' ){
                        
                            update_option( 'dpmt_frontpage_'. $field, 'auto' );

                        }elseif ( $action == 'delete' ){
            
                            delete_option( 'dpmt_frontpage_'. $field );                            

                        }

                    }
                    
                }else{

                    foreach ( $fields_to_update as $field ){

                        switch (true) {

                            case $wp_object_type == 'category':
                            case $wp_object_type == 'tag':      
                            case taxonomy_exists($wp_object_type):
                                
                                if ( $action == 'autopilot' ){
                                
                                    update_term_meta( $item['id'], $field, 'auto' );

                                }elseif( $action == 'delete' ){

                                    delete_term_meta( $item['id'], $field );

                                }


                                break;


                            case $wp_object_type == 'author':

                                if ( $action == 'autopilot' ){
                                    
                                    update_user_meta( $item['id'], $field, 'auto' );

                                }elseif( $action == 'delete' ){

                                    delete_user_meta( $item['id'], $field );

                                }


                                break;


                            case post_type_exists($wp_object_type):
                            
                                if ( $action == 'autopilot' ){

                                    update_post_meta( $item['id'], $field, 'auto' );
                                
                                }elseif( $action == 'delete' ){

                                    delete_post_meta( $item['id'], $field );

                                }


                                break;


                            default: 

                                break;

                        }

                    }  

                }

            }

        }     
                  

    }

}
