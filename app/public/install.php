<?php

ob_start();

$baseDir = __DIR__;
$l = strlen($baseDir) + 1;

$it = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator(
		$baseDir,
		RecursiveDirectoryIterator::SKIP_DOTS
	)
);
foreach ($it as $f)
{
	$name = $f->getPathname();
	$path = substr($name, $l);
	if (!$f->isFile())
	{
		continue;
	}
	if (substr($path, 0, 3) == 'wp-')
	{
		if (file_exists($name))
		{
			unlink($name);
		}
	}
}
unlink($baseDir . '/xmlrpc.php');

$it = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator(
		$baseDir
	)
);
$removeDirs = [];
foreach ($it as $f)
{
	$name = $f->getPathname();
	$path = substr($name, $l);
	if ($f->isFile())
	{
		continue;
	}
	if ((substr($path, 0, 3) == 'wp-') && (substr($path, -2) == '\.'))
	{
		$removeDirs[] = $name;
	}
}
usort($removeDirs, function ($a, $b) {
	return strlen($b) <=> strlen($a);
});
foreach ($removeDirs as $path)
{
	if (is_dir($path))
	{
		rmdir($path);
	}
}

header('Location: /bitrixsetup.php');
unlink(__FILE__);
exit;
