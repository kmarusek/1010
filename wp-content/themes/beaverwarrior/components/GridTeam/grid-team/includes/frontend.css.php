<?php

$desktop_width = 100 / (float)$module->settings->col_desktop;
$tablet_width = 100 / (float)$module->settings->col_tablet;
$mobile_width = 100 / (float)$module->settings->col_mobile;

$space = (float)$module->settings->space_between / 2;

$default_css = [
	'.GridTeam' => [
        '.GridTeam-team' => array_merge(
			['color' => $module->getModuleSettingColor('meet_team_color')],
			$module->getTypography('meet_team_typography')
		),
		'.GridTeam-position' => array_merge(
			['color' => $module->getModuleSettingColor('titles_color')],
			$module->getTypography('position_typography')
		),
		'.GridTeam-name' => array_merge(
			['color' => $module->getModuleSettingColor('name_color')],
			$module->getTypography('names_typography')
		),
		'.GridTeam-position' => array_merge(
			['color' => $module->getModuleSettingColor('modal_position_color')],
			$module->getTypography('modal_position_typography')
		),
		'GridTeam-modal-title' => array_merge(
			['color' => $module->getModuleSettingColor('modal_name_color')],
			$module->getTypography('modal_name_typography')
		),
		'.GridTeam_modal_text' => array_merge(
			['color' => $module->getModuleSettingColor('modal_text_color')],
			$module->getTypography('modal_text_typography')
		),
		'.GridTeam_modal_text' => [
			'p' => array_merge(
				['color' => $module->getModuleSettingColor('modal_text_color')],
				$module->getTypography('modal_text_typography')
			),
			'ul' => array_merge(
				['color' => $module->getModuleSettingColor('modal_text_color')],
				$module->getTypography('modal_text_typography')
			)
		],
		'.GridTeam-cta' => array_merge(
			['color' => $module->getModuleSettingColor('cta_color')],
			$module->getTypography('cta_typography')
		),
		'.GridTeam-cta:hover' => [
			'color' => $module->getModuleSettingColor('cta_hover_color')
		],
		'.GridTeam-panel_link:hover' => [
			'.GridTeam-position' => ['color' => $module->getModuleSettingColor('titles_hover_color')],
			'.GridTeam-name' => ['color' => $module->getModuleSettingColor('titles_hover_color')]
		],
		'.GridTeam-team_member' => [
			'width' => 'calc('.$desktop_width.'% - 1px)',
			'padding-left' => $space.'px',
			'padding-right' => $space.'px'
		],
		'.GridTeam-row' => [
			'margin-left' => '-'.$space.'px',
			'margin-right' => '-'.$space.'px'
		],
		'.GridTeam-close' => [
			'color' => $module->getModuleSettingColor('modal_close_color')
		],
		'.GridTeam-close:hover' => [
			'color' => $module->getModuleSettingColor('modal_close_hover_color')
		]
	]
];
$module->rendermoduleCSS($default_css);

$mobile_css = [
	'.GridTeam' => [
		'.GridTeam-team_member' => ['width' => 'calc('.$mobile_width.'% - 1px)'],
		'.GridTeam-position' => $module->getTypography('position_typography_responsive'),
		'.GridTeam-name' => $module->getTypography('names_typography_responsive'),
		'.GridTeam-modal-position' => $module->getTypography('modal_position_typography_responsive'),
		'.GridTeam-modal-title' => $module->getTypography('modal_name_typography_responsive'),
		'.GridTeam-modal_text' => $module->getTypography('modal_text_typography_responsive')
	]
];
$module->rendermoduleCSSResponsiveMobile($mobile_css);

$tablet_css = [
	'.GridTeam' => [
		'.GridTeam-team_member' => ['width' => 'calc('.$tablet_width.'% - 1px)'],
		'.GridTeam-position' => $module->getTypography('position_typography_medium'),
		'.GridTeam-name' => $module->getTypography('names_typography_medium'),
		'.GridTeam-modal-position' => $module->getTypography('modal_position_typography_medium'),
		'.GridTeam-modal-title' => $module->getTypography('modal_name_typography_medium'),
		'.GridTeam-modal_text' => $module->getTypography('modal_text_typography_medium')
	]
];
$module->rendermoduleCSSResponsiveTablet($tablet_css);
$desktop_css = [
	'.GridTeam' => [
		'.GridTeam-team_member' => ['width' => 'calc('.$desktop_width.'% - 1px)'],
		'.GridTeam-position' => $module->getTypography('position_typography'),
		'.GridTeam-name' => $module->getTypography('names_typography'),
		'.GridTeam-modal-position' => $module->getTypography('modal_position_typography'),
		'.GridTeam-modal-title' => $module->getTypography('modal_name_typography'),
		'.GridTeam-modal_text' => $module->getTypography('modal_text_typography')
	]
];
if($module->settings->col_desktop >3){
	$desktop_css = [
	'.GridTeam' => [
		'.GridTeam-image_container img' => ['width' => '100%'],
		'.GridTeam-image_container img' => ['height' => '100%'],
		'.GridTeam-image_container' => ['margin' => '10px']
	]
];
}
$module->rendermoduleCSSResponsiveDesktop($desktop_css);

?>