<?php
if( ! function_exists( 'bapf_template_styles_preview' ) ){
    function bapf_template_styles_preview() {
        include_once('template_styles_preview.php');
    }
}
if( ! function_exists( 'br_set_value_to_array' ) ){
    function br_set_value_to_array(&$arr, $index, $value = '') {
        if( ! isset($arr) || ! is_array($arr) ) {
            $arr = array();
        }
        if( ! is_array($index) ) {
            $index = array($index);
        }
        $array = &$arr;
        foreach($index as $i) {
            if( ! isset($array[$i]) ) {
                $array[$i] = array();
            }
            $array2 = &$array[$i];
            unset($array);
            $array = &$array2;
        }
        $array = $value;
        return $arr;
    }
}
if( ! function_exists('braapf_filters_must_be_recounted') ) {
    function braapf_filters_must_be_recounted($type = 'different') {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        if( $type === 'different' ) {
            return in_array(br_get_value_from_array($br_options, 'recount_hide'), array('recount', 'removeFirst_recount', 'removeRecount'));
        } elseif( $type === 'first' ) {
            return in_array(br_get_value_from_array($br_options, 'recount_hide'), array('removeFirst', 'removeFirst_recount', 'removeRecount'));
        } elseif( $type === 'none' ) {
            return ( ! br_get_value_from_array($br_options, 'recount_hide') || br_get_value_from_array($br_options, 'recount_hide') !== 'disable' );
        } else {
            return ( br_get_value_from_array($br_options, 'recount_hide') && br_get_value_from_array($br_options, 'recount_hide') !== 'disable' );
        }
    }
}
if( ! function_exists( 'berocket_aapf_insert_to_array' ) ){
    function berocket_aapf_insert_to_array($array, $key_in_array, $array_to_insert, $before = false) {
        $position = array_search($key_in_array, array_keys($array), true);
        if( $position !== FALSE ) {
            if( ! $before ) {
                $position++;
            }
            $array = array_slice($array, 0, $position, true) +
                                $array_to_insert +
                                array_slice($array, $position, NULL, true);
        }
        return $array;
    }
}
if( ! function_exists( 'br_get_current_language_code' ) ){
    /**
     * Permalink block in settings
     *
     */
    function br_get_current_language_code() {
        $language = '';
        if( function_exists( 'qtranxf_getLanguage' ) ) {
            $language = qtranxf_getLanguage();
        }
        if( defined('ICL_LANGUAGE_CODE') ) {
            $language = ICL_LANGUAGE_CODE;
        }
        return $language;
    }
}
if( ! function_exists( 'berocket_wpml_attribute_translate' ) ){
    function berocket_wpml_attribute_translate($slug) {
        $wpml_slug = apply_filters( 'wpml_translate_single_string', $slug, 'WordPress', sprintf( 'URL attribute slug: %s', $slug ) );
        if( $wpml_slug != $slug ) {
            $translations = get_option('berocket_wpml_attribute_slug_untranslate');
            if( ! is_array($translations) ) {
                $translations = array();
            }
            $translations[$wpml_slug] = $slug;
            update_option('berocket_wpml_attribute_slug_untranslate', $translations);
        }
        return $wpml_slug;
    }
}
if( ! function_exists( 'berocket_wpml_attribute_untranslate' ) ){
    function berocket_wpml_attribute_untranslate($slug) {
        $translations = get_option('berocket_wpml_attribute_slug_untranslate');
        if( is_array($translations) && ! empty($translations[$slug]) ) {
            $slug = $translations[$slug];
        }
        return $slug;
    }
}

if( ! function_exists( 'br_get_template_part' ) ){
    /**
     * Public function to get plugin's template
     *
     * @param string $name Template name to search for
     *
     * @return void
     */
    function br_get_template_part( $name = '' ){
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $BeRocket_AAPF->br_get_template_part( $name );
    }
}

if( ! function_exists( 'br_is_filtered' ) ){
    /**
     * Public function to check if filter set
     *
     * @param bool $filters is filter set
     * @param bool $limits is limit set
     * @param bool $price is price set
     *
     * @return bool
     */
    function br_is_filtered( $filters = true, $limits = true, $price = true, $search = true ){
        $filtered = false;
        if ( $filters ) {
            $filtered = $filtered || ( isset( $_POST['terms'] ) && is_array( $_POST['terms'] ) && count( $_POST['terms'] ) > 0 );
        }
        if ( $limits ) {
            $filtered = $filtered || ( isset( $_POST['limits'] ) && is_array( $_POST['limits'] ) && count( $_POST['limits'] ) > 0 );
        }
        if ( $price ) {
            $filtered = $filtered || ( isset( $_POST['price'] ) && is_array( $_POST['price'] ) && count( $_POST['price'] ) > 0 );
        }
        if ( $search ) {
            $filtered = $filtered || ! empty( $_GET['s'] );
        }
        return $filtered;
    }
}

if( ! function_exists( 'br_get_cache' ) ){
    /**
     * Get cached object
     *
     * @param string $key Key to find value
     * @param string $group Group with keys
     * @param string $cache_type Type of cache 'wordpress' or 'persistent'
     *
     * @return mixed
     */
    function br_get_cache( $key, $group ){
        return apply_filters('br_get_cache', false, $key, $group);
    }
}

if( ! function_exists( 'br_set_cache' ) ){
    /**
     * Save object to cache
     *
     * @param string $key Key to save value
     * @param mixed $value Value to save
     * @param string $group Group with keys
     * @param int $expire Expiration time in seconds
     * @param string $cache_type Type of cache 'wordpress' or 'persistent'
     *
     * @return void
     */
    function br_set_cache( $key, $value, $group, $expire ){
        return apply_filters('br_set_cache', true, $key, $value, $group, $expire);
    }
}

if ( ! function_exists( 'br_is_term_selected' ) ) {
    /**
     * Public function to check if term is selected
     *
     * @param object $term - Term to check for
     * @param boolean $checked - if TRUE return ' checked="checked"'
     * @param boolean $child_parent - if TRUE search child selected
     * @param integer $depth - current term depth in hierarchy
     *
     * @return string ' selected="selected"' if selected, empty string '' if not selected
     */
    function br_is_term_selected( $term, $checked = FALSE, $child_parent = FALSE, $depth = 0 ) {
        //TODO: Notice: Trying to get property 'taxonomy' of non-object
        $term_taxonomy = $term->taxonomy;
        if( $term_taxonomy == '_rating' ) {
            $term_taxonomy = 'product_visibility';
        }
        $is_checked = false;

        if ( ! empty($_POST['terms']) and ! empty($term) and is_object( $term ) ) {
            if ( $child_parent ) {
                $selected_terms = br_get_selected_term( $term_taxonomy );
                foreach( $selected_terms as $selected_term ) {
                    $ancestors = get_ancestors( $selected_term, $term_taxonomy );
                    if( count( $ancestors ) > $depth ) {
                        if ( $ancestors[count($ancestors) - ( $depth + 1 )] == $term->term_id ) {
                            $is_checked = true;
                        }
                    }
                }
            }
            foreach ( $_POST['terms'] as $p_term ) {
                if ( (  ! empty($p_term[0]) and ! empty($p_term[1]) and $p_term[0] == $term_taxonomy and $term->term_id == $p_term[1] ) or $is_checked ) {
                    if($checked) return ' checked="checked"';
                    else return ' selected="selected"';
                }
            }
        }
        if ( ! empty($_POST['add_terms']) and ! empty($term) and is_object( $term ) ) {
            foreach ( $_POST['add_terms'] as $p_term ) {
                if ( ( ! empty($p_term[0]) and ! empty($p_term[1]) and $p_term[0] == $term_taxonomy and $term->term_id == $p_term[1] ) or $is_checked ) {
                    if($checked) return ' checked="checked"';
                    else return ' selected="selected"';
                }
            }
        }
        if ( ! empty($_POST['price_ranges']) and ! empty($term) and is_object( $term ) and $term_taxonomy == 'price' ) {
            $is_checked = false;
            foreach ( $_POST['price_ranges'] as $p_term ) {
                if ( ( $term->term_id == $p_term ) ) {
                    if($checked) return ' checked="checked"';
                    else return ' selected="selected"';
                }
            }
        }
        return '';
    }
}

if ( ! function_exists( 'br_get_selected_term' ) ) {
    /**
     * Public function to get all selected terms in taxonomy
     *
     * @param object $taxonomy - Taxonomy name
     *
     * @return array selected terms
     */
    function br_get_selected_term( $taxonomy ) {
        $term_ids = array();
        if ( ! empty($_POST['terms']) ) {
            foreach ( $_POST['terms'] as $p_term ) {
                if ( ! empty($p_term[0]) and $p_term[0] == $taxonomy ) {
                    $term_ids[] = ( empty($p_term[1]) ? '' : $p_term[1] );
                }
            }
        }
        if ( ! empty($_POST['limits']) ) {
            foreach ( $_POST['limits'] as $v ) {
                if ( ! empty($v[0]) && $v[0] == $taxonomy ) {
                    $v[1] = urldecode( $v[1] );
                    $v[2] = urldecode( $v[2] );
                    $all_terms_name = array();
                    $all_terms_slug = array();
                    $terms = get_terms( $v[0] );
                    
                    $wc_order_by = wc_attribute_orderby( $v[0] );
                    BeRocket_AAPF_Widget_functions::sort_terms( $terms, array(
                        "wc_order_by"     => $wc_order_by,
                        "order_values_by" => '',
                        "filter_type"     => 'attribute',
                        "order_values_type"=> SORT_ASC
                    ) );
                    $is_numeric = true;
                    $is_with_string = false;
                    if( is_wp_error ( $all_terms_name ) ) {
                        BeRocket_updater::$error_log[] = $all_terms_name->errors;
                    }
                    if( ! is_numeric($v[1]) || ! is_numeric($v[2]) ) {
                        $is_with_string = true;
                    }
                    foreach ( $terms as $term ) {
                        if( ! is_numeric( substr( $term->name[0], 0, 1 ) ) ) {
                            $is_numeric = false;
                        }
                        if( ! is_numeric( $term->name ) ) {
                            $is_with_string = true;
                        }
                        array_push( $all_terms_name, $term->slug );
                        array_push( $all_terms_slug, $term->name );
                    }
                    if( $is_numeric ) {
                        array_multisort( $all_terms_slug, SORT_NUMERIC, $all_terms_name, $all_terms_slug );
                    } else {
                        //array_multisort( $all_terms_name, $all_terms_name, $all_terms_slug );
                    }
                    $taxonomy_terms = get_terms(array('fields' => 'id=>slug', 'taxonomy' => $v[0]));
                    if( $is_with_string ) {
                        $start_terms    = array_search( $v[1], $all_terms_name );
                        $end_terms      = array_search( $v[2], $all_terms_name );
                        $all_terms_name = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
                        $search = $all_terms_name;
                    } else {
                        $start_terms = false;
                        $end_terms = false;
                        $previous_pos = false;
                        $search = array();
                        foreach($all_terms_slug as $term_pos => $term) {
                            if( $term >= $v[1] && $start_terms === false ) {
                                $start_terms = $term_pos;
                            }
                            if( $end_terms === false ) {
                                if( $term > $v[2] ) {
                                    if( $previous_pos !== false ) {
                                        $end_terms = $previous_pos;
                                    }
                                } elseif( $term == $v[2] ) {
                                    $end_terms = $term_pos;
                                }
                            }
                            $previous_pos = $term_pos;
                        }
                        if( $start_terms > $end_terms ) {
                            $search = array();
                        } elseif( $v[1] > $v[2] ) {
                            $search = array();
                        } else {
                            $search = array_slice( $all_terms_name, $start_terms, ( $end_terms - $start_terms + 1 ) );
                        }
                    }
                    foreach($search as $search_el) {
                        $id = array_search($search_el, $taxonomy_terms);
                        if( $id !== FALSE ) {
                            $term_ids[] = $id;
                        }
                    }
                }
            }
        }
        return $term_ids;
    }
}

