<?php

if(!class_exists('GKImageShowStyle1')){
	
	class GKImageShowStyle1
	{
		var $ID;
		var $config;
		var $path;
		var $settings;
		var $slides;
		
		function GKImageShowStyle1(	$module_id, $settings, $base_path, $group_settings, $slide_data )
		{
			$this->ID = $module_id;
			$this->path = $base_path;
			$this->settings = $group_settings;
			$this->slides = $slide_data;
			//
			$this->parse($settings);
			$this->generate();
		}
		
		function returnJSData()
		{
			return array(
				"animation_speed" => $this->config['animation_speed'],
				"animation_interval" => $this->config['animation_interval'],
				"autoanimation" => $this->config['autoanimation'],
				"animation_type" => $this->config['animation_type'],
				"preloading" => $this->config['preloading'],
				"slide_links" => $this->config['slide_links'],
				"thumbs_tooltips" => $this->config['thumbs_tooltips'],
				"text_block_opacity" => $this->config['text_block_opacity'],
				"text_block_bgimage" => $this->config['text_block_bgimage']
			);
		}
		
		function parse($settings)
		{
			// creating configuration array (hash)
			$this->config = array(
										"thumbs_margin" => '0 0 0 0',
										"thumbs_padding" => '0 0 0 0',
										"thumbs_border" => '', // 1px solid red
										"thumbs_border_width" => 0,
										"thumbs_rows" => 3,
										"thumbs_cols" => 3,
										"thumbs_position" => 'right', // top|right|bottom|left|none
										"thumbs_tooltips" => true, // true|false
										"image_margin" => '0 0 0 0',
										"image_padding" => '0 0 0 0',
										"image_border" => '', // 1px solid red
										"image_border_width" => 0,
										"show_text_block" => true, // true|false
										"text_block_height" => 100,
										"text_block_bgimage" => false,
										"text_block_bgcolor" => "#000",
										"text_block_opacity" => 0.5,
										"text_block_position" => 'bottom', // top|bottom
										"clean_xhtml" => true, // true |false
										"readmore_button" => true, // true |false
										"title" => true, // true |false
										"wordcount" => 30,
										"title_link" => true, // true |false
										"readmore_text" => 'See details',
										"slide_links" => true, // true |false
										"preloading" => true, // true |false
										"animation_speed" => 500,
										"animation_interval" => 5000,
										"autoanimation" => true, // true |false
										"animation_type" => 'opacity' // top|bottom|right|left|opacity
									);
			// exploding settings
			$settings = preg_replace("/\n$/", '', $settings);
			$exploded_settings = explode(';', $settings);
			// parsing
			for( $i = 0; $i < count($exploded_settings) - 1; $i++ )
			{
				// preparing pair key-value
				$pair = explode('=', trim($exploded_settings[$i]));
				// extracting key and value from pair	
				$key = $pair[0];
				$value = $pair[1];	
				// checking existing of key in config array
				if(isset($this->config[$key]))
				{
					// setting value for key
					$this->config[$key] = $value;
				}
			}	
		}
		
		function generate()
		{
			require(JModuleHelper::getLayoutPath('mod_gk_image_show', 'style1'.DS.'tmpl'.DS.'content'));
		}
	}
	
}

?>