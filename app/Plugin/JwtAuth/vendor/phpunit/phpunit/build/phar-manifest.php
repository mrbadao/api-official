#!/usr/bin/env php
<?php
print 'phpunit/phpunit: ';

$tag = @exec('git describe --tags 2>&1');

if (strpos($tag, '-') === FALSE && strpos($tag, 'No names found') === FALSE) {
	print $tag;
} else {
	$branch = @exec('git rev-parse --abbrev-ref HEAD');
	$hash = @exec('git log -1 --format="%H"');
	print $branch . '@' . $hash;
}

print "\n";

$lock = json_decode(file_get_contents(__DIR__ . '/../composer.lock'));

foreach ($lock->packages as $package) {
	print $package->name . ': ' . $package->version;

	if (!preg_match('/^[v= ]*(([0-9]+)(\\.([0-9]+)(\\.([0-9]+)(-([0-9]+))?(-?([a-zA-Z-+][a-zA-Z0-9\\.\\-:]*)?)?)?)?)$/', $package->version)) {
		print '@' . $package->source->reference;
	}

	print "\n";
}
