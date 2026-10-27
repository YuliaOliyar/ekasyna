<?php
/**
 * Bonus w trzech liniach: „100% do” / „2 250 zł” / „+ 200 DS”.
 *
 * @package ekasyna
 *
 * @var array $args { casino: array }
 */

defined( 'ABSPATH' ) || exit;

$c = $args['casino'];
?>
<div class="ek-bonus">
	<?php if ( $c['bonus_pre'] ) : ?>
		<span class="ek-bonus__pre"><?php echo esc_html( $c['bonus_pre'] ); ?></span>
	<?php endif; ?>
	<?php if ( $c['bonus_amt'] ) : ?>
		<span class="ek-bonus__amt"><?php echo esc_html( $c['bonus_amt'] ); ?></span>
	<?php endif; ?>
	<?php if ( $c['bonus_post'] ) : ?>
		<span class="ek-bonus__post"><?php echo esc_html( $c['bonus_post'] ); ?></span>
	<?php endif; ?>
</div>
