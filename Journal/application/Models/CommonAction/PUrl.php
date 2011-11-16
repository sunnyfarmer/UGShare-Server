<?php
class Models_CommonAction_PUrl
{
	/**
	 * 
	 * get the relative path of $childPath base on $parentPath
	 * @param string $parentPath	base path(absolute path)
	 * @param string $childPath		converting path(absolute path)
	 */
	public static function convertAbsoToRela ($parentPath, $childPath)
	{
	    $relativePath = str_replace($parentPath, "", $childPath);
	    return $relativePath;
	}
}

