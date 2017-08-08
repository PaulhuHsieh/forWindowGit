<?php


	class item{
		
	public $chatid;#id=array();
	public $name;
	public $url;
	public $pubid;
	public $chatdate;
	
	}
	
	class chatroom{
		
	public 	$content;
	public  $id;
	public  $chatdate;
	
	}
	
	class image{
		
	public $id_jpg;
	public $chatroom_id;
	
	}
	
	
	
	$chatset=array();
	$chatsetinfo=array();
	$imageid=array();
	
	
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, "http://2cat.or.tl/~tedc21thc/live/pixmicat.php?mode=module&load=mod_threadlist&sort=date");
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$output = curl_exec($ch);
	
	
	
	if (empty($output)) {

	echo "實況 output no found!!";

	}else {

	#$prefix= '<th'    ;
	#$suffix= '</th>'    ;
	#$string= '測試中,請稍候eee測試中,請稍候eee測試中,請稍候';
	
	$perform= '/<tr class=.*?<\/tr>/is';
	
	preg_match_all($perform,$output,$matches);
	
    #echo count($matches[0]);
	
	echo "<br>";
	
	#echo $matches[0][0]; // 用來印出陣列(PHP Array)的內容
	
	
	$perform2='/<a href=[\'\"](.*)[\'\"]>/i'; # (.*)多存空間 [\'\"] '或是" i是大小寫都可以，存php連結  
	$perform3='/name=[\'\"](.*)[\'\"]/iU';
	$perform4='/<td>([^<]*)<\/td>/i';
	$perform5='/<a href=".*">(.*)<\/a>/iU';
	
	for($i=0;$i<count($matches[0]);$i++){
		
		array_push($chatset, new item());
		
		preg_match($perform2,$matches[0][$i],$matches2[$i]);
		preg_match($perform3,$matches[0][$i],$chatset[$i]->id);
		preg_match_all($perform4,$matches[0][$i],$aaa);
		preg_match($perform5,$matches[0][$i],$bbb);
		
		$chatset[$i]->chatid=$aaa[1][0];
		$chatset[$i]->chatdate=substr($aaa[1][3], 0, 18);
		$chatset[$i]->pubid=substr($aaa[1][3],19,strlen($aaa[1][3])-1);
		$chatset[$i]->name=$bbb[1];
	}
	
	$link2 = "http://2cat.or.tl/~tedc21thc/live/";
	
	
	
	for($i=0;$i<count($matches[0]);$i++){
		
		$chatset[$i]->url = $link2.$matches2[$i][1];
		
	}
	
	echo $chatset[0]->url;
	curl_close($ch);
	
	#var_dump($matches);
    #$match_r = mb_substr($match_r, 0, 10,'UTF-8');
	#echo $match_r;
	}
	
	
	for($i=0;$i<1;$i++){
	
		$ch2 = curl_init();
	
		curl_setopt($ch2, CURLOPT_URL, "http://2cat.or.tl/~tedc21thc/live/pixmicat.php?res=843350");
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	
		$output2 = curl_exec($ch2);
	
		if (empty($output2)) {

			echo "next step of website  no found!!";
			
		}else {
	
	      $perform6='/<div class="quote">(.*)<\/div>/i';
		  $perform7='/<div class="reply" id="r(.*)"><div>/i';
		  $perform8='/<\/span>(.*)<\/label>/i';
		  $perform9='/link"(.*)<\/a>-/i';
		  preg_match_all($perform6,$output2,$matches3);
		  preg_match_all($perform7,$output2,$matches4);
		  preg_match_all($perform8,$output2,$matches5);
		  preg_match_all($perform9,$output2,$matches6);
		  
		}
	
		
		
		$url="http://img.2cat.org/~tedc21thc/live/src/1461509816337.jpg";
		$headers = get_headers($url);
		$pattern = $headers[0];
		echo $pattern;
		echo $headers[2];
		if (preg_match("/200/i",$pattern)){//判斷圖片是否存在
		
			$contentType = $headers[2];
			switch ($contentType){
				
				case "Content-Type: image/jpeg":
					$extension=".jpg";
					break;
				case "Content-Type: image/png":
					$extension =".png";
					break;
				default:
					echo "not match";exit;
					break;
			}
		}
 
		$tmpFile="./tmp/".md5($url).$extension; 
		file_put_contents($tmpFile,file_get_contents($url));
		
		$perform10 = '/>No.(.*)<\//i';
		$perform11 = '/blank">(.*).jpg/i';
		
		for($k=0;$k<count($matches6[1]);$k++){  #count($matches6[1])
			
			array_push($imageid, new image());
			
			echo "<br>";
			echo "====================";
			echo "<br>";
			
			preg_match_all($perform10,$matches6[1][$k],$chatroomid);
			#echo $matches6[1][$k];
			$imageid[$k]->chatroom_id = $chatroomid[1][0];
			echo $imageid[$k]->chatroom_id;
			echo "<br>";
			preg_match_all($perform11,$matches6[1][$k],$chatid_jpg);
			$imageid[$k]->id_jpg = $chatid_jpg[1][0];
			echo $imageid[$k]->id_jpg;
			echo "<br>";
			echo "====================";
			echo "<br>";
			echo "====================yeee";
		}
		
		for($j=0;$j<count($matches3[1]);$j++){
			
			
			array_push($chatsetinfo, new chatroom());
			
			$chatsetinfo[$j]->content = $matches3[1][$j];
		    $chatsetinfo[$j]->id = $matches4[1][$j];
			$chatsetinfo[$j]->chatdate= $matches5[1][$j];
			
			echo "<br>";
			
			echo $chatsetinfo[$j]->content;
			echo $chatsetinfo[$j]->id;
			echo $chatsetinfo[$j]->chatdate;
			
			echo "<br>";
			echo "---------------------";
			echo "<br>";
			echo "---------------------";
			echo "<br>";
			echo "---------------------";
			echo "<br>";
		}
	
		
		
		curl_close($ch2);
	
	}
	
	
	
?>