if( ! function_exists( 'br_aapf_get_attributes' ) ) {
    /**
     * Get all possible woocommerce attribute taxonomies
     *
     * @return mixed|void
     */
    function br_aapf_get_attributes() {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $attributes           = array();

        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $attributes[ wc_attribute_taxonomy_name( $tax->attribute_name ) ] = $tax->attribute_label;
            }
        }

        return apply_filters( 'berocket_aapf_get_attributes', $attributes );
    }
}

if( ! function_exists( 'br_aapf_parse_order_by' ) ) {
    /**
     * br_aapf_parse_order_by - parsing order by data and saving to $args array that was passed into
     *
     * @param $args
     */
    function br_aapf_parse_order_by( &$args ) {
        $orderby = $_GET['orderby'] = $_POST['orderby'];
        $order   = "ASC";
        if ( preg_match( "/-/", ( empty($orderby) ? '' : $orderby ) ) ) {
            list( $orderby, $order ) = explode( "-", $orderby );
        }
        $order = strtoupper($order);

        // needed for woocommerce sorting funtionality
        if ( ! empty($orderby) and ! empty($order) ) {

            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            // Get ordering from query string unless defined
            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );

            // default - menu_order
            $args['orderby']  = 'menu_order title';
            $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';

            switch ( strtolower($orderby) ) {
                case 'rand' :
                    $args['orderby']  = 'rand';
                    break;
                case 'date' :
                    $args['orderby']  = 'date';
                    $args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
                    break;
                case 'price' :
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                    $args['meta_key'] = apply_filters('berocket_price_filter_meta_key', '_price', 'functions_280');
                    break;
                case 'popularity' :
                    $args['meta_key'] = 'total_sales';

                    // Sorting handled later though a hook
                    add_filter( 'posts_clauses', array( $BeRocket_AAPF, 'order_by_popularity_post_clauses' ) );
                    break;
                case 'rating' :
                    // Sorting handled later though a hook
                    add_filter( 'posts_clauses', array( $BeRocket_AAPF, 'order_by_rating_post_clauses' ) );
                    break;
                case 'title' :
                    $args['orderby']  = 'title';
                    $args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                    break;
                default:
                    break;
            }
        }
    }
}

if( ! function_exists( 'br_aapf_args_parser' ) ){
    /**
     * br_aapf_args_parser - extend $args based on passed filters
     *
     * @param array $args
     *
     * @return array
     */
    function br_aapf_args_parser( $args = array() ) {
        $br_options = BeRocket_AAPF::get_aapf_option();
        $tax_query = array();
        $tags             = '';

        if ( ! empty($_POST['terms']) ) {
            foreach ( $_POST['terms'] as $post_key => $t ) {
                if ( $t[4] == 'attribute' && $t[0] != 'product_cat' && $t[0] != 'product_tag' ) {
                    $taxonomies[ $t[0] ][]        = br_aapf_args_parser_check_terms($t[0], $t[1]);
                    $taxonomies_operator[ $t[0] ] = $t[2];
                } elseif ( taxonomy_exists( $t[0] ) ) {
                    $taxonomies[ $t[0] ][]        = $t[1];
                    $taxonomies_operator[ $t[0] ] = $t[2];
                }
            }
        }

        $taxonomies          = apply_filters( 'berocket_aapf_listener_taxonomies', ( empty($taxonomies) ? '' : $taxonomies ) );
        $taxonomies_operator = apply_filters( 'berocket_aapf_listener_taxonomies_operator', ( empty($taxonomies_operator) ? '' : $taxonomies_operator ) );

        if ( ! empty($taxonomies) ) {
            $tax_query['relation'] = 'AND';
            if ( $taxonomies ) {
                foreach ( $taxonomies as $k => $v ) {
                    if ( $taxonomies_operator[ $k ] == 'AND' ) {
                        $op = 'AND';
                    } else {
                        $op = 'OR';
                    }

                    $fields = 'id';
                    $current_tax_query = array();
                    $current_tax_query['relation'] = $op;
                    $include_children = false;
                    if( in_array($k, array('product_cat', 'berocket_brand')) ) {
                        $include_children = true;
                    }
                    foreach($v as $v_i) {
                        $current_tax_query[] = apply_filters('berocket_aapf_tax_query_attribute', array(
                            'taxonomy'          => $k,
                            'field'             => $fields,
                            'terms'             => $v_i,
                            'operator'          => 'IN',
                            'include_children'  => $include_children,
                            'is_berocket'       => true
                        ));
                    }
                    $tax_query[] = $current_tax_query;
                }
            }
        }

        if ( ! empty($tags) ) {
            $args['product_tag'] = $tags;
        }

        if ( ! empty($_POST['product_cat']) and $_POST['product_cat'] != '-1' ) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => strip_tags( $_POST['product_cat'] ),
                'operator' => 'IN',
                'is_berocket'=> true
            );
        }

        $args['tax_query'] = $tax_query;
        $args['post_type'] = 'product';

        if ( ! empty($_POST['orderby']) ) {
            br_aapf_parse_order_by( $args );
        }

        return $args;
    }
}

