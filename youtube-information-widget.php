<?php

/*
Plugin Name: YouTube Information Widget
Plugin URI: https://wordpress.org/plugins/youtube-information-widget
Description: This plugin allows you to embed information about your YouTube channel, including the last uploads, popular uploads, channel statistics including subscribers count, views count, and the about information, and also, a subscribe button next to your channel icon. Also supports shortcodes letting you generate more than a shortcode.
Author: Samuel Elh
Version: 2.1
Author URI: http://go.elegance-style.com/sam
*/

// registers and creates a new widget for thi plugin
class liteyiw_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'liteyiw_widget', 
			__('YouTube Information Widget', 'wordpress'), 
			array( 'description' => __( 'Embed information about your YouTube channel', 'wordpress' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$product_id = apply_filters( 'widget_product_id', $instance['product_id'] );
		$max_vids = apply_filters( 'widget_max_vids', $instance['max_vids'] );
		$vid_h = apply_filters( 'widget_vid_h', $instance['vid_h'] );
		$vid_w = apply_filters( 'widget_vid_w', $instance['vid_w'] );
		$is_channel = apply_filters( 'widget_is_channel', $instance['is_channel'] );
		$is_channel_id = apply_filters( 'widget_is_channel_id', $instance['is_channel_id'] );
		$cc_period = apply_filters( 'widget_cc_period', $instance['cc_period'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		echo yiw_widget_content( $product_id, $max_vids, $vid_h, $vid_w, $is_channel_id, $cc_period );
		echo $args['after_widget'];
	}

	public function form( $instance ) {

		$title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
		$product_id = ( isset( $instance[ 'product_id' ] ) ) ? $instance[ 'product_id' ] : '';
		$max_vids = ( isset( $instance[ 'max_vids' ] ) ) ? $instance[ 'max_vids' ] : '';
		$vid_h = ( isset( $instance[ 'vid_h' ] ) ) ? $instance[ 'vid_h' ] : '';
		$vid_w = ( isset( $instance[ 'vid_w' ] ) ) ? $instance[ 'vid_w' ] : '';
		$is_channel = ( isset( $instance[ 'is_channel' ] ) ) ? $instance[ 'is_channel' ] : '';
		$is_channel_id = ( isset( $instance[ 'is_channel_id' ] ) ) ? $instance[ 'is_channel_id' ] : '';
		$cc_period = ( isset( $instance[ 'cc_period' ] ) ) ? $instance[ 'cc_period' ] : '';
		$cc_period_val = ( $cc_period !== '' && is_numeric( $cc_period ) ) ? esc_attr( $cc_period ) : '2';

		?>
			<style type="text/css">
				.yiw-help { display: none!important;padding: 1em; }
				.yiw-help-vis { display: block!important; }
			</style>
			<p>
				<span style="cursor: pointer;text-decoration: underline;" onclick="helpToggle(this)">Show help</span>
				<span> &vert; </span>
				<a href="options-general.php?page=yiw_gen" title="Generate a shortcode to use instead of this widget, or in addition to it" style="color: #444;"> Generate shortcode(s) </a>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>" style="font-weight:bold;"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat yiw-input" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'product_id' ); ?>" style="font-weight:bold;"><?php _e( 'Username / ID:' ); ?></label> 
				<sub class="yiw-help">Enter your YouTube channel username or ID.<br>Example: username: <code>mullenweg</code> (youtube.com/user/<b>mullenweg</b>).<br>Example channel ID: <code>UCF0pVplsI8R5kcAqgtoRqoA</code> (youtube.com/channel/<b>UCF0pVplsI8R5kcAqgtoRqoA</b>)</sub>
				<input class="widefat yiw-input" onchange="clearCache()" id="<?php echo $this->get_field_id( 'product_id' ); ?>" name="<?php echo $this->get_field_name( 'product_id' ); ?>" type="text" value="<?php echo esc_attr( $product_id ); ?>" />
				<br>
				<input class="widefat yiw-input" onchange="clearCache()" id="<?php echo $this->get_field_id( 'is_channel_id' ); ?>" name="<?php echo $this->get_field_name( 'is_channel_id' ); ?>" type="checkbox" <?php if ( esc_attr($is_channel_id) != '' ) echo "checked"; ?> />
				<label for="<?php echo $this->get_field_id( 'is_channel_id' ); ?>"><?php _e( 'This is a channel ID (not custom slug)' ); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'max_vids' ); ?>" style="font-weight:bold;"><?php _e( 'Max. videos:' ); ?></label> 
				<sub class="yiw-help">How many videos would you like to show in "last uploads" and "popular uploads" tabs? By default, <code>2</code> videos will show.</sub>
				<input class="widefat yiw-input" onchange="clearCache()" id="<?php echo $this->get_field_id( 'max_vids' ); ?>" name="<?php echo $this->get_field_name( 'max_vids' ); ?>" type="number" value="<?php echo esc_attr( $max_vids ); ?>" min="1" max="20" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'vid_h' ); ?>" style="font-weight:bold;"><?php _e( 'Video height:' ); ?></label>
				<sub class="yiw-help">Enter a height value for the videos shown in "last uploads" and "popular uploads" tabs. Example : <code>250</code> ( in pixels ). The default value is <code>auto</code>.</sub>
				<input class="widefat yiw-input" onchange="clearCache()" id="<?php echo $this->get_field_id( 'vid_h' ); ?>" name="<?php echo $this->get_field_name( 'vid_h' ); ?>" type="number" value="<?php echo esc_attr( $vid_h ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'vid_w' ); ?>" style="font-weight:bold;"><?php _e( 'Video width:' ); ?></label>
				<sub class="yiw-help">Enter a width value for the videos shown in "last uploads" and "popular uploads" tabs. Example : <code>400</code> ( in pixels ). The default value is <code>auto</code>.</sub>
				<input class="widefat yiw-input" onchange="clearCache()" id="<?php echo $this->get_field_id( 'vid_w' ); ?>" name="<?php echo $this->get_field_name( 'vid_w' ); ?>" type="number" value="<?php echo esc_attr( $vid_w ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'cc_period' ); ?>" style="font-weight:bold;display:block;"><?php _e( 'Clear Cache:' ); ?></label>
				<span>Clear cache every</span>
				<input class="yiw-input" id="<?php echo $this->get_field_id( 'cc_period' ); ?>" name="<?php echo $this->get_field_name( 'cc_period' ); ?>" type="number" value="<?php echo esc_attr( $cc_period_val ); ?>" max="96" min="1" />
				<span>hour(s)</span>
				<br />
				<a href="<?php echo home_url('/') . "wp-content/plugins/youtube-information-widget/includes/clear_cache.php"; ?>" target="_new" class="yiw-cc-alt" style="color: #444;">Clear cache now?</a>
				<span class="yiw-cc-msg"></span>
			</p>
			<p>&nbsp;</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['product_id'] = ( ! empty( $new_instance['product_id'] ) ) ? strip_tags( $new_instance['product_id'] ) : '';
		$instance['max_vids'] = ( ! empty( $new_instance['max_vids'] ) ) ? strip_tags( $new_instance['max_vids'] ) : '';
		$instance['vid_h'] = ( ! empty( $new_instance['vid_h'] ) ) ? strip_tags( $new_instance['vid_h'] ) : '';
		$instance['vid_w'] = ( ! empty( $new_instance['vid_w'] ) ) ? strip_tags( $new_instance['vid_w'] ) : '';
		$instance['is_channel'] = ( ! empty( $new_instance['is_channel'] ) ) ? strip_tags( $new_instance['is_channel'] ) : '';
		$instance['is_channel_id'] = ( ! empty( $new_instance['is_channel_id'] ) ) ? strip_tags( $new_instance['is_channel_id'] ) : '';
		$instance['cc_period'] = ( ! empty( $new_instance['cc_period'] ) ) ? strip_tags( $new_instance['cc_period'] ) : '';

		return $instance;
	}
}

