<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package VPBakeryVisualComposer
 *
 */
if (!defined('_PS_VERSION_'))
    exit;
//$vc_is_wp_version_3_6_more = version_compare( preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), '3.6' ) >= 0;

// Used in "Button", "Call $vc_main->l('Blue')to Action", "Pie chart" blocks

//$vc_main->l

$vcPsImgSizesOption = JsComposer::getPsImgSizesOption();
//var_dump($vc_main->image_sizes_dropdown);
$colors_arr = array(
	$vc_main->l('Grey') => 'wpb_button',
	$vc_main->l('Blue') => 'btn-primary',
	$vc_main->l('Turquoise') => 'btn-info',
	$vc_main->l('Green') => 'btn-success',
	$vc_main->l('Orange') => 'btn-warning',
	$vc_main->l('Red') => 'btn-danger',
	$vc_main->l('Black') => "btn-inverse"
);



// Used in "Button" and "Call to Action" blocks
$size_arr = array(
	$vc_main->l('Regular size') => 'wpb_regularsize',
	$vc_main->l('Large') => 'btn-large',
	$vc_main->l('Small') => 'btn-small',
	$vc_main->l('Mini') => "btn-mini"
);

$target_arr = array(
	$vc_main->l('Same window') => '_self',
	$vc_main->l('New window') => "_blank"
);

$add_css_animation = array(
	'type' => 'dropdown',
	'heading' => $vc_main->l('CSS Animation'),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		$vc_main->l('No') => '',
		$vc_main->l('Top to bottom') => 'top-to-bottom',
		$vc_main->l('Bottom to top') => 'bottom-to-top',
		$vc_main->l('Left to right') => 'left-to-right',
		$vc_main->l('Right to left') => 'right-to-left',
		$vc_main->l('Appear from center') => "appear"
	),
	'description' => $vc_main->l('Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.')
);

vc_map( array(
	'name' => $vc_main->l('Row'),
	'base' => 'vc_row',
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'show_settings_on_create' => false,
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Place content elements inside the row'),
	'params' => array(
        /* New Params */
        array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Row stretch'),
			'param_name' => 'full_width',
			'value' => array(
				$vc_main->l('Default') => '',
				$vc_main->l('Stretch row') => 'stretch_row',
				$vc_main->l('Stretch row and content') => 'stretch_row_content',
				$vc_main->l('Stretch row and content (no paddings)') => 'stretch_row_content_no_spaces',
			),
			'description' => $vc_main->l('Select stretching options for row and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).'),
		),
        array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Columns gap'),
			'param_name' => 'gap',
			'value' => array(
				'0px' => '0',
				'1px' => '1',
				'2px' => '2',
				'3px' => '3',
				'4px' => '4',
				'5px' => '5',
				'10px' => '10',
				'15px' => '15',
				'20px' => '20',
				'25px' => '25',
				'30px' => '30',
				'35px' => '35',
			),
			'std' => '0',
			'description' => $vc_main->l('Select gap between columns in row.'),
		),
        array(
			'type' => 'checkbox',
			'heading' => $vc_main->l( 'Full height row?'),
			'param_name' => 'full_height',
			'description' => $vc_main->l( 'If checked row will be set to full height.'),
			'value' => array( $vc_main->l( 'Yes') => 'yes' ),
		),
        array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Columns position'),
			'param_name' => 'columns_placement',
			'value' => array(
				$vc_main->l('Middle') => 'middle',
				$vc_main->l('Top') => 'top',
				$vc_main->l('Bottom') => 'bottom',
				$vc_main->l('Stretch') => 'stretch',
			),
			'description' => $vc_main->l('Select columns position within row.'),
			'dependency' => array(
				'element' => 'full_height',
				'not_empty' => true,
			),
		),
        array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Equal height'),
			'param_name' => 'equal_height',
			'description' => $vc_main->l('If checked columns will be set to equal height.'),
			'value' => array( $vc_main->l('Yes') => 'yes' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Content position'),
			'param_name' => 'content_placement',
			'value' => array(
				$vc_main->l('Default') => '',
				$vc_main->l('Top') => 'top',
				$vc_main->l('Middle') => 'middle',
				$vc_main->l('Bottom') => 'bottom',
			),
			'description' => $vc_main->l('Select content position within columns.'),
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l( 'Use video background?'),
			'param_name' => 'video_bg',
			'description' => $vc_main->l( 'If checked, video will be used as row background.' ),
			'value' => array( $vc_main->l( 'Yes') => 'yes' ),
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'YouTube link'),
			'param_name' => 'video_bg_url',
			'value' => '',
			// default video url
			'description' => $vc_main->l( 'Add YouTube link.'),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Parallax'),
			'param_name' => 'video_bg_parallax',
			'value' => array(
				$vc_main->l( 'None') => '',
				$vc_main->l( 'Simple') => 'content-moving',
				$vc_main->l( 'With fade') => 'content-moving-fade',
			),
			'description' => $vc_main->l( 'Add parallax type background for row.'),
			'dependency' => array(
				'element' => 'video_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Parallax'),
			'param_name' => 'parallax',
			'value' => array(
				$vc_main->l( 'None') => '',
				$vc_main->l( 'Simple') => 'content-moving',
				$vc_main->l( 'With fade') => 'content-moving-fade',
			),
			'description' => $vc_main->l( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).' ),
			'dependency' => array(
				'element' => 'video_bg',
				'is_empty' => true,
			),
		),
		array(
			'type' => 'attach_image',
			'heading' => $vc_main->l( 'Image'),
			'param_name' => 'parallax_image',
			'value' => '',
			'description' => $vc_main->l( 'Select image from media library.'),
			'dependency' => array(
				'element' => 'parallax',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'el_id',
			'heading' => $vc_main->l( 'Row ID'),
			'param_name' => 'el_id',
			'description' => sprintf( $vc_main->l( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
        /* New params end */
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Font Color'),
			'param_name' => 'font_color',
			'description' => $vc_main->l('Select font color'),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
		),
		array(
			'type' => 'css_editor',
			'heading' => $vc_main->l('Css'),
			'param_name' => 'css',
			// 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
			'group' => $vc_main->l('Design options')
		)
	),
	'js_view' => 'VcRowView'
) );
vc_map( array(
	'name' => $vc_main->l('Row'), //Inner Row
	'base' => 'vc_row_inner',
	'content_element' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-row',
	'weight' => 1000,
	'show_settings_on_create' => false,
	'description' => $vc_main->l('Place content elements inside the row'),
	'params' => array(
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Font Color'),
			'param_name' => 'font_color',
			'description' => $vc_main->l('Select font color'),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		),
		array(
			'type' => 'css_editor',
			'heading' => $vc_main->l('Css'),
			'param_name' => 'css',
			// 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
			'group' => $vc_main->l('Design options')
		)
	),
	'js_view' => 'VcRowView'
) );
$column_width_list = array(
	$vc_main->l('1 column - 1/12') => '1/12',
	$vc_main->l('2 columns - 1/6') => '1/6',
	$vc_main->l('3 columns - 1/4') => '1/4',
	$vc_main->l('4 columns - 1/3') => '1/3',
	$vc_main->l('5 columns - 5/12') => '5/12',
	$vc_main->l('6 columns - 1/2') => '1/2',
	$vc_main->l('7 columns - 7/12') => '7/12',
	$vc_main->l('8 columns - 2/3') => '2/3',
	$vc_main->l('9 columns - 3/4') => '3/4',
	$vc_main->l('10 columns - 5/6') => '5/6',
	$vc_main->l('11 columns - 11/12') => '11/12',
	$vc_main->l('12 columns - 1/1') => '1/1'
);
vc_map( array(
	'name' => $vc_main->l('Column'),
	'base' => 'vc_column',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Font Color'),
			'param_name' => 'font_color',
			'description' => $vc_main->l('Select font color'),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		),
		array(
			'type' => 'css_editor',
			'heading' => $vc_main->l('Css'),
			'param_name' => 'css',
			// 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
			'group' => $vc_main->l('Design options')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Width'),
			'param_name' => 'width',
			'value' => $column_width_list,
			'group' => $vc_main->l('Width & Responsiveness'),
			'description' => $vc_main->l('Select column width.'),
			'std' => '1/1'
		),
		array(
			'type' => 'column_offset',
			'heading' => $vc_main->l('Responsiveness'),
			'param_name' => 'offset',
			'group' => $vc_main->l('Width & Responsiveness'),
			'description' => $vc_main->l('Adjust column for different screen sizes. Control width, offset and visibility settings.')
		)
	),
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => $vc_main->l("Column"),
	"base" => "vc_column_inner",
	"class" => "",
	"icon" => "",
	"wrapper_class" => "",
	"controls" => "full",
	"allowed_container_element" => false,
	"content_element" => false,
	"is_container" => true,
	"params" => array(
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Font Color'),
			'param_name' => 'font_color',
			'description' => $vc_main->l('Select font color'),
			'edit_field_class' => 'vc_col-md-6 vc_column'
		),
		array(
			"type" => "textfield",
			"heading" => $vc_main->l("Extra class name"),
			"param_name" => "el_class",
			"value" => "",
			"description" => $vc_main->l("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.")
		),
		array(
			"type" => "css_editor",
			"heading" => $vc_main->l('Css'),
			"param_name" => "css",
			// "description" => $vc_main->l("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer"),
			"group" => $vc_main->l('Design options')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Width'),
			'param_name' => 'width',
			'value' => $column_width_list,
			'group' => $vc_main->l('Width & Responsiveness'),
			'description' => $vc_main->l('Select column width.'),
			'std' => '1/1'
		)
	),
	"js_view" => 'VcColumnView'
) );
/* Text Block
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Text Block'),
	'base' => 'vc_column_text',
	'icon' => 'icon-wpb-layer-shape-text',
	'wrapper_class' => 'clearfix',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('A block of text with WYSIWYG editor'),
	'params' => array(
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => $vc_main->l('Text'),
			'param_name' => 'content',
			'value' => $vc_main->l('<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>')
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		),
		array(
			'type' => 'css_editor',
			'heading' => $vc_main->l('Css'),
			'param_name' => 'css',
			// 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
			'group' => $vc_main->l('Design options')
		)
	)
) );

/* Latest tweets
---------------------------------------------------------- */
/*vc_map( array(
    'name' => $vc_main->l('Twitter Widget'),
    'base' => 'vc_twitter',
    'icon' => 'icon-wpb-balloon-twitter-left',
    'category' => $vc_main->l('Social'),
    'params' => array(
  array(
        'type' => 'textfield',
        'heading' => $vc_main->l('Widget title'),
        'param_name' => 'title',
        'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
  ),
  array(
        'type' => 'textfield',
        'heading' => $vc_main->l('Twitter username'),
        'param_name' => 'twitter_name',
        'admin_label' => true,
        'description' => $vc_main->l('Type in twitter profile name from which load tweets.')
  ),
  array(
        'type' => 'dropdown',
        'heading' => $vc_main->l('Tweets count'),
        'param_name' => 'tweets_count',
        'admin_label' => true,
        'value' => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15),
        'description' => $vc_main->l('How many recent tweets to load.')
  ),
  array(
        'type' => 'textfield',
        'heading' => $vc_main->l('Extra class name'),
        'param_name' => 'el_class',
        'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
  )
)
) );*/

