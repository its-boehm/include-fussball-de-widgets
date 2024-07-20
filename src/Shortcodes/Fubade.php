<?php
/**
 * Include Fussball.de Widgets
 *
 * @package   ITSB\IncludeFussballDeWidgets
 * @author    Alexander Böhm <ab@its-boehm.de>
 * @license   GPL2
 * @link      https://wordpress.org/plugins/include-fussball-de-widgets/
 * @copyright 2019 Alexander Böhm
 */

declare( strict_types=1 );

namespace ITSB\IFDW\Shortcodes;

use ITSB\IFDW\Infrastructure\ShortcodeBase;

/**
 * The `Fubade` class defines and render the fubade shortcode.
 *
 * @since 3.1
 */
final class Fubade extends ShortcodeBase {
	/**
	 * Renders the fubade shortcode.
	 *
	 * This method is responsible for processing the shortcode attributes and rendering
	 * the fubade widget.
	 *
	 * @since 3.0
	 * @param array $atts Shortcode attributes
	 *                    (`api`, `id`, `classes`, `notice`, `fullWidth` and `devtools`).
	 * @return string The rendered fubade widget.
	 */
	public function shortcode( array $atts ): string {
		$a = shortcode_atts(
			[
				'id'        => '',
				'api'       => '',
				'classes'   => '',
				'notice'    => '',
				'fullWidth' => '',
				'devtools'  => '',
			],
			$atts
		);

		return ( new \ITSB\IFDW\Frontend\Fubade() )->output( $a );
	}
}
