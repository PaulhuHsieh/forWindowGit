<?php
	include("../src/autoloader.php");
	use MMC\Page\BootstrapPage;
	use MMC\Util\Secretary;
	use MMC\Util\ConfigParser;
	use MMC\Util\JSON;
	use KC\Data\KanMusu;
	use KC\Data\AASConsts;
	
	function version_log($log){
		$verXML = ConfigParser::getConfigFromXML($log);

		$verlog = "<div class='table-responsive'><table class='table table-hover'><tr><th>版本</th><th>日期</th><th>備註</th></tr>";
		foreach($verXML->log as $each_version){
			$verlog .= "<tr><td>". $each_version->version ."</td><td>". $each_version->date ."</td><td>". $each_version->descript ."</td></tr>";
		}
		$verlog .= "</table></div>";
		
		return $verlog;
	}
	
	$page=new BootstrapPage();
	
	if(isset($_GET['ass'])){
		$json_CLs = KanMusu::type("軽巡洋艦");
		$json_DDs = KanMusu::type("駆逐艦");
		$json_CLTs = KanMusu::type("重雷装巡洋艦");
		KanMusu::sort($json_CLs);
		KanMusu::sort($json_DDs);
		KanMusu::sort($json_CLTs);
		KanMusu::make_index($json_CLs, "id");
		KanMusu::make_index($json_DDs, "id");
		KanMusu::make_index($json_CLTs, "id");
		
		$page->setTitle("- ASS Calculator -");
		$page->setInnerStyle(AASConsts::instance()->kc_ass_css);
		$page->setOuterStyle("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css");
		$page->setOuterScript("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js");
		$page->setOuterScript("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/i18n/defaults-zh_TW.min.js");
		$page->importScript("src/script/setting.js");
		$page->addBodyOnload(AASConsts::instance()->kc_ass_menuOperation);
		$page->setInnerScript('var ass_ship_data = {"cl":' . new JSON($json_CLs) .', "dd":' . new JSON($json_DDs) . ', "clt":' . new JSON($json_CLTs) . '};');
		$page->addBody(AASConsts::instance()->kc_ass_navbar);
		$page->addBody($page->container(AASConsts::instance()->kc_ass_body));
		$page->addBody(str_replace("<!--version_history-->", version_log(AASConsts::instance()->kc_ass_version_log), AASConsts::instance()->kc_ass_version));
	}		
	else{
		$page->setTitle("KanColle@MomoCow");
		$page->addBody("<h1>KanColle@MomoCow</h1><hr><ul><li><a href='./ass'>反潛計算器</a></li></ul>");
	}
	
	echo $page->output();
?>