/* Separator (Divider)
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Separator'),
	'base' => 'vc_separator',
	'icon' => 'icon-wpb-ui-separator',
	'show_settings_on_create' => true,
	'category' => $vc_main->l('Content'),
//"controls"	=> 'popup_delete',
	'description' => $vc_main->l('Horizontal separator line'),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Color'),
			'param_name' => 'color',
			'value' => array_merge( getVcShared( 'colors' ), array( $vc_main->l('Custom color') => 'custom' ) ),
			'std' => 'grey',
			'description' => $vc_main->l('Separator color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Custom Border Color'),
			'param_name' => 'accent_color',
			'description' => $vc_main->l('Select border color for your element.'),
			'dependency'  => array(
				'element' => 'color',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Style'),
			'param_name' => 'style',
			'value' => getVcShared( 'separator styles' ),
			'description' => $vc_main->l('Separator style.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Element width'),
			'param_name' => 'el_width',
			'value' => getVcShared( 'separator widths' ),
			'description' => $vc_main->l('Separator element width in percents.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );

/* Textual block
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Separator with Text'),
	'base' => 'vc_text_separator',
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Horizontal separator line with heading'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'holder' => 'div',
			'value' => $vc_main->l('Title'),
			'description' => $vc_main->l('Separator title.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Title position'),
			'param_name' => 'title_align',
			'value' => array(
				$vc_main->l('Align center') => 'separator_align_center',
				$vc_main->l('Align left') => 'separator_align_left',
				$vc_main->l('Align right') => "separator_align_right"
			),
			'description' => $vc_main->l('Select title location.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Color'),
			'param_name' => 'color',
			'value' => array_merge( getVcShared( 'colors' ), array( $vc_main->l('Custom color') => 'custom' ) ),
			'std' => 'grey',
			'description' => $vc_main->l('Separator color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => $vc_main->l('Custom Color'),
			'param_name'  => 'accent_color',
			'description' => $vc_main->l('Custom separator color for your element.'),
			'dependency'  => array(
				'element' => 'color',
				'value'   => array( 'custom' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Style'),
			'param_name' => 'style',
			'value' => getVcShared( 'separator styles' ),
			'description' => $vc_main->l('Separator style.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Element width'),
			'param_name' => 'el_width',
			'value' => getVcShared( 'separator widths' ),
			'description' => $vc_main->l('Separator element width in percents.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'js_view' => 'VcTextSeparatorView'
) );

/* Message box
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Message Box'),
	'base' => 'vc_message',
	'icon' => 'icon-wpb-information-white',
	'wrapper_class' => 'alert',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Notification box'),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Message box type'),
			'param_name' => 'color',
			'value' => array(
				$vc_main->l('Informational') => 'alert-info',
				$vc_main->l('Warning') => 'alert-warning',
				$vc_main->l('Success') => 'alert-success',
				$vc_main->l('Error') => "alert-danger"
			),
			'description' => $vc_main->l('Select message type.'),
			'param_holder_class' => 'vc_message-type'
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Style'),
			'param_name' => 'style',
			'value' => getVcShared( 'alert styles' ),
			'description' => $vc_main->l('Alert style.')
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'messagebox_text',
			'heading' => $vc_main->l('Message text'),
			'param_name' => 'content',
			'value' => $vc_main->l('<p>I am message box. Click edit button to change this text.</p>')
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'js_view' => 'VcMessageView'
) );

/* Facebook like button
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Facebook Like'),
	'base' => 'vc_facebook',
	'icon' => 'icon-wpb-balloon-facebook-left',
	'category' => $vc_main->l('Social'),
	'description' => $vc_main->l('Facebook like button'),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button type'),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				$vc_main->l('Standard') => 'standard',
				$vc_main->l('Button count') => 'button_count',
				$vc_main->l('Box count') => 'box_count'
			),
			'description' => $vc_main->l('Select button type.')
		)
	)
) );

/* Tweetmeme button
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Tweetmeme Button'),
	'base' => 'vc_tweetmeme',
	'icon' => 'icon-wpb-tweetme',
	'category' => $vc_main->l('Social'),
	'description' => $vc_main->l('Share on twitter button'),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button type'),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				$vc_main->l('Horizontal') => 'horizontal',
				$vc_main->l('Vertical') => 'vertical',
				$vc_main->l('None') => 'none'
			),
			'description' => $vc_main->l('Select button type.')
		)
	)
) );

/* Google+ button
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Google+ Button'),
	'base' => 'vc_googleplus',
	'icon' => 'icon-wpb-application-plus',
	'category' => $vc_main->l('Social'),
	'description' => $vc_main->l('Recommend on Google'),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button size'),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				$vc_main->l('Standard') => '',
				$vc_main->l('Small') => 'small',
				$vc_main->l('Medium') => 'medium',
				$vc_main->l('Tall') => 'tall'
			),
			'description' => $vc_main->l('Select button size.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Annotation'),
			'param_name' => 'annotation',
			'admin_label' => true,
			'value' => array(
				$vc_main->l('Inline') => 'inline',
				$vc_main->l('Bubble') => '',
				$vc_main->l('None') => 'none'
			),
			'description' => $vc_main->l('Select type of annotation')
		)
	)
) );






/* Pinterest button
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Pinterest'),
	'base' => 'vc_pinterest',
	'icon' => 'icon-wpb-pinterest',
	'category' => $vc_main->l('Social'),
	'description' => $vc_main->l('Pinterest button'),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button layout'),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				$vc_main->l('Horizontal') => '',
				$vc_main->l('Vertical') => 'vertical',
				$vc_main->l('No count') => 'none' ),
			'description' => $vc_main->l('Select button layout.')
		)
	)
) );

/* Toggle (FAQ)
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('FAQ'),
	'base' => 'vc_toggle',
	'icon' => 'icon-wpb-toggle-small-expand',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Toggle element for Q&A block'),
	'params' => array(
		array(
			'type' => 'textfield',
			'holder' => 'h4',
			'class' => 'toggle_title',
			'heading' => $vc_main->l('Toggle title'),
			'param_name' => 'title',
			'value' => $vc_main->l('Toggle title'),
			'description' => $vc_main->l('Toggle block title.')
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'toggle_content',
			'heading' => $vc_main->l('Toggle content'),
			'param_name' => 'content',
			'value' => $vc_main->l('<p>Toggle content goes here, click edit button to change this text.</p>'),
			'description' => $vc_main->l('Toggle block content.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Default state'),
			'param_name' => 'open',
			'value' => array(
				$vc_main->l('Closed') => 'false',
				$vc_main->l('Open') => 'true'
			),
			'description' => $vc_main->l('Select "Open" if you want toggle to be open by default.')
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'js_view' => 'VcToggleView'
) );

/* Single image */
vc_map( array(
	'name' => $vc_main->l('Single Image'),
	'base' => 'vc_single_image',
	'icon' => 'icon-wpb-single-image',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Simple image with CSS animation'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'attach_image',
			'heading' => $vc_main->l('Image'),
			'param_name' => 'image',
			'value' => '',
			'description' => $vc_main->l('Select image from media library.')
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Image size'),
			'param_name' => 'img_size',
			'description' => $vc_main->l('Enter image size. Example: '.vc_get_image_sizes_string().'. Leave empty to use main image.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Image alignment'),
			'param_name' => 'alignment',
			'value' => array(
				$vc_main->l('Align left') => '',
				$vc_main->l('Align right') => 'right',
				$vc_main->l('Align center') => 'center'
			),
			'description' => $vc_main->l('Select image alignment.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Image style'),
			'param_name' => 'style',
			'value' => getVcShared( 'single image styles' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Border color'),
			'param_name' => 'border_color',
			'value' => getVcShared( 'colors' ),
			'std' => 'grey',
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'vc_box_border', 'vc_box_border_circle', 'vc_box_outline', 'vc_box_outline_circle' )
			),
			'description' => $vc_main->l('Border color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Link to large image?'),
			'param_name' => 'img_link_large',
			'description' => $vc_main->l('If selected, image will be linked to the larger image.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'href',
			'heading' => $vc_main->l('Image link'),
			'param_name' => 'link',
			'description' => $vc_main->l('Enter URL if you want this image to have a link.'),
			'dependency' => array(
				'element' => 'img_link_large',
				'is_empty' => true,
				'callback' => 'wpb_single_image_img_link_dependency_callback'
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Link Target'),
			'param_name' => 'img_link_target',
			'value' => $target_arr,
			'dependency' => array(
				'element' => 'img_link',
				'not_empty' => true
			)
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		),
		array(
			'type' => 'css_editor',
			'heading' => $vc_main->l('Css'),
			'param_name' => 'css',
			// 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
			'group' => $vc_main->l('Design options')
		)
	)
) );

