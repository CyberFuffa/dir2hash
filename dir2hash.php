#!/usr/bin/php
<?php

function absolute2Relative($root, $path) {
	return str_replace($root.'/', '', $path);
}

function getDirHashes($root, $dir, &$hashes = []) {
	$files = scandir($dir);

	foreach ($files as $key => $value) {
		$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
		if (!is_dir($path)) {
			$hash = md5($path);
			$hashes[$hash][] = absolute2Relative($root, $path);
		} else if ($value != "." && $value != "..") {
			getDirHashes($root, $path, $hashes);
			//$results[] = $path;
		}
	}

	return $hashes;
}

if (!isset($argv[1])) {
	die('Usage: '.$argv[0].' /path/to/directory/' . "\n");
}

$path = $argv[1];
$root = realpath($path);
$hashes = getDirHashes($root, $path);
echo json_encode($hashes, JSON_PRETTY_PRINT);
