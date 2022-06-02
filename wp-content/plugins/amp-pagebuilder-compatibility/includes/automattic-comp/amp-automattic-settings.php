<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(update_option('amp_pbc_automattic', $_POST['amp_pbc_automattic'])){
			echo "<div>Successfully saved</div>";
		}
	}
	$amppbc_savedData = get_option('amp_pbc_automattic');

	if ( is_plugin_active( 'elementor/elementor.php' ) ) {
		$settingsData['Elementor'] = pagebuilder_for_amp_elementor_Admin::get_admin_options();
	}

	$theme = wp_get_theme(); // gets the current theme
 	 if ( is_plugin_active( 'divi-builder/divi-builder.php' ) || 'Divi' == $theme->name || 'Divi' == $theme->parent_theme ) {
		$settingsData['Divi'] = pagebuilder_for_amp_divi_Admin::get_admin_options_divi();
	}
	//ux builder
	if(function_exists('flatsome_setup')){
		$settingsData['Flatsome'] = pagebuilder_for_amp_ux_Admin::get_admin_options_ux();
	}
	//Avada
    if( function_exists('amp_activate') && class_exists('FusionBuilder') ){
		$settingsData['Avada'] = pagebuilder_for_amp_avada_Admin::add_options_for_avada(array());
	}

	global $vc_manager;
	if($vc_manager instanceof Vc_Manager){
		$settingsData['WP_Bakery'] = AmpVCAdminSettings::get_admin_options_wp_bakery();
	}
	$settingsData['Others'] = pb_compatibility_admin_settings_extra::get_admin_options_other();
	//print_r($settingsData);die;
	$tabli = '';
	$tabcontent = '';
	$i=0;
	foreach ($settingsData as $key => $tabsvalue) {
		$selectedTab = '';
		$selectedTabCon = 'display:none';
		if($i==0){
			$selectedTab = "nav-tab-active";
			$selectedTabCon = 'display:block';
		}
		$i++;

		$tabli .= '<a class="nav-tab '.$selectedTab.'" data-tab="'.strtolower($key).'">'.$key.'</a>';
		$tabcontent .= '<div class="amp-pbc-'.strtolower($key).'" style="'.$selectedTabCon.'"><table class="form-table ">' ;
		foreach($tabsvalue[0]['fields'] as $fields){
			if(isset($fields['id'])){
				switch ($fields['type']) {
					case 'switch':
						$is_checked = '';
						if($amppbc_savedData[$fields['id']]==1){
							$is_checked = "checked";
						}
						$tabcontent .= '<tr><th class="'.$fields['id'].'" ><label for="'.$fields['id'].'">'.$fields['title'].'</label></th>
							<td>
								<input type="checkbox" class="amp-pbc-checkbox" value="1" id="'.$fields['id'].'" '.$is_checked.'>

							<input type="hidden" name="amp_pbc_automattic['.$fields['id'].']" value="'.(isset($amppbc_savedData[$fields['id']])? $amppbc_savedData[$fields['id']] : '0').'">
						</td></tr>';
						break;

					case 'textarea':
						$tabcontent .= '<tr><th class="'.$fields['id'].'" ><label for="'.$fields['id'].'">'.$fields['title'].'</label></th><td><textarea name="amp_pbc_automattic['.$fields['id'].']" id="'.$fields['id'].'" cols="100" rows="6">'.$amppbc_savedData[$fields['id']].'</textarea></td></tr>';
						break;
					case 'raw':
						$tabcontent .= '<tr><th class="'.$fields['id'].'" ><label for="'.$fields['id'].'">'.$fields['title'].'</label></th><td>'.$fields['content'].'</td></tr>';
						break;
					
					default:
						# code...
						break;
				}
			}
		}
		$tabcontent .= "</table></div>";
	}
	echo "<h2 class='amp-pbc-tabs nav-tab-wrapper adsforwp-tabs'> ".$tabli." </h2>";
	echo "<form method='post'>
			<div>
				".$tabcontent."
			</div>
			<input type='submit' class='button button-primary'>
	</form>";