/* Gallery/Slideshow
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Image Gallery'),
	'base' => 'vc_gallery',
	'icon' => 'icon-wpb-images-stack',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Responsive image gallery'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Gallery type'),
			'param_name' => 'type',
			'value' => array(
				$vc_main->l('Flex slider fade') => 'flexslider_fade',
				$vc_main->l('Flex slider slide') => 'flexslider_slide',
				$vc_main->l('Nivo slider') => 'nivo',
				$vc_main->l('Image grid') => 'image_grid'
			),
			'description' => $vc_main->l('Select gallery type.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Auto rotate slides'),
			'param_name' => 'interval',
			'value' => array( 3, 5, 10, 15, $vc_main->l('Disable') => 0 ),
			'description' => $vc_main->l('Auto rotate slides each X seconds.'),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'flexslider_fade', 'flexslider_slide', 'nivo' )
			)
		),
		array(
			'type' => 'attach_images',
			'heading' => $vc_main->l('Images'),
			'param_name' => 'images',
			'value' => '',
			'description' => $vc_main->l('Select images from media library.')
		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Image size'),
//			'param_name' => 'img_size',
//			'description' => $vc_main->l('Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.')
//		),
        array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Image size'),
			'param_name' => 'img_size',
			'value' => $vc_main->image_sizes_dropdown,
			'description' => $vc_main->l('Enter image size.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('On click'),
			'param_name' => 'eventclick',
			'value' => array(
				$vc_main->l('Open prettyPhoto') => 'link_image',
				$vc_main->l('Do nothing') => 'link_no',
				$vc_main->l('Open custom link') => 'custom_link'
			),
			'description' => $vc_main->l('Define action for onclick event if needed.')
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => $vc_main->l('Custom links'),
			'param_name' => 'custom_links',
			'description' => $vc_main->l('Enter links for each slide here. Divide links with linebreaks or comma (Enter) or (,) . '),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Custom link target'),
			'param_name' => 'custom_links_target',
			'description' => $vc_main->l('Select where to open  custom links.'),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $target_arr
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );

/* Image Carousel
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Image Carousel'),
	'base' => 'vc_images_carousel',
	'icon' => 'icon-wpb-images-carousel',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Animated carousel with images'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'attach_images',
			'heading' => $vc_main->l('Images'),
			'param_name' => 'images',
			'value' => '',
			'description' => $vc_main->l('Select images from media library.')
		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Image size'),
//			'param_name' => 'img_size',
//			'description' => $vc_main->l('Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.')
//		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Image size'),
			'param_name' => 'img_size',
			'value' => $vc_main->image_sizes_dropdown,
			'description' => $vc_main->l('Enter image size.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('On click'),
			'param_name' => 'eventclick',
			'value' => array(
				$vc_main->l('Open prettyPhoto') => 'link_image',
				$vc_main->l('Do nothing') => 'link_no',
				$vc_main->l('Open custom link') => 'custom_link'
			),
			'description' => $vc_main->l('What to do when slide is clicked?')
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => $vc_main->l('Custom links'),
			'param_name' => 'custom_links',
			'description' => $vc_main->l('Enter links for each slide here. Divide links with linebreaks or comma (Enter) or (,) . '),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Custom link target'),
			'param_name' => 'custom_links_target',
			'description' => $vc_main->l('Select where to open  custom links.'),
			'dependency' => array(
				'element' => 'onclick',
				'value' => array( 'custom_link' )
			),
			'value' => $target_arr
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Slider mode'),
			'param_name' => 'mode',
			'value' => array(
				$vc_main->l('Horizontal') => 'horizontal',
				$vc_main->l('Vertical') => 'vertical'
			),
			'description' => $vc_main->l('Slides will be positioned horizontally (for horizontal swipes) or vertically (for vertical swipes)')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Slider speed'),
			'param_name' => 'speed',
			'value' => '5000',
			'description' => $vc_main->l('Duration of animation between slides (in ms)')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Slides per view'),
			'param_name' => 'slides_per_view',
			'value' => '1',
			'description' => $vc_main->l('Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Supports also "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode isn\'t compatible with loop mode.')
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Slider autoplay'),
			'param_name' => 'autoplay',
			'description' => $vc_main->l('Enables autoplay mode.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Hide pagination control'),
			'param_name' => 'hide_pagination_control',
			'description' => $vc_main->l('If YES pagination control will be removed.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Hide prev/next buttons'),
			'param_name' => 'hide_prev_next_buttons',
			'description' => $vc_main->l('If "YES" prev/next control will be removed.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Partial view'),
			'param_name' => 'partial_view',
			'description' => $vc_main->l('If "YES" part of the next slide will be visible on the right side.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Slider loop'),
			'param_name' => 'wrap',
			'description' => $vc_main->l('Enables loop mode.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );

/* Tabs
---------------------------------------------------------- */
$tab_id_1 = time() . '-1-' . rand( 0, 100 );
$tab_id_2 = time() . '-2-' . rand( 0, 100 );
vc_map( array(
	"name" => $vc_main->l('Tabs'),
	'base' => 'vc_tabs',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-tab-content',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Tabbed content'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Auto rotate tabs'),
			'param_name' => 'interval',
			'value' => array( $vc_main->l('Disable') => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => $vc_main->l('Auto rotate tabs each X seconds.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . $vc_main->l('Tab 1') . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . $vc_main->l('Tab 2') . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => 'VcTabsView'
//	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
) );

/* Tour section
---------------------------------------------------------- */
$tab_id_1 = time() . '-1-' . rand( 0, 100 );
$tab_id_2 = time() . '-2-' . rand( 0, 100 );
WPBMap::map( 'vc_tour', array(
	'name' => $vc_main->l('Tour'),
	'base' => 'vc_tour',
	'show_settings_on_create' => false,
	'is_container' => true,
	'container_not_allowed' => true,
	'icon' => 'icon-wpb-ui-tab-content-vertical',
	'category' => $vc_main->l('Content'),
	'wrapper_class' => 'vc_clearfix',
	'description' => $vc_main->l('Vertical tabbed content'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Auto rotate slides'),
			'param_name' => 'interval',
			'value' => array( $vc_main->l('Disable') => 0, 3, 5, 10, 15 ),
			'std' => 0,
			'description' => $vc_main->l('Auto rotate slides each X seconds.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'custom_markup' => '
<div class="wpb_tabs_holder wpb_holder vc_clearfix vc_container_for_children">
<ul class="tabs_controls">
</ul>
%content%
</div>'
,
	'default_content' => '
[vc_tab title="' . $vc_main->l('Tab 1') . '" tab_id="' . $tab_id_1 . '"][/vc_tab]
[vc_tab title="' . $vc_main->l('Tab 2') . '" tab_id="' . $tab_id_2 . '"][/vc_tab]
',
	'js_view' => 'VcTabsView' 
//	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35'
) );

vc_map( array(
	'name' => $vc_main->l('Tab'),
	'base' => 'vc_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Tab title.')
		),
		array(
			'type' => 'tab_id',
			'heading' => $vc_main->l('Tab ID'),
			'param_name' => "tab_id"
		)
	),
	'js_view' => 'VcTabView'
//	'js_view' => $vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35'
) );

/* Accordion block
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Accordion'),
	'base' => 'vc_accordion',
	'show_settings_on_create' => false,
	'is_container' => true,
	'icon' => 'icon-wpb-ui-accordion',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Collapsible content panels'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Active section'),
			'param_name' => 'active_tab',
			'description' => $vc_main->l('Enter section number to be active on load or enter false to collapse all sections.')
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Allow collapsible all'),
			'param_name' => 'collapsible',
			'description' => $vc_main->l('Select checkbox to allow all sections to be collapsible.'),
			'value' => array( $vc_main->l('Allow') => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'custom_markup' => '
<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
%content%
</div>
<div class="tab_controls ui-accordion-header">
    <a class="add_tab" title="' . $vc_main->l('Add section') . '"><span class="vc_icon ui-icon-triangle-1-e"></span> <span class="tab-label">' . $vc_main->l('Add section') . '</span></a>
</div>
',
	'default_content' => '
    [vc_accordion_tab title="' . $vc_main->l('Section 1') . '"][/vc_accordion_tab]
    [vc_accordion_tab title="' . $vc_main->l('Section 2') . '"][/vc_accordion_tab]
',
	'js_view' => 'VcAccordionView'
) );
vc_map( array(
	'name' => $vc_main->l('Section'),
	'base' => 'vc_accordion_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Accordion section title.')
		),
	),
	'js_view' => 'VcAccordionTabView'
) );

/* Teaser grid
* @deprecated please use vc_posts_grid
---------------------------------------------------------- */

//vc_map( array(
//	'name' => $vc_main->l('Teaser (posts) Grid'),
//	'base' => 'vc_teaser_grid',
//	'content_element' => false,
//	'icon' => 'icon-wpb-application-icon-large',
//	'category' => $vc_main->l('Content'),
//	'params' => array(
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Widget title'),
//			'param_name' => 'title',
//			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Columns count'),
//			'param_name' => 'grid_columns_count',
//			'value' => array( 4, 3, 2, 1 ),
//			'admin_label' => true,
//			'description' => $vc_main->l('Select columns count.')
//		),
//		array(
//			'type' => 'posttypes',
//			'heading' => $vc_main->l('Post types'),
//			'param_name' => 'grid_posttypes',
//			'description' => $vc_main->l('Select post types to populate posts from.')
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Teasers count'),
//			'param_name' => 'grid_teasers_count',
//			'description' => $vc_main->l('How many teasers to show? Enter number or word "All".')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Content'),
//			'param_name' => 'grid_content',
//			'value' => array(
//				$vc_main->l('Teaser (Excerpt)') => 'teaser',
//				$vc_main->l('Full Content') => 'content'
//			),
//			'description' => $vc_main->l('Teaser layout template.')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Layout'),
//			'param_name' => 'grid_layout',
//			'value' => array(
//				$vc_main->l('Title + Thumbnail + Text') => 'title_thumbnail_text',
//				$vc_main->l('Thumbnail + Title + Text') => 'thumbnail_title_text',
//				$vc_main->l('Thumbnail + Text') => 'thumbnail_text',
//				$vc_main->l('Thumbnail + Title') => 'thumbnail_title',
//				$vc_main->l('Thumbnail only') => 'thumbnail',
//				$vc_main->l('Title + Text') => 'title_text' ),
//			'description' => $vc_main->l('Teaser layout.')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Link'),
//			'param_name' => 'grid_link',
//			'value' => array(
//				$vc_main->l('Link to post') => 'link_post',
//				$vc_main->l('Link to bigger image') => 'link_image',
//				$vc_main->l('Thumbnail to bigger image, title to post') => 'link_image_post',
//				$vc_main->l('No link') => 'link_no'
//			),
//			'description' => $vc_main->l('Link type.')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Link target'),
//			'param_name' => 'grid_link_target',
//			'value' => $target_arr,
//			'dependency' => array(
//				'element' => 'grid_link',
//				'value' => array( 'link_post', 'link_image_post' )
//			)
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Teaser grid layout'),
//			'param_name' => 'grid_template',
//			'value' => array(
//				$vc_main->l('Grid') => 'grid',
//				$vc_main->l('Grid with filter') => 'filtered_grid',
//				$vc_main->l('Carousel') => 'carousel'
//			),
//			'description' => $vc_main->l('Teaser layout template.')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Layout mode'),
//			'param_name' => 'grid_layout_mode',
//			'value' => array(
//				$vc_main->l('Fit rows') => 'fitRows',
//				$vc_main->l('Masonry') => 'masonry'
//			),
//			'dependency' => array(
//				'element' => 'grid_template',
//				'value' => array( 'filtered_grid', 'grid' )
//			),
//			'description' => $vc_main->l('Teaser layout template.')
//		),
//		array(
//			'type' => 'taxonomies',
//			'heading' => $vc_main->l('Taxonomies'),
//			'param_name' => 'grid_taxomonies',
//			'dependency' => array(
//				'element' => 'grid_template',
//				// 'not_empty' => true,
//				'value' => array( 'filtered_grid' ),
//				'callback' => 'wpb_grid_post_types_for_taxonomies_handler'
//			),
//			'description' => $vc_main->l('Select taxonomies.')
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Thumbnail size'),
//			'param_name' => 'grid_thumb_size',
//			'description' => $vc_main->l('Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ')
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Post/Page IDs'),
//			'param_name' => 'posts_in',
//			'description' => $vc_main->l('Fill this field with page/posts IDs separated by commas (,) to retrieve only them. Use this in conjunction with "Post types" field.')
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Exclude Post/Page IDs'),
//			'param_name' => 'posts_not_in',
//			'description' => $vc_main->l('Fill this field with page/posts IDs separated by commas (,) to exclude them from query.')
//		),
//		array(
//			'type' => 'exploded_textarea',
//			'heading' => $vc_main->l('Categories'),
//			'param_name' => 'grid_categories',
//			'description' => $vc_main->l('If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter) . ')
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Order by'),
//			'param_name' => 'orderby',
//			'value' => array(
//				'',
//				$vc_main->l('Date') => 'date',
//				$vc_main->l('ID') => 'ID',
//				$vc_main->l('Author') => 'author',
//				$vc_main->l('Title') => 'title',
//				$vc_main->l('Modified') => 'modified',
//				$vc_main->l('Random') => 'rand',
//				$vc_main->l('Comment count') => 'comment_count',
//				$vc_main->l('Menu order') => 'menu_order'
//			),
//			'description' => sprintf( $vc_main->l('Select how to sort retrieved posts. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
//		),
//		array(
//			'type' => 'dropdown',
//			'heading' => $vc_main->l('Order way'),
//			'param_name' => 'order',
//			'value' => array(
//				$vc_main->l('Descending') => 'DESC',
//				$vc_main->l('Ascending') => 'ASC'
//			),
//			'description' => sprintf( $vc_main->l('Designates the ascending or descending order. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Extra class name'),
//			'param_name' => 'el_class',
//			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
//		)
//	)
//) );

/* Posts Grid
---------------------------------------------------------- */
$vc_layout_sub_controls = array(
	array( 'link_post', $vc_main->l('Link to post') ),
	array( 'no_link', $vc_main->l('No link') ),
	array( 'link_image', $vc_main->l('Link to bigger image') )
);
if( (bool)Module::isInstalled('smartblog') && (bool)Module::isEnabled('smartblog')){ // smartblog
   
   $nthumbs = JsComposer::getSmartBlogPostsThumbSizes();
   
   $nthumbs = array_merge(array('Full'=>''), $nthumbs);
   
vc_map( array(
	'name' => $vc_main->l('Posts Grid'),
	'base' => 'vc_posts_grid',
	'icon' => 'icon-wpb-application-icon-large',
	'description' => $vc_main->l('Posts in grid view'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'loop',
			'heading' => $vc_main->l('Grids content'),
			'param_name' => 'loop',
			'settings' => array(
				'size' => array( 'hidden' => false, 'value' => 10 ),
				'order_by' => array( 'value' => 'created' ),
			),
			'description' => $vc_main->l('Create SmartBlog loop, to populate content from your site.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Columns count'),
			'param_name' => 'grid_columns_count',
			'value' => array( 6, 4, 3, 2, 1 ),
			'std' => 3,
			'admin_label' => true,
			'description' => $vc_main->l('Select columns count.')
		),
		
		array(
			'type' => 'sorted_list',
			'heading' => $vc_main->l('Teaser layout'),
			'param_name' => 'grid_layout',
			'description' => $vc_main->l('Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.'),
			'value' => 'title,image,text',
			'options' => array(
				array( 'image', $vc_main->l('Thumbnail'), $vc_layout_sub_controls ),
				array( 'title', $vc_main->l('Title'), $vc_layout_sub_controls ),
				array( 'text', $vc_main->l('Text'), array(
					array( 'excerpt', $vc_main->l('Teaser/Excerpt') ),
					array( 'text', $vc_main->l('Full content') )
				) ),
				array( 'link', $vc_main->l('Read more link') )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Link target'),
			'param_name' => 'grid_link_target',
			'value' => $target_arr,
			// 'dependency' => array(
			//     'element' => 'grid_link',
			//     'value' => array( 'link_post', 'link_image_post' )
			// )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Show filter'),
			'param_name' => 'filter',
			'value' => array( $vc_main->l('Yes, please') => 'yes' ),
			'description' => $vc_main->l('Select to add animated category filter to your posts grid.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Layout mode'),
			'param_name' => 'grid_layout_mode',
			'value' => array(
				$vc_main->l('Fit rows') => 'fitRows',
				$vc_main->l('Masonry') => 'masonry'
			),
			'description' => $vc_main->l('Teaser layout template.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Thumbnail size'),
			'param_name' => 'grid_thumb_size',
			'description' => $vc_main->l('Select thumbnail size.'),
			'value' => $nthumbs
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
// 'html_template' => dirname(__DIR__).'/composer/shortcodes_templates/vc_posts_grid.php'
) );

/* Post Carousel
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Post Carousel'),
	'base' => 'vc_carousel',
	'class' => '',
	'icon' => 'icon-wpb-vc_carousel',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Animated carousel with posts'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'loop',
			'heading' => $vc_main->l('Carousel content'),
			'param_name' => 'posts_query',
			'settings' => array(
				'size' => array( 'hidden' => false, 'value' => 10 ),
				'order_by' => array( 'value' => 'date' )
			),
			'description' => $vc_main->l('Create WordPress loop, to populate content from your site.')
		),
		array(
			'type' => 'sorted_list',
			'heading' => $vc_main->l('Teaser layout'),
			'param_name' => 'layout',
			'description' => $vc_main->l('Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.'),
			'value' => 'title,image,text',
			'options' => array(
				array( 'image', $vc_main->l('Thumbnail'), $vc_layout_sub_controls ),
				array( 'title', $vc_main->l('Title'), $vc_layout_sub_controls ),
				array( 'text', $vc_main->l('Text'), array(
					array( 'excerpt', $vc_main->l('Teaser/Excerpt') ),
					array( 'text', $vc_main->l('Full content') )
				) ),
				array( 'link', $vc_main->l('Read more link') )
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Link target'),
			'param_name' => 'link_target',
			'value' => $target_arr,
			// 'dependency' => array( 'element' => 'link', 'value' => array( 'link_post', 'link_image_post', 'link_image' ) )
		),
		
                array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Thumbnail size'),
			'param_name' => 'thumb_size',
			'description' => $vc_main->l('Select thumbnail size.'),
			'value' => $nthumbs
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Slider speed'),
			'param_name' => 'speed',
			'value' => '5000',
			'description' => $vc_main->l('Duration of animation between slides (in ms)')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Slider mode'),
			'param_name' => 'mode',
			'value' => array( $vc_main->l('Horizontal') => 'horizontal', $vc_main->l('Vertical') => 'vertical' ),
			'description' => $vc_main->l('Slides will be positioned horizontally (for horizontal swipes) or vertically (for vertical swipes)')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Slides per view'),
			'param_name' => 'slides_per_view',
			'value' => '1',
			'description' => $vc_main->l('Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Also supports for "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode doesn\'t compatible with loop mode.')
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Slider autoplay'),
			'param_name' => 'autoplay',
			'description' => $vc_main->l('Enables autoplay mode.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Hide pagination control'),
			'param_name' => 'hide_pagination_control',
			'description' => $vc_main->l('If "YES" pagination control will be removed'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Hide prev/next buttons'),
			'param_name' => 'hide_prev_next_buttons',
			'description' => $vc_main->l('If "YES" prev/next control will be removed'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Partial view'),
			'param_name' => 'partial_view',
			'description' => $vc_main->l('If "YES" part of the next slide will be visible on the right side'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Slider loop'),
			'param_name' => 'wrap',
			'description' => $vc_main->l('Enables loop mode.'),
			'value' => array( $vc_main->l('Yes, please') => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );


/* Posts slider
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Posts Slider'),
	'base' => 'vc_posts_slider',
	'icon' => 'icon-wpb-slideshow',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Slider with WP Posts'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Slider type'),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => array(
				$vc_main->l('Flex slider fade') => 'flexslider_fade',
				$vc_main->l('Flex slider slide') => 'flexslider_slide',
				$vc_main->l('Nivo slider') => 'nivo'
			),
			'description' => $vc_main->l('Select slider type.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Slides count'),
			'param_name' => 'count',
			'description' => $vc_main->l('How many slides to show? Leave blank in order to fetch all posts.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Auto rotate slides'),
			'param_name' => 'interval',
			'value' => array( 3, 5, 10, 15, $vc_main->l('Disable') => 0 ),
			'description' => $vc_main->l('Auto rotate slides each X seconds.')
		),
//		array(
//			'type' => 'posttypes',
//			'heading' => $vc_main->l('Post types'),
//			'param_name' => 'posttypes',
//			'description' => $vc_main->l('Select post types to populate posts from.')
//		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Description'),
			'param_name' => 'slides_content',
			'value' => array(
				$vc_main->l('No description') => '',
				$vc_main->l('Teaser (Excerpt)') => 'teaser'
			),
			'description' => $vc_main->l('Some sliders support description text, what content use for it?'),
			'dependency' => array(
				'element' => 'type',
				'value' => array( 'flexslider_fade', 'flexslider_slide' )
			),
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Output post title?'),
			'param_name' => 'slides_title',
			'description' => $vc_main->l('If selected, title will be printed before the teaser text.'),
			'value' => array( $vc_main->l('Yes, please') => true ),
			'dependency' => array(
				'element' => 'slides_content',
				'value' => array( 'teaser' )
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Link'),
			'param_name' => 'link',
			'value' => array(
				$vc_main->l('Link to post') => 'link_post',
				$vc_main->l('Link to bigger image') => 'link_image',
				$vc_main->l('Open custom link') => 'custom_link',
				$vc_main->l('No link') => 'link_no'
			),
			'description' => $vc_main->l('Link type.')
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => $vc_main->l('Custom links'),
			'param_name' => 'custom_links',
			'dependency' => array( 'element' => 'link', 'value' => 'custom_link' ),
			'description' => $vc_main->l('Enter links for each slide here. Divide links with linebreaks or comma (Enter) or (,).')
		),
		               
                array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Thumbnail size'),
			'param_name' => 'thumb_size',
			'description' => $vc_main->l('Select thumbnail size.'),
			'value' => $nthumbs
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Posts IDs'),
			'param_name' => 'posts_in',
			'description' => $vc_main->l('Fill this field with page/posts IDs separated by commas (,), to retrieve only them. Use this in conjunction with "Post types" field.')
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => $vc_main->l('Categories'),
			'param_name' => 'categories',
			'description' => $vc_main->l('If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter) . ')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order by'),
			'param_name' => 'orderby',
			'value' => array(
				'',                            
				$vc_main->l('Date') => 'created',
				$vc_main->l('ID') => 'id_smart_blog_post',				
				$vc_main->l('Title') => 'meta_title',
				$vc_main->l('Modified') => 'modified',				
				$vc_main->l('Position') => 'position',
				$vc_main->l('Slug') => 'link_rewrite'
			),
			'description' => $vc_main->l('Select how to sort retrieved posts.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order by'),
			'param_name' => 'order',
			'value' => array(                                
				$vc_main->l('Descending') => 'DESC',
				$vc_main->l('Ascending') => 'ASC'
			),
			'description' => $vc_main->l('Designates the ascending or descending order.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );
} //end smartblog
/* Widgetised sidebar
---------------------------------------------------------- */
//vc_map( array(
//	'name' => $vc_main->l('Widgetised Sidebar'),
//	'base' => 'vc_widget_sidebar',
//	'class' => 'wpb_widget_sidebar_widget',
//	'icon' => 'icon-wpb-layout_sidebar',
//	'category' => $vc_main->l('Structure'),
//	'description' => $vc_main->l('Place widgetised sidebar'),
//	'params' => array(
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Widget title'),
//			'param_name' => 'title',
//			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
//		),
//		array(
//			'type' => 'widgetised_sidebars',
//			'heading' => $vc_main->l('Sidebar'),
//			'param_name' => 'sidebar_id',
//			'description' => $vc_main->l('Select which widget area output.')
//		),
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Extra class name'),
//			'param_name' => 'el_class',
//			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
//		)
//	)
//) );

/* Button
---------------------------------------------------------- */
$icons_arr = array(
	$vc_main->l('None') => 'none',
	$vc_main->l('Address book icon') => 'wpb_address_book',
	$vc_main->l('Alarm clock icon') => 'wpb_alarm_clock',
	$vc_main->l('Anchor icon') => 'wpb_anchor',
	$vc_main->l('Application Image icon') => 'wpb_application_image',
	$vc_main->l('Arrow icon') => 'wpb_arrow',
	$vc_main->l('Asterisk icon') => 'wpb_asterisk',
	$vc_main->l('Hammer icon') => 'wpb_hammer',
	$vc_main->l('Balloon icon') => 'wpb_balloon',
	$vc_main->l('Balloon Buzz icon') => 'wpb_balloon_buzz',
	$vc_main->l('Balloon Facebook icon') => 'wpb_balloon_facebook',
	$vc_main->l('Balloon Twitter icon') => 'wpb_balloon_twitter',
	$vc_main->l('Battery icon') => 'wpb_battery',
	$vc_main->l('Binocular icon') => 'wpb_binocular',
	$vc_main->l('Document Excel icon') => 'wpb_document_excel',
	$vc_main->l('Document Image icon') => 'wpb_document_image',
	$vc_main->l('Document Music icon') => 'wpb_document_music',
	$vc_main->l('Document Office icon') => 'wpb_document_office',
	$vc_main->l('Document PDF icon') => 'wpb_document_pdf',
	$vc_main->l('Document Powerpoint icon') => 'wpb_document_powerpoint',
	$vc_main->l('Document Word icon') => 'wpb_document_word',
	$vc_main->l('Bookmark icon') => 'wpb_bookmark',
	$vc_main->l('Camcorder icon') => 'wpb_camcorder',
	$vc_main->l('Camera icon') => 'wpb_camera',
	$vc_main->l('Chart icon') => 'wpb_chart',
	$vc_main->l('Chart pie icon') => 'wpb_chart_pie',
	$vc_main->l('Clock icon') => 'wpb_clock',
	$vc_main->l('Fire icon') => 'wpb_fire',
	$vc_main->l('Heart icon') => 'wpb_heart',
	$vc_main->l('Mail icon') => 'wpb_mail',
	$vc_main->l('Play icon') => 'wpb_play',
	$vc_main->l('Shield icon') => 'wpb_shield',
	$vc_main->l('Video icon') => "wpb_video"
);

vc_map( array(
	'name' => $vc_main->l('Button'),
	'base' => 'vc_button',
	'icon' => 'icon-wpb-ui-button',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Eye catching button'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Text on the button'),
			'holder' => 'button',
			'class' => 'wpb_button',
			'param_name' => 'title',
			'value' => $vc_main->l('Text on the button'),
			'description' => $vc_main->l('Text on the button.')
		),
		array(
			'type' => 'href',
			'heading' => $vc_main->l('URL (Link)'),
			'param_name' => 'href',
			'description' => $vc_main->l('Button link.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Target'),
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array( 'element'=>'href', 'not_empty'=>true, 'callback' => 'vc_button_param_target_callback' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Color'),
			'param_name' => 'color',
			'value' => $colors_arr,
			'description' => $vc_main->l('Button color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Icon'),
			'param_name' => 'icon',
			'value' => $icons_arr,
			'description' => $vc_main->l('Button icon.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Size'),
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => $vc_main->l('Button size.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'js_view' => 'VcButtonView'
) );

vc_map( array(
	'name' => $vc_main->l('Button') . " 2",
	'base' => 'vc_button2',
	'icon' => 'icon-wpb-ui-button',
	'category' => array(
		$vc_main->l('Content')),
	'description' => $vc_main->l('Eye catching button'),
	'params' => array(
		array(
			'type' => 'vc_link',
			'heading' => $vc_main->l('URL (Link)'),
			'param_name' => 'link',
			'description' => $vc_main->l('Button link.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Text on the button'),
			'holder' => 'button',
			'class' => 'vc_btn',
			'param_name' => 'title',
			'value' => $vc_main->l('Text on the button'),
			'description' => $vc_main->l('Text on the button.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Style'),
			'param_name' => 'style',
			'value' => getVcShared( 'button styles' ),
			'description' => $vc_main->l('Button style.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Color'),
			'param_name' => 'color',
			'value' => getVcShared( 'colors' ),
			'description' => $vc_main->l('Button color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		/*array(
        'type' => 'dropdown',
        'heading' => $vc_main->l('Icon'),
        'param_name' => 'icon',
        'value' => getVcShared( 'icons' ),
        'description' => $vc_main->l('Button icon.')
  ),*/
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Size'),
			'param_name' => 'size',
			'value' => getVcShared( 'sizes' ),
			'std' => 'md',
			'description' => $vc_main->l('Button size.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'js_view' => 'VcButton2View'
) );

/* Call to Action Button
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Call to Action Button'),
	'base' => 'vc_cta_button',
	'icon' => 'icon-wpb-call-to-action',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Catch visitors attention with CTA block'),
	'params' => array(
		array(
			'type' => 'textarea',
			'admin_label' => true,
			'heading' => $vc_main->l('Text'),
			'param_name' => 'call_text',
			'value' => $vc_main->l('Click edit button to change this text.'),
			'description' => $vc_main->l('Enter your content.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Text on the button'),
			'param_name' => 'title',
			'value' => $vc_main->l('Text on the button'),
			'description' => $vc_main->l('Text on the button.')
		),
		array(
			'type' => 'href',
			'heading' => $vc_main->l('URL (Link)'),
			'param_name' => 'href',
			'description' => $vc_main->l('Button link.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Target'),
			'param_name' => 'target',
			'value' => $target_arr,
			'dependency' => array( 'element' => 'href', 'not_empty' => true, 'callback' => 'vc_cta_button_param_target_callback' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Color'),
			'param_name' => 'color',
			'value' => $colors_arr,
			'description' => $vc_main->l('Button color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Icon'),
			'param_name' => 'icon',
			'value' => $icons_arr,
			'description' => $vc_main->l('Button icon.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Size'),
			'param_name' => 'size',
			'value' => $size_arr,
			'description' => $vc_main->l('Button size.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button position'),
			'param_name' => 'position',
			'value' => array(
				$vc_main->l('Align right') => 'cta_align_right',
				$vc_main->l('Align left') => 'cta_align_left',
				$vc_main->l('Align bottom') => 'cta_align_bottom'
			),
			'description' => $vc_main->l('Select button alignment.')
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	),
	'js_view' => 'VcCallToActionView'
) );

vc_map( array(
	'name' => $vc_main->l('Call to Action Button') . ' 2',
	'base' => 'vc_cta_button2',
	'icon' => 'icon-wpb-call-to-action',
	'category' => array( $vc_main->l('Content') ),
	'description' => $vc_main->l('Catch visitors attention with CTA block'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Heading first line'),
			'admin_label' => true,
			//'holder' => 'h2',
			'param_name' => 'h2',
			'value' => $vc_main->l('Hey! I am first heading line feel free to change me'),
			'description' => $vc_main->l('Text for the first heading line.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Heading second line'),
			//'holder' => 'h4',
			//'admin_label' => true,
			'param_name' => 'h4',
			'value' => '',
			'description' => $vc_main->l('Optional text for the second heading line.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('CTA style'),
			'param_name' => 'style',
			'value' => getVcShared( 'cta styles' ),
			'description' => $vc_main->l('Call to action style.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Element width'),
			'param_name' => 'el_width',
			'value' => getVcShared( 'cta widths' ),
			'description' => $vc_main->l('Call to action element width in percents.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Text align'),
			'param_name' => 'txt_align',
			'value' => getVcShared( 'text align' ),
			'description' => $vc_main->l('Text align in call to action block.')
		),
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Custom Background Color'),
			'param_name' => 'accent_color',
			'description' => $vc_main->l('Select background color for your element.')
		),
		array(
			'type' => 'textarea_html',
			//holder' => 'div',
			//'admin_label' => true,
			'heading' => $vc_main->l('Promotional text'),
			'param_name' => 'content',
			'value' => $vc_main->l('I am promo text. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.')
		),
		array(
			'type' => 'vc_link',
			'heading' => $vc_main->l('URL (Link)'),
			'param_name' => 'link',
			'description' => $vc_main->l('Button link.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Text on the button'),
			//'holder' => 'button',
			//'class' => 'wpb_button',
			'param_name' => 'title',
			'value' => $vc_main->l('Text on the button'),
			'description' => $vc_main->l('Text on the button.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button style'),
			'param_name' => 'btn_style',
			'value' => getVcShared( 'button styles' ),
			'description' => $vc_main->l('Button style.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Color'),
			'param_name' => 'color',
			'value' => getVcShared( 'colors' ),
			'description' => $vc_main->l('Button color.'),
			'param_holder_class' => 'vc_colored-dropdown'
		),
		/*array(
        'type' => 'dropdown',
        'heading' => $vc_main->l('Icon'),
        'param_name' => 'icon',
        'value' => getVcShared( 'icons' ),
        'description' => $vc_main->l('Button icon.')
  ),*/
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Size'),
			'param_name' => 'size',
			'value' => getVcShared( 'sizes' ),
			'std' => 'md',
			'description' => $vc_main->l('Button size.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Button position'),
			'param_name' => 'position',
			'value' => array(
				$vc_main->l('Align right') => 'right',
				$vc_main->l('Align left') => 'left',
				$vc_main->l('Align bottom') => 'bottom'
			),
			'description' => $vc_main->l('Select button alignment.')
		),
		$add_css_animation,
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );

/* Video element
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Video Player'),
	'base' => 'vc_video',
	'icon' => 'icon-wpb-film-youtube',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Embed YouTube/Vimeo player'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Video link'),
			'param_name' => 'link',
			'admin_label' => true,
			'description' => $vc_main->l('Link to the video. ' )
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		),
		array(
			'type' => 'css_editor',
			'heading' => $vc_main->l('Css'),
			'param_name' => 'css',
			// 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
			'group' => $vc_main->l('Design options')
		)
	)
) );

/* Google maps element
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Google Maps'),
	'base' => 'vc_gmaps',
	'icon' => 'icon-wpb-map-pin',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Map block'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'textarea_safe',
			'heading' => $vc_main->l('Map embed iframe'),
			'param_name' => 'link',
			'description' => sprintf( $vc_main->l('Visit %s to create your map. 1) Find location 2) Click "Share" and make sure map is public on the web 3) Click folder icon to reveal "Embed on my site" link 4) Copy iframe code and paste it here.'), '<a href="https://mapsengine.google.com/" target="_blank">Google maps</a>' )
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Map height'),
			'param_name' => 'size',
			'admin_label' => true,
			'description' => $vc_main->l('Enter map height in pixels. Example: 200 or leave it empty to make map responsive.')
		),
		/*array(
        'type' => 'dropdown',
        'heading' => $vc_main->l('Map type'),
        'param_name' => 'type',
        'value' => array( $vc_main->l('Map') => 'm', $vc_main->l('Satellite') => 'k', $vc_main->l('Map + Terrain') => "p" ),
        'description' => $vc_main->l('Select map type.')
  ),
  array(
        'type' => 'dropdown',
        'heading' => $vc_main->l('Map Zoom'),
        'param_name' => 'zoom',
        'value' => array( $vc_main->l('14 - Default') => 14, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20)
  ),
  array(
        'type' => 'checkbox',
        'heading' => $vc_main->l('Remove info bubble'),
        'param_name' => 'bubble',
        'description' => $vc_main->l('If selected, information bubble will be hidden.'),
        'value' => array( $vc_main->l('Yes, please') => true),
  ),*/
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );

/* Raw HTML
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Raw HTML'),
	'base' => 'vc_raw_html',
	'icon' => 'icon-wpb-raw-html',
	'category' => $vc_main->l('Structure'),
	'wrapper_class' => 'clearfix',
	'description' => $vc_main->l('Output raw html code on your page'),
	'params' => array(
		array(
			'type' => 'textarea_raw_html',
			'holder' => 'div',
			'heading' => $vc_main->l('Raw HTML'),
			'param_name' => 'content',
			'value' => base64_encode( '<p>I am raw html block.<br/>Click edit button to change this html</p>' ),
			'description' => $vc_main->l('Enter your HTML content.')
		),
	)
) );

/* Raw JS
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Raw JS'),
	'base' => 'vc_raw_js',
	'icon' => 'icon-wpb-raw-javascript',
	'category' => $vc_main->l('Structure'),
	'wrapper_class' => 'clearfix',
	'description' => $vc_main->l('Output raw javascript code on your page'),
	'params' => array(
		array(
			'type' => 'textarea_raw_html',
			'holder' => 'div',
			'heading' => $vc_main->l('Raw js'),
			'param_name' => 'content',
			'value' => $vc_main->l( base64_encode( '<script type="text/javascript"> alert("Enter your js here!" ); </script>' )),
			'description' => $vc_main->l('Enter your JS code.')
		),
	)
) );

/* Flickr
---------------------------------------------------------- */
vc_map( array(
	'base' => 'vc_flickr',
	'name' => $vc_main->l('Flickr Widget'),
	'icon' => 'icon-wpb-flickr',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Image feed from your flickr account'),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Flickr ID'),
			'param_name' => 'flickr_id',
			'admin_label' => true,
			'description' => sprintf( $vc_main->l('To find your flickID visit %s.'), '<a href="http://idgettr.com/" target="_blank">idGettr</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Number of photos'),
			'param_name' => 'count',
			'value' => array( 9, 8, 7, 6, 5, 4, 3, 2, 1 ),
			'description' => $vc_main->l('Number of photos.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Type'),
			'param_name' => 'type',
			'value' => array(
				$vc_main->l('User') => 'user',
				$vc_main->l('Group') => 'group'
			),
			'description' => $vc_main->l('Photo stream type.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display'),
			'param_name' => 'display',
			'value' => array(
				$vc_main->l('Latest') => 'latest',
				$vc_main->l('Random') => 'random'
			),
			'description' => $vc_main->l('Photo order.')
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );


/* Graph
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Progress Bar'),
	'base' => 'vc_progress_bar',
	'icon' => 'icon-wpb-graph',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Animated progress bar'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
		),
		array(
			'type' => 'exploded_textarea',
			'heading' => $vc_main->l('Graphic values'),
			'param_name' => 'values',
			'description' => $vc_main->l('Input graph values, titles and color here. Divide values with linebreaks (Enter). Example: 90|Development|#e75956'),
			'value' => "90|Development,80|Design,70|Marketing"
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Units'),
			'param_name' => 'units',
			'description' => $vc_main->l('Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Bar color'),
			'param_name' => 'bgcolor',
			'value' => array(
				$vc_main->l('Grey') => 'bar_grey',
				$vc_main->l('Blue') => 'bar_blue',
				$vc_main->l('Turquoise') => 'bar_turquoise',
				$vc_main->l('Green') => 'bar_green',
				$vc_main->l('Orange') => 'bar_orange',
				$vc_main->l('Red') => 'bar_red',
				$vc_main->l('Black') => 'bar_black',
				$vc_main->l('Custom Color') => 'custom'
			),
			'description' => $vc_main->l('Select bar background color.'),
			'admin_label' => true
		),
		array(
			'type' => 'colorpicker',
			'heading' => $vc_main->l('Bar custom color'),
			'param_name' => 'custombgcolor',
			'description' => $vc_main->l('Select custom background color for bars.'),
			'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'custom' ) )
		),
		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Options'),
			'param_name' => 'options',
			'value' => array(
				$vc_main->l('Add Stripes?') => 'striped',
				$vc_main->l('Add animation? Will be visible with striped bars.') => 'animated'
			)
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		)
	)
) );

/**
 * Pie chart
 */
vc_map( array(
	'name' => $vc_main->l('Pie chart'),
	'base' => 'vc_pie',
	'class' => '',
	'icon' => 'icon-wpb-vc_pie',
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Animated pie chart'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Widget title'),
			'param_name' => 'title',
			'description' => $vc_main->l('Enter text which will be used as widget title. Leave blank if no title is needed.'),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Pie value'),
			'param_name' => 'value',
			'description' => $vc_main->l('Input graph value here. Choose range between 0 and 100.'),
			'value' => '50',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Pie label value'),
			'param_name' => 'label_value',
			'description' => $vc_main->l('Input integer value for label. If empty "Pie value" will be used.'),
			'value' => ''
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Units'),
			'param_name' => 'units',
			'description' => $vc_main->l('Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.')
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Bar color'),
			'param_name' => 'color',
			'value' => $colors_arr, //$pie_colors,
			'description' => $vc_main->l('Select pie chart color.'),
			'admin_label' => true,
			'param_holder_class' => 'vc_colored-dropdown'
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
		),

	)
) );


/* Empty Space Element
---------------------------------------------------------- */
vc_map( array(
	'name' => $vc_main->l('Empty Space'),
	'base' => 'vc_empty_space',
	'icon' => 'icon-wpb-ui-empty_space',
	'show_settings_on_create' => true,
	'category' => $vc_main->l('Content'),
	'description' => $vc_main->l('Add spacer with custom height'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Height'),
			'param_name' => 'height',
			'value' => '32px',
			'admin_label' => true,
			'description' => $vc_main->l('Enter empty space height.'),
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Extra class name'),
			'param_name' => 'el_class',
			'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
		),
	),
) );

/* Custom Heading element
----------------------------------------------------------- */
vc_map( array(
    'name' => $vc_main->l('Custom Heading'),
    'base' => 'vc_custom_heading',
    'icon' => 'icon-wpb-ui-custom_heading',
    'show_settings_on_create' => true,
    'category' => $vc_main->l('Content'),
    'description' => $vc_main->l('Add custom heading text with google fonts'),
    'params' => array(
        array(
            'type' => 'textarea',
            'heading' => $vc_main->l('Text'),
            'param_name' => 'text',
            'admin_label' => true,
            'value'=> $vc_main->l('This is custom heading element with Google Fonts'),
            'description' => $vc_main->l('Enter your content. If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.'),
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value'=>'',
            'settings'=>array(
                'fields'=>array(
                    'tag'=>'h2', // default value h2
                    'text_align',
                    'font_size',
                    'line_height',
                    'color',
                    //'font_style_italic'
                    //'font_style_bold'
                    //'font_family'

                    'tag_description' => $vc_main->l('Select element tag.'),
                    'text_align_description' => $vc_main->l('Select text alignment.'),
                    'font_size_description' => $vc_main->l('Enter font size.'),
                    'line_height_description' => $vc_main->l('Enter line height.'),
                    'color_description' => $vc_main->l('Select color for your element.'),
                    //'font_style_description' => $vc_main->l('Put your description here'),
                    //'font_family_description' => $vc_main->l('Put your description here'),
                ),
            ),
           // 'description' => $vc_main->l( '', 'js_composer' ),
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => '',// Not recommended, this will override 'settings'. 'font_family:'.rawurlencode('Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic').'|font_style:'.rawurlencode('900 bold italic:900:italic'),
            'settings' => array(
                //'no_font_style' // Method 1: To disable font style
                //'no_font_style'=>true // Method 2: To disable font style
                'fields'=>array(
                    'font_family'=>'Abril Fatface:regular', //'Exo:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic',// Default font family and all available styles to fetch
                    'font_style'=>'400 regular:400:normal', // Default font style. Name:weight:style, example: "800 bold regular:800:normal"
                    'font_family_description' => $vc_main->l('Select font family.'),
                    'font_style_description' => $vc_main->l('Select font styling.')
                )
            ),
           // 'description' => $vc_main->l(''),
        ),
        array(
            'type' => 'textfield',
            'heading' => $vc_main->l('Extra class name'),
            'param_name' => 'el_class',
            'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
        ),
        array(
            'type' => 'css_editor',
            'heading' => $vc_main->l('Css'),
            'param_name' => 'css',
            // 'description' => $vc_main->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.'),
            'group' => $vc_main->l('Design options')
        )
    ),
) );

/*-----------------------New Maping Start ------------------------------*/

/*---------Start Of Vc Featured products-------------*/

vc_map( array(
	'name' => $vc_main->l('Featured Products'),
	'base' => 'vc_featured_products',
	'class' => '',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l('PrestaShop'),
	'description' => $vc_main->l('Display products set as "featured"'),
	'params' => array(

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Per Page'),
			'param_name' => 'per_page',
			'value' => '12',
			'admin_label' => true
		),

		array(
			'type' => 'checkbox',
			'heading' => $vc_main->l('Random'),
			'param_name' => 'random',
			'value' => array( '' => 'yes' )
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order by'),
			'param_name' => 'orderby',
			'value' => array(
				$vc_main->l('Product Id') => 'id_product',
				$vc_main->l('Price') => 'price',
				$vc_main->l('Published Date') => 'date_add',
				$vc_main->l('Product Name') => 'name',
				$vc_main->l('Position') => 'position',
				$vc_main->l('Manufacturer') => 'manufacturer',
			),
		
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order'),
			'param_name' => 'order',
			'value' => array(
				$vc_main->l('DESC') => 'DESC',
				$vc_main->l('ASC') => 'ASC',
			),
		
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
	)
) );

/*---------End Of Vc Featured products---------------*/

/*---------Start Of Vc New products-------------*/

vc_map( array(
	'name' => $vc_main->l('New products'),
	'base' => 'vc_new_products',
	'class' => '',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l('PrestaShop'),
	'description' => $vc_main->l('Lists New products'),
	'params' => array(

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Page'),
			'param_name' => 'page',
			'value' => '0',			
			'description' => 'First page will staring from 0',			
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Per Page'),
			'param_name' => 'per_page',
			'value' => '12',
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order by'),
			'param_name' => 'orderby',
			'value' => array(
				$vc_main->l('Product Id') => 'id_product',
				$vc_main->l('Price') => 'price',
				$vc_main->l('Published Date') => 'date_add',
				$vc_main->l('Update Date') => 'date_upd',
				$vc_main->l('Product Name') => 'name',				
			),
		
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order'),
			'param_name' => 'order',
			'value' => array(
				$vc_main->l('DESC') => 'DESC',
				$vc_main->l('ASC') => 'ASC',
			),					
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
	)
) );

/*------------End Of Vc New products----------------*/


/*---------Start Of Vc_bestsellers_products-------------*/

vc_map( array(
	'name' => $vc_main->l('Bestsellers Products'),
	'base' => 'vc_bestsellers_products',
	'class' => '',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l('PrestaShop'),
	'description' => $vc_main->l('List best selling products on sale'),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Page'),
			'param_name' => 'page',
			'value' => '0',
			'description' => 'First page will staring from 0',
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Per Page'),
			'param_name' => 'per_page',
			'value' => '12',
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order by'),
			'param_name' => 'orderby',
			'value' => array(				
				$vc_main->l('Sales') => 'sales',
				$vc_main->l('Quantity') => 'quantity',				
				$vc_main->l('Published Date') => 'date_add',								
			),
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order : '),
			'param_name' => 'order',
			'value' => array(
				$vc_main->l('DESC') => 'DESC',
				$vc_main->l('ASC') => 'ASC',
			),
		
			'admin_label' => true
		),


		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
	)
) );