// adds jQuery scripts to head section of admin area if the browsed area is widgets.php ( widgets dashboard )
$request_uri = $_SERVER["REQUEST_URI"];
$pos_check = strpos($request_uri, '/widgets.php');

if ( is_numeric( $pos_check ) ) {
	add_action('admin_enqueue_scripts', 'yiw_admin_widgets_jquery');
	add_action('admin_footer', 'yiw_helpToggle_js');
}
function yiw_admin_widgets_jquery() {

	wp_deregister_script('jquery');
	wp_register_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
	//wp_register_script('yiw-admin-cc', plugin_dir_url( __FILE__ ) . 'includes/admin_cc.js', false, null);
	wp_enqueue_script('jquery');
	//wp_enqueue_script("yiw-admin-cc");

}
function yiw_helpToggle_js() {
	?>
		<script type="text/javascript">
		// Copyright YouTube information Widget Plugin, by Samuel Elh ( sam.elegance-style.com/contact-me/ )
		// JavaScript toggle help
		function helpToggle(elem) {
		  if ( elem.innerHTML == "Show help" )
		  	elem.innerHTML = "Hide help";
		  else
		  	elem.innerHTML = "Show help";
		  var elem2 = document.getElementsByClassName('yiw-help');
		  elem2 = [].slice.call(elem2, 0);
		  for (var i = 0; i < elem2.length; ++i)
		    elem2[i].classList.toggle('yiw-help-vis');
		}
		// jQuery force cache update
		$(document).ready(function(){

			$('div[id*="_liteyiw_widget"] input[type="submit"]').click(function() {
				$('div[id*="_liteyiw_widget"] input[type="submit"]').prop("disabled", "disabled");
				$.ajax({
					url : "../wp-content/plugins/youtube-information-widget/includes/clear_cache.php",
					type : "post",
					success: function(){
						$('div[id*="_liteyiw_widget"] input[type="submit"]').prop("disabled", false);
					}
				})
			})
			$('a.yiw-cc-alt').click(function() {
				$('span.yiw-cc-msg').html('doing..');
				$.ajax({
				url : "../wp-content/plugins/youtube-information-widget/includes/clear_cache.php",
				type : "post",
					success: function(){
						$('span.yiw-cc-msg').html('done!');
					}
				})
				event.preventDefault();
			})
			
		})
		</script>
	<?php
}
// the main function used to load widget content
function yiw_widget_content ( $product_id, $max_vids, $vid_h, $vid_w, $is_channel_id, $cc_period ) {

	if ( $product_id == '' ) {
		echo "<p> YouTube Information Widget needs to be configured, kindly fill out a YouTube username or channel ID from your dashboard widgets area. </p>";
		return false;
	}

	$last_update = esc_attr(get_option( 'liteyiw_last_update' ));
	$op_1 = esc_attr(get_option( 'liteyiw_op_1' ));
	$op_2 = esc_attr(get_option( 'liteyiw_op_2' ));
	$op_3 = esc_attr(get_option( 'liteyiw_op_3' ));
	$op_4 = esc_attr(get_option( 'liteyiw_op_4' ));
	$op_5 = esc_attr(get_option( 'liteyiw_op_5' ));
	$op_6 = esc_attr(get_option( 'liteyiw_op_6' ));
	$op_7 = esc_attr(get_option( 'liteyiw_op_7' ));
	$force_update = esc_attr(get_option( 'liteyiw_force_update' ));
	$date1 = $last_update;
	$date2 = time();
	$subTime = $date1 - $date2;
	$m = ($subTime/60)%60;
	$h = ($subTime/(60*60))%24;
	$cc_period_val = ( $cc_period !== '' && is_numeric( $cc_period ) ) ? esc_attr( $cc_period ) : '2';
	// empties the existing cache, and gets fresh new one
	if ( $h >= $cc_period_val || $last_update == '' || $force_update == "1" ) {
		
		$max_vids_val = ( $max_vids == '' ) ? "2" : esc_attr( $max_vids );
		$vid_h_val = ( $vid_h == '' ) ? "auto" : esc_attr( $vid_h );
		$vid_w_val = ( $vid_w == '' ) ? "auto" : esc_attr( $vid_w );
		$key = "AIzaSyB9OPUPAtVh3_XqrByTwBTSDrNzuPZe8fo";
		$api_1_meta = ( $is_channel_id !== '' ) ? "&id=" . $product_id : "&forUsername=" . $product_id;
		$api_1 = "https://www.googleapis.com/youtube/v3/channels?part=snippet$api_1_meta&key=$key";
		$json_1 = file_get_contents($api_1);
		$json_data_1 = json_decode($json_1, false);
		$channel_id = $json_data_1->items[0]->id;
		$channel_name = esc_attr( $json_data_1->items[0]->snippet->title );
		$channel_thumb = esc_attr( $json_data_1->items[0]->snippet->thumbnails->high->url );
		$api_meta_all = "&id=$channel_id";
		$api_2 = "https://www.googleapis.com/youtube/v3/channels?part=statistics$api_meta_all&key=$key";
		$api_3 = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=$channel_id&maxResults=$max_vids_val&key=$key&type=video";
		$api_4 = "https://www.googleapis.com/youtube/v3/search?order=viewCount&part=snippet&channelId=$channel_id&maxResults=$max_vids_val&key=$key&type=video";
		$json_2 = file_get_contents($api_2);
		$json_data_2 = json_decode($json_2, false);
		$json_3 = file_get_contents($api_3);
		$json_data_3 = json_decode($json_3, false);
		$json_4 = file_get_contents($api_4);
		$json_data_4 = json_decode($json_4, false);
		$channel_link = "https://youtube.com/channel/$channel_id/";
		$subscribe_button = "
			<script src=\"https://apis.google.com/js/platform.js\"></script>
			<div class=\"g-ytsubscribe\" data-channelid=\"$channel_id\" data-layout=\"default\" data-count=\"default\">
				<a href=\"$channel_link?sub_confirmation=1\">subscribe</a>
			</div>
		";
		$last_uploads = "";
		$id_verify = '';
		foreach ( $json_data_3->items as $item ) {

			$id = $item->id->videoId;
			$id_verify .= $id;
			$last_uploads .=
			"<iframe id=\"ytplayer\" type=\"text/html\" width=\"$vid_w_val\" height=\"$vid_h_val\" src=\"//www.youtube.com/embed/$id?rel=0&showinfo=1\" frameborder=\"0\" allowfullscreen></iframe>
			<div style=\"height: .55em;\"></div>";

		}
		if ( $id_verify == '' )
			$last_uploads = "<p>Apologize, nothing found for this channel.</p>";
		else
			$last_uploads .= "<a href=\"" . $channel_link . "videos\" title=\"More uploads of $channel_name on YouTube\">Browse more &raquo;</a>";
		$popular_uploads = "";
		$id_verify = '';
		foreach ( $json_data_4->items as $item ) {

			$id = $item->id->videoId;
			$id_verify .= $id;
			$popular_uploads .=
			"<iframe id=\"ytplayer\" type=\"text/html\" width=\"$vid_w_val\" height=\"$vid_h_val\" src=\"//www.youtube.com/embed/$id?rel=0&showinfo=1\" frameborder=\"0\" allowfullscreen></iframe>
			<div style=\"height: .55em;\"></div>";

		}
		if ( $id_verify == '' )
			$popular_uploads = "<p>Apologize, nothing found for this channel.</p>";
		else
			$popular_uploads .= "<a href=\"" . $channel_link . "videos?sort=p&flow=grid&view=0\" title=\"More popular uploads of $channel_name on YouTube\">Browse more &raquo;</a>";
		$channel_info = '';
		$info_about = esc_attr( nl2br( $json_data_1->items[0]->snippet->description ) );
		$info_subs = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->subscriberCount ) );
		$info_vids = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->videoCount ) );
		$info_view = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->viewCount ) );
		$info_comment = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->commentCount ) );
		$channel_info .= ( $info_about != '' ) ? "<p><strong>About:</strong><br class=\"clear\"> $info_about </p>" : "";
		$channel_info .= ( $info_subs != '' ) ? "<p><strong>Total subscribers:</strong><br class=\"clear\"> $info_subs </p>" : "";
		$channel_info .= ( $info_vids != '' ) ? "<p><strong>Total uploads:</strong><br class=\"clear\"> $info_vids </p>" : "";
		$channel_info .= ( $info_view != '' ) ? "<p><strong>Total upload views:</strong><br class=\"clear\"> $info_view </p>" : "";
		$channel_info .= ( $info_comment != '' ) ? "<p><strong>Total comments:</strong><br class=\"clear\"> $info_comment </p>" : "";

		if ( $channel_info == '' )
			$channel_info = "<p>Apologize, nothing found for this channel.</p>";
		if ( $op_1 = '' )
			add_option( 'liteyiw_op_1', esc_attr( $channel_name ) );
		else
			update_option( 'liteyiw_op_1', esc_attr( $channel_name ) );
		if ( $op_2 = '' )
			add_option( 'liteyiw_op_2', esc_attr( $channel_link ) );
		else
			update_option( 'liteyiw_op_2', esc_attr( $channel_link ) );
		if ( $op_3 = '' )
			add_option('liteyiw_op_3', esc_attr( $channel_thumb ));
		else
			update_option( 'liteyiw_op_3', esc_attr( $channel_thumb ) );
		if ( $op_4 = '' )
			update_option( 'liteyiw_op_4', esc_attr( $subscribe_button ) );
		else
			update_option( 'liteyiw_op_4', esc_attr( $subscribe_button ) );
		if ( $op_5 = '' )
			add_option( 'liteyiw_op_5', esc_attr( $last_uploads ) );
		else
			update_option( 'liteyiw_op_5', esc_attr( $last_uploads ) );
		if ( $op_6 = '' )
			add_option( 'liteyiw_op_6', esc_attr( $popular_uploads) );
		else
			update_option( 'liteyiw_op_6', esc_attr( $popular_uploads) );
		if ( $op_7 = '' )
			add_option( 'liteyiw_op_7', esc_attr( $channel_info) );
		else
			update_option( 'liteyiw_op_7', esc_attr( $channel_info ) );
		if ( $last_update = '' )
			add_option( 'liteyiw_last_update', esc_attr( time() ) );
		else
			update_option( 'liteyiw_last_update', esc_attr( time() ) );
		if ( $force_update = '' )
			add_option( 'liteyiw_force_update', '0' );
		else
			update_option( 'liteyiw_force_update', '0' );
		// using the new cached data without the need to get them from database
		$op_1 = $channel_name;
		$op_2 = $channel_link;
		$op_3 = $channel_thumb;
		$op_4 = $subscribe_button;
		$op_5 = $last_uploads;
		$op_6 = $popular_uploads;
		$op_7 = $channel_info;

	}
	// if cache clearing isn't needed, output the existing cache
	?>
		<?php $rndm = rand("1", "999"); ?>
		<div id="ytio-container">
			<div id="ytio-avatar">
				<div id="ytio-left" class="inline">
					<a href="<?php echo html_entity_decode( $op_2 ); ?>" title="<?php echo html_entity_decode( $op_1 ); ?>">
						<img src="<?php echo html_entity_decode( $op_3 ); ?>" height="90" width="90" alt="<?php echo html_entity_decode( $op_1 ); ?>" />
					</a>
				</div>
				<div id="ytio-right" class="inline">
					<a href="<?php echo html_entity_decode( $op_2 ); ?>">
						<span><?php echo html_entity_decode( $op_1 ); ?></span>
					</a><br class="clear" />
					<?php echo html_entity_decode( $op_4 ); ?>
				</div>
			</div>

			<div id="ytio-uploads">
				<div id="ytio-switch">
					<span id="sw-st-<?php echo $rndm; ?>"  onclick="this.setAttribute('class','active'),document.getElementById('sw-nd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('sw-rd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-last-uploads-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-popular-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid'),document.getElementById('ytio-stats-<?php echo $rndm; ?>').setAttribute('class','ytio-hid');" class="active">Last uploads</span>
					<span id="sw-nd-<?php echo $rndm; ?>" onclick="this.setAttribute('class','active'),document.getElementById('sw-st-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('sw-rd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-popular-uploads-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-last-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid'),document.getElementById('ytio-stats-<?php echo $rndm; ?>').setAttribute('class','ytio-hid');">Popular uploads</span>
					<span id="sw-rd-<?php echo $rndm; ?>" onclick="this.setAttribute('class','active'),document.getElementById('sw-st-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('sw-nd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-stats-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-last-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid'),document.getElementById('ytio-popular-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid');">Info</span>
				</div>
				<div style="padding: 1em;">
					<div id="ytio-last-uploads-<?php echo $rndm; ?>">
						<?php echo html_entity_decode( $op_5 ); ?>
					</div>
					<div id="ytio-popular-uploads-<?php echo $rndm; ?>" class="ytio-hid">
						<?php echo html_entity_decode( $op_6 ); ?>
					</div>
					<div id="ytio-stats-<?php echo $rndm; ?>" class="ytio-hid">
						<?php echo html_entity_decode( $op_7 ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php

}