if( ! function_exists( 'br_aapf_args_parser_attributes_terms' ) ) {
    function br_aapf_args_parser_attributes_terms($args) {
        global $wpdb;
        $args = array_merge( array(
            'taxonomy'  => 'product_cat',
            'return'    => 'terms'
        ), $args);
		$wpdb->query("SET SESSION group_concat_max_len = 1000000");
        $md5 = $wpdb->get_var(
            $wpdb->prepare("SELECT MD5(GROUP_CONCAT(t.slug+t.term_id+tt.parent+tt.count)) 
                FROM $wpdb->terms AS t 
                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id 
                WHERE tt.taxonomy IN ('%s')", 
                $args['taxonomy']
            )
        );
        $md5 = apply_filters('BRaapf_cache_check_md5', $md5, 'br_aapf_args_parser_attributes_terms', $args);
        $attributes_terms = get_option( apply_filters('br_aapf_md5_cache_text', 'br_get_taxonomy_args_parser_'.$args['taxonomy']) );
        if( empty($attributes_terms) || $attributes_terms['md5'] != $md5 ) {
            $attributes_terms = array(
                'terms' => array(),
                'md5'   => $md5,
                'time'  => time()
            );

            $terms = get_terms( array( $args['taxonomy'] ), array( 'orderby' => 'name', 'order' => 'ASC' ) );
            if ( $terms && ! is_wp_error($terms) ) {
                foreach ( $terms as $term ) {
                    $attributes_terms['terms'][$term->term_id] = $term->term_id;
                }
            }
            update_option(apply_filters('br_aapf_md5_cache_text', 'br_get_taxonomy_args_parser_'.$args['taxonomy']), $attributes_terms);
        }
        return br_get_value_from_array($attributes_terms, $args['return']);
    }
}
if( ! function_exists( 'br_aapf_args_parser_check_terms' ) ) {
    function br_aapf_args_parser_check_terms($attribute, $term) {
        $attributes_terms = br_aapf_args_parser_attributes_terms(array('taxonomy' => $attribute));
        return ( empty($attributes_terms[ $term ]) ? '' : $attributes_terms[ $term ] );
    }
}
if( ! function_exists( 'br_aapf_args_converter' ) ) {
    /**
     * convert args-url to normal filters
     */
    function br_aapf_args_converter($query) {
        $br_options = BeRocket_AAPF::get_aapf_option();
        do_action('br_aapf_args_converter_before', $query);
        if(! empty($_GET['filters'])) {
            if( empty($br_options['seo_uri_decode']) ) {
                $_GET['filters'] = urlencode($_GET['filters']);
                $_GET['filters'] = str_replace('+', urlencode('+'), $_GET['filters']);
                $_GET['filters'] = urldecode($_GET['filters']);
            }
        }
        $filters_string = apply_filters('brapf_args_converter_get_string', (empty($_GET['filters']) ? '' : $_GET['filters']), $br_options, $query);
        $_POST['terms'] = array();
        $_POST['add_terms'] = array();
        $_POST['limits'] = array();
        $_POST['price'] = array();
        $_POST['price_ranges'] = array();
        $filters = array();
        if( empty($filters_string) ) {
            $filters = array();
        } elseif ( preg_match( "~\|~", $filters_string ) ) {
            $filters = explode( "|", $filters_string );
        } elseif( $filters_string ) {
            $filters[0] = $filters_string;
        }

        global $br_url_parser_middle_result;
        foreach ( $filters as $filter ) {
            if( isset($min) ) {
                unset($min);
            }
            if( isset($min) ) {
                unset($max);
            }
            if ( preg_match( "~\[~", $filter ) ) {
                list( $attribute, $value ) = explode( "[", trim( preg_replace( "~\]~", "", $filter) ), 2 );
                $attribute = berocket_wpml_attribute_untranslate($attribute);
                $value = html_entity_decode($value);
                
                $braapf_sliders = br_get_value_from_array($_SESSION, 'braapf_sliders');
                if( ! is_array($braapf_sliders) ) {
                    $braapf_sliders = array();
                }
                $parse_type = 'default';
                if( (! empty($braapf_sliders['pa_'.$attribute]) || ! empty($braapf_sliders[$attribute])) && preg_match( "~\_~", $value ) ) {
                    $parse_type = 'slider';
                } elseif( term_exists( sanitize_title($value), 'pa_'.$attribute ) ) {
                    $parse_type = 'or';
                } elseif ( preg_match( "~\*~", $value ) ) {
                    $parse_type = 'price_range';
                } elseif ( preg_match( "~\+~", $value ) ) {
                    $parse_type = 'and';
                } elseif ( preg_match( "~\-~", $value ) ) {
                    $parse_type = 'or';
                } elseif ( preg_match( "~\_~", $value ) ) {
                    $parse_type = 'slider';
                }
                switch($parse_type) {
                    case 'or':
                        $value = explode( "-", $value );
                        if( ! empty($br_options['slug_urls']) && $attribute != '_stock_status' && $attribute != '_sale' ) {
                            $values = array();
                            for ( $i = 0; $i < count( $value) ; $i++ ) {
                                $values[ $i ] = urldecode( $value[ $i ] );
                            }

                            $value = array();
                            $attribute_check = $attribute;
                            if( $attribute == '_rating' ) {
                                $attribute_check = 'product_visibility';
                            }
                            for ( $i = 0; $i < count( $values ); $i++ ) {
                                $cur_value = $values;
                                for ( $ii = count( $values ); $ii > 0; $ii-- ) {
                                    if ( ! term_exists( implode( '-', $cur_value ), $attribute_check ) && ! term_exists( implode( '-', $cur_value ), 'pa_' . $attribute_check ) ) {
                                        array_pop( $cur_value );
                                        if ( ! $cur_value ) {
                                            break 2;
                                        }
                                    } else {
                                        $value[] = implode( '-', array_splice( $values, 0, count( $cur_value ) ) );
                                        $i       = - 1;
                                        break;
                                    }
                                }
                            }
                        }
                        $operator = 'OR';
                        break;
                    case 'and':
                        $value    = explode( "+", $value );
                        $operator = 'AND';
                        break;
                    case 'slider':
                        list( $min, $max ) = explode( "_", $value );
                        $operator = '';
                        break;
                    case 'price_range':
                        $value = explode( "-", $value );
                        break;
                    default:
                        $value    = explode( " ", $value );
                        $operator = 'OR';
                        break;
                }
            } else {
                list( $attribute, $value ) = explode( "-", $filter, 2 );
            }

            $br_url_parser_middle_result[ $attribute ] = $value;

            if ( $attribute == 'price' ) {
                if ( isset( $min ) && isset( $max ) ) {
                    $_POST['price'] = apply_filters('berocket_min_max_filter', array( $min, $max ));
                    $BeRocket_AAPF = BeRocket_AAPF::getInstance();
                    $BeRocket_AAPF->wcml_currency_price_fix();
                } else {
                    $_POST['price_ranges'] = $value;
                }
            } elseif ( $attribute == 'order' ) {
                $_GET['orderby'] = $value;
            } else {
                if ( $operator ) {
                    foreach ( $value as $v ) {
                        $type = FALSE;
                        $operator_2 = $attribute_2 = '';
                        if($attribute == 'product_tag') {
                            $type = 'tag';
                            $attribute_2 = 'product_tag';
                            $operator_2 = $operator;
                        } elseif( taxonomy_exists( 'pa_'.$attribute ) ) {
                            $type = 'attribute';
                            $attribute_2 = "pa_" . $attribute;
                            $operator_2 = $operator;
                        } elseif( taxonomy_exists( $attribute ) ) {
                            $type = 'custom_taxonomy';
                            $attribute_2 = $attribute;
                            $operator_2 = $operator;
                        } elseif( $attribute == '_stock_status' || $attribute == '_sale' ) {
                            $type = 'attribute';
                            $attribute_2 = $attribute;
                            $operator_2 = $operator;
                        } elseif( $attribute == '_rating' ) {
                            $type = 'custom_taxonomy';
                            $attribute_2 = 'product_visibility';
                            $operator_2 = $operator;
                        }
                        if($type !== FALSE) {
                            if( $attribute_2 == '_stock_status' || $attribute_2 == '_sale' ) {
                                if( $attribute_2 == '_stock_status' ) {
                                    $slug_name = array( '', 'instock', 'outofstock');
                                } else {
                                    $slug_name = array( '', 'sale', 'notsale');
                                }
                                if( ! empty($br_options['slug_urls']) ) {
                                    $attr_name = $v;
                                    $v = array_search( $v, $slug_name );
                                } else {
                                    $attr_name = $slug_name[$v];
                                }
                            } else {
                                if( ! empty($br_options['slug_urls']) ) {
                                    $attr_name_object = get_term_by( 'slug', $v, $attribute_2, 'OBJECT' );
                                    $attr_name        = ( $attr_name_object == false ) ? '' : $attr_name_object->term_id;
                                    $slug_name        = $v;
                                    $v                = $attr_name;
                                    $attr_name        = $slug_name;
                                } else {
                                    $attr_name_object = get_term_by( 'id', $v, $attribute_2, 'OBJECT' );
                                    $attr_name        = ( $attr_name_object == false ) ? '' : $attr_name_object->slug;
                                }
                            }

                            if( $attribute_2 == '_sale' ) {
                                $_POST['add_terms'][] = array( $attribute_2, $v, $operator_2, $attr_name, $type );
                            } else {
                                $_POST['terms'][] = array( $attribute_2, $v, $operator_2, $attr_name, $type );
                            }
                        }
                    }
                } else {
                    if( taxonomy_exists('pa_'.$attribute) ) {
                        $attribute = 'pa_'.$attribute;
                    }
                    $_POST['limits'][] = array( $attribute, $min, $max );
                }
            }
        }
        do_action('br_aapf_args_converter_after', $query);
        foreach(array('terms', 'add_terms', 'limits', 'price', 'price_ranges', '', '') as $post_field) {
            if( isset($_POST[$post_field]) && empty($_POST[$post_field]) ) {
                unset($_POST[$post_field]);
            }
        }
    }
}

function br_widget_is_hide( $attribute, $widget_is_hide = false ) {
    if ( $widget_is_hide ) {
        if ( ! empty( $_POST['terms'] ) ) {
            foreach ( $_POST['terms'] as $term ) {
                if ( $term[0] == $attribute ) {
                    return false;
                }
            }
        }
        if ( ! empty( $_POST['limits'] ) ) {
            foreach ( $_POST['limits'] as $a ) {
                if ( $a[0] == $attribute ) {
                    return false;
                }
            }
        }
        if ( $attribute == 'price' and ( ! empty( $_POST['price'] ) or ! empty( $_POST['price_ranges'] ) ) ) {
            return false;
        }
    }

    return $widget_is_hide;
}

