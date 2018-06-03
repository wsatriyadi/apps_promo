<?php

/**
 * @author Wisnu Satriyadi <wsatriyadi@gmail.com>
 * @copyright Sera Soft 2018
 * @package apps_promo
 * 
 * 
 * Created using Ionic App Builder
 * http://codecanyon.net/item/ionic-mobile-app-builder/15716727
 */


/** CONFIG:START **/
$config["host"] 		= "localhost" ; 		//host
$config["user"] 		= "root" ; 		//Username SQL
$config["pass"] 		= "" ; 		//Password SQL
$config["dbase"] 		= "db_apps_promo" ; 		//Database
$config["utf8"] 		= true ; 		//turkish charset set false
$config["timezone"] 		= "Asia/Jakarta" ; 		//turkish charset set false
$config["abs_url_images"] 		= "http://domain.com/apps/apps_promo//media/image/" ; 		//Absolute Images URL
$config["abs_url_videos"] 		= "http://domain.com/apps/apps_promo//media/media/" ; 		//Absolute Videos URL
$config["abs_url_audios"] 		= "http://domain.com/apps/apps_promo//media/media/" ; 		//Absolute Audio URL
$config["abs_url_files"] 		= "http://domain.com/apps/apps_promo//media/file/" ; 		//Absolute Files URL
$config["image_allowed"][] 		= array("mimetype"=>"image/jpeg","ext"=>"jpg") ; 		//whitelist image
$config["image_allowed"][] 		= array("mimetype"=>"image/jpg","ext"=>"jpg") ; 		
$config["image_allowed"][] 		= array("mimetype"=>"image/png","ext"=>"png") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"text/plain","ext"=>"txt") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"","ext"=>"tmp") ; 		
/** CONFIG:END **/

date_default_timezone_set($config['timezone']);
if(isset($_SERVER["HTTP_X_AUTHORIZATION"])){
	list($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]) = explode(":" , base64_decode(substr($_SERVER["HTTP_X_AUTHORIZATION"],6)));
}
$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Routes not found");

/** connect to mysql **/
$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);
if (mysqli_connect_errno()){
	die(mysqli_connect_error());
}


if(!isset($_GET["json"])){
	$_GET["json"]= "route";
}
if((!isset($_GET["form"])) && ($_GET["json"] == "submit")) {
	$_GET["json"]= "route";
}

if($config["utf8"]==true){
	$mysql->set_charset("utf8");
}

$get_dir = explode("/", $_SERVER["PHP_SELF"]);
unset($get_dir[count($get_dir)-1]);
$main_url = "http://" . $_SERVER["HTTP_HOST"] . implode("/",$get_dir)."/";


