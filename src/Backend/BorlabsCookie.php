<?php
// phpcs:disable
// cspell:disable
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

namespace ITSB\IFDW\Backend;

use ITSB\IFDW\Infrastructure\ActionBase;
use ITSB\IFDW\Utils\Settings;

/**
 * Class BorlabsCookie provides functions to add
 * "Borlabs-Cookie" (https://borlabs.io) support.
 *
 * @since 3.0
 */
class BorlabsCookie extends ActionBase {
	const CB_ID = 'fubade';

	/**
	 * The table cookies.
	 *
	 * @var string
	 */
	private $tableNameCookies;

	/**
	 * The table cookieGroups.
	 *
	 * @var string
	 */
	private $tableNameCookieGroups;

	/**
	 * Create the fubade content-blocker, if not exists already.
	 *
	 * @since 3.0
	 * @return void
	 */
	public function action(): void {
		if ( ! $this->checkBorlabsCookieIsActivated() ) {
			return;
		}

		global $wpdb;

		$this->tableNameCookies      = $wpdb->base_prefix . 'borlabs_cookie_cookies';
		$this->tableNameCookieGroups = $wpdb->base_prefix . 'borlabs_cookie_groups';

		if ( ! $this->checkFubadeCookieExists() ) {
			$this->addCookie();
		}

		if ( BorlabsCookieHelper()->getContentBlockerData( self::CB_ID ) ) {
			return;
		}

		/* Setup variables */
		$cbHtml = '<div class="_brlbs-content-blocker">
	<div class="_brlbs-embed brlbs-ifdw">
		<img class="_brlbs-thumbnail" src="' .
			plugins_url( 'assets/images/cb-fubade.png', Settings::getPluginName() ) . '" alt="%%name%%">
		<div class="_brlbs-caption">
			<p>
				' . __(
				'By loading the widget, you agree to the privacy policy of fussball.de.',
				'include-fussball-de-widgets'
			) . '<br>
				<a href="%%privacy_policy_url%%" target="_blank" rel="nofollow">' .
					__( 'Learn more', 'include-fussball-de-widgets' ) . '</a>
			</p>
			<p>
			<a class="_brlbs-btn" href="#" data-borlabs-cookie-unblock role="button">
					' . __( 'Load widget', 'include-fussball-de-widgets' ) . '
				</a>
			</p>
			<p>
				<label>
					<input type="checkbox" name="unblockAll" value="1" checked>
					<small>' . __( 'Always load fussball.de Widgets', 'include-fussball-de-widgets' ) . '</small>
				</label>
			</p>
		</div>
	</div>
</div>';

		$cbCss = '.BorlabsCookie ._brlbs-content-blocker .brlbs-ifdw ._brlbs-caption a {
	color: #aaa;
}

.BorlabsCookie ._brlbs-content-blocker .brlbs-ifdw ._brlbs-caption a._brlbs-btn {
	background: #0000a8;
	color: #fff;
	border-radius: 50px;
}

.BorlabsCookie ._brlbs-content-blocker .brlbs-ifdw ._brlbs-caption a._brlbs-btn:hover {
	background: #fff;
	color: #0000a8;
}';

		BorlabsCookieHelper()->addContentBlocker(
			self::CB_ID,
			__( 'Fussball.de Widget', 'include-fussball-de-widgets' ),
			'',
			'http://www.fussball.de/privacy/',
			[ 'fussball.de', 'www.fussball.de' ],
			$cbHtml,
			$cbCss,
			'',
			'',
			[],
			false,
			false
		);
	}

	/**
	 * Check if BorlabsCookie plugin is activated.
	 *
	 * @since 3.1
	 * @return bool If the BorlabsCookie plugin is activated it is true, otherwise false.
	 */
	public function checkBorlabsCookieIsActivated(): bool {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'borlabs-cookie/borlabs-cookie.php' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if the `fubade` exists.
	 *
	 * @since 3.0
	 * @return bool If the fubade cookie exists it is true, otherwise false.
	 */
	private function checkFubadeCookieExists(): bool {

		// FIXME: use correct database caching.
		global $wpdb;
		$cookieId = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT `cookie_id` FROM `' . $this->tableNameCookies . '` WHERE `cookie_id` = %s LIMIT 1',
				self::CB_ID
			)
		);

		if ( $cookieId > 0 ) {
			return true;
		}

		return false;
	}

	/**
	 * Add the fubade cookie, if not exists already.
	 *
	 * @since 3.0
	 * @return void
	 */
	private function addCookie(): void {
		$defaultBlogLanguage = substr( get_option( 'WPLANG', 'en_US' ), 0, 2 ) ?? 'en';
		$cookieGroupIds      = [];

		global $wpdb;
		$cookieGroups = $wpdb->get_results(
			'SELECT	`id`, `group_id`
			 FROM		`' . $this->tableNameCookieGroups . '`
			 WHERE	`language` = "' . esc_sql( $defaultBlogLanguage ) . '"'
		);

		foreach ( $cookieGroups as $groupData ) {
			$cookieGroupIds[ $groupData->group_id ] = $groupData->id;
		}

		// phpcs:disable Squiz.Strings.DoubleQuoteUsage
		$sqlQuery = "INSERT INTO `" . $this->tableNameCookies . "`
			(
				`cookie_id`,
				`language`,
				`cookie_group_id`,
				`service`,
				`name`,
				`provider`,
				`purpose`,
				`privacy_policy_url`,
				`hosts`,
				`cookie_name`,
				`cookie_expiry`,
				`opt_in_js`,
				`position`,
				`status`,
				`undeletable`
			)
			VALUES
			(
				'" . self::CB_ID . "',
				'" . esc_sql( $defaultBlogLanguage ) . "',
				'" . esc_sql( $cookieGroupIds['external-media'] ) . "',
				'Custom',
				'Fußball.de',
				'Fußball.de',
				'" . _x( 'Used to unblock Fußball.de content.', 'Cookie - Default Entry Fußball.de', 'borlabs-cookie' ) . "',
				'" . _x( 'http://www.fussball.de/privacy/', 'Cookie - Default Entry Fußball.de', 'borlabs-cookie' ) . "',
				'" . esc_sql( serialize( [ 'fussball.de', 'www.fussball.de' ] ) ) . "',
				'" . self::CB_ID . "',
				'" . _x( 'Unlimited', 'Cookie - Default Entry Fußball.de', 'borlabs-cookie' ) . "',
				'" . esc_sql(
					'<script>if("object" === typeof window.BorlabsCookie) { window.BorlabsCookie.unblockContentId("' . self::CB_ID
					. '"); }</script>'
				) . "',
				82,
				1,
				0
			) ON DUPLICATE KEY UPDATE `undeletable` = VALUES(`undeletable`)";
		// phpcs:enable Squiz.Strings.DoubleQuoteUsage

		$wpdb->query( $sqlQuery );
	}
}
