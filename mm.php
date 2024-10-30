<?php
/*
  Plugin Name: Mm
  Description: High-quality media processing for your blog.
  Author: David Persson
  Author URI: http://nperson.de
  Version: 1.0.0
 */

/**
 * WordPress Mm
 *
 * Copyright (c) 2013 David Persson. All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the license.txt file.
 */

require_once __DIR__ . '/lib/mm/bootstrap.php';
require_once 'Mime/Type.php';
require_once 'Media/Process.php';
require_once __DIR__ . '/mm-admin.php';

// Auto-register and allow custom sizes for any theme loaded.
add_action('after_setup_theme', 'mm_after_setup_theme');
function mm_after_setup_theme() {
	add_theme_support('post-thumbnails');
}

// Register a couple of filter functions.
add_action('plugins_loaded', 'mm_init_early');
function mm_init_early() {
	add_filter('intermediate_image_sizes_advanced', 'mm_filter_image_sizes', 99, 1);
	add_filter('wp_read_image_metadata', 'mm_filter_read_image_metadata', 10, 3);
	add_filter('wp_generate_attachment_metadata', 'mm_filter_attachment_metadata', 10, 2);
}

// Provide our versions as options in the menu in addition to the built-in ones.
add_filter('image_size_names_choose', 'mm_image_sizes_choose');
function mm_image_sizes_choose($sizes) {
	return array_merge($sizes, array(
		'fix0' => 'Version fix0',
		'fix1' => 'Version fix1'
	));
}

// We handle only our sizes.
function mm_filter_image_sizes($sizes) {
	$options = get_option('mm');

	foreach ($options['versions'] as $key => $value) {
		unset($sizes[$key]);
	}
	return $sizes;
}

// Make current file globally available to following function.
$mm_file = null;
function mm_filter_read_image_metadata($metadata, $file, $ignore) {
	global $mm_file;

	$mm_file = $file;
	return $metadata;
}

// This is main method where we hook into the WP image processing process.
function mm_filter_attachment_metadata($metadata, $attachment_id) {
	global $mm_file;

	$file = $mm_file;
	$path = pathinfo($file);
	$options = get_option('mm');

	foreach ($options['versions'] as $version => $versionOptions) {
		$instructions = mm_version_options_to_instructions($versionOptions);
		$media = Media_Process::factory(array('source' => $file));

		$media = mm_make_version($media, $version, $instructions);

		$extension = null;
		if (isset($instructions['convert'])) {
			$extension = Mime_Type::guessExtension($instructions['convert']);
		} else {
			$extension = Mime_Type::guessExtension($file);
		}
		$target = $path['dirname'] . '/' . $path['filename'] . "-{$version}.{$extension}";
		$target = $media->store($target, array('overwrite' => true));

		$media = Media_Info::factory(array('source' => $target));

		$metadata['sizes'][$version] = array(
			'file' => basename($target),
			'width' => $media->get('width'),
			'height' => $media->get('height')
		);
	}
	return $metadata;
}

// Small ad-hoc render Helper function to render templates for the admin.
function mm_render_template($template, $data = array()) {
	extract($data);

	ob_start();
	require __DIR__ . "/templates/{$template}.html.php";
	return ob_get_clean();
};

// "Makes" a version of the media by applying a set of instructions.
function mm_make_version($media, $version, $instructions) {
	if ($media->name() != 'image') {
		return false;
	}
	foreach ($instructions as $method => $args) {
		if (is_int($method)) {
			$method = $args;
			$args = null;
		}
		if (method_exists($media, $method)) {
			$result = call_user_func_array(array($media, $method), (array) $args);
		} else {
			$result = $media->passthru($method, $args);
		}
		if ($result === false) {
			return false;
		} elseif (is_a($result, 'Media_Process_Generic')) {
			$media = $result;
		}
	}
	return $media;
}

// Converts options composed from the POST data from the admin settings and
// converts it into a set of "instructions". These instructions are then used
// in the make process to build the resulting media.
function mm_version_options_to_instructions($options) {
	$instructions = array(
		$options['method'] => array((integer) $options['width'], (integer) $options['height']),
		'convert' => $options['mime_type']
	);
	if (isset($options['strip'])) {
		$instructions['strip'] = array('xmp', '8bim', 'app1', 'app12', 'exif');
	}
	if (isset($options['compress'])) {
		if ($options['mime_type'] == 'image/png') {
			$instructions['compress'] = 5.5;
		} elseif ($options['mime_type'] == 'image/jpeg') {
			$instructions['compress'] = 90;
		}
	}
	if (isset($options['crush'])) {
		$instructions['crush'] = true;
	}
	if (isset($options['interlace'])) {
		$instructions['interlace'] = true;
	}
	if (isset($options['profile'])) {
		$instructions += array(
			'colorProfile' => __DIR__ . '/lib/mm/data/sRGB_IEC61966-2-1_black_scaled.icc',
			'colorDepth' => 8,
		);
	}
	return $instructions;
}

?>