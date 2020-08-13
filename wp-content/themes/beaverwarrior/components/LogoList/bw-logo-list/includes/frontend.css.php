<?php

$desktop_width = 100 / (float)$module->settings->col_desktop;
$tablet_width = 100 / (float)$module->settings->col_tablet;
$mobile_width = 100 / (float)$module->settings->col_mobile;

$default_css = [
	'&.fl-module-bw-logo-list' => [
        '.module_title' => ['color' => $module->getModuleSettingColor('titles_color')],
		'.block_title' => ['color' => $module->getModuleSettingColor('titles_color')],
		'.triptych_panel_link:hover' => [
			'.title' => ['color' => $module->getModuleSettingColor('titles_hover_color')],
			'.block_title' => ['color' => $module->getModuleSettingColor('titles_hover_color')]
		],
		'.logo_block' => ['width' => $desktop_width.'%'],
		'.tabbed-slider-mobile .paginationContaniner .owlPagination.active .pagi' =>
			["background-color" => $module->getModuleSettingColor("icon_color")], 
		'.gradient:before' => ['background-image' => FLBuilderColor::gradient($settings->overlay_gradient_left)],
		'.gradient:after' => ['background-image' => FLBuilderColor::gradient($settings->overlay_gradient_right)]
	]
];
$module->rendermoduleCSS($default_css);

$mobile_css = [
	'&.fl-module-bw-logo-list' => [
		'.logo_block' => ['width' => $mobile_width.'%']
	]
];
$module->rendermoduleCSSResponsiveMobile($mobile_css);

$tablet_css = [
	'&.fl-module-bw-logo-list' => [
		'.logo_block' => ['width' => $tablet_width.'%']
	]
];
$module->rendermoduleCSSResponsiveTablet($tablet_css);

$desktop_css = [
	'&.fl-module-bw-logo-list' => [
		'.logo_block' => ['width' => $desktop_width.'%']
	]
];
$module->rendermoduleCSSResponsiveDesktop($desktop_css);

?>