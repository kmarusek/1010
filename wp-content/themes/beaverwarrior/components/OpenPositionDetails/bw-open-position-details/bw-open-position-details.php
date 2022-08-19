<?php

/**
 * @class BWOpenPositionDetails
 *
 */
class BWOpenPositionDetails extends BeaverWarriorFLModule {

	/**
	 * Parent class constructor.
	 * @method __construct
	 */
	public function __construct(){
		FLBuilderModule::__construct(
			array(
				'name'            => __('Open Position Details', 'fl-builder'),
				'description'     => __('Open Position Details module', 'fl-builder'),
				'category'        => __('Space Station', 'skeleton-warrior'),
				'dir'             => $this->getModuleDirectory( __DIR__ ),
				'url'             => $this->getModuleDirectoryURI( __DIR__ ),
				'editor_export'   => true,
				'enabled'         => true,
				'partial_refresh' => true
			)
		);
	}
}

FLBuilder::register_module('BWOpenPositionDetails', array(
      'general' => array (
         'title' => __('General', 'fl-builder'),
         'sections' => array (
            'general' => array (
               'title' => "",
               'fields' => array(
                  'apply_now_link' => array(
                     'type' => 'text',
                     'label' => __('CTA link','skeleton-warrior')
                  ),
                   'apply_now_text' => array(
                       'type' => 'text',
                       'label' => __('CTA text','skeleton-warrior')
                   ),
               )
            ),
         )
      ),
   )
);