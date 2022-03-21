<?php
/**
 * TransactionalNotification Email Template component
 *
 * @package jusdocs
 */

namespace Theme\Components\Emails;

/**
 * Handle template and props
 */
class TransactionalNotification extends EmailTemplateDefault {
    /**
	 * Email html template path relative to theme root
	 *
	 * @var string
	 */
    public $email_template = 'emails/dist/TransactionalNotification/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array();

	/**
	 * Init and set the default initial props
	 *
	 * @param array $props
	 */
	public function __construct( $props = array() ) {
		$email_props = $this->email_props();
		$this->props = array_merge( $this->props, $email_props );

		parent::__construct( $props );
	}

	/**
	 * This method is meant to be override by the extending class in order to provide
	 * the respective email component defaults
	 *
	 * @return array
	 */
	public function email_props() : array {
		return array();
	}
}