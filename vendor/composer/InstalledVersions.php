<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '0686091d9d9bbca8f4acc2656e1db2a771508d00',
    'name' => 'juandev/blog',
  ),
  'versions' => 
  array (
    'bramus/router' => 
    array (
      'pretty_version' => '1.6.1',
      'version' => '1.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '55657b76da8a0a509250fb55b9dd24e1aa237eba',
    ),
    'juandev/blog' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '0686091d9d9bbca8f4acc2656e1db2a771508d00',
    ),
    'lcobucci/clock' => 
    array (
      'pretty_version' => '2.2.x-dev',
      'version' => '2.2.9999999.9999999-dev',
      'aliases' => 
      array (
        0 => '9999999-dev',
      ),
      'reference' => 'fb533e093fd61321bfcbac08b131ce805fe183d3',
    ),
    'lcobucci/jwt' => 
    array (
      'pretty_version' => '4.3.x-dev',
      'version' => '4.3.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => '5a97b2b33c2368685f8ac9886115d84f70dd14ae',
    ),
    'stella-maris/clock' => 
    array (
      'pretty_version' => '0.1.5',
      'version' => '0.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '447879c53ca0b2a762cdbfba5e76ccf4deca9158',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
