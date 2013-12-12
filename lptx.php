<?php
/*
Plugin Name: Limit Title Characters
Plugin URI: http://github.com/willgladstone/limit-title-characters
Description: A modification of Jean-Philippe Murray's plugin 'Limit A Post Title by X Characters'. Limits any post type title length as defined in options. Shows the current character count and stops the publication process if the length goes over.
Version: 1.3.1.1
Author: Will Gladstone
Author URI: http://github.com/willgladstone/
*/

/*  Copyright 2012 Jean-Philippe Murray (email : himself@jpmurray.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// ------------------------------------------------------------------------
// PLUGIN PREFIX:
// ------------------------------------------------------------------------

// 'lptx_' prefix is derived from [l]imit a [p]ost [t]itle to [x] characters

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------
// HOOKS TO SETUP DEFAULT PLUGIN OPTIONS, HANDLE CLEAN-UP OF OPTIONS WHEN
// PLUGIN IS DEACTIVATED AND DELETED, INITIALISE PLUGIN, ADD OPTIONS PAGE.
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'lptx_add_defaults');
register_uninstall_hook(__FILE__, 'lptx_delete_plugin_options');
add_action('admin_init', 'lptx_init' );
add_action('admin_menu', 'lptx_add_options_page');
add_filter( 'plugin_action_links', 'lptx_plugin_action_links', 10, 2 );

$options = get_option('lptx_options');

// Set-up Action and Filter Hooks for the plugin itself
add_action('add_meta_boxes', 'lptx_box_characterCount');
add_action('admin_print_scripts', 'lptx_scriptsNl18n'); 	;

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('add_meta_boxes', 'lptx_box_characterCount');
// ------------------------------------------------------------------------------

function lptx_box_characterCount() {
	$options = get_option( 'lptx_options' );
	if ( $options['admin_disable'] == 1 ) {
		// if the plugin is activated, do stuff !
		if ( !current_user_can( 'administrator' ) ) {
			foreach ( $options['post_types'] as $post_type ) {
				add_meta_box('count-characters-in-title',__('Your title\'s character count:','lptx'), 'lptx_counter', $post_type, 'side', 'high');
			}
		}
	} else if ( $options['admin_disable'] == 2 ) {
		foreach ( $options['post_types'] as $post_type ) {
			add_meta_box('count-characters-in-title',__('Your title\'s character count:','lptx'), 'lptx_counter', $post_type, 'side', 'high');
		}
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('init', 'lptx_scriptsNl18n');
// ------------------------------------------------------------------------------
function lptx_scriptsNl18n() {
	$options = get_option( 'lptx_options' );
	$traduction = array( 'alertMessage' => __( 'You are over the maximum allowed characters for the title!','lptx' ) );

	$plugin_dir = basename( dirname( __FILE__ ) );
	load_plugin_textdomain( 'lptx', 'wp-content/plugins/'.$plugin_dir.'/languages', $plugin_dir.'/languages' );

	if ( $options['admin_disable']==1) {
		// if plugin is activated, do stuff !.
		if ( !current_user_can( 'administrator' ) ) {
			wp_enqueue_style('lptx_css',WP_PLUGIN_URL . '/limit-a-post-title-to-x-characters/css/lptx-style.css');
			wp_enqueue_script('lptx_js',WP_PLUGIN_URL . '/limit-a-post-title-to-x-characters/js/lptx-script.js',array('jquery'),'1.b',true );
			wp_localize_script('lptx_js', 'traductionFromWP', $traduction );
		}
	} else if ( $options['admin_disable'] == 2 ) {
		wp_enqueue_style('lptx_css',WP_PLUGIN_URL . '/limit-a-post-title-to-x-characters/css/lptx-style.css');
		wp_enqueue_script('lptx_js',WP_PLUGIN_URL . '/limit-a-post-title-to-x-characters/js/lptx-script.js',array('jquery'),'1.b',true );
		wp_localize_script('lptx_js', 'traductionFromWP', $traduction );
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_meta_box('count-characters-in-title',__('Title\'s character count','lptx'), 'lptx_counter', 'post', 'side', 'high');
// ------------------------------------------------------------------------------

function lptx_counter() {
	$options = get_option( 'lptx_options' ); ?>
	<div id="lptx-container">
		<input type="hidden" id="lptx_maximum" value="<?php echo $options['char_limit']; ?>"/>
		<div id="lptx-counter" class="post-title-count <?php echo lptx_returnClassMaximum( $options['char_limit'] ); ?>"><?php echo lptx_getTitleLength(); ?></div>
        <div id="lptx-counter-available"> <?php _e('of','lptx'); ?> <?php echo $options['char_limit']; ?></div>
		<div id="lptx-clear">
			<br /><br /><a id="empty-title" href="#"><?php _e( 'Clear the title field','lptx' ); ?></a>
		</div>
	</div>
	<?php
}

// ------------------------------------------------------------------------------
// FUNCTION FOR: lptx_counter();
// ------------------------------------------------------------------------------

function lptx_returnClassMaximum( $limit ) {
	global $post;
	$class = "";
	if ( strlen( $post->post_title ) > $limit ) :
		$class = "lptx-over";
	endif;
	return $class;
}

function lptx_getTitleLength() {
	global $post;
	return strlen( $post->post_title );
}

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'lptx_delete_plugin_options')
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function lptx_delete_plugin_options() {
	delete_option( 'lptx_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'lptx_add_defaults')
// ------------------------------------------------------------------------------

// Define default option settings
function lptx_add_defaults() {
	$tmp = get_option( 'lptx_options' );
    if ( ( $tmp['chk_default_options_db'] == '1' ) || ( !is_array( $tmp ) ) ) {
		delete_option( 'lptx_options' ); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"char_limit" => "144",
						"post_types" => array( 'post' ),
						"admin_disable" => "2",
						"chk_default_options_db" => ""
		);
		update_option( 'lptx_options', $arr );
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'lptx_init' )
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function lptx_init() {
	register_setting( 'lptx_plugin_options', 'lptx_options', 'lptx_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'lptx_add_options_page');
// ------------------------------------------------------------------------------

// Add menu page
function lptx_add_options_page() {
	add_options_page( __( 'Limit Title Characters Options Page','lptx' ), 'Limit Title Characters', 'manage_options', __FILE__, 'lptx_render_form' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------

// Render the Plugin options form
function lptx_render_form() { ?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Limit Title Characters</h2>
		<p><?php _e('Below are the optional setting that you can change to alter the default usage of the plugin.','lptx'); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields('lptx_plugin_options'); ?>
			<?php $options = get_option('lptx_options'); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Maximum allowed','lptx'); ?></th>
					<td>
						<input type="text" size="57" name="lptx_options[char_limit]" value="<?php echo $options['char_limit']; ?>" />
                        <br /><span style="color:#666666;margin-left:2px;"><?php _e('Enter the maximum number of character allowed in the title of a post.','lptx'); ?></span>
					</td>
				</tr>
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row"><?php _e('Post Types to Limit','lptx'); ?></th>
					<?php $post_types = get_post_types();?>
					<td>
						<select multiple name='lptx_options[post_types][]'>
							<?php foreach ( $post_types as $post_type ) : ?>
								<option value="<?php echo $post_type; ?>" <?php echo in_array( $post_type, $options['post_types'] ) ? 'selected' : ''; ?>><?php _e( $post_type, 'lptx'); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row"><?php _e('Disable limit for admins','lptx'); ?></th>
					<td>
						<select name='lptx_options[admin_disable]'>
							<option value='1' <?php selected('1', $options['admin_disable']); ?>><?php _e('Yes','lptx'); ?></option>
							<option value='2' <?php selected('2', $options['admin_disable']); ?>><?php _e('No','lptx'); ?></option>
						</select>
					</td>
				</tr>

				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row"><?php _e('Database Options','lptx'); ?></th>
					<td>
						<label><input name="lptx_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> <?php _e('Restore defaults upon plugin deactivation/reactivation','lptx'); ?></label>
						<br /><span style="color:#666666;margin-left:2px;"><?php _e('Only check this if you want to reset plugin settings upon Plugin reactivation','lptx'); ?></span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function lptx_validate_options( $input ) {
	return $input;
}

// Display a Settings link on the main Plugins page
function lptx_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$lptx_links = '<a href="'.get_admin_url().'options-general.php?page=limit-a-post-title-to-x-characters/lptx.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $lptx_links );
	}

	return $links;
}


