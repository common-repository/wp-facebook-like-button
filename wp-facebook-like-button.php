<?php 
/* 
Plugin Name: WP Facebook Like Button
Plugin URI: http://www.dolcebita.com/wordpress/facebook-like-button/
Description: The Like button enables users to make connections to your pages and share content back to their friends on Facebook with one click.
Version: 0.3
Author: Marcos Esperon
Author URI: http://www.dolcebita.com/
*/

/*  Copyright 2010  Marcos Esperon

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; 
*/

function fl_generate($url) {
	
  $layout = get_option('fl_layout');
  $show_faces = (get_option('fl_show_faces') == null) ? 'false' : 'true';
  $width = (get_option('fl_width') == null) ? '640' : get_option('fl_width');
  $height = (get_option('fl_height') == null) ? '' : get_option('fl_height');
  $action = (get_option('fl_action') == null) ? 'like' : get_option('fl_action');
  $font = (get_option('fl_font') == null) ? '' : get_option('fl_font');
  $colorscheme = (get_option('fl_colorscheme') == null) ? 'light' : get_option('fl_colorscheme');
  $locale = (get_option('fl_locale') == null) ? '' : get_option('fl_locale');
  $style = (get_option('fl_style') == null) ? '' : get_option('fl_style');
  
  $output = '<div id="fb-like" style="'.$style.'"><iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&amp;layout='.$layout.'&amp;show_faces='.$show_faces.'&amp;width='.$width.'&amp;action='.$action.'&amp;font='.$font.'&amp;colorscheme='.$colorscheme.'&amp;locale='.$locale.'" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:'.$width.'px; height:'.$height.'px"></iframe></div>';
  
  return $output;
  
}

function fl_generator($url) {
  echo fl_generate($url);
}

function fl_generator_box($content) {

    global $post;
    

    if (get_option('fl_display_post') == null && is_single()) {
        return $content;
    }
    
    if (get_option('fl_display_page') == null && is_page()) {
        return $content;
    }

    if (get_option('fl_display_front') == null && (is_home() || is_front_page())) {
        return $content;
    }
		
    $url = '';
    if (get_post_status($post->ID) == 'publish') {
        $url = get_permalink();
    }
    $box = fl_generate($url);
        
    if (get_option('fl_where') == 'beforeandafter') {
      return $box . $content . $box;
    } else if (get_option('fl_where') == 'before') {
      return $box . $content;
    } else {
      return $content . $box;
    }
		
}

function fl_remove_filter($content) {
	if (!is_feed()) {
    	remove_action('the_content', 'fl_generator_box');
	}
    return $content;
}

function fl_options_page() {
?>
    <div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Facebook Like Button</h2>
    <form method="post" action="options.php">
    <?php
        // New way of setting the fields, for WP 2.7 and newer
        if(function_exists('settings_fields')){
            settings_fields('fl-options');
        } else {
            wp_nonce_field('update-options');
            ?>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="fl_display_post,fl_display_page,fl_display_front,fl_where,fl_layout,fl_show_faces,fl_width,fl_height,fl_action,fl_font,fl_colorscheme,fl_locale,fl_style" />
            <?php
        }
    ?>
        <p><em>If you have any problem or suggestion please leave your comment at <a href="http://www.dolcebita.com/wordpress/facebook-like-button/">dolcebita.com</a> or visit <a href="http://developers.facebook.com/docs/reference/plugins/like">http://developers.facebook.com/docs/reference/plugins/like</a>.</em></p>
        <h3>Options</h3>
        <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
            <tr>
	            <tr>
	                <th scope="row">
	                    Display
	                </th>
	                <td>
                        <input type="checkbox" value="1" <?php if (get_option('fl_display_post') == '1') echo 'checked="checked"'; ?> name="fl_display_post" id="fl_display_post" group="fl_display" onblur="preview();"/>
                        <label for="fl_display_post">Display the URL on posts</label>
                        <br />
                        <input type="checkbox" value="1" <?php if (get_option('fl_display_page') == '1') echo 'checked="checked"'; ?> name="fl_display_page" id="fl_display_page" group="fl_display" onblur="preview();"/>
                        <label for="fl_display_page">Display the URL on pages</label>
                        <br />
                        <input type="checkbox" value="1" <?php if (get_option('fl_display_front') == '1') echo 'checked="checked"'; ?> name="fl_display_front" id="fl_display_front" group="fl_display" onblur="preview();"/>
                        <label for="fl_display_front">Display the URL on the front page (home)</label>
	                </td>
	            </tr>
                <th scope="row">
                    Position
                </th>
                <td>
                  <select name="fl_where" id="fl_where" onchange="preview();">
                    <option <?php if (get_option('fl_where') == 'before') echo 'selected="selected"'; ?> value="before">Before</option>
                    <option <?php if (get_option('fl_where') == 'after') echo 'selected="selected"'; ?> value="after">After</option>
                    <option <?php if (get_option('fl_where') == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter">Before and After</option>
                  </select>
                </td>
            </tr> 
            </tr>
                <th scope="row">
                    Layout Style
                </th>
                <td>
                  <select name="fl_layout" id="fl_layout" onchange="preview();">
                    <option <?php if (get_option('fl_layout') == 'standard') echo 'selected="selected"'; ?> value="standard">Standard</option>
                    <option <?php if (get_option('fl_layout') == 'button_count') echo 'selected="selected"'; ?> value="button_count">Button Count</option>                			
                  </select>
                </td>
            </tr>             
            <tr>
                <th scope="row">
                    Show Faces
                </th>
                <td>
                    <input type="checkbox" value="1" <?php if (get_option('fl_show_faces') == '1') echo 'checked="checked"'; ?> name="fl_show_faces" id="fl_show_faces" onclick="preview();" />                        
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Width
                </th>
                <td>
                    <input type="text" value="<?php echo get_option('fl_width'); ?>" name="fl_width" id="fl_width" size="5" onblur="preview();" /> px
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Height
                </th>
                <td>
                    <input type="text" value="<?php echo get_option('fl_height'); ?>" name="fl_height" id="fl_height" size="5" onblur="preview();" /> px
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Verb to Display
                </th>
                <td>                	
                  <select name="fl_action" id="fl_action" onchange="preview();">
                    <option <?php if (get_option('fl_action') == 'like') echo 'selected="selected"'; ?> value="like">Like</option>
                    <option <?php if (get_option('fl_action') == 'recommend') echo 'selected="selected"'; ?> value="recommend">Recommend</option>                			
                  </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Font
                </th>
                <td>
                  <select name="fl_font" id="fl_font" onchange="preview();">
                    <option <?php if (get_option('fl_font') == '') echo 'selected="selected"'; ?> value=""></option>
                    <option <?php if (get_option('fl_font') == 'arial') echo 'selected="selected"'; ?> value="arial">Arial</option>                			
                    <option <?php if (get_option('fl_font') == 'lucida grande') echo 'selected="selected"'; ?> value="lucida grande">Lucida Grande</option>
                    <option <?php if (get_option('fl_font') == 'segoe ui') echo 'selected="selected"'; ?> value="segoe ui">Segoe UI</option>                			
                    <option <?php if (get_option('fl_font') == 'tahoma') echo 'selected="selected"'; ?> value="tahoma">Tahoma</option>
                    <option <?php if (get_option('fl_font') == 'trebuchet+ms') echo 'selected="selected"'; ?> value="trebuchet+ms">Trebuchet MS</option>                			
                    <option <?php if (get_option('fl_font') == 'verdana') echo 'selected="selected"'; ?> value="verdana">Verdana</option>                			
                  </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Color Scheme
                </th>
                <td>
                  <select name="fl_colorscheme" id="fl_colorscheme" onchange="preview();">
                    <option <?php if (get_option('fl_colorscheme') == 'light') echo 'selected="selected"'; ?> value="light">Light</option>
                    <option <?php if (get_option('fl_colorscheme') == 'dark') echo 'selected="selected"'; ?> value="dark">Dark</option>                			
                  </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Locale
                </th>
                <td>
                	<input type="text" value="<?php echo get_option('fl_locale'); ?>" name="fl_locale" id="fl_locale" size="5" onblur="preview();" /> (en_US, es_ES, gl_ES, it_IT, fr_FR...)
                </td>
            </tr>
            <tr>
                <th scope="row">
                    CSS Style
                </th>
                <td>
                    <input type="text" value="<?php echo get_option('fl_style'); ?>" name="fl_style" id="fl_style" onblur="preview();" /> 
                    <span class="description">Add additional CSS style to the div.</span>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
    <h3>Preview</h3>
    <div id="fl_preview"></div>
    </div>
<?php
}

function fl_admin_head() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {		
	preview();
});

