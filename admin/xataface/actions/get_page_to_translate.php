<?php

class dataface_actions_get_page_to_translate {

	
	function handle($params){
		$app =& Dataface_Application::getInstance();
		if ( !isset($_GET['key']) ) trigger_error("No key specified", E_USER_ERROR);
		
		$sql = "select `value` from `".TRANSLATION_PAGE_TABLE."` where `key` = '".addslashes($_GET['key'])."'";
		$res = xf_db_query($sql,$app->db());
		if ( !$res ) trigger_error(xf_db_error($app->db()), E_USER_ERROR);
		if ( xf_db_num_rows($res) == 0 ) trigger_error("Sorry the specified key was invalid.", E_USER_ERROR);
		list($content) = xf_db_fetch_row($res);
		@xf_db_free_result($res);
		
		if ( function_exists('tidy_parse_string') ){
			$config = array('show-body-only'=>true, 'output-encoding'=>'utf8');
			
			$html = tidy_repair_string($content, $config, "utf8");
			$content = trim($html);
		}
		
		df_display(array('content'=>$content), 'TranslationPageTemplate.html');
		return true;
		
	}

}

?>