/*---------End Of vc_bestsellers Products------------*/


/*---------Start Of Vc Special products-------------*/

vc_map( array(
	'name' => $vc_main->l('Special products'),
	'base' => 'vc_special_products',
	'class' => '',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l('PrestaShop'),
	'description' => $vc_main->l('Lists special products'),
	'params' => array(

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Page : '),
			'param_name' => 'page',
			'value' => '0',			
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Per Page : '),
			'param_name' => 'per_page',
			'value' => '12',
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order by : '),
			'param_name' => 'orderby',
			'value' => array(
				$vc_main->l('Product Id') => 'id_product',
				$vc_main->l('Price') => 'price',
				$vc_main->l('Published Date') => 'date_add',
				$vc_main->l('Product Name') => 'name',				
			),
					
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Order : '),
			'param_name' => 'order',
			'value' => array(
				$vc_main->l('DESC') => 'DESC',
				$vc_main->l('ASC') => 'ASC',
			),
		
			'admin_label' => true
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
	)
) );

/*--------------End Of Special products------------------*/


/*--------------Start Of Product Supplier ---------------*/
/**
 * @shortcode product supplier
 * @description Display product supplier loop.
 *
 * @param number integer
 * @param columns integer
 * @param orderby array
 * @param order array
 * @param hide_empty bool
 * @param parent integer
 * @param ids string
 * The `number` field is used to display the number of products and the `ids` field is to tell the shortcode which categories to display.
 * Set the parent paramater to 0 to only display top level categories. Set ids to a comma separated list of category ids to only show those.
 */
