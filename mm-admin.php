<?php
/**
 * WordPress Mm
 *
 * Copyright (c) 2013 David Persson. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the license.txt file.
 */

require_once __DIR__ . '/mm.php';
require_once 'Mime/Type.php';
require_once 'Media/Process.php';

// Register the settings pane und "Settings".
add_action('admin_menu', 'mm_admin_menu');
function mm_admin_menu() {
	add_options_page('Mm Plugin Options', 'Mm', 'manage_options', 'mm', 'mm_admin_options');
}

// Construct and handle submissions on the settings pane.
function mm_admin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	if ($_POST) {
		update_option('mm', array(
			'versions' => $_POST['versions']
		));
	}

	// @fixme Versions are currently hardcoded. Make more flexible.
	$versions = array('fix0', 'fix1');
	$mediaProcessConfig = Media_Process::config();
	$mimeTypeConfig = array(
		'glob' => str_replace('Mime_Type_Glob_Adapter_', '', get_class(Mime_Type::$glob)),
		'magic' => str_replace('Mime_Type_Magic_Adapter_', '', get_class(Mime_Type::$magic))
	);
	$options = get_option('mm');
	$versionDefaults = array(
		'width' => 1000,
		'height' => 1000,
		'mime_type' => 'image/png',
		'method' => 'fitInside'
	);
	foreach ($versions as $version) {
		if (!isset($options['versions'][$version])) {
			$options['versions'][$version] = $versionDefaults;
		}
	}
	$methods = array(
		'fitInside' => 'fit inside',
		'fitOutside' => 'fit outside',
		'crop' => 'crop',
		'zoom' => 'zoom',
		'zoomFit' => 'zoom & fit',
		'zoomCrop' => 'zoom & crop',
		'fitCrop' => 'fit & crop'
	);
	$formats = array(
		'image/png' => 'PNG',
		'image/jpeg' => 'JPEG'
	);
	$optimizations = array(
		'compress' => 'compress slightly',
		// 'crush' => 'crush',
		'strip' => 'strip all metadata',
		// 'profile' => 'apply color profile',
		'interlace' => 'interlace'
	);

	echo mm_render_template('admin-options', compact(
		'options',
		'versions',
		'mediaProcessConfig', 'mimeTypeConfig',
		'methods', 'formats', 'optimizations'
	));
}

?>