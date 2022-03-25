<?php

$target = dirname(__DIR__) . '/storage/app/public';
$link = dirname(__DIR__) . '/public/storage';

if(file_exists($link))
{
	if(is_link($link))
        exit("The symbolic link already exists.\n");

    if(! unlink($link) )
        exit("Manually delete '" . __DIR__ . "/storage' and try again.\n");
}

if (! windows_os()) {
	if(symlink($target, $link))
	    echo "symlink has been created successfully!";
	else
    	echo "FAILED! Please enable symlink function and try again.";
}
else {
	$mode = $this->isDirectory($target) ? 'J' : 'H';

	exec("mklink /{$mode} \"{$link}\" \"{$target}\"");
}