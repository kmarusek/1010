<?php

/**
 * @class BWOpenPositions
 *
 */
class BWOpenPositions extends BeaverWarriorFLModule {

	/**
	 * Parent class constructor.
	 * @method __construct
	 */
	public function __construct(){
		FLBuilderModule::__construct(
			array(
				'name'            => __('Open Positions', 'fl-builder'),
				'description'     => __('Open Positions module', 'fl-builder'),
				'category'        => __('Space Station', 'skeleton-warrior'),
				'dir'             => $this->getModuleDirectory( __DIR__ ),
				'url'             => $this->getModuleDirectoryURI( __DIR__ ),
				'editor_export'   => true,
				'enabled'         => true,
				'partial_refresh' => true
			)

		);
        $this->add_css( 'font-awesome' );
    }
}

FLBuilder::register_module(
	'BWOpenPositions', array(
	'style' => array (
	    'title' => __('General', 'fl-builder'),
	    'sections' => array (
	            'style' => array (
	                'fields' => array(
                        'subheading' => [
                            'type' => 'text',
                            'label' => __('Subheading','skeleton-warrior')
                        ],
                        'heading' => [
                            'type' => 'text',
                            'label' => __('Heading','skeleton-warrior')
                        ],
	                )
	            )
	        )
	    )
	)
);