vc_map( array(
	'name' => $vc_main->l('Product Supplier'),
	'base' => 'vc_product_supplier',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l( 'PrestaShop'),
	'description' => $vc_main->l( 'Show multiple products in a Supplier'),
	'params' => array(


		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),
                array(
			'type' => 'autocomplete',
			'heading' => $vc_main->l( 'Select supplier'),
			'param_name' => 'id_supplier',
			'description' => $vc_main->l( 'Enter supplier name to see suggestions'),                        
                        'settings' => array(
                            'vc_catalog_type' => 'supplier',
                            'multiple' => false
                         )
		),
                array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'Page'),
			'value' => 1,
			'param_name' => 'page',
			'description' => $vc_main->l( 'Page to show'),
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'Per page'),
			'value' => 12,
			'param_name' => 'per_page',
			'description' => $vc_main->l( 'How much items per page to show'),
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order by'),
			'param_name' => 'orderby',
			'value' => array(
                            $vc_main->l('Product id') => 'id_product',
                            $vc_main->l('Product title') => 'pl.name',
                            $vc_main->l('Price') => 'price',
                            $vc_main->l('Date Published') => 'date_add',
                            $vc_main->l('Manufacturer Name') => 'manufacturer_name',
			),
			'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order way'),
			'param_name' => 'order',
			'value' => array(
			$vc_main->l('Descending') => 'DESC',
			$vc_main->l('Ascending') => 'ASC',
			),
			'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
                array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
		
                
                
		
	)
) );