// little function to return short numbers, like 1.4k instead of 1400
function yiw_pretty_num ( $num ) {

	if(empty ($num) ) {
			return false;
	} else {
		if( $num < 1000 )
			return $num;
		$x = round($num);
		$x_number_format = number_format($x);
		$x_array = explode(',', $x_number_format);
		$x_parts = array(' thousand', ' million', ' billion', ' trillion');
		$x_count_parts = count($x_array) - 1;
		$x_display = $x;
		$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
		$x_display .= $x_parts[$x_count_parts - 1];
		return $x_display;
	}

}

function yiw_load_widget() {
	register_widget( 'liteyiw_widget' );
}
add_action( 'widgets_init', 'yiw_load_widget' );

// puts the scripts (JS and CSS) in the head section of your blog if widget is active
if ( is_active_widget( false, false, 'liteyiw_widget', true ) ) {
	//
}
add_action( 'wp_enqueue_scripts', 'yiw_enqueue_scripts' );

function yiw_enqueue_scripts() {
	//wp_register_script("yiw-tab-toggle", plugin_dir_url( __FILE__ ) . "includes/tab-toggle.js");
	//wp_enqueue_script("yiw-tab-toggle");
	wp_enqueue_style('ytio-css', plugin_dir_url( __FILE__ ) . 'includes/style.css' );
}

