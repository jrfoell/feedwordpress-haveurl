<?php

class FWP_HaveURL {

	private static $instance;
	private $current_obj = NULL;
	
	private function __construct() {
		$this->post_type = get_option( 'feedwordpress_syndicated_post_type', 'post' );
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}
	
	public function hook() {
		add_filter( 'syndicated_item_content', array( $this, 'resolve_urls' ), 1, 2 ); //run after normal syndication filters
	}
	
	public function resolve_urls( $content, $obj ) {
		$this->current_obj = $obj;

		foreach ( $obj->uri_attrs as $pair ) :
			list( $tag, $attr ) = $pair;
			$pattern = FeedWordPressHTML::attributeRegex( $tag, $attr );
			$content = preg_replace_callback (
				$pattern,
				array( $this, 'resolve_url' ),
				$content
			);
		endforeach;

		return $content;	
	}

	function resolve_url( $refs ) {
		$tag = FeedWordPressHTML::attributeMatch( $refs );

		if ( $tag['tag'] == 'a' && $tag['attribute'] == 'href' ) {		
			$index = strpos( $tag['value'], $this->current_obj->feedmeta['feed/link'] );
			if ( $index === 0 ) {	
				$path = substr( $tag['value'], strlen( $this->current_obj->feedmeta['feed/link'] ) );			
				$post = get_page_by_path( $path, OBJECT, $this->post_type );		
				if ( $post )
					return $tag['prefix'] . get_permalink( $post->ID ) . $tag['suffix'];
			}
		}

		return $tag['prefix'] . $tag['value'] . $tag['suffix']; //no change
	}

}
