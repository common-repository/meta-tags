<?php
/**
 * Displays all meta tags in a table.
 * 
 * @since 2.0.0
 */

defined('ABSPATH') || die();

?>

<div class="wrap dpmt-table">
    <?php
    
    echo '
    <h1>'. __( 'Meta Tags', 'dp-meta-tags' ) . '</h1>

    <p>' . __( 'Click on an item to edit its meta tags. You can also set all of them to <b>autopilot</b> mode. <b>Autopilot</b> means that the plugin will retrieve the informations from the page itself.', 'dp-meta-tags' ) .    
    ' <a href="#" class="dpmt-toggle" data-toggle="1">' . __( 'Click here to learn how!', 'dp-meta-tags' ) . '</a></p>

    <div class="dpmt-hidden" data-toggle="1">

        <p>' . __( '<code>Posts:</code> title will be the post title, description will be the excerpt (if set) or the first few sentences, image will be the featured image or the first attached image, video and audio is the same', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Pages:</code> title will be the page title, description will be the excerpt (if set) or the first few sentences, image will be the featured image or the first attached image, video and audio is the same', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Categories, tags:</code> title will be the category/tag name, description will be the category/tag description', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<code>Authors:</code> title will be the author name, description will be the biographical info', 'dp-meta-tags' ) . 
        '</p>

        <p>' . __( '<b>Please note:</b> some meta tags cannot be filled automatically, e.g.: Twitter username', 'dp-meta-tags' ) . 
        '</p>

    </div>

    <div class="nav-tab-wrapper">';

        // display tabbed navigation, default types first   
        $possible_types = [
            'page' => esc_html__( 'Pages', 'dp-meta-tags' ),
            'post' => esc_html__( 'Posts', 'dp-meta-tags' ),
            'category' => esc_html__( 'Post Categories', 'dp-meta-tags' ),
            'tag' => esc_html__( 'Post Tags', 'dp-meta-tags' ),
            'author' => esc_html__( 'Authors', 'dp-meta-tags' )
        ];


        // get all custom post types and their taxonomies
        $args = array(
            'public' => true,
            '_builtin' => false
        );
        $cpt_list = get_post_types($args, 'objects');

        
        foreach ($cpt_list as $cpt) {            
            
            $possible_types[$cpt->name] = esc_html($cpt->label);
            $taxonomies = get_object_taxonomies($cpt->name, 'objects');           
            
            foreach ($taxonomies as $tax){      
                if ($tax->public && $tax->show_ui){
                    $possible_types[$tax->name] = esc_html($tax->label);
                }         
            }

        }


        foreach ( $possible_types as $key => $value ) {

            echo '<a href="options-general.php?page='. $_GET['page'];

            if ( $key != 'page' ) {
                echo '&tab='. $key;
            }

            echo '" class="nav-tab';

            if ( !empty($_GET['tab']) && $_GET['tab'] == $key || empty($_GET['tab']) && $key == 'page' ){
                echo  ' nav-tab-active';
            }  

            echo '">' . $value . '</a>';

        }
        
    ?>        
    </div>

        <div class="table-holder">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <?php
                    
                    // meta tag groups
                    foreach ($dpmt_meta_tag_list as $item => $details){
                        echo '<th>'. $item .' 
                        <span class="dashicons dashicons-editor-help" data-tip="'. esc_attr( wp_strip_all_tags( $details['info'] ) ) .'"></span></th>';
                    }

                    ?>                
                </tr>
            </thead>

            <tbody>
            <?php

                // load autopilot settings array form wp_options
                $dpmt_autopilot_settings = get_option('dpmt_autopilot_settings');      
                $autopilot_arr = maybe_unserialize($dpmt_autopilot_settings);                      


                // list all items of the wp object type
                $type = ( !empty($_GET['tab']) ? $_GET['tab'] : 'page' );

                $paged = ( !empty($_GET['paged']) ? intval( abs( $_GET['paged'] ) ) : 1 );
                
                $items_per_page = 25;

                $offset = ($paged * $items_per_page) - $items_per_page;

                $items = DPMT_Retrieve_List::get_list( $type, $items_per_page, $offset );
                
                $taginfo = new DPMT_Retrieve_Tags( $dpmt_meta_tag_list );

                if ( ! empty($items['list']) ){
                    foreach ( $items['list'] as $item ){

                        echo '                
                        <tr>
                            <td>';
                                if ($item['id'] == 'front'){
                                    echo '<i><b><a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item['id'] .'">'. esc_html__( 'Frontpage', 'dp-meta-tags' ) .'</a></b></i>
                                    <span class="dashicons dashicons-editor-help" data-tip="'. 
                                    esc_attr__('Your homepage displays the latest posts, you\'ll need meta tags there as well.')
                                    .'"></span>';
                                }else{
                                    echo '<a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item['id'] .'">'. $item['title'] .'</a>';
                                }
                            echo '
                            </td>';

                            $statuses = $taginfo->get_status( $type, $item['id'] );
                            foreach ($statuses as $group => $status){

                                $var = $dpmt_meta_tag_list[$group]['var'];

                                if ( 
                                    $status != 'mixed' && $status != 'custom' && 
                                    ! empty($autopilot_arr[$type][$var]) && 
                                    $autopilot_arr[$type][$var] == 'autopilot'
                                ){
                                    echo '<td><i>'. esc_html('always autopilot', 'dp-meta-tags') .'</i></td>';
                                }else{
                                    echo '<td>'. $status .'</td>';
                                }

                            }

                            echo '
                        </tr>
                        ';

                    }
                }else{
                    echo '<tr><td colspan="6">&nbsp;</td></tr>';
                }

            ?>
            </tbody>    

            <tfoot>
                <tr>                    
                <?php 
                
                // always autopilot buttons

                    echo '<th></th>';



                    foreach ($dpmt_meta_tag_list as $group => $info){

                        echo '<td>';
                            
                        if ( $group != 'Custom' ){

                            if ( 
                                !empty($dpmt_autopilot_settings) &&
                                ! empty($autopilot_arr[$type][$info['var']]) && 
                                $autopilot_arr[$type][$info['var']] == 'autopilot'
                            ){

                                echo '<a href="'. 
                                wp_nonce_url( 
                                    admin_url(
                                        'options-general.php?page=dpmt-editor&tab='. $type .'&automatize=no&tagtype='. $info['var']),
                                        'dpmt-automatize'
                                    ) .
                                '"><input type="checkbox" name="dpmt-autopilot" value="'. $group .'" checked="checked" /> '.
                                esc_html__( 'Always on autopilot', 'dp-meta-tags' ) .
                                '</a>';

                            }else{

                                echo '<a href="'. 
                                wp_nonce_url( 
                                    admin_url(
                                        'options-general.php?page=dpmt-editor&tab='. $type .'&automatize=yes&tagtype='. $info['var']),
                                        'dpmt-automatize'
                                    ) .
                                '"><input type="checkbox" name="dpmt-autopilot" value="'. $group .'" /> '.
                                esc_html__( 'Always on autopilot', 'dp-meta-tags' ) .
                                '</a>';

                            }

                        }
                        
                        echo '</td>';

                    }

                ?>
                </tr>
            </tfoot>
        </table>
        </div>

    <?php

        // pagination        

        $total_pages = ceil( $items['items_found'] / $items_per_page );
        $prev_page = $paged - 1;
        $next_page = $paged + 1;
        $tab = ( !empty($_GET['tab']) ? $_GET['tab'] : '' );

        echo '
        <form method="GET">
        <div class="tablenav bottom">
            <div class="tablenav-pages">

                <span class="displaying-num">' . sprintf(
                    __( '%d items', 'dp-meta-tags' ),
                    $items['items_found']
                ) . '</span>';


            if ( $total_pages > 1 ){

                echo '
                <span class="pagination-links">';


                // prev page links
                if ( !empty($paged) && $paged > 1 ){

                    echo '
                    <a class="first-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '">
                        <span class="screen-reader-text">'. esc_html__('First page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&laquo;</span>
                    </a>

                    <a class="prev-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '&amp;paged='. $prev_page .'">
                        <span class="screen-reader-text">'. esc_html__('Previous page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&lsaquo;</span>
                    </a>';
                
                }else{

                    echo '
                    <span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>';

                }
                    

                // page info
                echo '
                <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text">'. 
                        esc_html__('Current page', 'dp-meta-tags' ) 
                    .'</label>
                    <input type="hidden" name="page" value="dpmt-editor" />
                    <input type="hidden" name="tab" value="'. $tab .'" />
                    <input class="current-page" id="current-page-selector" type="text" name="paged" value="'. $paged .'" size="1" aria-describedby="table-paging" />
                    <span class="tablenav-paging-text"> / <span class="total-pages">'. $total_pages .'</span>
                    </span>
                </span>';


                // next page links
                if ( $paged != $total_pages ){

                    echo '
                    <a class="next-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '&amp;paged='. $next_page .'">
                        <span class="screen-reader-text">'. esc_html__('Next page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&rsaquo;</span>
                    </a>
                    
                    <a class="last-page" href="' . 
                    admin_url( 'options-general.php?page=dpmt-editor&tab=' . $tab ) . '&amp;paged=' . $total_pages .'">
                        <span class="screen-reader-text">'. esc_html__('Last page', 'dp-meta-tags' ) .'</span>
                        <span aria-hidden="true">&raquo;</span>
                    </a>';

                }else{

                    echo '
                    <span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';

                }


                echo '
                </span>';

            }


            echo ' 
            </div>
        </div>
        </form>
        ';

    ?>  

</div>