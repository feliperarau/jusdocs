<?php
namespace ElementorForAmp\Widgets\Pro\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Amp_Skin_Full_Content extends AMP_Skin_Classic {
	use Skin_Content_Base;

	public function get_id() {
		return 'full_content';
	}
}
