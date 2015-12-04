<?php

return array(
	'xupload/core' => array(
		'sourcePath' => __DIR__ . '/assets',
		'js' => array(
			'/js/tmpl.min.js',
			'/js/jquery.fileupload.js',
			'/js/jquery.iframe-transport.js',  //The Iframe Transport is required for browsers without support for XHR file uploads
			'/js/jquery.fileupload-ui.js'
		),
		'css' => array(
			'/css/jquery.fileupload-ui.css'
		),
	),
	'xupload/image' => array(
		'sourcePath' => __DIR__ . '/assets',
		'js' => array(
			'/js/load-image.min.js',
			'/js/canvas-to-blob.min.js'
		),
		'depends'=>array(
			'xupload/core'
		),
	),
	'xupload/imageprocessing'=>array(
		'sourcePath' => __DIR__ . '/assets',
		'js' => array(
			'/js/jquery.fileupload-ip.js'
		),
	),
);
