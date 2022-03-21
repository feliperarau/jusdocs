<?php
namespace Theme\Core;

use SolidPress\Core\Theme;

/**
 * Extends Theme Class and allow subfolders
 */
class Boilerplate extends Theme {
	/**
	 * Search in folders for classes that implements Registrable interface,
	 * creates a new instance of those classes and calls the register method.
	 *
	 * @return void
	 */
	protected function load_registrable_classes(): void {
		foreach ( $this->registrable_namespaces as $namespace ) {
			$namespace_dir = implode(
				DIRECTORY_SEPARATOR,
				array(
					get_template_directory(),
					$this->base_folder,
					$namespace,
				)
			);

            $class_folder = str_replace( '\\', '/', $namespace_dir );

			foreach ( scandir( $class_folder, 1 ) as $file ) {
				if ( strpos( $file, '.php' ) === false ) {
					continue;
				}

				$class_name_array = explode( '.php', $file );
				$class_name       = array_shift( $class_name_array );
				$class_namespaced =
					$this->namespace . '\\' . $namespace . '\\' . $class_name;
				$class_instance   = new $class_namespaced();
				$class_instance->register();
			}
		}
	}
}