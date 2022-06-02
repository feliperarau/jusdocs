<?php

$completeContent = preg_replace('/<a href="#\/" class="active">Overview<\/a>/s', '<a href="#/" class="" role="tab" aria-controls="sample3-tabpanel1" option="0" selected>Overview</a>' , $completeContent,1);

$completeContent = preg_replace('/<a href="#\/">Details<\/a>/s', '<a href="#/" role="tab" aria-controls="sample3-tabpanel2" option="1">Details</a>' , $completeContent,1);

$completeContent = preg_replace('/<a href="#\/">Shipping<\/a>/s', '<a href="#/" role="tab" aria-controls="sample3-tabpanel3" option="2">Shipping</a>' , $completeContent,1);

$completeContent = preg_replace('/<a href="#\/">Reviews<\/a>/s', '<a href="#/" role="tab"  aria-controls="sample3-tabpanel4" option="3">Reviews</a>' , $completeContent,1);

$completeContent = preg_replace('/<a href="#\/">Related Seeds<\/a>/s', '<a href="#/" role="tab" aria-controls="sample3-tabpanel5" option="4">Related Seeds</a>' , $completeContent,1);

$completeContent = preg_replace('/<div id="Overview" class="em(.*?)>/s', '<span class="amp-custom-pro-tabs"><div id="Overview" class="em$1>' , $completeContent,1);

$completeContent = preg_replace('/<div id="Related" class="em(.*?)">(.*?)<\/div>/s', '<div id="Related" class="em$1">$2</div></span amp>' , $completeContent,1);

$amp_selector_open = '<amp-selector id="myTabPanels" class="tabpanels">';
$amp_selector_close = '</amp-selector>';

$completeContent = preg_replace('/<span class="amp-custom-pro-tabs">(.*?)<\/span amp>/s', ''.$amp_selector_open.'<span class="amp-custom-pro-tabs">$1</span>'.$amp_selector_close.'' , $completeContent,1);

//amp selector content 
$completeContent = preg_replace('/<div id="Overview" class="em(.*?)">/s', '<div id="Overview" class="em$1" role="tabpanel" aria-labelledby="sample3-tab1" option selected>' , $completeContent);

$completeContent = preg_replace('/<div id="Details" class="em(.*?)">/s', '<div id="Details" class="em$1" role="tabpanel" aria-labelledby="sample3-tab2" option>' , $completeContent);

$completeContent = preg_replace('/<div id="Shipping" class="em(.*?)">/s', '<div id="Shipping" class="em$1" role="tabpanel" aria-labelledby="sample3-tab3" option>' , $completeContent);

$completeContent = preg_replace('/<div id="Reviews" class="em(.*?)">/s', '<div id="Reviews" class="em$1" role="tabpanel" aria-labelledby="sample3-tab4" option>' , $completeContent);

$completeContent = preg_replace('/<div id="Related" class="em(.*?)">/s', '<div id="Related" class="em$1" role="tabpanel" aria-labelledby="sample3-tab5" option>' , $completeContent);

