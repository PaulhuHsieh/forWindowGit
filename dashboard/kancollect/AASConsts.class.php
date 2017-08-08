<?php
	namespace KC\Data;
	use MMC\Data\Consts;
	
	class AASConsts extends Consts{
		protected static $_instance;
		
		protected $kc_ass_version_log = "./inc/kc_ass.ver";
		
		protected $kc_ass_css = <<<EOF
		body{
			background-color: #f2f6ff;
		}
		nav{
			padding-top:5px;
		}
		
		span{
			margin-left:5px;
			margin-right:5px;
		}
		
		.searchedHeader{
			padding-top:10px;
			padding-bottom:10px;
		}
		
		.searchedItem{
			background-color:white;
			margin-bottom:10px;
			margin-top:10px;
		}
		.beenDisabled{
			background-color:#ddd;
		}
		.float-right-btn{
			margin-right:18px;
		}
		#current_page{
			color:#967672;
			padding-left: 0;
		}
EOF;
		
		protected $kc_ass_navbar = <<<EOF
			<nav class="navbar navbar-default navbar-fixed-top">
				<div class="row">
					<a class="navbar-brand" href="./">KanColle@MomoCow /</a>
					<a class="navbar-brand" id="current_page" href="./ass"><strong>反潛計算器</strong></a>
					<div class="col-md-offset-3">
						<form class="form-inline">
							<div class="form-group">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default active">
										<input type="radio" name="stype" id="select_type_clt" ship_type="clt" autocomplete="off" checked="">重雷装巡洋艦
									</label>
									<label class="btn btn-default">
										<input type="radio" name="stype" id="select_type_cl" ship_type="cl" autocomplete="off">軽巡洋艦
									</label>
									<label class="btn btn-default">
										<input type="radio" name="stype" id="select_type_dd" ship_type="dd" autocomplete="off">駆逐艦
									</label>
								</div>
							</div>
							<div class="form-group">
								<select class="selectpicker" title="選擇一位艦娘" data-live-search="true" data-live-search-placeholder="目前僅支援日文漢字查找" data-live-search-style="contains" id='selectShips'>
								</select>
								<button type="button" class="btn btn-default" aria-label="開始" id="start">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								</button>
							</div>
							<div class="form-group pull-right">
								<button type="button" class="btn btn-info float-right-btn" data-toggle="modal" data-target="#version_info">版本</button>
							</div>
						</form>
					</div>	
				</div>
			</nav>
EOF;

		protected $kc_ass_body = <<<EOF
		<div class="row">
			<div class="list-group" id="searchedList"></div>
		</div>
