<?php
/**
 * EmailComponent
 *
 * @package jusdocs
 */

namespace Theme\Core;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;
use Theme\Helpers\Page;
use Mustache_Engine as Mustache;

use Error;

/**
 * Handle template and props
 */
class EmailComponent extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Emails/EmailTemplate/template';

    /**
	 * Email html template path relative to theme root
	 *
	 * @var string
	 */
    public $email_template;

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'email_html' => '',
	);


	/**
	 * Init and set the default values
	 *
	 * @param array $props
	 */
	public function __construct( $props = array() ) {
		$this->set_defaults();

		parent::__construct( $props );

        $this->props['email_html'] = $this->get_email_template( $this->email_template, $this->props );
	}

	/**
	 * Set the email common defaults
	 *
	 * @return void
	 */
	public function set_defaults() {
		$base_defaults = array();

		$comp_defaults = $this->defaults();

		$defaults = ! empty( $comp_defaults ) ? array_merge( $base_defaults, $comp_defaults ) : $base_defaults;

		$this->props = array_merge( $defaults, $this->props );
	}

	/**
	 * This method is meant to be override by the extending class in order to provide
	 * the respective email component defaults
	 *
	 * @return array
	 */
	public function defaults() {
		return array();
	}
    /**
	 * Search for template file full path
	 *
	 * @param string $template - template relative to theme root path
	 * @return string template full path
	 */
    public function get_template_path( string $template ): string {
		return locate_template(
			$template . '.html',
			false,
			false
		);
	}

	/**
	 * Load email template file
	 *
	 * @param string $template - template relative to theme root path
     * @throws Error Error if template do not exists.
	 * @return string
	 */
	public function load_template_html( string $template ): string {
		$template_file = $this->get_template_path( $template );

		if ( ! $template_file ) {
			throw new Error( "Template '{$template}' not found!" );
		}

        ob_start();
		include $template_file;
        return ob_get_clean();
	}

    /**
     * Parse template variables and convert them into dynamic content
     *
     * @param string $template
     * @param array  $props
     *
     * @return string
     */
    public function convert_template_vars( string $template, array $props = array() ) : string {
        $m = new Mustache( array( 'entity_flags' => ENT_QUOTES ) );
        return $m->render( $template, $props );
    }

    /**
     * Get the dynamic email content
     *
     * @param string $template
     * @param array  $props
     *
     * @return string
     */
    public function get_email_template( $template, $props ) : string {
        $template_html = $this->load_template_html( $template );

        $dynamic_template = $this->convert_template_vars(
            $template_html,
            $props
        );

        return $dynamic_template;
    }

    /**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		return array();
	}
}