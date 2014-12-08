<?php
/**
* @package WordPress
* @subpackage Mondira
* @version 1.0
*/
/*
* Return panigation string
* 
* @param empty
* @return string
*/
if ( ! function_exists('mondira_pagination') ) {
	function mondira_pagination() {
		global $wp_query, $max_num_pages;
        $total = $wp_query->max_num_pages;
        $big = 999999999;  
        
        $paged = (get_query_var('page')) ? get_query_var('page') : (get_query_var('paged')) ? get_query_var('paged') : 1;   
        $pages = paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, $paged ),
            'total' => $total,
            'prev_next' => false,
            'type'  => 'array'
        ) );
        if( is_array( $pages ) ) {
            echo '<ul class="pagination">';
            if ( $paged > 1 ) {
                echo '<li>' . get_previous_posts_link(__('&#8592; Previous', "mondira")) . '</li>';
            }
            echo '<li class="disabled"><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
            foreach ( $pages as $page ) {
                    echo "<li>$page</li>";
            }
            echo '<li>' . get_next_posts_link(__('Next &#8594;', "mondira")) . '</li>';
           echo '</ul>';
        }
	}
}

/**
 * Link Pages
 * @author toscha
 * @link http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages
 * @param  array $args
 * @return void
 * Modification of wp_link_pages() with an extra element to highlight the current page.
 */
function bootstrap_link_pages( $args = array () ) {
    $defaults = array(
        'before'      => '<p>' . __('Pages:', 'mondira'),
        'after'       => '</p>',
        'before_link' => '',
        'after_link'  => '',
        'current_before' => '',
        'current_after' => '',
        'link_before' => '',
        'link_after'  => '',
        'pagelink'    => '%',
        'echo'        => 1
    );
 
    $r = wp_parse_args( $args, $defaults );
    $r = apply_filters( 'wp_link_pages_args', $r );
    extract( $r, EXTR_SKIP );
 
    global $page, $numpages, $multipage, $more, $pagenow;
 
    if ( ! $multipage )
    {
        return;
    }
 
    $output = $before;
 
    for ( $i = 1; $i < ( $numpages + 1 ); $i++ )
    {
        $j       = str_replace( '%', $i, $pagelink );
        $output .= ' ';
 
        if ( $i != $page || ( ! $more && 1 == $page ) )
        {
            $output .= "{$before_link}" . _wp_link_page( $i ) . "{$link_before}{$j}{$link_after}</a>{$after_link}";
        }
        else
        {
            $output .= "{$current_before}{$link_before}<a>{$j}</a>{$link_after}{$current_after}";
        }
    }
 
    print $output . $after;
}


/*
* Return current pagination paged number
* 
* @param empty
* @return int
*/
if ( !function_exists('pagination_paged')) {
    function pagination_paged() {
        global $paged;  
        
        if ( get_query_var('paged') )
            $paged = get_query_var('paged');
        elseif ( get_query_var('page') )
            $paged = get_query_var('page');
        elseif ( !empty($_GET['paged']) )
            $paged = $_GET['paged'];
        elseif ( !empty($_GET['page']) )
            $paged = $_GET['page'];
        else
            $paged = 1;
            
        return $paged;
    }
}

/*
* Return panigation string
* 
* @param empty
* @return string
*/
if ( !function_exists('mondira_pagejump') ) {
	function mondira_pagejump($pages = '', $range = 4) {
		 $showitems = ($range * 2)+1; 
		 global $paged;
		 if ( empty($paged) ) $paged = 1;
		 
		 if ( $pages == '' ) {
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if(!$pages) {
				 $pages = 1;
			 }
		 } 
		 if ( 1 != $pages ) {
			echo '<div class="post-navigation clr"><div class="alignleft">';
			previous_posts_link( '&larr; ' . __('Newer Posts', 'mondira' ) );
			echo '</div><div class="alignright">';
			next_posts_link( __('Older Posts', 'mondira' ) .' &rarr;' );
			echo '</div></div>';
		 }
	}
}


/*
*
* Return the of next_post_link WordPress default function.
* 
* @Author: Jewel Ahmed
* @Author Web: http://codeatomic.com
* @Last Updated: 09 Nov, 2014
*/
if ( !function_exists( 'mondira_get_next_post_link' ) ) {
	function mondira_get_next_post_link( $link, $title ) {
		ob_start();
		next_post_link( $link, $title );
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}

/*
*
* Return the of previous_post_link WordPress default function.
* 
* @Author: Jewel Ahmed
* @Author Web: http://codeatomic.com
* @Last Updated: 09 Nov, 2014
*/
if ( !function_exists( 'mondira_get_previous_post_link' ) ) {
	function mondira_get_previous_post_link( $link, $title ) {
		ob_start();
		previous_post_link( $link, $title );
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}

/*
*
* Return the of previous_posts_link WordPress default function.
* 
* @Author: Jewel Ahmed
* @Author Web: http://codeatomic.com
* @Last Updated: 09 Nov, 2014
*/
if ( !function_exists( 'mondira_get_previous_posts_link' ) ) {
	function mondira_get_previous_posts_link( $title ) {
		ob_start();
		previous_posts_link( $title );
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}

/*
*
* Return the of next_posts_link WordPress default function.
* 
* @Author: Jewel Ahmed
* @Author Web: http://codeatomic.com
* @Last Updated: 09 Nov, 2014
*/
if ( !function_exists( 'mondira_get_next_posts_link' ) ) {
	function mondira_get_next_posts_link( $title ) {
		ob_start();
		next_posts_link( $title );
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}

/*
*
* Return the of previous_comments_link WordPress default function.
* 
* @Author: Jewel Ahmed
* @Author Web: http://codeatomic.com
* @Last Updated: 09 Nov, 2014
*/
if ( !function_exists( 'mondira_get_previous_comments_link' ) ) {
	function mondira_get_previous_comments_link( $title ) {
		ob_start();
		previous_comments_link( $title );
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}

/*
*
* Return the of next_comments_link WordPress default function.
* 
* @Author: Jewel Ahmed
* @Author Web: http://codeatomic.com
* @Last Updated: 09 Nov, 2014
*/
if ( !function_exists( 'mondira_get_next_comments_link' ) ) {
	function mondira_get_next_comments_link( $title ) {
		ob_start();
		next_comments_link( $title );
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}