function preview() {
	var style	= jQuery('#fl_style').val();
	var layout	= jQuery('#fl_layout').val();
	var faces	= jQuery('#fl_show_faces').is(':checked') ? true : false;
	var width	= jQuery('#fl_width').val();
	var height	= jQuery('#fl_height').val();
	var verb	= jQuery('#fl_action').val();
	var font	= jQuery('#fl_font').val();
	var color	= jQuery('#fl_colorscheme').val();
  var locale	= jQuery('#fl_locale').val();
	
	var output	= '<div class="fl-like" style="'+ style +'"><iframe src="http://www.facebook.com/plugins/like.php?href=&amp;layout='+ layout +'&amp;show_faces='+ faces +'&amp;width='+ width +'&amp;action='+ verb +'&amp;font='+ font +'&amp;colorscheme='+ color +'&amp;locale='+ locale +'" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:'+ width +'px; height:'+ height +'px"></iframe></div>';
	
	jQuery('#fl_preview').html(output);
}
</script>
	
<?php }

function fl_init(){
    if(function_exists('register_setting')){
        register_setting('fl-options', 'fl_display_page');
        register_setting('fl-options', 'fl_display_post');
        register_setting('fl-options', 'fl_display_front');
        register_setting('fl-options', 'fl_where');  
        register_setting('fl-options', 'fl_layout');  
        register_setting('fl-options', 'fl_show_faces');  
        register_setting('fl-options', 'fl_width');  
        register_setting('fl-options', 'fl_height');  
        register_setting('fl-options', 'fl_action');  
        register_setting('fl-options', 'fl_font');  
        register_setting('fl-options', 'fl_colorscheme');
        register_setting('fl-options', 'fl_locale');
    }
}

if(is_admin()){
    add_action('admin_init', 'fl_init');
    add_action('admin_menu', 'fl_option_page');
	  add_action('admin_head', 'fl_admin_head');
}

// Set the default options when the plugin is activated
function fl_activate(){    
    add_option('fl_where', 'after');
    add_option('fl_layout', 'standard');
    add_option('fl_show_faces', '1');
    add_option('fl_width', '640');
    add_option('fl_height', '');
    add_option('fl_font', '');
    add_option('fl_colorscheme', 'light');
    add_option('fl_locale', 'en_US');
}

function fl_option_page() {
	add_options_page(__('Facebook Like Button', 'facebook-like-button'), 'Facebook Like Button', 8, basename(__FILE__), 'fl_options_page');
}

add_filter('the_content', 'fl_generator_box');
add_filter('get_the_excerpt', 'fl_remove_filter', 9);

register_activation_hook( __FILE__, 'fl_activate');

?>