switch($_GET["json"]){	
	// TODO: -+- Listing : free
	case "free":
		$rest_api=array();
		$where = $_where = null;
		// TODO: -+----+- statement where
		if(isset($_GET["id"])){
			if($_GET["id"]!="-1"){
				$_where[] = "`id` LIKE '%".$mysql->escape_string($_GET["id"])."%'";
			}
		}
		if(isset($_GET["title"])){
			if($_GET["title"]!="-1"){
				$_where[] = "`title` LIKE '%".$mysql->escape_string($_GET["title"])."%'";
			}
		}
		if(isset($_GET["thumbnail"])){
			if($_GET["thumbnail"]!="-1"){
				$_where[] = "`thumbnail` LIKE '%".$mysql->escape_string($_GET["thumbnail"])."%'";
			}
		}
		if(isset($_GET["link"])){
			if($_GET["link"]!="-1"){
				$_where[] = "`link` LIKE '%".$mysql->escape_string($_GET["link"])."%'";
			}
		}
		if(isset($_GET["text"])){
			if($_GET["text"]!="-1"){
				$_where[] = "`text` LIKE '%".$mysql->escape_string($_GET["text"])."%'";
			}
		}
		if(is_array($_where)){
			$where = " WHERE " . implode(" AND ",$_where);
		}
		// TODO: -+----+- orderby
		$order_by = "`id`";
		$sort_by = "DESC";
		if(!isset($_GET["order"])){
			$_GET["order"] = "`id`";
		}
		// TODO: -+----+- sort asc/desc
		if(!isset($_GET["sort"])){
			$_GET["sort"] = "desc";
		}
		if($_GET["sort"]=="asc"){
			$sort_by = "ASC";
		}else{
			$sort_by = "DESC";
		}
		if($_GET["order"]=="id"){
			$order_by = "`id`";
		}
		if($_GET["order"]=="title"){
			$order_by = "`title`";
		}
		if($_GET["order"]=="thumbnail"){
			$order_by = "`thumbnail`";
		}
		if($_GET["order"]=="link"){
			$order_by = "`link`";
		}
		if($_GET["order"]=="text"){
			$order_by = "`text`";
		}
		if($_GET["order"]=="random"){
			$order_by = "RAND()";
		}
		$limit = 100;
		if(isset($_GET["limit"])){
			$limit = (int)$_GET["limit"] ;
		}
		// TODO: -+----+- SQL Query
		$sql = "SELECT * FROM `free` ".$where."ORDER BY ".$order_by." ".$sort_by." LIMIT 0, ".$limit." " ;
		if($result = $mysql->query($sql)){
			$z=0;
			while ($data = $result->fetch_array()){
				if(isset($data['id'])){$rest_api[$z]['id'] = $data['id'];}; # id
				if(isset($data['title'])){$rest_api[$z]['title'] = $data['title'];}; # heading-1
				
				/** images**/
				$abs_url_images = $config['abs_url_images'].'/';
				$abs_url_videos = $config['abs_url_videos'].'/';
				$abs_url_audios = $config['abs_url_audios'].'/';
				if(!isset($data['thumbnail'])){$data['thumbnail']='undefined';}; # images
				if((substr($data['thumbnail'], 0, 7)=='http://')||(substr($data['thumbnail'], 0, 8)=='https://')){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if(substr($data['thumbnail'], 0, 5)=='data:'){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if($data['thumbnail'] != ''){
					$rest_api[$z]['thumbnail'] = $abs_url_images . $data['thumbnail']; # images
				}else{
					$rest_api[$z]['thumbnail'] = ''; # images
				}
				if(isset($data['link'])){$rest_api[$z]['link'] = $data['link'];}; # link
				if(isset($data['text'])){$rest_api[$z]['text'] = $data['text'];}; # to_trusted
				$z++;
			}
			$result->close();
			if(isset($_GET["id"])){
				if(isset($rest_api[0])){
					$rest_api = $rest_api[0];
				}
			}
		}

		break;
	// TODO: -+- route
	case "route":		$rest_api=array();
		$rest_api["site"]["name"] = "Apps Promo" ;
		$rest_api["site"]["description"] = "Aplikasi Promo Harian" ;
		$rest_api["site"]["imabuilder"] = "rev18.05.21" ;

		$rest_api["routes"][0]["namespace"] = "free";
		$rest_api["routes"][0]["tb_version"] = "Upd.1806010438";
		$rest_api["routes"][0]["methods"][] = "GET";
		$rest_api["routes"][0]["args"]["id"] = array("required"=>"false","description"=>"Selecting `Free` based `id`");
		$rest_api["routes"][0]["args"]["title"] = array("required"=>"false","description"=>"Selecting `Free` based `title`");
		$rest_api["routes"][0]["args"]["thumbnail"] = array("required"=>"false","description"=>"Selecting `Free` based `thumbnail`");
		$rest_api["routes"][0]["args"]["link"] = array("required"=>"false","description"=>"Selecting `Free` based `link`");
		$rest_api["routes"][0]["args"]["text"] = array("required"=>"false","description"=>"Selecting `Free` based `text`");
		$rest_api["routes"][0]["args"]["order"] = array("required"=>"false","description"=>"order by `random`, `id`, `title`, `thumbnail`, `link`, `text`");
		$rest_api["routes"][0]["args"]["sort"] = array("required"=>"false","description"=>"sort by `asc` or `desc`");
		$rest_api["routes"][0]["args"]["limit"] = array("required"=>"false","description"=> "limit the items that appear","type"=>"number");
		$rest_api["routes"][0]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=free";
		break;
	// TODO: -+- submit

	case "submit":
		$rest_api=array();

		$rest_api["methods"][0] = "POST";
		$rest_api["methods"][1] = "GET";

	break;

}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,X-Authorization');
if (!isset($_GET["callback"])){
	header('Content-type: application/json');
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo json_encode($rest_api,JSON_UNESCAPED_UNICODE);
	}else{
		echo json_encode($rest_api);
	}

}else{
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api,JSON_UNESCAPED_UNICODE). ");" ;
	}else{
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api) . ");" ;
	}

}