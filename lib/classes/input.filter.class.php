<?php

class InputFilter
{
	private $includePath;

	public function __construct($incl = null)
	{
		$this->includePath = $_SERVER["DOCUMENT_ROOT"];
	}

	public function filterHTML($input)
	{
		require_once $this->includePath . "/lib/HTMLPurifier/HTMLPurifier.auto.php";
		
		$config   = HTMLPurifier_Config::createDefault();
		//$config->set('HTML.Allowed','a[href|target|title],img[class|src|border|alt|title|hspace|vspace|width|height|align|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|style]');
		
		$purifier = new HTMLPurifier($config);
		$output   = $purifier->purify($input);

		return $output;
	}
}