// adds Settings and Donate links in the plugin's snippet in admin plugins list (plugins.php) 
function yiw_support_link( $links ) {
    $settings_link = '<a href="http://wordpress.org/support/plugin/youtube-information-widget" target="_new">' . __( 'Support' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
function yiw_donate_link( $links ) {
    $settings_link = '<a href="http://go.elegance-style.com/donate/" target="_new">' . __( 'Donate' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'yiw_support_link' );
add_filter( "plugin_action_links_$plugin", 'yiw_donate_link' );



function yiw_widget_shortcode( $atts ) {
	
	$a = shortcode_atts( array(
        'channel' => 'channel',
        'id' => 'id',
        'max' => '',
        'height' => '',
        'width' => '',
        'cache' => 'cache'
    ), $atts );

	$product_id = esc_attr( "{$a['channel']}" );
	$max_vids = ( "{$a['max']}" !== '' && is_numeric( "{$a['max']}" ) ) ? esc_attr( "{$a['max']}" ) : '2';
	$vid_h = ( "{$a['height']}" !== '' && is_numeric( "{$a['height']}" ) ) ? esc_attr( "{$a['height']}" ) : 'auto';
	$vid_w = ( "{$a['width']}" !== '' && is_numeric( "{$a['width']}" ) ) ? esc_attr( "{$a['width']}" ) : 'auto';
	$cc_period = ( "{$a['cache']}" !== '' && is_numeric( "{$a['cache']}" ) ) ? esc_attr( "{$a['cache']}" ) : '2';
	$is_channel_id = esc_attr( "{$a['id']}" );
	$key = "AIzaSyB9OPUPAtVh3_XqrByTwBTSDrNzuPZe8fo";
	$api_1_meta = ( $is_channel_id !== 'id' ) ? "&id=" . $product_id : "&forUsername=" . $product_id;
	$api_1 = "https://www.googleapis.com/youtube/v3/channels?part=snippet$api_1_meta&key=$key";
	$json_1 = file_get_contents($api_1);
	$json_data_1 = json_decode($json_1, false);
	$channel_id = esc_attr( $json_data_1->items[0]->id );
	$rand = "shortcode_" . $channel_id . "_meta_" . $max_vids . "_" . $cc_period;
	//$all = "channel: $product_id || max: $max_vids || height: $vid_h || width: $vid_w || periode of caching: $cc_period || random num: $rand || id or not: $is_channel_id";
	if ( $product_id == 'channel' || $rand == '' ) {
		echo "<p> YouTube Information Widget needs to be configured, kindly fill out a YouTube username or channel ID from your dashboard widgets area. </p>";
		return false;
	}
	$last_update = esc_attr(get_option( "yiw_last_update_$rand" ));
	$op_1 = esc_attr(get_option( "yiw_op_1_$rand" ));
	$op_2 = esc_attr(get_option( "yiw_op_2_$rand" ));
	$op_3 = esc_attr(get_option( "yiw_op_3_$rand" ));
	$op_4 = esc_attr(get_option( "yiw_op_4_$rand" ));
	$op_5 = esc_attr(get_option( "yiw_op_5_$rand" ));
	$op_6 = esc_attr(get_option( "yiw_op_6_$rand" ));
	$op_7 = esc_attr(get_option( "yiw_op_7_$rand" ));
	$force_update = esc_attr(get_option( "yiw_force_update_$rand" ));
	$date1 = ($last_update);
	$date2 = time();
	$subTime = $date1 - $date2;
	$m = str_replace("-", "", ($subTime/60)%60 );
	$h = str_replace("-", "", ($subTime/(60*60))%24 );
	$cc_period_val = ( $cc_period !== '' && is_numeric( $cc_period ) ) ? esc_attr( $cc_period ) : '2';
	// empties the existing cache, and gets fresh new one
	if ( $h >= $cc_period_val || $last_update == '' || $force_update == "1" ) {
		
		$max_vids_val = ( $max_vids == '' && !is_numeric( $max_vids ) ) ? "2" : esc_attr( $max_vids );
		$vid_h_val = ( $vid_h == '' ) ? "auto" : esc_attr( $vid_h );
		$vid_w_val = ( $vid_w == '' ) ? "auto" : esc_attr( $vid_w );
		$channel_name = esc_attr( $json_data_1->items[0]->snippet->title );
		$channel_thumb = esc_attr( $json_data_1->items[0]->snippet->thumbnails->high->url );
		$api_meta_all = "&id=$channel_id";
		$api_2 = "https://www.googleapis.com/youtube/v3/channels?part=statistics$api_meta_all&key=$key";
		$api_3 = "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=$channel_id&maxResults=$max_vids_val&key=$key&type=video";
		$api_4 = "https://www.googleapis.com/youtube/v3/search?order=viewCount&part=snippet&channelId=$channel_id&maxResults=$max_vids_val&key=$key&type=video";
		$json_2 = file_get_contents($api_2);
		$json_data_2 = json_decode($json_2, false);
		$json_3 = file_get_contents($api_3);
		$json_data_3 = json_decode($json_3, false);
		$json_4 = file_get_contents($api_4);
		$json_data_4 = json_decode($json_4, false);
		$channel_link = "https://youtube.com/channel/$channel_id/";
		$subscribe_button = "
			<script src=\"https://apis.google.com/js/platform.js\"></script>
			<div class=\"g-ytsubscribe\" data-channelid=\"$channel_id\" data-layout=\"default\" data-count=\"default\">
				<a href=\"$channel_link?sub_confirmation=1\">subscribe</a>
			</div>
		";
		$last_uploads = "";
		$id_verify = '';
		foreach ( $json_data_3->items as $item ) {

			$id = $item->id->videoId;
			$id_verify .= $id;
			$last_uploads .=
			"<iframe id=\"ytplayer\" type=\"text/html\" width=\"$vid_w_val\" height=\"$vid_h_val\" src=\"//www.youtube.com/embed/$id?rel=0&showinfo=1\" frameborder=\"0\" allowfullscreen></iframe>
			<div style=\"height: .55em;\"></div>";

		}
		if ( $id_verify == '' )
			$last_uploads = "<p>Apologize, nothing found for this channel.</p>";
		else
			$last_uploads .= "<a href=\"" . $channel_link . "videos\" title=\"More uploads of $channel_name on YouTube\">Browse more &raquo;</a>";
		$popular_uploads = "";
		$id_verify = '';
		foreach ( $json_data_4->items as $item ) {

			$id = $item->id->videoId;
			$id_verify .= $id;
			$popular_uploads .=
			"<iframe id=\"ytplayer\" type=\"text/html\" width=\"$vid_w_val\" height=\"$vid_h_val\" src=\"//www.youtube.com/embed/$id?rel=0&showinfo=1\" frameborder=\"0\" allowfullscreen></iframe>
			<div style=\"height: .55em;\"></div>";

		}
		if ( $id_verify == '' )
			$popular_uploads = "<p>Apologize, nothing found for this channel.</p>";
		else
			$popular_uploads .= "<a href=\"" . $channel_link . "videos?sort=p&flow=grid&view=0\" title=\"More popular uploads of $channel_name on YouTube\">Browse more &raquo;</a>";
		$channel_info = '';
		$info_about = esc_attr( nl2br( $json_data_1->items[0]->snippet->description ) );
		$info_subs = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->subscriberCount ) );
		$info_vids = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->videoCount ) );
		$info_view = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->viewCount ) );
		$info_comment = esc_attr( yiw_pretty_num( $json_data_2->items[0]->statistics->commentCount ) );
		$channel_info .= ( $info_about != '' ) ? "<p><strong>About:</strong><br class=\"clear\"> $info_about </p>" : "";
		$channel_info .= ( $info_subs != '' ) ? "<p><strong>Total subscribers:</strong><br class=\"clear\"> $info_subs </p>" : "";
		$channel_info .= ( $info_vids != '' ) ? "<p><strong>Total uploads:</strong><br class=\"clear\"> $info_vids </p>" : "";
		$channel_info .= ( $info_view != '' ) ? "<p><strong>Total upload views:</strong><br class=\"clear\"> $info_view </p>" : "";
		$channel_info .= ( $info_comment != '' ) ? "<p><strong>Total comments:</strong><br class=\"clear\"> $info_comment </p>" : "";

		if ( $channel_info == '' )
			$channel_info = "<p>Apologize, nothing found for this channel.</p>";
		if ( $op_1 = '' )
			add_option( "yiw_op_1_$rand", esc_attr( $channel_name ) );
		else
			update_option( "yiw_op_1_$rand", esc_attr( $channel_name ) );
		if ( $op_2 = '' )
			add_option( "yiw_op_2_$rand", esc_attr( $channel_link ) );
		else
			update_option( "yiw_op_2_$rand", esc_attr( $channel_link ) );
		if ( $op_3 = '' )
			add_option("yiw_op_3_$rand", esc_attr( $channel_thumb ));
		else
			update_option( "yiw_op_3_$rand", esc_attr( $channel_thumb ) );
		if ( $op_4 = '' )
			update_option( "yiw_op_4_$rand", esc_attr( $subscribe_button ) );
		else
			update_option( "yiw_op_4_$rand", esc_attr( $subscribe_button ) );
		if ( $op_5 = '' )
			add_option( "yiw_op_5_$rand", esc_attr( $last_uploads ) );
		else
			update_option( "yiw_op_5_$rand", esc_attr( $last_uploads ) );
		if ( $op_6 = '' )
			add_option( "yiw_op_6_$rand", esc_attr( $popular_uploads) );
		else
			update_option( "yiw_op_6_$rand", esc_attr( $popular_uploads) );
		if ( $op_7 = '' )
			add_option( "yiw_op_7_$rand", esc_attr( $channel_info) );
		else
			update_option( "yiw_op_7_$rand", esc_attr( $channel_info ) );
		if ( $last_update = '' )
			add_option( "yiw_last_update_$rand", esc_attr( time() ) );
		else
			update_option( "yiw_last_update_$rand", esc_attr( time() ) );
		if ( $force_update = '' )
			add_option( "yiw_force_update_$rand", '0' );
		else
			update_option( "yiw_force_update_$rand", '0' );		
		// using the new cached data without the need to get them from database
		$op_1 = $channel_name;
		$op_2 = $channel_link;
		$op_3 = $channel_thumb;
		$op_4 = $subscribe_button;
		$op_5 = $last_uploads;
		$op_6 = $popular_uploads;
		$op_7 = $channel_info;

	}
	// if cache clearing isn't needed, output the existing cache
	ob_start();
	?>
		<?php $rndm = rand("1", "999"); ?>
		<div id="ytio-container">
			<div id="ytio-avatar">
				<div id="ytio-left" class="inline">
					<a href="<?php echo html_entity_decode( $op_2 ); ?>" title="<?php echo html_entity_decode( $op_1 ); ?>">
						<img src="<?php echo html_entity_decode( $op_3 ); ?>" height="90" width="90" alt="<?php echo html_entity_decode( $op_1 ); ?>" />
					</a>
				</div>
				<div id="ytio-right" class="inline">
					<a href="<?php echo html_entity_decode( $op_2 ); ?>">
						<span><?php echo html_entity_decode( $op_1 ); ?></span>
					</a><br class="clear" />
					<?php echo html_entity_decode( $op_4 ); ?>
				</div>
			</div>

			<div id="ytio-uploads">
				<div id="ytio-switch">
					<span id="sw-st-<?php echo $rndm; ?>"  onclick="this.setAttribute('class','active'),document.getElementById('sw-nd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('sw-rd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-last-uploads-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-popular-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid'),document.getElementById('ytio-stats-<?php echo $rndm; ?>').setAttribute('class','ytio-hid');" class="active">Last uploads</span>
					<span id="sw-nd-<?php echo $rndm; ?>" onclick="this.setAttribute('class','active'),document.getElementById('sw-st-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('sw-rd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-popular-uploads-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-last-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid'),document.getElementById('ytio-stats-<?php echo $rndm; ?>').setAttribute('class','ytio-hid');">Popular uploads</span>
					<span id="sw-rd-<?php echo $rndm; ?>" onclick="this.setAttribute('class','active'),document.getElementById('sw-st-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('sw-nd-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-stats-<?php echo $rndm; ?>').setAttribute('class',''),document.getElementById('ytio-last-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid'),document.getElementById('ytio-popular-uploads-<?php echo $rndm; ?>').setAttribute('class','ytio-hid');">Info</span>
				</div>
				<div style="padding: 1em;">
					<div id="ytio-last-uploads-<?php echo $rndm; ?>">
						<?php echo html_entity_decode( $op_5 ); ?>
					</div>
					<div id="ytio-popular-uploads-<?php echo $rndm; ?>" class="ytio-hid">
						<?php echo html_entity_decode( $op_6 ); ?>
					</div>
					<div id="ytio-stats-<?php echo $rndm; ?>" class="ytio-hid">
						<?php echo html_entity_decode( $op_7 ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php
	return ob_get_clean();

}
add_shortcode('yt-info', 'yiw_widget_shortcode');