/*-----------------End Of Product Supplier------------------*/

/*---------------Start Of Product Suppliers-----------------*/


vc_map( array(
    'name' => $vc_main->l( 'Product Suppliers'),
	'base' => 'vc_product_suppliers',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l('PrestaShop'),
	'description' => $vc_main->l('Display Suppliers Product loop'),
    'params' => array(

        array(
                'type' => 'textfield',
                'heading' => $vc_main->l('Title'),
                'param_name' => 'title',
                'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => $vc_main->l('Speed'),
            'param_name' => 'speed',
            'description' => $vc_main->l('Display Suppliers Product loop'),
            'value' => '500',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => $vc_main->l('Max Slides Number'),
            'param_name' => 'maxslide',
            'description' => $vc_main->l('Input graph value here. Choose range between 0 and 100.'),
            'value' => '4',
            'admin_label' => true
        ),
         array(
            'type' => 'dropdown',
            'heading' => $vc_main->l('Image Size'),
            'param_name' => 'img_size',
            'value' => $vcPsImgSizesOption,
            'description' => $vc_main->l('Set Image size for Suppliers'),            
        ),
        array(
            'type' => 'dropdown',
            'heading' => $vc_main->l('Slider Type'),
            'param_name' => 'slider_type',
            'value' => array(
                'Bx Slider' => 'bxslider',
                'Flex Slider' => 'flexslider',
            ),
            'description' => $vc_main->l('Set Slider Type'),            
        ),
    )
) );

/*------------------------------End Of Suppliers---------------------------------*/

/*---------------------Start Of product--------------------------*/

/**
 * @shortcode product
 * @description Show a single product by ID or SKU.
 *
 * @param id integer
 * @param sku string
 * If the product isn’t showing, make sure it isn’t set to Hidden in the Catalog Visibility.
 * To find the Product ID, go to the Product > Edit screen and look in the URL for the postid= .
 */
vc_map( array(
	'name' => $vc_main->l( 'Product' ),
	'base' => 'vc_product',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l( 'PrestaShop' ),
	'description' => $vc_main->l( 'Show a single product' ),
	'params' => array(
                array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),
		array(
			'type' => 'autocomplete',
			'heading' => $vc_main->l( 'Select product'),
			'param_name' => 'id',
			'description' => $vc_main->l( 'Enter product title to see suggestions'),                        
                        'settings' => array(
                            'vc_catalog_type' => 'product',
                            'multiple' => false
                         )
		)
	)
) );
/*------------------------End Of product-------------------------*/


/*------------------------Start Of products-------------------------*/

/**
 * @shortcode products
 * @description Show multiple products by ID or SKU. Make note of the plural ‘products’.
 *
 * @param columns integer
 * @param orderby array
 * @param order array
 * If the product isn’t showing, make sure it isn’t set to Hidden in the Catalog Visibility.
 */
vc_map( array(
	'name' => $vc_main->l( 'Products'),
	'base' => 'vc_products',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l( 'PrestaShop'),
	'description' => $vc_main->l( 'Show multiple products.'),
	'params' => array(
                array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),
                array(
			'type' => 'autocomplete',
			'heading' => $vc_main->l( 'Select products'),
			'param_name' => 'ids',
			'description' => $vc_main->l( 'Enter product title to see suggestions'),                        
                        'settings' => array(
                            'vc_catalog_type' => 'product',
                            'multiple' => true
                         )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order by'),
			'param_name' => 'orderby',
			'value' => array(
				$vc_main->l('Id') => 'id_product',
				$vc_main->l('Publish Date') => 'date_add',
				$vc_main->l('Update Date') => 'date_upd',
				$vc_main->l('Name') => 'name',
				$vc_main->l('Price') => 'price',
			),
			'std' => 'id_product',
			'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s. Default by Title', 'js_composer' ), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order way'),
			'param_name' => 'order',
			'value' => array(
			$vc_main->l('Descending') => 'DESC',
			$vc_main->l('Ascending') => 'ASC',
		),
			'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s. Default by ASC', 'js_composer' ), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
        array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
		
	)
) );

/*------------------------End Of Vc products-------------------------*/

/*------------------------Start Of Vc Add to Cart-------------------------*/
/**
 * @shortcode add_to_cart
 * @description Show the price and add to cart button of a single product by ID (or SKU).
 *
 * @param id integer
 * @param sku string
 * @param style string
 * If the product isn’t showing, make sure it isn’t set to Hidden in the Catalog Visibility.
 */
vc_map( array(
	'name' => $vc_main->l( 'Add to cart'),
	'base' => 'vc_add_to_cart',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l( 'PrestaShop'),
	'description' => $vc_main->l( 'Show multiple products by ID or SKU'),
	'params' => array(
		array(
			'type' => 'autocomplete',
			'heading' => $vc_main->l( 'Select product'),
			'param_name' => 'id_product',
			'description' => $vc_main->l( 'Enter product title to see suggestions'),                        
                        'settings' => array(
                            'vc_catalog_type' => 'product',
                            'multiple' => false
                         )
		),		
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'Wrapper inline style', 'js_composer' ),
			'param_name' => 'style',
		),
	)
) );

/*------------------------End Of Vc Add to Cart---------------------------*/

/*------------------------Start Of Vc product Category-------------------------*/

/**
 * @shortcode product_category
 * @description Show multiple products in a category by slug.
 *
 * @param per_page integer
 * @param columns integer
 * @param orderby array
 * @param order array
 * @param category string
 * Go to: WooCommerce > Products > Categories to find the slug column.
 */