if ( ! function_exists( 'br_aapf_get_styled' ) ) {
    function br_aapf_get_styled() {
        return array(
            "title"          => array(
                "name" => __('Widget Title', 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "label"          => array(
                "name" => __('Label(checkbox/radio)', 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selectbox"      => array(
                "name" => __("Drop-Down", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => false,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => false,
                    "theme"       => true,
                    "image"       => false,
                ),
            ),
            "slider_input"   => array(
                "name" => __("Slider Inputs", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description"    => array(
                "name" => __("Description Block", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_border"    => array(
                "name" => __("Description Block Border", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_title"    => array(
                "name" => __("Description Block Title", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "description_text"    => array(
                "name" => __("Description Block Text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area"    => array(
                "name" => __("Selected filters area text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_hover"    => array(
                "name" => __("Selected filters area mouse over the text", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => true,
                    "font_family" => true,
                    "font_size"   => true,
                    "item_size"   => false,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_block"    => array(
                "name" => __("Selected filters area link background", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
            "selected_area_border"    => array(
                "name" => __("Selected filters area link border", 'BeRocket_AJAX_domain'),
                "has"  => array(
                    "color"       => true,
                    "bold"        => false,
                    "font_family" => false,
                    "font_size"   => false,
                    "item_size"   => true,
                    "theme"       => false,
                    "image"       => false,
                ),
            ),
        );
    }
}

if ( ! function_exists( 'br_aapf_converter_styles' ) ) {
    function br_aapf_converter_styles( $user_options = array() ) {
        $converted_styles = $converted_classes = array();
        $styled           = br_aapf_get_styled();
        $included_fonts   = array();
        if ( ! empty($user_options) ) {
            foreach ( $user_options as $element => $style ) {
                if ( ! empty($styled[ $element ]['has']) ) {
                    foreach ( $styled[ $element ]['has'] as $style_name => $use ) {
                        if ( $use ) {
                            if( empty($converted_styles[ $element ]) ) {
                                $converted_styles[ $element ] = '';
                            }
                            if ( $style_name == 'color' && ! empty($style['color']) ) {
                                @ $converted_styles[ $element ] .= "color: #" . ltrim( $style['color'], '#' ) . ";";
                            }
                            if ( $style_name == 'bold' and ! empty($style['bold']) ) {
                                @ $converted_styles[ $element ] .= "font-weight: {$style['bold']};";
                            }
                            if ( $style_name == 'font_size' && ! empty($style['font_size']) ) {
                                @ $converted_styles[ $element ] .= "font-size: " . ( (float) $style['font_size'] ) . "px;";
                            }


                            if ( $style_name == 'theme' ) {
                                if ( empty($style['theme']) ) {
                                    $style['theme'] = 'default';
                                } else {
                                    @ $converted_classes[ $element ] .= " themed";
                                }
                                if( empty($converted_classes[ $element ]) ) {
                                    @ $converted_classes[ $element ] = " " . $style['theme'];
                                } else {
                                    @ $converted_classes[ $element ] .= " " . $style['theme'];
                                }
                            }

                            if ( $style_name == 'font_family' and $style['font_family'] ) {
                                @ $converted_styles[ $element ] .= "font-family: '" . $style['font_family'] . "';";
                                if ( ! in_array( $style['font_family'], $included_fonts ) ) {
                                    $included_fonts[] = $style['font_family'];

                                    $http = ( is_ssl() ? 'https' : 'http' );
                                    wp_register_style( "berocket_aapf_widget-{$element}-font", $http . '://fonts.googleapis.com/css?family=' . urlencode( $style['font_family'] ) );
                                    wp_enqueue_style( "berocket_aapf_widget-{$element}-font" );
                                }
                            }
                        }
                    }
                }
            }
        }

        return array( "style" => $converted_styles, "class" => $converted_classes );
    }
}
if( ! function_exists('berocket_reset_orderby_clauses_popularity') ) {
    function berocket_reset_orderby_clauses_popularity($args) {
        $args['orderby'] = '';
        return $args;
    }
}

if ( ! function_exists( 'g_fonts_list' ) ) {
    function g_fonts_list() {
        return array(
            "ABeeZee",
            "Abel",
            "Abril Fatface",
            "Aclonica",
            "Acme",
            "Actor",
            "Adamina",
            "Advent Pro",
            "Aguafina Script",
            "Akronim",
            "Aladin",
            "Aldrich",
            "Alef",
            "Alegreya",
            "Alegreya SC",
            "Alegreya Sans",
            "Alegreya Sans SC",
            "Alex Brush",
            "Alfa Slab One",
            "Alice",
            "Alike",
            "Alike Angular",
            "Allan",
            "Allerta",
            "Allerta Stencil",
            "Allura",
            "Almendra",
            "Almendra Display",
            "Almendra SC",
            "Amarante",
            "Amaranth",
            "Amatic SC",
            "Amethysta",
            "Amiri",
            "Anaheim",
            "Andada",
            "Andika",
            "Angkor",
            "Annie Use Your Telescope",
            "Anonymous Pro",
            "Antic",
            "Antic Didone",
            "Antic Slab",
            "Anton",
            "Arapey",
            "Arbutus",
            "Arbutus Slab",
            "Architects Daughter",
            "Archivo Black",
            "Archivo Narrow",
            "Arimo",
            "Arizonia",
            "Armata",
            "Artifika",
            "Arvo",
            "Asap",
            "Asset",
            "Astloch",
            "Asul",
            "Atomic Age",
            "Aubrey",
            "Audiowide",
            "Autour One",
            "Average",
            "Average Sans",
            "Averia Gruesa Libre",
            "Averia Libre",
            "Averia Sans Libre",
            "Averia Serif Libre",
            "Bad Script",
            "Balthazar",
            "Bangers",
            "Basic",
            "Battambang",
            "Baumans",
            "Bayon",
            "Belgrano",
            "Belleza",
            "BenchNine",
            "Bentham",
            "Berkshire Swash",
            "Bevan",
            "Bigelow Rules",
            "Bigshot One",
            "Bilbo",
            "Bilbo Swash Caps",
            "Bitter",
            "Black Ops One",
            "Bokor",
            "Bonbon",
            "Boogaloo",
            "Bowlby One",
            "Bowlby One SC",
            "Brawler",
            "Bree Serif",
            "Bubblegum Sans",
            "Bubbler One",
            "Buda",
            "Buenard",
            "Butcherman",
            "Butterfly Kids",
            "Cabin",
            "Cabin Condensed",
            "Cabin Sketch",
            "Caesar Dressing",
            "Cagliostro",
            "Calligraffitti",
            "Cambay",
            "Cambo",
            "Candal",
            "Cantarell",
            "Cantata One",
            "Cantora One",
            "Capriola",
            "Cardo",
            "Carme",
            "Carrois Gothic",
            "Carrois Gothic SC",
            "Carter One",
            "Caudex",
            "Cedarville Cursive",
            "Ceviche One",
            "Changa One",
            "Chango",
            "Chau Philomene One",
            "Chela One",
            "Chelsea Market",
            "Chenla",
            "Cherry Cream Soda",
            "Cherry Swash",
            "Chewy",
            "Chicle",
            "Chivo",
            "Cinzel",
            "Cinzel Decorative",
            "Clicker Script",
            "Coda",
            "Coda Caption",
            "Codystar",
            "Combo",
            "Comfortaa",
            "Coming Soon",
            "Concert One",
            "Condiment",
            "Content",
            "Contrail One",
            "Convergence",
            "Cookie",
            "Copse",
            "Corben",
            "Courgette",
            "Cousine",
            "Coustard",
            "Covered By Your Grace",
            "Crafty Girls",
            "Creepster",
            "Crete Round",
            "Crimson Text",
            "Croissant One",
            "Crushed",
            "Cuprum",
            "Cutive",
            "Cutive Mono",
            "Damion",
            "Dancing Script",
            "Dangrek",
            "Dawning of a New Day",
            "Days One",
            "Dekko",
            "Delius",
            "Delius Swash Caps",
            "Delius Unicase",
            "Della Respira",
            "Denk One",
            "Devonshire",
            "Dhurjati",
            "Didact Gothic",
            "Diplomata",
            "Diplomata SC",
            "Domine",
            "Donegal One",
            "Doppio One",
            "Dorsa",
            "Dosis",
            "Dr Sugiyama",
            "Droid Sans",
            "Droid Sans Mono",
            "Droid Serif",
            "Duru Sans",
            "Dynalight",
            "EB Garamond",
            "Eagle Lake",
            "Eater",
            "Economica",
            "Ek Mukta",
            "Electrolize",
            "Elsie",
            "Elsie Swash Caps",
            "Emblema One",
            "Emilys Candy",
            "Engagement",
            "Englebert",
            "Enriqueta",
            "Erica One",
            "Esteban",
            "Euphoria Script",
            "Ewert",
            "Exo",
            "Exo 2",
            "Expletus Sans",
            "Fanwood Text",
            "Fascinate",
            "Fascinate Inline",
            "Faster One",
            "Fasthand",
            "Fauna One",
            "Federant",
            "Federo",
            "Felipa",
            "Fenix",
            "Finger Paint",
            "Fira Mono",
            "Fira Sans",
            "Fjalla One",
            "Fjord One",
            "Flamenco",
            "Flavors",
            "Fondamento",
            "Fontdiner Swanky",
            "Forum",
            "Francois One",
            "Freckle Face",
            "Fredericka the Great",
            "Fredoka One",
            "Freehand",
            "Fresca",
            "Frijole",
            "Fruktur",
            "Fugaz One",
            "GFS Didot",
            "GFS Neohellenic",
            "Gabriela",
            "Gafata",
            "Galdeano",
            "Galindo",
            "Gentium Basic",
            "Gentium Book Basic",
            "Geo",
            "Geostar",
            "Geostar Fill",
            "Germania One",
            "Gidugu",
            "Gilda Display",
            "Give You Glory",
            "Glass Antiqua",
            "Glegoo",
            "Gloria Hallelujah",
            "Goblin One",
            "Gochi Hand",
            "Gorditas",
            "Goudy Bookletter 1911",
            "Graduate",
            "Grand Hotel",
            "Gravitas One",
            "Great Vibes",
            "Griffy",
            "Gruppo",
            "Gudea",
            "Gurajada",
            "Habibi",
            "Halant",
            "Hammersmith One",
            "Hanalei",
            "Hanalei Fill",
            "Handlee",
            "Hanuman",
            "Happy Monkey",
            "Headland One",
            "Henny Penny",
            "Herr Von Muellerhoff",
            "Hind",
            "Holtwood One SC",
            "Homemade Apple",
            "Homenaje",
            "IM Fell DW Pica",
            "IM Fell DW Pica SC",
            "IM Fell Double Pica",
            "IM Fell Double Pica SC",
            "IM Fell English",
            "IM Fell English SC",
            "IM Fell French Canon",
            "IM Fell French Canon SC",
            "IM Fell Great Primer",
            "IM Fell Great Primer SC",
            "Iceberg",
            "Iceland",
            "Imprima",
            "Inconsolata",
            "Inder",
            "Indie Flower",
            "Inika",
            "Irish Grover",
            "Istok Web",
            "Italiana",
            "Italianno",
            "Jacques Francois",
            "Jacques Francois Shadow",
            "Jim Nightshade",
            "Jockey One",
            "Jolly Lodger",
            "Josefin Sans",
            "Josefin Slab",
            "Joti One",
            "Judson",
            "Julee",
            "Julius Sans One",
            "Junge",
            "Jura",
            "Just Another Hand",
            "Just Me Again Down Here",
            "Kalam",
            "Kameron",
            "Kantumruy",
            "Karla",
            "Karma",
            "Kaushan Script",
            "Kavoon",
            "Kdam Thmor",
            "Keania One",
            "Kelly Slab",
            "Kenia",
            "Khand",
            "Khmer",
            "Khula",
            "Kite One",
            "Knewave",
            "Kotta One",
            "Koulen",
            "Kranky",
            "Kreon",
            "Kristi",
            "Krona One",
            "La Belle Aurore",
            "Laila",
            "Lakki Reddy",
            "Lancelot",
            "Lateef",
            "Lato",
            "League Script",
            "Leckerli One",
            "Ledger",
            "Lekton",
            "Lemon",
            "Libre Baskerville",
            "Life Savers",
            "Lilita One",
            "Lily Script One",
            "Limelight",
            "Linden Hill",
            "Lobster",
            "Lobster Two",
            "Londrina Outline",
            "Londrina Shadow",
            "Londrina Sketch",
            "Londrina Solid",
            "Lora",
            "Love Ya Like A Sister",
            "Loved by the King",
            "Lovers Quarrel",
            "Luckiest Guy",
            "Lusitana",
            "Lustria",
            "Macondo",
            "Macondo Swash Caps",
            "Magra",
            "Maiden Orange",
            "Mako",
            "Mallanna",
            "Mandali",
            "Marcellus",
            "Marcellus SC",
            "Marck Script",
            "Margarine",
            "Marko One",
            "Marmelad",
            "Martel Sans",
            "Marvel",
            "Mate",
            "Mate SC",
            "Maven Pro",
            "McLaren",
            "Meddon",
            "MedievalSharp",
            "Medula One",
            "Megrim",
            "Meie Script",
            "Merienda",
            "Merienda One",
            "Merriweather",
            "Merriweather Sans",
            "Metal",
            "Metal Mania",
            "Metamorphous",
            "Metrophobic",
            "Michroma",
            "Milonga",
            "Miltonian",
            "Miltonian Tattoo",
            "Miniver",
            "Miss Fajardose",
            "Modak",
            "Modern Antiqua",
            "Molengo",
            "Molle",
            "Monda",
            "Monofett",
            "Monoton",
            "Monsieur La Doulaise",
            "Montaga",
            "Montez",
            "Montserrat",
            "Montserrat Alternates",
            "Montserrat Subrayada",
            "Moul",
            "Moulpali",
            "Mountains of Christmas",
            "Mouse Memoirs",
            "Mr Bedfort",
            "Mr Dafoe",
            "Mr De Haviland",
            "Mrs Saint Delafield",
            "Mrs Sheppards",
            "Muli",
            "Mystery Quest",
            "NTR",
            "Neucha",
            "Neuton",
            "New Rocker",
            "News Cycle",
            "Niconne",
            "Nixie One",
            "Nobile",
            "Nokora",
            "Norican",
            "Nosifer",
            "Nothing You Could Do",
            "Noticia Text",
            "Noto Sans",
            "Noto Serif",
            "Nova Cut",
            "Nova Flat",
            "Nova Mono",
            "Nova Oval",
            "Nova Round",
            "Nova Script",
            "Nova Slim",
            "Nova Square",
            "Numans",
            "Nunito",
            "Odor Mean Chey",
            "Offside",
            "Old Standard TT",
            "Oldenburg",
            "Oleo Script",
            "Oleo Script Swash Caps",
            "Open Sans",
            "Open Sans Condensed",
            "Oranienbaum",
            "Orbitron",
            "Oregano",
            "Orienta",
            "Original Surfer",
            "Oswald",
            "Over the Rainbow",
            "Overlock",
            "Overlock SC",
            "Ovo",
            "Oxygen",
            "Oxygen Mono",
            "PT Mono",
            "PT Sans",
            "PT Sans Caption",
            "PT Sans Narrow",
            "PT Serif",
            "PT Serif Caption",
            "Pacifico",
            "Paprika",
            "Parisienne",
            "Passero One",
            "Passion One",
            "Pathway Gothic One",
            "Patrick Hand",
            "Patrick Hand SC",
            "Patua One",
            "Paytone One",
            "Peddana",
            "Peralta",
            "Permanent Marker",
            "Petit Formal Script",
            "Petrona",
            "Philosopher",
            "Piedra",
            "Pinyon Script",
            "Pirata One",
            "Plaster",
            "Play",
            "Playball",
            "Playfair Display",
            "Playfair Display SC",
            "Podkova",
            "Poiret One",
            "Poller One",
            "Poly",
            "Pompiere",
            "Pontano Sans",
            "Port Lligat Sans",
            "Port Lligat Slab",
            "Prata",
            "Preahvihear",
            "Press Start 2P",
            "Princess Sofia",
            "Prociono",
            "Prosto One",
            "Puritan",
            "Purple Purse",
            "Quando",
            "Quantico",
            "Quattrocento",
            "Quattrocento Sans",
            "Questrial",
            "Quicksand",
            "Quintessential",
            "Qwigley",
            "Racing Sans One",
            "Radley",
            "Rajdhani",
            "Raleway",
            "Raleway Dots",
            "Ramabhadra",
            "Ramaraja",
            "Rambla",
            "Rammetto One",
            "Ranchers",
            "Rancho",
            "Ranga",
            "Rationale",
            "Ravi Prakash",
            "Redressed",
            "Reenie Beanie",
            "Revalia",
            "Ribeye",
            "Ribeye Marrow",
            "Righteous",
            "Risque",
            "Roboto",
            "Roboto Condensed",
            "Roboto Slab",
            "Rochester",
            "Rock Salt",
            "Rokkitt",
            "Romanesco",
            "Ropa Sans",
            "Rosario",
            "Rosarivo",
            "Rouge Script",
            "Rozha One",
            "Rubik Mono One",
            "Rubik One",
            "Ruda",
            "Rufina",
            "Ruge Boogie",
            "Ruluko",
            "Rum Raisin",
            "Ruslan Display",
            "Russo One",
            "Ruthie",
            "Rye",
            "Sacramento",
            "Sail",
            "Salsa",
            "Sanchez",
            "Sancreek",
            "Sansita One",
            "Sarina",
            "Sarpanch",
            "Satisfy",
            "Scada",
            "Scheherazade",
            "Schoolbell",
            "Seaweed Script",
            "Sevillana",
            "Seymour One",
            "Shadows Into Light",
            "Shadows Into Light Two",
            "Shanti",
            "Share",
            "Share Tech",
            "Share Tech Mono",
            "Shojumaru",
            "Short Stack",
            "Siemreap",
            "Sigmar One",
            "Signika",
            "Signika Negative",
            "Simonetta",
            "Sintony",
            "Sirin Stencil",
            "Six Caps",
            "Skranji",
            "Slabo 13px",
            "Slabo 27px",
            "Slackey",
            "Smokum",
            "Smythe",
            "Sniglet",
            "Snippet",
            "Snowburst One",
            "Sofadi One",
            "Sofia",
            "Sonsie One",
            "Sorts Mill Goudy",
            "Source Code Pro",
            "Source Sans Pro",
            "Source Serif Pro",
            "Special Elite",
            "Spicy Rice",
            "Spinnaker",
            "Spirax",
            "Squada One",
            "Sree Krushnadevaraya",
            "Stalemate",
            "Stalinist One",
            "Stardos Stencil",
            "Stint Ultra Condensed",
            "Stint Ultra Expanded",
            "Stoke",
            "Strait",
            "Sue Ellen Francisco",
            "Sunshiney",
            "Supermercado One",
            "Suranna",
            "Suravaram",
            "Suwannaphum",
            "Swanky and Moo Moo",
            "Syncopate",
            "Tangerine",
            "Taprom",
            "Tauri",
            "Teko",
            "Telex",
            "Tenali Ramakrishna",
            "Tenor Sans",
            "Text Me One",
            "The Girl Next Door",
            "Tienne",
            "Timmana",
            "Tinos",
            "Titan One",
            "Titillium Web",
            "Trade Winds",
            "Trocchi",
            "Trochut",
            "Trykker",
            "Tulpen One",
            "Ubuntu",
            "Ubuntu Condensed",
            "Ubuntu Mono",
            "Ultra",
            "Uncial Antiqua",
            "Underdog",
            "Unica One",
            "UnifrakturCook",
            "UnifrakturMaguntia",
            "Unkempt",
            "Unlock",
            "Unna",
            "VT323",
            "Vampiro One",
            "Varela",
            "Varela Round",
            "Vast Shadow",
            "Vesper Libre",
            "Vibur",
            "Vidaloka",
            "Viga",
            "Voces",
            "Volkhov",
            "Vollkorn",
            "Voltaire",
            "Waiting for the Sunrise",
            "Wallpoet",
            "Walter Turncoat",
            "Warnes",
            "Wellfleet",
            "Wendy One",
            "Wire One",
            "Yanone Kaffeesatz",
            "Yellowtail",
            "Yeseva One",
            "Yesteryear",
            "Zeyada"
        );
    }
}

if ( ! function_exists( 'br_get_post_meta_price' ) ) {
    /**
     * Public function to get price of product
     *
     * @param int $object_id product id
     *
     * @return float product price
     */
    function br_get_post_meta_price( $object_id ) {
        global $wpdb;

        $meta_list = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT meta_value FROM {$wpdb->postmeta} 
                WHERE post_id = %d AND meta_key = '%s' 
                ORDER BY meta_id ASC LIMIT 1",
                $object_id,
                apply_filters('berocket_price_filter_meta_key', '_price', 'functions_1553')
            ), 
            ARRAY_A
        );

        return maybe_unserialize( $meta_list['meta_value'] );
    }
}

if ( ! function_exists( 'br_get_taxonomy_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function br_get_taxonomy_id( $taxonomy, $value, $field = 'slug', $return = 'term_id' ) {
        global $wpdb;

        if ( 'id' == $field ) {
            return $value;
        } elseif ( 'slug' == $field ) {
            $field = 't.slug';
            $value = sanitize_title( $value );
            if ( empty( $value ) ) {
                return false;
            }
        } elseif ( 'name' == $field ) {
            $value = wp_unslash( $value );
            $field = 't.name';
        } else {
            return false;
        }

        $term = $wpdb->get_row(
            $wpdb->prepare( "SELECT t.term_id, tt.term_taxonomy_id FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy}
                  AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = '%s' AND $field = %s LIMIT 1", $taxonomy, $value )
        );

        if ( ! $term )
            return false;

        $term = (array)$term;
        return $term[$return];
    }
}

if ( ! function_exists( 'br_get_sub_taxonomies' ) ) {
    /**
     * Public function to get sub categories from category
     *
     * @param string $field_value value for search
     * @param string $field_name by what field is search
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     *
     * @return string|array|o category
     */
    function br_get_sub_taxonomies( $taxonomy, $field_value, $field_name = 'slug', $args = array(), $return = 'term_id' ) {
        $defaults  = array( 'return' => 'string', 'include_parent' => false, 'max_depth' => 9 );
        $args      = wp_parse_args( $args, $defaults );
        $parent_id = 0;

        if ( $field_value ) {
            $parent_id = br_get_taxonomy_id( $taxonomy, $field_value, $field_name, $return );
        }

        $args['taxonomy_name'] = $taxonomy;
        $categories = br_get_cat_hierarchy( $args, $parent_id );

        if ( $args['include_parent'] ) {
            if ( $args['return'] == 'string' ) {
                if ( $parent_id ) {
                    if ( $categories ) $categories .= ",";
                    $categories .= $parent_id;
                }
            } elseif ( $args['return'] == 'array' ) {
                array_unshift( $cat_hierarchy, $parent_id );
            } elseif ( $args['return'] == 'hierarchy_objects' ) {
                $cat = br_get_category( $parent_id );
                $cat->depth = 0;
                $cat_hierarchy[ $parent_id ] = $cat;
                ksort( $cat_hierarchy );
            }
        }

        return $categories;
    }
}

if ( ! function_exists( 'br_get_category_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function br_get_category_id( $value, $field = 'slug', $return = 'term_id' ) {
        $term = br_get_cache( $value.$field.$return, 'br_get_category_id' );
        if( $term === false ) {
            $term = _br_get_category_id( $value, $field, $return );
            br_set_cache( $value.$field.$return, $term, 'br_get_category_id', BeRocket_AJAX_cache_expire );
        }
        return $term;
    }
}

if ( ! function_exists( '_br_get_category_id' ) ) {
    /**
     * Public function to get category id by $value in $field
     *
     * @param string $value value for search
     * @param string $field by what field is search
     *
     * @return int category id
     */
    function _br_get_category_id( $value, $field = 'slug', $return = 'term_id' ) {
        global $wpdb;

        if ( 'id' == $field ) {
            return $value;
        } elseif ( 'slug' == $field ) {
            $field = 't.slug';
            $value = sanitize_title( $value );
            if ( empty( $value ) ) {
                return false;
            }
        } elseif ( 'name' == $field ) {
            $value = wp_unslash( $value );
            $field = 't.name';
        } else {
            return false;
        }

        $term = $wpdb->get_row(
            $wpdb->prepare( "SELECT t.term_id, tt.term_taxonomy_id FROM {$wpdb->terms} AS t INNER JOIN {$wpdb->term_taxonomy}
                  AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_cat' AND $field = %s LIMIT 1", $value )
        );

        if ( ! $term )
            return false;

        $term = (array)$term;
        return $term[$return];
    }
}

if ( ! function_exists( 'br_get_category' ) ) {
    /**
     * Public function to get category by ID
     *
     * @param int $id category id
     *
     * @return object category
     */
    function br_get_category( $id ) {
        global $wpdb;

        if ( ! $id = (int) $id or ! $term = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->terms} WHERE term_id = %d", $id ) ) ) {
            return false;
        }

        return $term;
    }
}

if ( ! function_exists( 'br_get_sub_categories' ) ) {
    /**
     * Public function to get sub categories from category
     *
     * @param string $field_value value for search
     * @param string $field_name by what field is search
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     *
     * @return string|array|o category
     */
    function br_get_sub_categories( $field_value, $field_name = 'slug', $args = array(), $return = 'term_id' ) {
        $defaults  = array( 'return' => 'string', 'include_parent' => false, 'max_depth' => 9 );
        $args      = wp_parse_args( $args, $defaults );
        $parent_id = 0;

        if ( $field_value ) {
            $parent_id = br_get_category_id( $field_value, $field_name, $return );
        }

        $categories = br_get_cat_hierarchy( $args, $parent_id );

        if ( $args['include_parent'] ) {
            if ( $args['return'] == 'string' ) {
                if ( $parent_id ) {
                    if ( $categories ) $categories .= ",";
                    $categories .= $parent_id;
                }
            } elseif ( $args['return'] == 'array' ) {
                array_unshift( $cat_hierarchy, $parent_id );
            } elseif ( $args['return'] == 'hierarchy_objects' ) {
                $cat = br_get_category( $parent_id );
                $cat->depth = 0;
                $cat_hierarchy[ $parent_id ] = $cat;
                ksort( $cat_hierarchy );
            }
        }
        return $categories;
    }
}

if ( ! function_exists( 'br_wp_get_object_terms' ) ) {
    /**
     * Public function to get terms by id and taxonomy
     *
     * @param int $object_id category id
     * @param int $taxonomy category id
     *
     * @return array terms
     */
    function br_wp_get_object_terms( $object_id, $taxonomy, $args = array() ) {
        global $wpdb;

        if ( empty( $object_id ) || empty( $taxonomy ) )
            return array();

        $object_id = (int) $object_id;

        $terms = array();
        $fields = $args['fields'] ? $args['fields'] : 'all' ;

        $select_this = '';
        if ( 'all' == $fields ) {
            $select_this = 't.*, tt.*';
        } elseif ( 'ids' == $fields ) {
            $select_this = 't.term_id';
        } elseif ( 'names' == $fields ) {
            $select_this = 't.name';
        } elseif ( 'slugs' == $fields ) {
            $select_this = 't.slug';
        } elseif ( 'all_with_object_id' == $fields ) {
            $select_this = 't.*, tt.*, tr.object_id';
        }

        $query = $wpdb->prepare(
            "SELECT {$select_this} FROM {$wpdb->terms} AS t
            INNER JOIN {$wpdb->term_taxonomy} AS tt ON tt.term_id = t.term_id
            INNER JOIN {$wpdb->term_relationships} AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.taxonomy = %s AND tr.object_id = %d
            ORDER BY t.term_id ASC",
            $taxonomy,
            $object_id
        );

        if( BeRocket_AAPF::$debug_mode ) {
            $wpdb->show_errors();
            BeRocket_AAPF::$error_log['102_get_object_terms_SELECT'] = $query;
        }

        $objects = false;
        if ( 'all' == $fields || 'all_with_object_id' == $fields ) {
            $_terms = $wpdb->get_results( $query );
            if( BeRocket_AAPF::$debug_mode ) {
                BeRocket_AAPF::$error_log['000_select_status'][] = @ $wpdb->last_error;
            }
            foreach ( $_terms as $key => $term ) {
                $_terms[$key] = sanitize_term( $term, $taxonomy, 'raw' );
            }
            $terms = array_merge( $terms, $_terms );
            $objects = true;
        } elseif ( 'ids' == $fields || 'names' == $fields || 'slugs' == $fields ) {
            $_terms = $wpdb->get_col( $query );
            if( BeRocket_AAPF::$debug_mode ) {
                ob_start();
                if ( $wpdb->last_error ) {
                    $wpdb->print_error();
                }
                BeRocket_AAPF::$error_log['000_select_status'][] = ob_get_contents();
                ob_end_clean();
            }
            $_field = ( 'ids' == $fields ) ? 'term_id' : 'name';
            foreach ( $_terms as $key => $term ) {
                $_terms[$key] = sanitize_term_field( $_field, $term, $term, $taxonomy, 'raw' );
            }
            $terms = array_merge( $terms, $_terms );
        } elseif ( 'tt_ids' == $fields ) {
            $terms = $wpdb->get_col(
                "SELECT tr.term_taxonomy_id FROM {$wpdb->term_relationships} AS tr 
                INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
                WHERE tr.object_id IN ({$object_ids}) AND tt.taxonomy IN ({$taxonomies}) {$orderby} {$order}"
            );
            if( BeRocket_AAPF::$debug_mode ) {
                ob_start();
                if ( $wpdb->last_error ) {
                    $wpdb->print_error();
                }
                BeRocket_AAPF::$error_log['000_select_status'][] = ob_get_contents();
                ob_end_clean();
            }
            foreach ( $terms as $key => $tt_id ) {
                $terms[$key] = sanitize_term_field( 'term_taxonomy_id', $tt_id, 0, $taxonomy, 'raw' ); // 0 should be the term id, however is not needed when using raw context.
            }
        }

        if ( ! $terms ) {
            return array();
        } elseif ( $objects && 'all_with_object_id' !== $fields ) {
            $_tt_ids = array();
            $_terms = array();
            foreach ( $terms as $term ) {
                if ( in_array( $term->term_taxonomy_id, $_tt_ids ) ) {
                    continue;
                }

                $_tt_ids[] = $term->term_taxonomy_id;
                $_terms[] = $term;
            }
            $terms = $_terms;
        } elseif ( ! $objects ) {
            $terms = array_values( array_unique( $terms ) );
        }

        return $terms;
    }
}

if ( ! function_exists( 'br_get_cat_hierarchy' ) ) {
    /**
     * Public function to get terms by id and taxonomy
     *
     * @param array $args 'return' - type of return data, 
     * 'include_parent' = include parent to cate gories list, 'max_depth' - max depth of sub category
     * @param int $parent_id category id that will be used as parent
     * @param int $depth sub categories depth
     *
     * @return array terms
     */
    function br_get_cat_hierarchy( $args, $parent_id = 0, $depth = 0 ) {
        $cat_hierarchy = br_get_taxonomy_hierarchy(array(
            'taxonomy'  => 'product_cat',
            'parent'    => $parent_id,
            'depth'     => $depth
        ));

        return $cat_hierarchy;
    }
}

if ( ! function_exists( 'br_select_post_status' ) ) {
    /**
     * Public function to get string with possible post statuses for the mysql query
     *
     * @return array string
     */
    function br_select_post_status() {
        global $wpdb, $br_select_post_status;

        if ( $br_select_post_status ) {
            return $br_select_post_status;
        }

        if ( ! isset( $wpdb->posts ) ) return '1=1';

        if ( is_user_logged_in() ) {
            $br_select_post_status = "( {$wpdb->posts}.post_status='publish' OR {$wpdb->posts}.post_status='private' )";
        } else {
            $br_select_post_status = "{$wpdb->posts}.post_status='publish'";
        }

        return $br_select_post_status;
    }
}

if ( ! function_exists( 'br_where_search' ) ) {
    /**
     * Public function to get string with possible post statuses for the mysql query
     *
     * @return array string
     */
    function br_where_search( &$query = '' ) {
        $s = '';
        $has_new_function = class_exists('WC_Query') && method_exists('WC_Query', 'get_main_query') && method_exists('WC_Query', 'get_main_search_query_sql');
        if( $has_new_function ) {
            $WC_Query_get_main_query = WC_Query::get_main_query();
            $has_new_function = ! empty($WC_Query_get_main_query);
        }
        if( $has_new_function ) {
            $s = WC_Query::get_main_search_query_sql();

            if ( ! empty( $s ) ) {
                $s = ' AND ' . $s;

                if ( ! empty( $query ) ) {
                    $query['where'] .= $s;
                }
            }
        }

        return $s;
    }
}

if ( ! function_exists( 'br_filters_old_wc_compatible' ) ) {
    /**
     * Public function to get string with possible post statuses for the mysql query
     *
     * @return array string
     */
    function br_filters_old_wc_compatible( $query, $new = false ) {
        global $br_old_wp_query;
        if ( ! isset( $br_old_wp_query ) ) {
            if ( ! $new ) {
                $BeRocket_AAPF = BeRocket_AAPF::getInstance();
                $query      = $BeRocket_AAPF->apply_user_price( $query, true );
                $query      = $BeRocket_AAPF->apply_user_filters( $query, true );
                $query_vars = $query->query_vars;
            } else {
                $query_vars = array();
            }

            $query_vars[ 'posts__in' ] = apply_filters( 'bapf_loop_shop_post_in', array() );
            $br_old_wp_query           = $query_vars;
        }

        return $br_old_wp_query;
    }
}

if ( ! function_exists( 'br_filters_query' ) ) {
    function br_filters_query( $query, $for = 'price', $product_cat = null ) {
        global $wpdb, $wp_query;

        $old_join_posts = $old_query_vars = $old_tax_query = $old_meta_query = '';
        $has_new_function = method_exists('WC_Query', 'get_main_query') && method_exists('WC_Query', 'get_main_meta_query') && method_exists('WC_Query', 'get_main_tax_query');
        if( $has_new_function ) {
            $WC_Query_get_main_query = WC_Query::get_main_query();
            $has_new_function = ! empty($WC_Query_get_main_query);
        }
        if ( ! $has_new_function ) {
            $old_query_vars = br_filters_old_wc_compatible( $wp_query );
            $old_meta_query = ( empty( $old_query_vars[ 'meta_query' ] ) || ! is_array($old_query_vars[ 'meta_query' ]) ? array() : $old_query_vars[ 'meta_query' ] );
            $old_tax_query  = ( empty( $old_query_vars[ 'tax_query' ] ) || ! is_array($old_query_vars[ 'tax_query' ]) ? array() : $old_query_vars[ 'tax_query' ] );
        } else {
            $old_query_vars = br_filters_old_wc_compatible( $wp_query, true );
        }

        if ( ! empty( $old_query_vars[ 'posts__in' ] ) ) {
            $old_join_posts = " AND {$wpdb->posts}.ID IN (" . implode( ',', $old_query_vars[ 'posts__in' ] ) . ") ";
        }

        if ( $has_new_function ) {
            $tax_query = WC_Query::get_main_tax_query();
        } else {
            $tax_query = $old_tax_query;
        }

        if ( $has_new_function ) {
            $meta_query = WC_Query::get_main_meta_query();
        } else {
            $meta_query = $old_meta_query;
        }
        if( $for == 'price' ) {
            foreach($meta_query as $meta_query_key => $meta_query_val) {
                if( is_array($meta_query_val) ) {
                    if( isset($meta_query_val['key']) ) {
                        if( br_get_value_from_array($meta_query_val, 'key') == apply_filters('berocket_price_filter_meta_key', '_price', 'functions_2008') ) {
                            unset($meta_query[$meta_query_key]);
                        }
                    } else {
                        foreach($meta_query_val as $meta_query2_key => $meta_query2_val) {
                            if( is_array($meta_query2_val) && br_get_value_from_array($meta_query2_val, 'key') == apply_filters('berocket_price_filter_meta_key', '_price', 'functions_2013') ) {
                                unset($meta_query_val[$meta_query2_key]);
                            }
                        }
                        $meta_query[$meta_query_key] = $meta_query_val;
                    }
                }
            }
        }

        $queried_object = $wp_query->get_queried_object_id();
        if ( ! empty( $queried_object ) ) {
            $query_object = $wp_query->get_queried_object();
            if ( ! empty( $query_object->taxonomy ) && ! empty( $query_object->slug ) ) {
                $tax_query[ $query_object->taxonomy ] = array(
                    'taxonomy' => $query_object->taxonomy,
                    'terms'    => array( $query_object->slug ),
                    'field'    => 'slug',
                );
            }
        }
        if( ! empty($product_cat) ) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array($product_cat),
                'operator' => 'IN',
            );
        }
        $meta_query = new WP_Meta_Query( $meta_query );
        $tax_query  = new WP_Tax_Query( $tax_query );

        if ( $for == 'price' ) {
            foreach ( $meta_query->queries as $mkey => $mquery ) {
                if ( isset( $mquery[ 'key' ] ) and $mquery[ 'key' ] == apply_filters('berocket_price_filter_meta_key', '_price', 'functions_2047') ) {
                    unset( $meta_query->queries[ $mkey ] );
                }
            }
        }

        $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

        if( ! is_array($query) ) {
            $query = array('join' => '', 'where' => '');
        }

        // Generate query
        if( ! isset($query[ 'join' ]) ) {
            $query[ 'join' ] = '';
        }
        /*$query[ 'join' ] .= "
                    INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
                    INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
                    INNER JOIN {$wpdb->terms} AS terms USING( term_id )
                    ";*/
        $query[ 'join' ] .= $tax_query_sql[ 'join' ] . $meta_query_sql[ 'join' ];
        if( ! isset($query[ 'where' ]) ) {
            $query[ 'where' ] = '';
        }
        $query[ 'where' ]
            .= "
                    WHERE {$wpdb->posts}.post_type IN ( 'product' )
                    AND " . br_select_post_status() . "
                    " . $tax_query_sql[ 'where' ] . $meta_query_sql[ 'where' ] . "
                ";
        if ( defined( 'WCML_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
            $query[ 'join' ] = $query[ 'join' ] . " INNER JOIN {$wpdb->prefix}icl_translations as wpml_lang ON ( {$wpdb->posts}.ID = wpml_lang.element_id )";
            $query[ 'where' ] = $query[ 'where' ] . " AND wpml_lang.language_code = '" . ICL_LANGUAGE_CODE . "' AND wpml_lang.element_type = 'post_product'";
        }
        br_where_search( $query );
        if ( ! empty( $post__in ) ) {
            $query[ 'where' ] .= " AND {$wpdb->posts}.ID IN (\"" . implode( '","', $post__in ) . "\")";
        }
        if( $has_new_function ) {
            $author = $WC_Query_get_main_query->get('author');
            if( empty($author) ) {
                $author = false;
            }
            if( $author != false ) {
                $query['where'] .= " AND {$wpdb->posts}.post_author IN ({$author})";
            }
        }
        
        /*if( function_exists('wc_get_product_visibility_term_ids') ) {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();
            $query[ 'where' ] .= " AND ( {$wpdb->posts}.ID NOT IN (SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id='" . $product_visibility_term_ids[ 'exclude-from-catalog' ] . "') ) ";
        }*/

        $query[ 'where' ] .= $old_join_posts;
        //$query['group_by'] = "GROUP BY {$wpdb->posts}.ID";
        $query = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );

        return $query;
    }
}

if( ! function_exists('berocket_add_filter_to_link') ) {
    add_filter( 'berocket_add_filter_to_link', 'berocket_add_filter_to_link', 100, 2 );
    function berocket_add_filter_to_link( $current_url = false, $args = array() ) {
        $args = array_merge( array(
            'attribute'        => '',
            'values'           => array(),
            'operator'         => 'OR',
            'remove_attribute' => false,
            'slider'           => false
        ), $args );

        extract( $args );

        if ( ! is_array( $values ) ) {
            $values = array( $values );
        }

        $options = BeRocket_AAPF::get_aapf_option();

        if ( $current_url === false ) {
            $current_url = "//" . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
            $filters     = ( empty( $_GET[ 'filters' ] ) ? '' : $_GET[ 'filters' ] );
        } else {
            parse_str( parse_url( $current_url, PHP_URL_QUERY ), $filters );
            $filters = br_get_value_from_array( $filters, 'filters' );
        }

        $current_url = remove_query_arg( 'filters', $current_url );
        if ( strpos( $current_url, '?' ) === false ) {
            $url_string   = $current_url;
            $query_string = '';
        } else {
            list( $url_string, $query_string ) = explode( '?', $current_url );
        }

        list( $url_string, $query_string, $filters ) = apply_filters( 'berocket_add_filter_to_link_explode', array(
            $url_string,
            $query_string,
            $filters
        ), $current_url );

        if ( empty( $options[ 'seo_uri_decode' ] ) ) {
            $filters = urlencode( $filters );
            $filters = str_replace( '+', urlencode( '+' ), $filters );
            $filters = urldecode( $filters );
        }

        if ( substr( $attribute, 0, 3 ) == 'pa_' ) {
            $attribute = substr( $attribute, 3 );
        }
        $strip_symbols = apply_filters('brapf_TEMP_generate_url_strip_symbols', array('filters' => '|', 'before_val' => '[', 'after_val' => ']'));
        $regex = '#(([^'.preg_quote($strip_symbols['filters']).']+?)'.preg_quote($strip_symbols['before_val']).'(.+?)'.preg_quote($strip_symbols['after_val']).')'.preg_quote($strip_symbols['filters']).'#';
        
        if ( strpos( $strip_symbols['filters'] . $filters, $strip_symbols['filters'] . $attribute . $strip_symbols['before_val'] ) === false ) {
            $filters      = (( empty( $filters ) ? '' : $filters . $strip_symbols['filters'] ) . $attribute . $strip_symbols['before_val'] . implode( ( $slider ? '_' : ( $operator == 'OR' ? '-' : '+' ) ), $values ) . $strip_symbols['after_val']);
            preg_match_all( $regex, $filters.$strip_symbols['filters'], $matches );
            $filter_array = apply_filters('brapf_TEMP_generate_url_explode_filters', $matches[1], $filters);
        } else {
            preg_match_all( $regex, $filters.$strip_symbols['filters'], $matches );
            $filter_array = apply_filters('brapf_TEMP_generate_url_explode_filters', $matches[1], $filters);
            global $br_url_parser_middle_result;

            foreach ( $filter_array as $filter_str_i => $filter_str ) {
                if ( strpos( $filter_str, $attribute . $strip_symbols['before_val'] ) !== false ) {
                    $filter_str = str_replace(array($attribute.$strip_symbols['before_val'], $strip_symbols['after_val']), array('', ''), $filter_str);
                    if ( $slider ) {
                        $implode    = '_';
                        $filter_str = '';
                    } elseif ( $attribute == 'price' ) {
                        $implode    = '-';
                        $filter_str = '';
                    } elseif ( strpos( $filter_str, '+' ) !== false ) {
                        $implode = '+';
                    } elseif ( strpos( $filter_str, '-' ) !== false ) {
                        $implode = '-';
                    } elseif ( strpos( $filter_str, '_' ) !== false ) {
                        $implode    = ( $operator == 'OR' ? '-' : '+' );
                        $filter_str = '';
                    } else {
                        $implode = ( $operator == 'OR' ? '-' : '+' );
                    }

                    $filter_values = $br_url_parser_middle_result[$attribute];
                    if ( ! empty( $filter_str ) and ! $filter_values ) {
                        $filter_values = explode( $implode, $filter_str );
                    }

                    foreach ( $values as $value ) {
                        if ( ( $search_i = array_search( $value, $filter_values ) ) === false ) {
                            if ( $remove_attribute ) {
                                $filter_values = array( $value );
                            } else {
                                $filter_values[] = $value;
                            }
                        } else {
                            unset( $filter_values[ $search_i ] );
                        }
                    }

                    if ( count( $filter_values ) ) {
                        $filter_str                    = $attribute . $strip_symbols['before_val'] . implode( $implode, $filter_values ) . $strip_symbols['after_val'];
                        $filter_array[ $filter_str_i ] = $filter_str;
                    } else {
                        unset( $filter_array[ $filter_str_i ] );
                    }

                    break;
                }
            }
        }

        list( $filter_array, $strip_symbols['filters'] ) = apply_filters( 'berocket_add_filter_to_link_filters_str', array(
            $filter_array,
            $strip_symbols['filters']
        ) );

        $filters = implode( $strip_symbols['filters'], $filter_array );
        list( $url_string, $query_string, $filters ) = apply_filters( 'berocket_add_filter_to_link_implode', array(
            $url_string,
            $query_string,
            $filters
        ) );

        if ( ! empty( $query_string ) ) {
            $url_string .= '?' . $query_string;
        }

        if ( ! empty( $filters ) ) {
            $url_string = add_query_arg( 'filters', $filters, $url_string );
        }
        return $url_string;
    }
}

if( ! function_exists('berocket_filter_query_vars_hook') ) {
    function berocket_filter_query_vars_hook($query_vars) {
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $query_vars = $BeRocket_AAPF->woocommerce_filter_query_vars($query_vars);
        return $query_vars;
    }
}

if( ! function_exists('br_get_taxonomy_hierarchy') ) {
    function br_get_taxonomy_hierarchy($args = array()) {
        global $wpdb;
        $args = array_merge(array(
            'taxonomy' => 'product_cat',
            'return'   => 'taxonomy',
            'parent'   => 0,
            'depth'    => 0
        ), $args);
		$wpdb->query("SET SESSION group_concat_max_len = 1000000");
        $md5 = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MD5(GROUP_CONCAT(t.slug+t.term_id+tt.parent+tt.count+tt.term_taxonomy_id)) FROM $wpdb->terms AS t 
                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id 
                WHERE tt.taxonomy IN (%s)",
                $args['taxonomy']
            )
        );
        $md5 = apply_filters('BRaapf_cache_check_md5', $md5, 'br_get_taxonomy_hierarchy', $args);
        $hierarchy_data = get_option( apply_filters('br_aapf_md5_cache_text', 'br_get_taxonomy_hierarchy_'.$args['taxonomy']) );
        if( empty($hierarchy_data) || $hierarchy_data['md5'] != $md5 ) {
            $hierarchy = br_generate_taxonomy_hierarchy($args['taxonomy']);
            $hierarchy_data = array(
                'terms'     => $hierarchy,
                'hierarchy' => array(),
                'child'     => array(),
                'md5'       => $md5,
                'time'      => time()
            );
            foreach($hierarchy as $hierarchy_term) {
                $hierarchy_data['hierarchy'][$hierarchy_term->term_id] = array($hierarchy_term->term_id);
                foreach($hierarchy_term->child_list as $child_list_id => $child_list_array) {
                    $hierarchy_data['hierarchy'][$child_list_id] = array_merge(array($hierarchy_term->term_id), $child_list_array);
                }
                foreach($hierarchy_term->parent_list as $parent_list_id => $parent_list_array) {
                    $hierarchy_data['child'][$parent_list_id] = $parent_list_array;
                }
            }
            update_option( apply_filters('br_aapf_md5_cache_text', 'br_get_taxonomy_hierarchy_'.$args['taxonomy']), $hierarchy_data );
        }
        if( is_array($hierarchy_data) && isset($hierarchy_data[$args['return']]) ) {
            return $hierarchy_data[$args['return']];
        }
        if( $args['return'] == 'all' ) {
            return $hierarchy_data;
        }
        $terms = $hierarchy_data['terms'];
        if( $args['parent'] != 0 ) {
            if( isset($hierarchy_data['hierarchy'][$args['parent']]) && is_array($hierarchy_data['hierarchy'][$args['parent']]) && count($hierarchy_data['hierarchy'][$args['parent']]) ) {
                foreach($hierarchy_data['hierarchy'][$args['parent']] as $child_id) {
                    $terms = $terms[$child_id]->child;
                }
            }
        }
        if( $args['depth'] > 0 ) {
            foreach($terms as &$term) {
                foreach($term->child_list as $child_list_id => $child_list) {
                    if( count($child_list) == $args['depth'] ) {
                        $child = &$term;
                        $child2 = &$child;
                        foreach($child_list as $child_id) {
                            unset($child2);
                            $child2 = &$child;
                            unset($child);
                            $child = &$child2->child[$child_id];
                        }
                        unset($child2->child[$child_id]);
                    }
                    if( count($child_list) >= $args['depth'] ) {
                        unset($term->child_list[$child_list_id]);
                    }
                }
            }
            if( isset($term) ) {
                unset($term);
            }
        }
        return $terms;
    }
}

if( ! function_exists('br_generate_taxonomy_hierarchy') ) {
    function br_generate_taxonomy_hierarchy($taxonomy, $parent = 0) {
        $terms = get_terms( array(
            'taxonomy'      => $taxonomy,
            'hide_empty'    => false,
            'parent'        => $parent,
            'suppress_filter' => (function_exists('wpm_get_language') ? 0 : 1)
        ) );
        $result_terms = array();
        if( is_array($terms) ) {
            foreach($terms as $term) {
                $child_terms = br_generate_taxonomy_hierarchy($taxonomy, $term->term_id);
                $term->child = array();
                $term->child_list = array();
                $term->parent_list = array($term->term_id => array($term->term_id));
                if( ! empty($child_terms) && is_array($child_terms) && count($child_terms) ) {
                    foreach($child_terms as $child_term) {
                        $term->child[$child_term->term_id] = $child_term;
                        $term->child_list[$child_term->term_id] = array($child_term->term_id);
                        foreach($child_term->child_list as $child_list_id => $child_list_array) {
                            $term->child_list[$child_list_id] = array_merge(array($child_term->term_id), $child_list_array);
                        }
                        foreach($child_term->parent_list as $parent_list_id => $parent_list_array) {
                            $term->parent_list[$term->term_id] = array_merge(array($parent_list_id), $term->parent_list[$term->term_id]);
                            $term->parent_list[$parent_list_id] = $parent_list_array;
                        }
                    }
                }
                $result_terms[$term->term_id] = $term;
            }
        }
        return $result_terms;
    }
}

if( ! function_exists('br_generate_child_relation') ) {
    function br_generate_child_relation($taxonomy) {
        global $wpdb;
		$wpdb->query("SET SESSION group_concat_max_len = 1000000");
        $newmd5 = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MD5(GROUP_CONCAT(t.slug+t.term_id+tt.parent+tt.count)) FROM $wpdb->terms AS t 
                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id 
                WHERE tt.taxonomy IN (%s)",
                $taxonomy
            )
        );
        $newmd5 = apply_filters('BRaapf_cache_check_md5', $newmd5, 'br_generate_child_relation', $taxonomy);
        $md5 = get_option(apply_filters('br_aapf_md5_cache_text', 'br_generate_child_relation_'.$taxonomy));
        if($md5 != $newmd5) {
            $terms = get_terms( array(
                'taxonomy'      => $taxonomy,
                'hide_empty'    => false,
                'fields'        => 'ids',
                'suppress_filter' => (function_exists('wpm_get_language') ? 0 : 1)
            ) );
            foreach($terms as $term_id) {
                delete_metadata( 'berocket_term', $term_id, 'child' );
                add_metadata( 'berocket_term', $term_id, 'child', $term_id );
                $child = get_term_children( $term_id, $taxonomy );
                if( ! is_wp_error($child) && is_array($child) && count($child) ) {
                    foreach($child as $child_id) {
                        add_metadata( 'berocket_term', $term_id, 'child', $child_id );
                    }
                }
            }
            update_option(apply_filters('br_aapf_md5_cache_text', 'br_generate_child_relation_'.$taxonomy), $newmd5);
        }
    }
}

if ( ! function_exists('berocket_format_number') ) {
    function berocket_format_number( $number, &$format = false ) {
        if( ! isset($format) || ! is_array($format) ) {
            $format = array(
                'thousand_separate' =>wc_get_price_thousand_separator(), 
                'decimal_separate'  => wc_get_price_decimal_separator(), 
                'decimal_number'    => wc_get_price_decimals()
            );
        }
        return number_format( $number, $format['decimal_number'], $format['decimal_separate'], $format['thousand_separate']);
    }
}
if( ! function_exists('berocket_aapf_get_filter_types') ) {
    function berocket_aapf_get_filter_types ($type = 'widget') {
        $berocket_admin_filter_types = array(
            'tag' => array('checkbox','radio','select','color','image','tag_cloud'),
            'product_cat' => array('checkbox','radio','select','color','image'),
            'sale' => array('checkbox','radio','select'),
            'custom_taxonomy' => array('checkbox','radio','select','color','image'),
            'attribute' => array('checkbox','radio','select','color','image'),
            'price' => array('slider'),
            'filter_by' => array('checkbox','radio','select','color','image'),
        );
        $berocket_admin_filter_types_by_attr = array(
            'checkbox' => array('value' => 'checkbox', 'text' => 'Checkbox'),
            'radio' => array('value' => 'radio', 'text' => 'Radio'),
            'select' => array('value' => 'select', 'text' => 'Select'),
            'color' => array('value' => 'color', 'text' => 'Color'),
            'image' => array('value' => 'image', 'text' => 'Image'),
            'slider' => array('value' => 'slider', 'text' => 'Slider'),
            'tag_cloud' => array('value' => 'tag_cloud', 'text' => 'Tag cloud'),
        );
        return apply_filters( 'berocket_admin_filter_types_by_attr', array($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr), $type );
    }
}
if( ! function_exists('braapf_get_loader_element') ) {
    function braapf_get_loader_element() {
        $loader = array(
            'template' => array(
                'type'          => 'tag',
                'tag'           => 'div',
                'attributes'    => array(
                    'class'     => array(
                        'bapf_loader_page'
                    ),
                ),
                'content' => array(
                    'container' => array(
                        'type'          => 'tag',
                        'tag'           => 'div',
                        'attributes'    => array(
                            'class'     => array(
                                'bapf_lcontainer'
                            ),
                        ),
                        'content' => array(
                            'loader' => array(
                                'type'          => 'tag',
                                'tag'           => 'span',
                                'attributes'    => array(
                                    'class'     => array(
                                        'bapf_loader'
                                    ),
                                ),
                                'content' => array(
                                    'first' => array(
                                        'type'          => 'tag',
                                        'tag'           => 'span',
                                        'attributes'    => array(
                                            'class'     => array(
                                                'bapf_lfirst'
                                            ),
                                        ),
                                    ),
                                    'second' => array(
                                        'type'          => 'tag',
                                        'tag'           => 'span',
                                        'attributes'    => array(
                                            'class'     => array(
                                                'bapf_lsecond'
                                            ),
                                        ),
                                    ),
                                )
                            )
                        )
                    )
                )
            )
        );
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $options = $BeRocket_AAPF->get_option();
        if( ! empty($options['ajax_load_icon']) ) {
            $loader['template']['content']['container']['content']['loader'] = array(
                'type'          => 'tag_open',
                'tag'           => 'img',
                'attributes'    => array(
                    'class'     => array(
                        'bapf_limg'
                    ),
                    'src'       => $options['ajax_load_icon'],
                    'alt'       => __('Loading...', 'BeRocket_AJAX_domain')
                ),
            );
        }
        if( ! empty($options['ajax_load_text']) && is_array($options['ajax_load_text']) ) {
            if( ! empty($options['ajax_load_text']['top']) ) {
                $loader['template']['content']['container']['content']['text_above'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_labove'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['top'])
                );
            }
            if( ! empty($options['ajax_load_text']['bottom']) ) {
                $loader['template']['content']['container']['content']['text_below'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_lbelow'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['bottom'])
                );
            }
            if( ! empty($options['ajax_load_text']['left']) ) {
                $loader['template']['content']['container']['content']['text_before'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_lbefore'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['left'])
                );
            }
            if( ! empty($options['ajax_load_text']['right']) ) {
                $loader['template']['content']['container']['content']['text_after'] = array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'     => array(
                            'bapf_lafter'
                        ),
                    ),
                    'content' => array($options['ajax_load_text']['right'])
                );
            }
        }
        return BeRocket_AAPF_Template_Build($loader);
    }
}
if( ! function_exists('braapf_is_filters_displayed_debug') ) {
    function braapf_is_filters_displayed_debug($id, $type, $status, $message) {
        if( BeRocket_AAPF::$user_can_manage ) {
            $temp = BeRocket_AAPF::$current_page_filters;
            if( ! in_array($id, $temp['added']) ) {
                $temp['added'][] = $id;
                if( ! isset($temp[$type]) || ! is_array($temp[$type]) ) {
                    $temp[$type] = array();
                }
                if( ! isset($temp[$type][$status]) || ! is_array($temp[$type][$status]) ) {
                    $temp[$type][$status] = array();
                }
                $temp[$type][$status][$id] = $message;
            }
            BeRocket_AAPF::$current_page_filters = $temp;
        }
    }
}
