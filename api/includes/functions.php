<?php
	function GenerateUrl ($s) {
		//Convert accented characters, and remove parentheses and apostrophes
		$from = explode (',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,(,),[,],/,'");
		$to = explode (',', 'c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,,,,,-,');
		//Do the replacements, and convert all other non-alphanumeric characters to spaces
		$s = preg_replace ('~[^\w\d]+~', '-', str_replace ($from, $to, trim ($s)));
		//Remove a - at the beginning or end and make lowercase
		return strtolower (preg_replace ('/^-/', '', preg_replace ('/-$/', '', $s)));
	}

	function CheckUrl ($s) {
		// Get the current URL without the query string, with the initial slash
		$myurl = preg_replace ('/\?.*$/', '', $_SERVER['REQUEST_URI']);
		//If it is not the same as the desired URL, then redirect
		if ($myurl != $s) { header("Location: $s", true, 301); exit; }
	}


	function getDirectoryTree($outerDir, $x) {

		$dirs = array_diff(scandir($outerDir), array('.', '..'));
		$dir_array = array();
		foreach($dirs as $d) {

			if(is_dir($outerDir . DIRECTORY_SEPARATOR . $d)) {
				$dir_array[] = getDirectoryTree($outerDir . DIRECTORY_SEPARATOR . $d , $x);
			} else {
				if ($x ? preg_match(DIRECTORY_SEPARATOR . $x .'$/i', $d) : 1) {
					$dir_array[] = str_replace(FS_ROOT, '', $outerDir) . '/' . $d;
				}
			}
		}

		$return = array();
		array_walk_recursive($dir_array, function($a) use (&$return) { $return[] = $a; });

		return $return;
	}

	function out($params) {

		$out = json_encode($params);
		header('Content-type: application/json; charset="UTF-8"');
		header('Content-Length: '.strlen($out));
		header('Connection: close');
		echo $out;
		flush();

	}