vc_map( array(
	'name' => $vc_main->l( 'Product category'),
	'base' => 'vc_product_category',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l( 'PrestaShop'),
	'description' => $vc_main->l( 'Show multiple products in a category'),
	'params' => array(

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),

		array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'Per page'),
			'value' => 12,
			'param_name' => 'per_page',
			'description' => $vc_main->l( 'How much items per page to show'),
		),

		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order by'),
			'param_name' => 'orderby',
			'value' => array(
				$vc_main->l('Product id') => 'id_product',
				$vc_main->l('Date Publish') => 'date_add',
				$vc_main->l('Date Update') => 'date_upd',
				$vc_main->l('Price') => 'price',
				$vc_main->l('Name') => 'name',
				$vc_main->l('Position') => 'position',
			),
			'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order way'),
			'param_name' => 'order',
			'value' => array(
                $vc_main->l('Descending') => 'DESC',
                $vc_main->l('Ascending') => 'ASC',
			),
			'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
        array(
			'type' => 'autocomplete',
			'heading' => $vc_main->l( 'Select category'),
			'param_name' => 'id_category',
			'description' => $vc_main->l( 'Enter category name to see suggestions'),
            'settings' => array(
                'vc_catalog_type' => 'category',
                'multiple' => false
             )
		),
        array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
	)
) );

/*------------------------End Of Vc products Category-------------------------*/


/*---------Start Of Vc Product categories-------------*/



//vc_map( array(
//	'name' => $vc_main->l('Product categories'),
//	'base' => 'vc_product_categories',
//	'class' => '',
//	'icon' => 'vc_prestashop_icon',
//	'category' => $vc_main->l('PrestaShop'),
//	'description' => $vc_main->l( 'Display product categories loop'),
//	'params' => array(
//
//		array(
//			'type' => 'textfield',
//			'heading' => $vc_main->l('Title'),
//			'param_name' => 'title',
//			'admin_label' => true
//		),
//		array(
//			'type' => 'autocomplete',
//			'heading' => $vc_main->l( 'Select category'),
//			'param_name' => 'id_categories',
//			'description' => $vc_main->l( 'Enter category name to see suggestions'),                        
//                        'settings' => array(
//                            'vc_catalog_type' => 'category',
//                            'multiple' => true
//                         )
//		),                
//                array(
//                    'type' => 'textfield',
//                    'heading' => $vc_main->l('Speed'),
//                    'param_name' => 'speed',
//                    'description' => $vc_main->l('Enter text which will be used as Speed. Leave blank if no title is needed.'),
//                    'value' => '500',
//                    'admin_label' => true
//                ),
//                array(
//                    'type' => 'textfield',
//                    'heading' => $vc_main->l('Max Slides Number'),
//                    'param_name' => 'maxslide',
//                    'description' => $vc_main->l('Input graph value here. Choose range between 0 and 100.'),
//                    'value' => '4',
//                    'admin_label' => true
//                ),
//                array(
//                    'type' => 'dropdown',
//                    'heading' => $vc_main->l('Image Size'),
//                    'param_name' => 'img_size',
//                    'value' => $vcPsImgSizesOption,
//                    'description' => $vc_main->l('Set Image size for Categories'),            
//                ),
//                array(
//                    'type' => 'dropdown',
//                    'heading' => $vc_main->l('Slider Type'),
//                    'param_name' => 'slider_type',
//                    'value' => array(
//                        'Bx Slider' => 'bxslider',
//                        'Flex Slider' => 'flexslider',
//                    ),
//                    'description' => $vc_main->l('Set Slider Type'),            
//                ),
//
//	)
//) );

/*--------------End Of Vc Product categories------------------*/

/*--------------Start Of vc_manufacturer------------------*/

/**
 * @shortcode product_category
 * @description Show multiple products in a category by slug.
 *
 * @param per_page integer
 * @param columns integer
 * @param orderby array
 * @param order array
 * @param category string
 * Go to: WooCommerce > Products > Categories to find the slug column.
 */
vc_map( array(
	'name' => $vc_main->l( 'Product manufacturer'),
	'base' => 'vc_product_manufacturer',
	'icon' => 'vc_prestashop_icon',
	'category' => $vc_main->l( 'PrestaShop'),
	'description' => $vc_main->l( 'Show multiple products in a manufacturer'),
	'params' => array(


		array(
			'type' => 'textfield',
			'heading' => $vc_main->l('Title'),
			'param_name' => 'title',
			'admin_label' => true
		),
                array(
			'type' => 'autocomplete',
			'heading' => $vc_main->l( 'Select Manufacturer'),
			'param_name' => 'id_manufacturer',
			'description' => $vc_main->l( 'Enter manufacturer name to see suggestions'),                        
                        'settings' => array(
                            'vc_catalog_type' => 'manufacturer',
                            'multiple' => false
                         ),                         
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'Per page'),
			'value' => 12,
			'param_name' => 'per_page',
			'description' => $vc_main->l( 'How much items per page to show'),
		),
		array(
			'type' => 'textfield',
			'heading' => $vc_main->l( 'Page'),
			'value' => 1,
			'param_name' => 'page',
			'description' => $vc_main->l( 'Page to show'),
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order by'),
			'param_name' => 'orderby',
			'value' => array(
				$vc_main->l('Product Id') => 'id_product',
				$vc_main->l('Product Name') => 'name',
				$vc_main->l('Manufacturer Name') => 'manufacturer_name',
				$vc_main->l('Product Quantity') => 'quantity',
				$vc_main->l('Product Price') => 'price',
			),
			'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
		array(
			'type' => 'dropdown',
			'heading' => $vc_main->l( 'Order way'),
			'param_name' => 'order',
			'value' => array(
			$vc_main->l('Descending') => 'DESC',
			$vc_main->l('Ascending') => 'ASC',
			),
			'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="https://dev.mysql.com/doc/refman/5.0/en/order-by-optimization.html" target="_blank">Mysql Reference</a>' )
		),
                array(
			'type' => 'dropdown',
			'heading' => $vc_main->l('Display Type'),
			'param_name' => 'display_type',
			'value' => array(
				$vc_main->l('Grid View') => 'grid',
				$vc_main->l('Sidebar View') => 'sidebar',
			),
			'admin_label' => true
		),
		
	)
) );

/*--------------End Of vc_manufacturer------------------*/

/*---------------------Start Of vc_manufacturers--------------------------*/



vc_map( array(
    'name' => $vc_main->l('Product Manufacturers'),
    'base' => 'vc_product_manufacturers',
    'class' => '',
    'icon' => 'vc_prestashop_icon',
    'category' => $vc_main->l('PrestaShop'),
    'description' => $vc_main->l('Display Manufacturers Product loop'),
    'params' => array(
        array(
            'type' => 'textfield',
            'heading' => $vc_main->l('Title'),
            'param_name' => 'title',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => $vc_main->l('Speed'),
            'param_name' => 'speed',
            'description' => $vc_main->l('Enter text which will be used as Speed. Leave blank if no title is needed.'),
            'value' => '500',
            'admin_label' => true
        ),
        array(
            'type' => 'textfield',
            'heading' => $vc_main->l('Max Slides Number'),
            'param_name' => 'maxslide',
            'description' => $vc_main->l('Input graph value here. Choose range between 0 and 100.'),
            'value' => '4',
            'admin_label' => true
        ),
        array(
            'type' => 'dropdown',
            'heading' => $vc_main->l('Image Size'),
            'param_name' => 'img_size',
            'value' => $vcPsImgSizesOption,
            'description' => $vc_main->l('Set Image size for Manufacturer'),            
        ),
        array(
            'type' => 'dropdown',
            'heading' => $vc_main->l('Slider Type'),
            'param_name' => 'slider_type',
            'value' => array(
                'Bx Slider' => 'bxslider',
                'Flex Slider' => 'flexslider',
            ),
            'description' => $vc_main->l('Set Slider Type'),            
        ),
    )
) );

/*--------------End Of vc_manufacturers------------------*/

/*------------------------Start Of Vc products Categorys-------------------------*/

/**
		 * @shortcode product_categories
		 * @description Display product categories loop.
		 *
		 * @param number integer
		 * @param columns integer
		 * @param orderby array
		 * @param order array
		 * @param hide_empty bool
		 * @param parent integer
		 * @param ids string
		 * The `number` field is used to display the number of products and the `ids` field is to tell the shortcode which categories to display.
		 * Set the parent paramater to 0 to only display top level categories. Set ids to a comma separated list of category ids to only show those.
		 */

/*------------------------
		vc_map( array(
			'name' => $vc_main->l( 'Product categories'),
			'base' => 'product_categories',
			'icon' => 'vc_prestashop_icon',
			'category' => $vc_main->l( 'PrestaShop'),
			'description' => $vc_main->l( 'Display product categories loop'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Number'),
					'param_name' => 'number',
					'description' => $vc_main->l( 'The `number` field is used to display the number of products.'),
				),
				array(
					'type' => 'dropdown',
					'heading' => $vc_main->l( 'Order by'),
					'param_name' => 'orderby',
					'value' => array(
					$vc_main->l('Descending') => 'desc',
					$vc_main->l('Ascending') => 'asc',
					),
					'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
				),
				array(
					'type' => 'dropdown',
					'heading' => $vc_main->l( 'Order way'),
					'param_name' => 'order',
					'value' => array(
					$vc_main->l('Descending') => 'desc',
					$vc_main->l('Ascending') => 'asc',
					),
					'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
				),
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Columns'),
					'value' => 4,
					'param_name' => 'columns',
					'description' => $vc_main->l( 'How much columns grid'),
				),
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Number'),
					'param_name' => 'hide_empty',
					'description' => $vc_main->l( 'Hide empty'),
				),
				array(
					'type' => 'autocomplete',
					'heading' => $vc_main->l( 'Categories'),
					'param_name' => 'ids',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'description' => $vc_main->l( 'List of product categories'),
				),
			)
		) );

-------------------------*/
/*------------------------End Of Vc products Categorys-------------------------*/



/*------------------------Start Of Vc product page--------------------------*/

/**
		 * @shortcode product_page
		 * @description Show a full single product page by ID or SKU.
		 *
		 * @param id integer
		 * @param sku string
		 */

/*------------------------
		vc_map( array(
			'name' => $vc_main->l( 'Product page'),
			'base' => 'product_page',
			'icon' => 'vc_prestashop_icon',
			'category' => $vc_main->l( 'PrestaShop'),
			'description' => $vc_main->l( 'Show single product by ID or SKU'),
			'params' => array(
				array(
					'type' => 'autocomplete',
					'heading' => $vc_main->l( 'Select identificator'),
					'param_name' => 'id',
					'description' => $vc_main->l( 'Input product ID or product SKU or product title to see suggestions'),
				),
				array(
					'type' => 'hidden',
					'param_name' => 'sku',
				),
			)
		) );
--------------------------*/
/*------------------------End Of Vc product page-------------------------*/




/*------------------------Start Of Vc Sale product-------------------------*/

/**
		 * @shortcode sale_products
		 * @description List all products on sale.
		 *
		 * @param per_page integer
		 * @param columns integer
		 * @param orderby array
		 * @param order array
		 */
/*------------------------
		vc_map( array(
			'name' => $vc_main->l( 'Sale products'),
			'base' => 'sale_products',
			'icon' => 'vc_prestashop_icon',
			'category' => $vc_main->l( 'PrestaShop'),
			'description' => $vc_main->l( 'List all products on sale'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Per page'),
					'value' => 12,
					'param_name' => 'per_page',
					'description' => $vc_main->l( 'How much items per page to show'),
				),
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Columns'),
					'value' => 4,
					'param_name' => 'columns',
					'description' => $vc_main->l( 'How much columns grid'),
				),
				array(
					'type' => 'dropdown',
					'heading' => $vc_main->l( 'Order by'),
					'param_name' => 'orderby',
					'value' => array(
						$vc_main->l('Id') => 'id_product',
						$vc_main->l('Date') => 'date',
						$vc_main->l('Author') => 'Author',
					),
					'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
				),
				array(
					'type' => 'dropdown',
					'heading' => $vc_main->l( 'Order way'),
					'param_name' => 'order',
					'value' => array(
					$vc_main->l('Descending') => 'desc',
					$vc_main->l('Ascending') => 'asc',
				),
					'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
				),
			)
		) );

-------------------*/


/*------------------------Start Of Vc Top Rated product-------------------------*/


/**
		 * @shortcode top_rated_products
		 * @description List top rated products on sale.
		 *
		 * @param per_page integer
		 * @param columns integer
		 * @param orderby array
		 * @param order array
		 */

