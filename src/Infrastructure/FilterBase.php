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
 * The `FilterBase` class sets the general config for the filter.
 *
 * WordPress offers filter hooks to allow plugins to modify various types
 * of internal data at runtime.
 *
 * A plugin can modify data by binding a callback to a filter hook. When the
 * filter is later applied, each bound callback is run in order of priority,
 * and given the opportunity to modify a value by returning a new value.
 *
 * @since 3.1
 */
abstract class FilterBase {
	/**
	 * Add the init filter for registering the fubade api.
	 *
	 * @since 3.0
	 * @param string $tag           The name of the filter to hook the callback
	 *                              to.
	 * @param int    $acceptedArgs  (Optional) The number of arguments the
	 *                              function accepts.
	 *                              Default 1.
	 * @param int    $priority      (Optional) Used to specify the order in
	 *                              the functions associated with a particular
	 *                              action are executed. Lower numbers correspond
	 *                              with earlier execution, and functions with
	 *                              the same priority are executed in the order
	 *                              in which they were added to the action.
	 *                              Default 10.
	 * @return void
	 */
	public function addFilter( string $tag, int $acceptedArgs = 1, int $priority = 10 ): void {
		add_filter( $tag, [ $this, 'filter' ], $priority, $acceptedArgs );
	}

	/**
	 * The filter callback to be run when the filter is applied.
	 *
	 * @since 3.1
	 * @param mixed ...$args List of arguments.
	 * @return array List of modified plugin meta links.
	 */
	abstract public function filter( ...$args ): array;
}
