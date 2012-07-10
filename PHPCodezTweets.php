<?php
/**
* Plugin Name: PHPCodez Tweets
* Plugin URI: http://phpcodez.com/
* Description: A Widget That Displays Tweets
* Version: 0.1
* Author: Pramod T P
* Author URI: http://phpcodez.com/
*/

add_action( 'widgets_init', 'wpc_tweets_widgets' );

function wpc_tweets_widgets() {
	register_widget( 'wpctweetsWidget' );
}

class wpctweetsWidget extends WP_Widget {
	function wpctweetsWidget() {
		$widget_ops = array( 'classname' => 'wpcClass', 'description' => __('A Widget That Displays Tweets.', 'wpcClass') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wpc-tweets' );
		$this->WP_Widget( 'wpc-tweets', __('PHPCodez Tweets', ''), $widget_ops, $control_ops );
	}

	
	function widget( $args, $instance ) {
		extract( $args );
		$username=$instance['twitterUsername']; $limit=$instance['tweetCount'];
		if(!is_numeric($limit)){$limit = 100;}
		$tweetFile	=	"http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $limit . "";
		$xml = simplexml_load_file($tweetFile);
	?>	
		<table>
			<?php
				for($i=0;$i<$limit;$i++){  if(empty($xml->entry[$i]->content)) break;
					$attr = $xml->entry->link[1]->attributes();
			?>	
				<tr>
					<?php if($instance['showImage']) {?><td><img src="<?php echo $attr['href']; ?>" /></td><?php } ?>
					<td style=" font-size:12px;">
					<?php 	echo  $xml->entry[$i]->content;	?>
					</td>
				</tr>
			<?php } ?>	
		</table>
		
		
	<?php  
	
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['twitterUsername'] = strip_tags( $new_instance['twitterUsername'] );
		$instance['tweetCount'] 	=  $new_instance['tweetCount'] ;
		$instance['showImage']	 	=  $new_instance['showImage'] ;
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitterUsername' ); ?>"><?php _e('Twitter Username', 'wpcclass'); ?></label>
			<input id="<?php echo $this->get_field_id( 'twitterUsername' ); ?>" name="<?php echo $this->get_field_name( 'twitterUsername' ); ?>" value="<?php echo $instance['twitterUsername']; ?>" style="width:95%; border:1px solid #ccc" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'tweetCount' ); ?>"><?php _e('Number of tweets', 'wpcclass'); ?></label>
			<input id="<?php echo $this->get_field_id( 'tweetCount' ); ?>" name="<?php echo $this->get_field_name( 'tweetCount' ); ?>" value="<?php echo $instance['tweetCount']; ?>" style="width:95%; border:1px solid #ccc" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'showImage' ); ?>"><?php _e('Show Image', 'wpcclass'); ?></label>
			<input type="checkbox" value="1" id="<?php echo $this->get_field_id( 'showImage' ); ?>" name="<?php echo $this->get_field_name( 'showImage' ); ?>" <?php if($instance['showImage']) echo 'checked="checked"'; ?>   />
		</p>
	<?php
	}
	
	
}

?>