/*------------------------
		vc_map( array(
			'name' => $vc_main->l( 'Top Rated Products'),
			'base' => 'top_rated_products',
			'icon' => 'vc_prestashop_icon',
			'category' => $vc_main->l( 'PrestaShop'),
			'description' => $vc_main->l( 'List all products on sale'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Per page'),
					'value' => 12,
					'param_name' => 'per_page',
					'description' => $vc_main->l( 'How much items per page to show'),
				),
				array(
					'type' => 'textfield',
					'heading' => $vc_main->l( 'Columns'),
					'value' => 4,
					'param_name' => 'columns',
					'description' => $vc_main->l( 'How much columns grid'),
				),
				array(
					'type' => 'dropdown',
					'heading' => $vc_main->l( 'Order by'),
					'param_name' => 'orderby',
					'value' => array(
						$vc_main->l('Id') => 'id_product',
						$vc_main->l('Date') => 'date',
						$vc_main->l('Author') => 'Author',
					),
					'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
				),
				array(
					'type' => 'dropdown',
					'heading' => $vc_main->l( 'Order way'),
					'param_name' => 'order',
					'value' => array(
					$vc_main->l('Descending') => 'desc',
					$vc_main->l('Ascending') => 'asc',
				),
					'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
				),
			)
		) );

-------------------------*/
/*------------------------End Of Vc Top Rated product-------------------------*/



/*------------------------Start Of Vc product Attributes----------------------*/


/**
		 * @shortcode product_attribute
		 * @description List products with an attribute shortcode.
		 *
		 * @param per_page integer
		 * @param columns integer
		 * @param orderby array
		 * @param order array
		 * @param attribute string
		 * @param filter string
		 */

	
//		vc_map( array(
//			'name' => $vc_main->l( 'Product Attribute'),
//			'base' => 'product_attribute',
//			'icon' => 'vc_prestashop_icon',
//			'category' => $vc_main->l( 'PrestaShop'),
//			'description' => $vc_main->l( 'List products with an attribute shortcode'),
//			'params' => array(
//				array(
//					'type' => 'textfield',
//					'heading' => $vc_main->l( 'Per page'),
//					'value' => 12,
//					'param_name' => 'per_page',
//					'description' => $vc_main->l( 'How much items per page to show'),
//				),
//				array(
//					'type' => 'textfield',
//					'heading' => $vc_main->l( 'Columns'),
//					'value' => 4,
//					'param_name' => 'columns',
//					'description' => $vc_main->l( 'How much columns grid'),
//				),
//				array(
//					'type' => 'dropdown',
//					'heading' => $vc_main->l( 'Order by'),
//					'param_name' => 'orderby',
//					'value' => array(
//						$vc_main->l('Id') => 'id_product',
//						$vc_main->l('Date') => 'date',
//						$vc_main->l('Author') => 'Author',
//					),
//					'description' => sprintf( $vc_main->l( 'Select how to sort retrieved products. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
//				),
//				array(
//					'type' => 'dropdown',
//					'heading' => $vc_main->l( 'Order way'),
//					'param_name' => 'order',
//					'value' => array(
//					$vc_main->l('Descending') => 'desc',
//					$vc_main->l('Ascending') => 'asc',
//					),
//					'description' => sprintf( $vc_main->l( 'Designates the ascending or descending order. More at %s.'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
//				),
//				array(
//					'type' => 'dropdown',
//					'heading' => $vc_main->l( 'Attribute'),
//					'param_name' => 'attribute',
//					'value' => array(
//					$vc_main->l('Black') => 'desc',
//					$vc_main->l('Red') => 'asc',
//					),
//					'description' => $vc_main->l( 'List of product taxonomy attribute' ),
//				),
//				array(
//					'type' => 'checkbox',
//					'heading' => $vc_main->l( 'Filter'),
//					'param_name' => 'filter',
//					'value' => array( 'empty' => 'empty' ),
//					'description' => $vc_main->l( 'Taxonomy values'),
//					'dependency' => array(
//						'element' => 'attribute',
//						'is_empty' => true,
//						'callback' => 'vcWoocommerceProductAttributeFilterDependencyCallback',
//					),
//				),
//			)
//		) );



/*------------------------End Of Vc product Attributes------------------------*/




/* Support for 3rd Party plugins
---------------------------------------------------------- */
//// Contact form 7 plugin
//include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
//if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
//	global $wpdb;
//	$cf7 = $wpdb->get_results(
//		"
//  SELECT ID, post_title
//  FROM $wpdb->posts
//  WHERE post_type = 'wpcf7_contact_form'
//  "
//	);
//	$contact_forms = array();
//	if ( $cf7 ) {
//		foreach ( $cf7 as $cform ) {
//			$contact_forms[$cform->post_title] = $cform->ID;
//		}
//	} else {
//		$contact_forms[$vc_main->l('No contact forms found')] = 0;
//	}
//	vc_map( array(
//		'base' => 'contact-form-7',
//		'name' => $vc_main->l('Contact Form 7'),
//		'icon' => 'icon-wpb-contactform7',
//		'category' => $vc_main->l('Content'),
//		'description' => $vc_main->l('Place Contact Form7'),
//		'params' => array(
//			array(
//				'type' => 'textfield',
//				'heading' => $vc_main->l('Form title'),
//				'param_name' => 'title',
//				'admin_label' => true,
//				'description' => $vc_main->l('What text use as form title. Leave blank if no title is needed.')
//			),
//			array(
//				'type' => 'dropdown',
//				'heading' => $vc_main->l('Select contact form'),
//				'param_name' => 'id',
//				'value' => $contact_forms,
//				'description' => $vc_main->l('Choose previously created contact form from the drop down list.')
//			)
//		)
//	) );
//} // if contact form7 plugin active
//
//if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
//	$gravity_forms_array[$vc_main->l('No Gravity forms found.')] = '';
//	if ( class_exists( 'RGFormsModel' ) ) {
//		$gravity_forms = RGFormsModel::get_forms( 1, 'title' );
//		if ( $gravity_forms ) {
//			$gravity_forms_array = array( $vc_main->l('Select a form to display.') => '' );
//			foreach ( $gravity_forms as $gravity_form ) {
//				$gravity_forms_array[$gravity_form->title] = $gravity_form->id;
//			}
//		}
//	}
//	vc_map( array(
//		'name' => $vc_main->l('Gravity Form'),
//		'base' => 'gravityform',
//		'icon' => 'icon-wpb-vc_gravityform',
//		'category' => $vc_main->l('Content'),
//		'description' => $vc_main->l('Place Gravity form'),
//		'params' => array(
//			array(
//				'type' => 'dropdown',
//				'heading' => $vc_main->l('Form'),
//				'param_name' => 'id',
//				'value' => $gravity_forms_array,
//				'description' => $vc_main->l('Select a form to add it to your post or page.'),
//				'admin_label' => true
//			),
//			array(
//				'type' => 'dropdown',
//				'heading' => $vc_main->l('Display Form Title'),
//				'param_name' => 'title',
//				'value' => array(
//					$vc_main->l('No') => 'false',
//					$vc_main->l('Yes') => 'true'
//				),
//				'description' => $vc_main->l('Would you like to display the forms title?'),
//				'dependency' => array( 'element' => 'id', 'not_empty' => true )
//			),
//			array(
//				'type' => 'dropdown',
//				'heading' => $vc_main->l('Display Form Description'),
//				'param_name' => 'description',
//				'value' => array(
//					$vc_main->l('No') => 'false',
//					$vc_main->l('Yes') => 'true'
//				),
//				'description' => $vc_main->l('Would you like to display the forms description?'),
//				'dependency' => array( 'element' => 'id', 'not_empty' => true )
//			),
//			array(
//				'type' => 'dropdown',
//				'heading' => $vc_main->l('Enable AJAX?'),
//				'param_name' => 'ajax',
//				'value' => array(
//					$vc_main->l('No') => 'false',
//					$vc_main->l('Yes') => 'true'
//				),
//				'description' => $vc_main->l('Enable AJAX submission?'),
//				'dependency' => array( 'element' => 'id', 'not_empty' => true )
//			),
//			array(
//				'type' => 'textfield',
//				'heading' => $vc_main->l('Tab Index'),
//				'param_name' => 'tabindex',
//				'description' => $vc_main->l('(Optional) Specify the starting tab index for the fields of this form. Leave blank if you\'re not sure what this is.'),
//				'dependency' => array( 'element' => 'id', 'not_empty' => true )
//			)
//		)
//	) );
//} // if gravityforms active





// 

/*** Visual Composer Content elements refresh ***/
class VcSharedLibrary {
	// Here we will store plugin wise (shared) settings. Colors, Locations, Sizes, etc...
	private static $colors = array(
		'Blue' => 'blue', // Why $vc_main->l('Blue') doesn't work?
		'Turquoise' => 'turquoise',
		'Pink' => 'pink',
		'Violet' => 'violet',
		'Peacoc' => 'peacoc',
		'Chino' => 'chino',
		'Mulled Wine' => 'mulled_wine',
		'Vista Blue' => 'vista_blue',
		'Black' => 'black',
		'Grey' => 'grey',
		'Orange' => 'orange',
		'Sky' => 'sky',
		'Green' => 'green',
		'Juicy pink' => 'juicy_pink',
		'Sandy brown' => 'sandy_brown',
		'Purple' => 'purple',
		'White' => 'white'
	);

	public static $icons = array(
		'Glass' => 'glass',
		'Music' => 'music',
		'Search' => 'search'
	);

	public static $sizes = array(
		'Mini' => 'xs',
		'Small' => 'sm',
		'Normal' => 'md',
		'Large' => 'lg'
	);

	public static $button_styles = array(
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'3D' => '3d',
		'Square Outlined' => 'square_outlined'
	);

	public static $cta_styles = array(
		'Rounded' => 'rounded',
		'Square' => 'square',
		'Round' => 'round',
		'Outlined' => 'outlined',
		'Square Outlined' => 'square_outlined'
	);

	public static $txt_align = array(
		'Left' => 'left',
		'Right' => 'right',
		'Center' => 'center',
		'Justify' => 'justify'
	);

	public static $el_widths = array(
		'100%' => '',
		'90%' => '90',
		'80%' => '80',
		'70%' => '70',
		'60%' => '60',
		'50%' => '50'
	);

	public static $sep_styles = array(
		'Border' => '',
		'Dashed' => 'dashed',
		'Dotted' => 'dotted',
		'Double' => 'double'
	);

	public static $box_styles = array(
		'Default' => '',
		'Rounded' => 'vc_box_rounded',
		'Border' => 'vc_box_border',
		'Outline' => 'vc_box_outline',
		'Shadow' => 'vc_box_shadow',
		'Bordered shadow' => 'vc_box_shadow_border',
		'3D Shadow' => 'vc_box_shadow_3d',
		'Circle' => 'vc_box_circle', //new
		'Circle Border' => 'vc_box_border_circle', //new
		'Circle Outline' => 'vc_box_outline_circle', //new
		'Circle Shadow' => 'vc_box_shadow_circle', //new
		'Circle Border Shadow' => 'vc_box_shadow_border_circle' //new
	);

	public static function getColors() {
		return self::$colors;
	}

	public static function getIcons() {
		return self::$icons;
	}

	public static function getSizes() {
		return self::$sizes;
	}

	public static function getButtonStyles() {
		return self::$button_styles;
	}

	public static function getCtaStyles() {
		return self::$cta_styles;
	}

	public static function getTextAlign() {
		return self::$txt_align;
	}

	public static function getElementWidths() {
		return self::$el_widths;
	}

	public static function getSeparatorStyles() {
		return self::$sep_styles;
	}

	public static function getBoxStyles() {
		return self::$box_styles;
	}
}

//VcSharedLibrary::getColors();
function getVcShared( $asset = '' ) {
	switch ( $asset ) {
		case 'colors':
			return VcSharedLibrary::getColors();
			break;

		case 'icons':
			return VcSharedLibrary::getIcons();
			break;

		case 'sizes':
			return VcSharedLibrary::getSizes();
			break;

		case 'button styles':
		case 'alert styles':
			return VcSharedLibrary::getButtonStyles();
			break;

		case 'cta styles':
			return VcSharedLibrary::getCtaStyles();
			break;

		case 'text align':
			return VcSharedLibrary::getTextAlign();
			break;

		case 'cta widths':
		case 'separator widths':
			return VcSharedLibrary::getElementWidths();
			break;

		case 'separator styles':
			return VcSharedLibrary::getSeparatorStyles();
			break;

		case 'single image styles':
			return VcSharedLibrary::getBoxStyles();
			break;

		default:
			# code...
			break;
	}
}