// registers a menu and a settings page for this plugin
add_action('admin_menu', 'yiw_create_menu');
function yiw_create_menu() {
	add_options_page( 'YouTube Information Widget - Shortcode generator', 'YIW Shortcode Gen.', 'manage_options', 'yiw_gen', 'yiw_settings_page' );
	add_action( 'admin_init', 'register_yiw_settings' );
}
// registers bbPress Ultimate settings and options fields
function register_yiw_settings() {
	register_setting( 'yiw-settings', 'yiw_tabs_1' );
	register_setting( 'yiw-settings', 'yiw_tabs_2' );
	register_setting( 'yiw-settings', 'yiw_tabs_3' );
	register_setting( 'yiw-settings', 'yiw_cc_def' );
	register_setting( 'yiw-settings', 'yiw_m_def' );
}
// outputs content in the plugin's settings page (options-general.php?page=bbpress_ultimate)
function yiw_settings_page() {
	?>

		<h1>Shortcodes Generator</h1>
		<p>Fill out this quick form to generate a shortcode to use:</p>
	    <form action="#gen-msg" method="post">
	        <?php
			$atts = '';
			$html = "";
			$val_1 = "";
			$val_2 = "";
			$val_3 = "";
			$val_4 = "";
			$val_5 = "";
			$val_6 = "";
			if ( isset( $_POST["yiw_scd_submit"] ) && $_POST["yiw_scd_id"] != '' ) {

				$val_1 = ( isset( $_POST["yiw_scd_id"] ) ) ? esc_attr( $_POST["yiw_scd_id"] ) : "";
				$val_2 = ( isset( $_POST["yiw_scd_is_id"] ) ) ? esc_attr( $_POST["yiw_scd_is_id"] ) : "";
				$val_3 = ( isset( $_POST["yiw_scd_max"] ) ) ? esc_attr( $_POST["yiw_scd_max"] ) : "";
				$val_4 = ( isset( $_POST["yiw_scd_h"] ) ) ? esc_attr( $_POST["yiw_scd_h"] ) : "";
				$val_5 = ( isset( $_POST["yiw_scd_w"] ) ) ? esc_attr( $_POST["yiw_scd_w"] ) : "";
				$val_6 = ( isset( $_POST["yiw_scd_cc"] ) ) ? esc_attr( $_POST["yiw_scd_cc"] ) : "";
				$id = ( isset( $_POST["yiw_scd_id"] ) ) ? $atts .= "channel=\"". esc_attr( $_POST["yiw_scd_id"] ). "\"" : '';
				$is_id = ( isset( $_POST["yiw_scd_is_id"] ) ) ? $atts .= " id=\"1\"" : '';
				$m = ( isset( $_POST["yiw_scd_max"] ) && $_POST["yiw_scd_max"] != ''  ) ? $atts .= " max=\"". esc_attr( $_POST["yiw_scd_max"] ). "\"" : '';
				$h = ( isset( $_POST["yiw_scd_h"] ) && $_POST["yiw_scd_h"] != '' ) ? $atts .= " height=\"". esc_attr( $_POST["yiw_scd_h"] ). "\"" : '';
				$w = ( isset( $_POST["yiw_scd_w"] ) && $_POST["yiw_scd_w"] != ''  ) ? $atts .= " width=\"". esc_attr( $_POST["yiw_scd_w"] ). "\"" : '';
				$c = ( isset( $_POST["yiw_scd_cc"] ) && $_POST["yiw_scd_cc"] != ''  ) ? $atts .= " cache=\"". esc_attr( $_POST["yiw_scd_cc"] ). "\"" : '';
				$html .= "<div id=\"gen-msg\" style=\"background-color: #fff;padding: 1em;border:1px solid #E5E5E5;\">";
				$html .= "<h3>Generated!</h3>";
				$html .= "<span>Congratulations, here's your shortcode, add it somewhere around your site:</span>";
				$html .= "<p></p>";
				$html .= "<p><textarea onclick=\"this.select();\" rows=\"2\" cols=\"70\" style=\"background-color: #fff;display: inline-block;padding: .5em 1em;font-family: Consolas,Monaco,monospace;border: 1px solid #C7C7C7;max-width:100%;\">[yt-info $atts]</textarea></p>";
				$html .= "<p></p><span>Once used, the data will be imported to database.</span>";
				$html .= "</div>";

			}
			if ( isset( $_POST["yiw_scd_submit"] ) && ! isset( $_POST["yiw_scd_id"] ) ) {
				$html .= "<div id=\"gen-msg\" style=\"background-color: #fff;padding: 1em;border:1px solid #E5E5E5;\">";
				$html .= "<p><strong>Please fill out the required fields to generate a shortcode.</strong></p>";
				$html .= "</div>";
			}

			?>
			<table class="widefat striped">	
				<tr>
					<td>
						<label for="yiw_scd_id">YouTube username or channel ID: <i>(required)</i></label>
					</td>
					<td>
						<input type="text" name="yiw_scd_id" id="yiw_scd_id" value="<?php echo $val_1; ?>" required="required" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="yiw_scd_is_id">Thick this if you are providing a channel ID above:</label>
					</td>
					<td>
						<input type="checkbox" name="yiw_scd_is_id" <?php if ( $val_2 != "" ) echo "checked=\"checked\""; ?> id="yiw_scd_is_id" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="yiw_scd_max">Max videos to show: <i>(optional)</i></label>
					</td>
					<td>
						<input type="number" name="yiw_scd_max" value="<?php echo $val_3; ?>" id="yiw_scd_max" max="50" min="1" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="yiw_scd_h">Videos height: <i>(optional)</i></label>
					</td>
					<td>
						<input type="number" name="yiw_scd_h" value="<?php echo $val_4; ?>" id="yiw_scd_h" max="5000" min="10" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="yiw_scd_w">Videos width: <i>(optional)</i></label>
					</td>
					<td>
						<input type="number" name="yiw_scd_w" value="<?php echo $val_5; ?>" id="yiw_scd_w" max="5000" min="10" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="yiw_scd_cc">Clear cache every <code>?</code> hour(s) <i>(optional)</i></label>
					</td>
					<td>
						<input type="number" name="yiw_scd_cc" value="<?php echo $val_6; ?>" id="yiw_scd_cc" max="200" min="1" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="yiw_scd_submit" value="Generate Shortcode!" class="button" />
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
	    </form>
	    <?php echo $html; ?>
		<fieldset style="border: 1px solid #D5D5D5; padding: 1em;line-height: 2;">
			<legend style="padding: 0 1em;font-weight: 600;text-decoration: underline;">Support, review, ..</legend>
			<p>Thank you for using our plugin. Enjoying it? then consider rating it! we depened on your ratings and reviews to improve the plugin's performance and optimize it:</p>
			<a href="https://wordpress.org/support/plugin/youtube-information-widget">Support forum on WordPress.org &raquo;</a>
			<br style="clear: both;"> 
			<a href="https://twitter.com/intent/tweet?text=@samuel_elh%20">Get support on Twitter, mention '@samuel_elh' &raquo;</a>
			<br style="clear: both;"> 
			<a href="https://wordpress.org/support/view/plugin-reviews/youtube-information-widget?rate=5#postform">Rate &amp; Review this plugin &raquo;</a>
			<br style="clear: both;">
			<a href="https://twitter.com/intent/follow?screen_name=samuel_elh">Follow @samuel_elh &raquo;</a>
			<br style="clear: both;">
			<a href="https://twitter.com/intent/tweet?text=Check%20out%20YouTube%20Information%20Widget%20plugin%20on%20WordPress%20https://wordpress.org/plugins/youtube-information-widget/%20via%20@samuel_elh">Tweet about this plugin &raquo;</a>
		</fieldset>
		</div>
	<?php
}
// You have reached the end !