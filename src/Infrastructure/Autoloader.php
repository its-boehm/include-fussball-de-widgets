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

namespace ITSB\IFDW\Infrastructure;

/**
 * The Autoloader for all classes in the plugin.
 *
 * @since 3.1
 */
final class Autoloader {
	private const ROOT     = 'root';
	private const BASE_DIR = 'baseDir';
	private const PREFIX   = 'prefix';
	private const SUFFIX   = 'suffix';

	private const DEFAULT_PREFIX = '';
	private const DEFAULT_SUFFIX = '.php';

	private const AUTOLOAD_METHOD = 'autoload';

	/**
	 * Array containing the registered namespace structures.
	 *
	 * @since 3.1
	 * @var array<array>
	 */
	private $namespaceNames = [];

	/**
	 * Destructor for the Autoloader class.
	 *
	 * The destructor automatically unregister the autoload callback function
	 * with the SPL autoload system.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function __destruct() {
		$this->unregister();
	}

	/**
	 * Registers the autoload callback with the SPL autoload system.
	 *
	 * @since 3.1
	 * @return void
	 * @throws \Exception If the autoloader could not be registered.
	 */
	public function register(): void {
		spl_autoload_register( [ $this, self::AUTOLOAD_METHOD ] );
	}

	/**
	 * Unregister the autoload callback with the SPL autoload system.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function unregister(): void {
		spl_autoload_unregister( [ $this, self::AUTOLOAD_METHOD ] );
	}

	/**
	 * Returns the Namespaces.
	 *
	 * @since 3.1
	 * @return string[]
	 */
	public function getNamespaces() {
		return $this->namespaces;
	}

	/**
	 * Add a specific namespace structure with our custom autoloader.
	 *
	 * @since 3.1
	 *
	 * @param string $root    Root namespace name.
	 * @param string $baseDir Directory containing the class files.
	 * @param string $prefix  (Optional) Prefix to be added before the
	 *                        class. Defaults to an empty string.
	 * @param string $suffix  (Optional) Suffix to be added after the
	 *                        class. Defaults to '.php'.
	 * @return self
	 */
	public function addNamespace(
		string $root,
		string $baseDir,
		string $prefix = self::DEFAULT_PREFIX,
		string $suffix = self::DEFAULT_SUFFIX
	): self {
		$this->namespaces[] = [
			self::ROOT     => $this->normalizeRoot( $root ),
			self::BASE_DIR => $this->ensureTrailingSlash( $baseDir ),
			self::PREFIX   => $prefix,
			self::SUFFIX   => $suffix,
		];

		return $this;
	}

	/**
	 * The autoload function that gets registered with the SPL Autoloader
	 * system.
	 *
	 * @since 3.1
	 * @param string $className The class that got requested by the spl_autoloader.
	 * @return void
	 */
	public function autoload( string $className ): void {
		// Iterate over namespaces to find a match.
		foreach ( $this->namespaces as $namespaceName ) {
			// Move on if the object does not belong to the current namespace.
			if ( 0 !== strpos( $className, $namespaceName[ self::ROOT ] ) ) {
				continue;
			}

			// Remove namespace root level to correspond with root filesystem.
			$filename = str_replace( $namespaceName[ self::ROOT ], '', $className );

			// Remove a leading backslash from the class name.
			$filename = $this->removeLeadingBackslash( $filename );

			// Replace the namespace separator "\" by the system-dependent
			// directory separator.
			$filename = str_replace( '\\', DIRECTORY_SEPARATOR, $filename );

			// Add base_dir, prefix and suffix.
			$filepath = $namespaceName[ self::BASE_DIR ]
				. $namespaceName[ self::PREFIX ]
				. $filename
				. $namespaceName[ self::SUFFIX ];

			// Require the file if it exists and is readable.
			if ( is_readable( $filepath ) ) {
				require_once $filepath;
			}
		}
	}

	/**
	 * Normalize a namespace root.
	 *
	 * @since 3.1
	 * @param string $root Namespace root that needs to be normalized.
	 * @return string Normalized namespace root.
	 */
	private function normalizeRoot( string $root ): string {
		$root = $this->removeLeadingBackslash( $root );
		$root = $this->ensureTrailingBackslash( $root );

		return $root;
	}

	/**
	 * Remove a leading backslash from a namespace.
	 *
	 * @since 3.1
	 * @param string $namespaceName Namespace to remove the leading backslash from.
	 * @return string Modified namespace.
	 */
	private function removeLeadingBackslash( string $namespaceName ): string {
		return ltrim( $namespaceName, '\\' );
	}

	/**
	 * Make sure a namespace ends with a trailing backslash.
	 *
	 * @param string $namespaceName Namespace to check the trailing backslash of.
	 * @return string Modified namespace.
	 */
	private function ensureTrailingBackslash( string $namespaceName ): string {
		return rtrim( $namespaceName, '\\' ) . '\\';
	}

	/**
	 * Make sure a path ends with a trailing slash.
	 *
	 * @since 3.1
	 * @param string $path Path to check the trailing slash of.
	 * @return string Modified path.
	 */
	private function ensureTrailingSlash( string $path ): string {
		return rtrim( $path, '/' ) . '/';
	}
}
