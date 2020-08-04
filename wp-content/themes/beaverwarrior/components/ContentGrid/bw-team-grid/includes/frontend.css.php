
<?php

$desktop_width = 100 / (float)$module->settings->col_desktop;
$tablet_width = 100 / (float)$module->settings->col_tablet;
$mobile_width = 100 / (float)$module->settings->col_mobile;

$space = (float)$module->settings->space_between / 2;

$default_css = [
	'&.fl-module-bw-team-grid' => [
		'.team_member_position' => array_merge(
			['color' => $module->getModuleSettingColor('titles_color')],
			$module->getTypography('position_typography')
		),
		'.team_member_name' => array_merge(
			['color' => $module->getModuleSettingColor('name_color')],
			$module->getTypography('names_typography')
		),
		'.modal-position' => array_merge(
			['color' => $module->getModuleSettingColor('modal_position_color')],
			$module->getTypography('modal_position_typography')
		),
		'.modal-title' => array_merge(
			['color' => $module->getModuleSettingColor('modal_name_color')],
			$module->getTypography('modal_name_typography')
		),
		'.team_member_modal_text' => array_merge(
			['color' => $module->getModuleSettingColor('modal_text_color')],
			$module->getTypography('modal_text_typography'),
		),
		'.team_member_modal_text' => [
			'p' => array_merge(
				['color' => $module->getModuleSettingColor('modal_text_color')],
				$module->getTypography('modal_text_typography'),
			),
			'ul' => array_merge(
				['color' => $module->getModuleSettingColor('modal_text_color')],
				$module->getTypography('modal_text_typography'),
			)
		],
		'.cta' => array_merge(
			['color' => $module->getModuleSettingColor('cta_color')],
			$module->getTypography('cta_typography'),
		),
		'.cta:hover' => [
			'color' => $module->getModuleSettingColor('cta_hover_color')
		],
		'.triptych_panel_link:hover' => [
			'.team_member_position' => ['color' => $module->getModuleSettingColor('titles_hover_color')],
			'.team_member_name' => ['color' => $module->getModuleSettingColor('titles_hover_color')]
		],
		'.team_member' => [
			'width' => 'calc('.$desktop_width.'% - 1px)',
			'padding-left' => $space.'px',
			'padding-right' => $space.'px'
		],
		'.row' => [
			'margin-left' => '-'.$space.'px',
			'margin-right' => '-'.$space.'px'
		],
		'.close' => [
			'color' => $module->getModuleSettingColor('modal_close_color')
		],
		'.close:hover' => [
			'color' => $module->getModuleSettingColor('modal_close_hover_color')
		]
	]
];
$module->rendermoduleCSS($default_css);

$mobile_css = [
	'&.fl-module-bw-team-grid' => [
		'.team_member' => ['width' => 'calc('.$mobile_width.'% - 1px)'],
		'.team_member_position' => $module->getTypography('position_typography_responsive'),
		'.team_member_name' => $module->getTypography('names_typography_responsive'),
		'.modal-position' => $module->getTypography('modal_position_typography_responsive'),
		'.modal-title' => $module->getTypography('modal_name_typography_responsive'),
		'.team_member_modal_text' => $module->getTypography('modal_text_typography_responsive')
	]
];
$module->rendermoduleCSSResponsiveMobile($mobile_css);

$tablet_css = [
	'&.fl-module-bw-team-grid' => [
		'.team_member' => ['width' => 'calc('.$tablet_width.'% - 1px)'],
		'.team_member_position' => $module->getTypography('position_typography_medium'),
		'.team_member_name' => $module->getTypography('names_typography_medium'),
		'.modal-position' => $module->getTypography('modal_position_typography_medium'),
		'.modal-title' => $module->getTypography('modal_name_typography_medium'),
		'.team_member_modal_text' => $module->getTypography('modal_text_typography_medium')
	]
];
$module->rendermoduleCSSResponsiveTablet($tablet_css);

$desktop_css = [
	'&.fl-module-bw-team-grid' => [
		'.team_member' => ['width' => 'calc('.$desktop_width.'% - 1px)'],
		'.team_member_position' => $module->getTypography('position_typography'),
		'.team_member_name' => $module->getTypography('names_typography'),
		'.modal-position' => $module->getTypography('modal_position_typography'),
		'.modal-title' => $module->getTypography('modal_name_typography'),
		'.team_member_modal_text' => $module->getTypography('modal_text_typography')
	]
];
$module->rendermoduleCSSResponsiveDesktop($desktop_css);

?>