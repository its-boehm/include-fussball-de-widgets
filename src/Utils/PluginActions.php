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

namespace ITSB\IFDW\Utils;

use ITSB\IFDW\Infrastructure\FilterBase;

/**
 * The `PluginActions` class adds additional information to the plugin on the
 * plugin page.
 *
 * @since 3.0
 */
final class PluginActions extends FilterBase {
	/**
	 * Additional links on plugin page.
	 * Add additional links to the plugin on the plugin page along with meta
	 * information.
	 *
	 * @since 3.0
	 * @param mixed ...$args List of arguments.
	 * @return array List of modified plugin meta links.
	 */
	public function filter( ...$args ): array {
		$links = func_get_arg( 0 );
		$file  = func_get_arg( 1 );

		if ( Settings::getPluginName() !== $file ) {
			return $links;
		}

		$href      =
			esc_url( 'https://www.paypal.me/mheob' );
		$ariaLabel = esc_attr__( 'Plugin Additional Links', 'include-fussball-de-widgets' );
		$linkText  = esc_html__( 'Donate', 'include-fussball-de-widgets' );
		$rowMeta   = [ 'docs' => "<a href=\"$href\" target=\"_blank\" aria-label=\"$ariaLabel\">$linkText</a>" ];

		return array_merge( $links, $rowMeta );
	}
}
