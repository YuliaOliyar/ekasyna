<?php
/**
 * Dane strukturalne JSON-LD: Article (z autorem i datą aktualizacji) oraz Review dla recenzji kasyna.
 * Jeśli używasz Yoast/Rank Math z własnym schema, wyłącz: add_filter( 'ekasyna_schema', '__return_false' );
 *
 * @package ekasyna
 */

defined( 'ABSPATH' ) || exit;

/**
 * Wypisuje JSON-LD.
 */
function ekasyna_schema() {
	if ( ! apply_filters( 'ekasyna_schema', true ) || ! is_singular() ) {
		return;
	}

	$post   = get_queried_object();
	$author = get_userdata( (int) $post->post_author );
	$person = $author ? array(
		'@type' => 'Person',
		'name'  => $author->display_name,
		'url'   => get_author_posts_url( $author->ID ),
	) : null;

	$publisher = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);

	if ( 'kasyno' === $post->post_type ) {
		$c    = ekasyna_casino( $post );
		$data = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Review',
			'name'          => sprintf( /* translators: %s nazwa */ __( '%s — recenzja', 'ekasyna' ), $c['name'] ),
			'datePublished' => get_the_date( 'c', $post ),
			'dateModified'  => get_the_modified_date( 'c', $post ),
			'author'        => $person,
			'publisher'     => $publisher,
			'itemReviewed'  => array(
				'@type' => 'Organization',
				'name'  => $c['name'],
			),
		);
		if ( $c['rating'] > 0 ) {
			$data['reviewRating'] = array(
				'@type'       => 'Rating',
				'ratingValue' => $c['rating'],
				'bestRating'  => 10,
				'worstRating' => 0,
			);
		}
	} else {
		$data = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => $person,
			'publisher'        => $publisher,
			'mainEntityOfPage' => get_permalink( $post ),
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array_filter( $data ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ekasyna_schema', 20 );