EOF;

		protected $kc_ass_menuOperation = <<<EOF
		<script>
		(function(){
			var noClassOpt = "未分類";
			
			function adjust_layout(){
				$("#searchedList").css("padding-top", $("nav").css("height"));
			}
			
			function calc_ass_sum(){//console.log(this);
				$(this).parents(".list-group-item").find(".ass_sum").text(Math.floor((parseFloat($(this).parents(".list-group-item").find(".fin_ass").text())-parseFloat($(this).parents(".list-group-item").find(".ini_ass").text()))*parseFloat($(this).parents(".list-group-item").find(".shipLevel").val())/99 + parseFloat($(this).parents(".list-group-item").find(".ini_ass").text())));
				var pre_ASS_min = Math.ceil((100-parseFloat($(this).parents(".list-group-item").find(".ini_ass").text()))/(parseFloat($(this).parents(".list-group-item").find(".fin_ass").text())-parseFloat($(this).parents(".list-group-item").find(".ini_ass").text()))*99);
				if(pre_ASS_min > 155){
					pre_ASS_min = "不存在";
				}
				else if(pre_ASS_min < 1){
					pre_ASS_min = 1;
				}
				$(this).parents(".list-group-item").find(".preASS_min_level").text(pre_ASS_min);
			}
			
			function menu_filter(){
				if($("#selectShips > .select_type_clt").length > 0) disappeared.select_type_clt = $("#selectShips > .select_type_clt").detach();
				if($("#selectShips > .select_type_cl").length > 0) disappeared.select_type_cl = $("#selectShips > .select_type_cl").detach();
				if($("#selectShips > .select_type_dd").length > 0) disappeared.select_type_dd = $("#selectShips > .select_type_dd").detach();
				var show_up = $("input:radio[name='stype']:checked").attr("id");
				disappeared[show_up].appendTo("#selectShips");
				$('#selectShips').selectpicker('refresh');
				$('#selectShips').selectpicker('val', '');
			}
			
			function generate_searched_item(){
				var selectedID = $('#selectShips :checked').attr("id");
				if(selectedID !== undefined && selectedID !== null && selectedID.trim() != ""){
					searchedNum++;
					var kanmusu = ass_ship_data[$("input:radio[name='stype']:checked").attr("ship_type")][selectedID];
					var aasContent = "<div class='table-responsive'><table class='table table-bordered'><tr><td>"+kanmusu['class']+" "+kanmusu['class_no']+"番艦</td><th>基礎數據</th><th>裝備</th><th>達到<strong>先制反潛<strong>的最低等級</th><th>等級</th><td><input type='number' min='1' max='155' value='88' class='shipLevel'></td></tr>" +
														  "<tr><td rowspan='4'></td><th>初始值</th><td><select class='selectpicker weapon1' title='第1格' data-live-search='true'></select></td><td rowspan='4' class='preASS_min_level'></td><th colspan='2'>總反潛值</th></tr>"+
														  "<tr><td class='ini_ass'>"+kanmusu['ass']+"</td><td><select class='selectpicker weapon2' title='第2格' data-live-search='true'></select></td><td rowspan='3' colspan='2' class='ass_sum'></td></tr>"+
														  "<tr><th>最終值</th><td><select class='selectpicker weapon3' title='第3格' data-live-search='true'></select></td></tr>"+
														  "<tr><td class='fin_ass'>"+kanmusu['max_ass']+"</td><td><select class='selectpicker weapon4' title='第4格' data-live-search='true'></select></td></tr></table></div>";
					$("#searchedList").prepend('<div class="list-group-item alert alert-dismissible fade in btn searchedItem" id="item' + searchedNum.toString() + '"  data-toggle="collapse" data-target="#result' + searchedNum.toString() + '"><div class="row searchedHeader"><div class="col-md-11 col-xs-9 text-left"><span class="badge">' + searchedNum.toString() + '</span><span>' + kanmusu['type'] + " " + kanmusu['name'] + '</span></div><div class="col-md-1 col-xs-1"><button class="btn btn-warning"  data-dismiss="alert" aria-label="Close"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></div></div>'+
					'<div class="row"><div class="collapse" id="result' + searchedNum.toString() + '"><div class="well text-center">' + aasContent + '</div></div></div></div>');
					for(slot_idx=kanmusu['slot']+1;slot_idx <= 4; slot_idx++){
						$("#item" + searchedNum.toString() + ".list-group-item .weapon"+slot_idx).attr("disabled",true).parent().addClass("beenDisabled");
					}
					$("#item" + searchedNum.toString() + ".list-group-item").on("click", ".collapse", function(e){e.stopPropagation();}).find(".well").css("cursor","text").css("background-color", "white").find("table td, table th").css("text-align","center").find("select.selectpicker").selectpicker('refresh');
					$("#item" + searchedNum.toString() + ".list-group-item .shipLevel").on("change", function(){if($(this).val()>155)$(this).val(155);else if($(this).val()<1)$(this).val(1);calc_ass_sum.call(this);});
					calc_ass_sum.call($("#item" + searchedNum.toString() + ".list-group-item table"));
				}
			}
			
			$.each(ass_ship_data["cl"], function(index, ship){
				if(!("class" in ship)){
					if($("optgroup[label='" + noClassOpt + "'].select_type_cl").length==0){
						$("#selectShips").append("<optgroup label='" + noClassOpt + "' class='select_type_cl'><option data-tokens='" + ship['name'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option></optgroup>");
					}
					else{
						$("optgroup[label='" + noClassOpt + "'].select_type_cl").append("<option data-tokens='" + ship['name'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option>");
					}
				}
				else{
					if($("optgroup[label='" + ship['class'] + "'].select_type_cl").length==0){
						$("#selectShips").prepend("<optgroup label='" + ship['class'] + "' class='select_type_cl'><option data-tokens='" + ship['name'] + " " + ship['class'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option></optgroup>");
					}
					else{
						$("optgroup[label='" + ship['class'] + "'].select_type_cl").append("<option data-tokens='" + ship['name'] + " " + ship['class'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option>");
					}
				}
			});
			$.each(ass_ship_data["dd"], function(index, ship){
				if(!("class" in ship)){
					if($("optgroup[label='" + noClassOpt + "'].select_type_dd").length==0){
						$("#selectShips").append("<optgroup label='" + noClassOpt + "' class='select_type_dd'><option data-tokens='" + ship['name'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option></optgroup>");
					}
					else{
						$("optgroup[label='" + noClassOpt + "'].select_type_dd").append("<option data-tokens='" + ship['name'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option>");
					}
				}
				else{
					if($("optgroup[label='" + ship['class'] + "'].select_type_dd").length==0){
						$("#selectShips").prepend("<optgroup label='" + ship['class'] + "' class='select_type_dd'><option data-tokens='" + ship['name'] + " " + ship['class'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option></optgroup>");
					}
					else{
						$("optgroup[label='" + ship['class'] + "'].select_type_dd").append("<option data-tokens='" + ship['name'] + " " + ship['class'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option>");
					}
				}
			});
			$.each(ass_ship_data["clt"], function(index, ship){
				if(!("class" in ship)){
					if($("optgroup[label='" + noClassOpt + "'].select_type_clt").length==0){
						$("#selectShips").append("<optgroup label='" + noClassOpt + "' class='select_type_clt'><option data-tokens='" + ship['name'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option></optgroup>");
					}
					else{
						$("optgroup[label='" + noClassOpt + "'].select_type_clt").append("<option data-tokens='" + ship['name'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option>");
					}
				}
				else{
					if($("optgroup[label='" + ship['class'] + "'].select_type_clt").length==0){
						$("#selectShips").prepend("<optgroup label='" + ship['class'] + "' class='select_type_clt'><option data-tokens='" + ship['name'] + " " + ship['class'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option></optgroup>");
					}
					else{
						$("optgroup[label='" + ship['class'] + "'].select_type_clt").append("<option data-tokens='" + ship['name'] + " " + ship['class'] + "' id='id" + ship['id'] + "'>" + ship['name'] + "</option>");
					}
				}
			});
			menu_filter();
			adjust_layout();
			
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				alert("很抱歉，手機版本暫時仍無法提供關鍵字搜尋的服務，我們會盡快推出新版本以便手機用戶，敬請見諒!");
				$('.selectpicker').selectpicker('mobile');
			}

			
			$("input:radio[name='stype']").on("change", menu_filter);
			$("#start").on("click", generate_searched_item);
			$("form.form-inline").on("submit", function(e){e.preventDefault();});
			$("window").on("resize", adjust_layout);
		})();
		</script>
EOF;

		protected $kc_ass_version = <<<EOF
		<div id="version_info" class="modal fade" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">版本資訊</h4>
			  </div>
			  <div class="modal-body">
				<!--version_history-->
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>

		  </div>
		</div>
EOF;
	
		public static function instance(){
			if (!self::$_instance instanceof self){
				self::$_instance = new self();	
			}
			return self::$_instance;
		}
	}
?>