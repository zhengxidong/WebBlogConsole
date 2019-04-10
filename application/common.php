<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Request;
use think\Session;
use think\Controller;

//标签选中
function tag($articleForTagList,$tagList){
    //$tagList为所有标签
    foreach($tagList as $value){
        $booldata =true;
        //$articleForTagList为文章所拥有的标签
        foreach($articleForTagList as $va){
            if($va['tag_id']==$value['id']){
                $booldata = false;
                echo '<label class="checkbox-inline"><input type="checkbox" name="tagId[]" value="'.$value['id'].'" checked><span>'.$value['tag_name'].'</span></label>';

            }
        }
        if($booldata) {

          echo '<label class="checkbox-inline"><input type="checkbox" name="tagId[]" value="'.$value['id'].'"><span>'.$value['tag_name'].'</span></label>';

        }
    }

}

//随机获取颜色
function randColor(){
    $colors = array();
    for($i = 0;$i<6;$i++){
        $colors[] = dechex(rand(0,15));
    }
    return implode('',$colors);

}

/**
 * RGB转 十六进制
 * @param $rgb RGB颜色的字符串 如：rgb(255,255,255);
 * @return string 十六进制颜色值 如：#FFFFFF
 */
function RGBToHex($rgb){
    $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
    $re = preg_match($regexp, $rgb, $match);
    $re = array_shift($match);
    $hexColor = "#";
    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    for ($i = 0; $i < 3; $i++) {
        $r = null;
        $c = $match[$i];
        $hexAr = array();
        while ($c > 16) {
            $r = $c % 16;
            $c = ($c / 16) >> 0;
            array_push($hexAr, $hex[$r]);
        }
        array_push($hexAr, $hex[$c]);
        $ret = array_reverse($hexAr);
        $item = implode('', $ret);
        $item = str_pad($item, 2, '0', STR_PAD_LEFT);
        $hexColor .= $item;
    }
    return $hexColor;
}
/**
 * 十六进制 转 RGB
 */
function hex2rgb($hexColor) {
    $color = str_replace('#', '', $hexColor);
    if (strlen($color) > 3) {
        $rgb = array(
            'r' => hexdec(substr($color, 0, 2)),
            'g' => hexdec(substr($color, 2, 2)),
            'b' => hexdec(substr($color, 4, 2))
        );
    } else {
        $color = $hexColor;
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = array(
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b)
        );
    }
    $rgb = implode(',',$rgb);
    return $rgb;
}

function mbSubStr($content)
{
  $content = mb_substr($content,0,175);
  return $content;
}
function getAddress($ip)
{

   
   if(empty($ip)) return false;
   $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;

   $curl = curl_init();

   curl_setopt($curl,CURLOPT_URL,$url);
   curl_setopt($curl,CURLOPT_HEADER,0);

   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

   $outInput = curl_exec($curl);
   $curl_errno = curl_errno($curl);
        $curl_error = curl_error($curl);
   curl_close($curl);

   if ($curl_errno > 0) {
                //echo "cURL Error ($curl_errno): $curl_error\n";
		return false;
        } 
	//else {
                //echo "Data received: $data\n";
        //}

   //return $data->data;
	//print_r($data);

	$data = json_decode($outInput,true);
	//var_dump($data['data']);

	$returnData = [

	'ip' => $data['data']['ip'],
	'country' => $data['data']['country'],
	'area' => $data['data']['area'],
	'region' => $data['data']['region'],
	'city' => $data['data']['city'],
	'county' => $data['data']['county'],
	'isp' => $data['data']['isp'],
	'country_id' => $data['data']['country_id'],
	'area_id' => $data['data']['area_id'],
	'region_id' => $data['data']['region_id'],
	'city_id' => $data['data']['city_id'],
	'county_id' => $data['data']['county_id'],
	'isp_id' => $data['data']['isp_id'],

	];

	$returnData = (object)$returnData;
   //var_dump($returnData);
   //exit;

	return $returnData;
   //print_r(file_get_contents($url));
   #$ipinfo=json_decode(file_get_contents($url));

   #var_dump($ipinfo);

   #exit;	
   #if(!$ipinfo){
   #   return false;
   #}
   #if($ipinfo->code=='1'){
   #    return false;
   #}
   //$city = $ipinfo->data->region.$ipinfo->data->city;
   //return $ipinfo->data;
}
