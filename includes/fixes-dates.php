<?php
/**
 * Fixes dates and convert them to Jalali date.
 *
 * @author              Mobin Ghasempoor
 * @package             WP-Parsidate
 * @subpackage          Fixes/Dates
 */

global $wpp_settings;

if (get_locale() == 'fa_IR' && $wpp_settings['persian_date'] != 'disable') {
    add_filter('the_time', 'wpp_fix_post_time', 10, 2);
    add_filter('the_date', 'wpp_fix_post_date', 10, 2);
    add_filter('get_comment_time', 'wpp_fix_comment_time', 10, 2);
    add_filter('get_comment_date', 'wpp_fix_comment_date', 10, 2);
    add_filter('get_post_modified_time', 'wpp_fix_post_date' , 10, 2 );

    add_action('date_i18n', 'wpp_fix_i18n', 10, 3);
}

/**
 * Fixes post date and returns in Jalali format
 *
 * @param           string $time Post time
 * @param           string $format Date format
 *
 * @return          string Formatted date
 */
function wpp_fix_post_date($time, $format = '')
{
    global $post, $wpp_settings;

    // It's seems some plugin like acf does not exits $post.
    if (empty($post)) {
        return $time;
    }

    if (empty($format)) {
        $format = get_option('date_format');
    }

    if(!disable_wpp())
    return date($format,strtotime($post->post_modified));

    return parsidate($format, $post->post_date, $wpp_settings['conv_dates'] == 'disable' ? 'eng' : 'per');
}

/**
 * Fixes post time and returns in Jalali format
 *
 * @param           string $time Post time
 * @param           string $format Date format
 *
 * @return          string Formatted date
 */
function wpp_fix_post_time($time, $format = '')
{
    global $post, $wpp_settings;

    if (empty($post)) {
        return $time;
    }

    if (empty($format)) {
        $format = get_option('time_format');
    }
    if(!disable_wpp())
    return date($format,strtotime($post->post_date));
    return parsidate($format, $post->post_date, $wpp_settings['conv_dates'] == 'disable' ? 'eng' : 'per');
}

/**
 * Fixes comment time and returns in Jalali format
 *
 * @param           string $time Comment time
 * @param           string $fomat Date format
 *
 * @return          string Formatted date
 */
function wpp_fix_comment_time($time, $format = '')
{
    global $comment, $wpp_settings;

    if (empty($comment)) {
        return $time;
    }

    if (empty($format)) {
        $format = get_option('time_format');
    }
    if(!disable_wpp())
    return date($format,strtotime($comment->comment_date));
    return parsidate($format, $comment->comment_date, $wpp_settings['conv_dates'] == 'disable' ? 'eng' : 'per');
}

/**
 * Fixes comment date and returns in Jalali format
 *
 * @param           string $time Comment time
 * @param           string $format Date format
 *
 * @return          string Formatted date
 */
function wpp_fix_comment_date($time, $format = '')
{
    global $comment, $wpp_settings;

    if (empty($comment)) {
        return $time;
    }

    if (empty($format)) {
        $format = get_option('date_format');
    }
    if(!disable_wpp())
    return date($format,strtotime($comment->comment_date));
    return parsidate($format, $comment->comment_date, $wpp_settings['conv_dates'] == 'disable' ? 'eng' : 'per');
}

/**
 * Fixes i18n date formatting and convert them to Jalali
 *
 * @param           string $format_string Date format
 * @param           string $timestamp Unix timestamp
 * @param           string $gmt GMT timestamp
 *
 * @return          string Formatted time
 */
function wpp_fix_i18n($format_string, $timestamp, $gmt)
{
    global $wpp_settings;
	global $post;
	$post_id = !empty($post) ? $post->ID : null;

    if(!disable_wpp())
        return $format_string;

	if( $post_id != null && get_post_type($post_id) == 'shop_order' ) // TODO: Remove after implement convert date for woocommerce
		return $format_string;
	else
		return parsidate($timestamp, $gmt, $wpp_settings['conv_dates'] == 'disable' ? 'eng' : 'per');
}

function array_key_exists_r($needle, $haystack, $value = null)
{
    $result = array_key_exists($needle, $haystack);
    if ($result) {
        if ($value != null && $haystack[$needle])
            return 1;
        return $result;
    }
    foreach ($haystack as $v) {
        if (is_array($v) || is_object($v))
            $result = array_key_exists_r($needle, $v);
        if ($result)
            return $result;
    }
    return $result;
}
