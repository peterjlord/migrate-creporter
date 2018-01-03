<?php
# modules/custom/migrate_creporter/src/Plugin/migrate/process/Youtube.php
namespace Drupal\migrate_creporter\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Custom process plugin to convert youtube scheme uri to video url.
 *
 * @MigrateProcessPlugin(
 *   id = "youtube"
 * )
 */
class Youtube extends ProcessPluginBase {

  const SCHEME = 'youtube://';
  const BASE_URL = 'http://youtube.com/watch?';
  private $url = '';

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Convert youtube scheme uri to video url.
    //https://youtu.be/-BlddHn_2hc
    $this->url = $value['video_url'];
    $this->trimmer();
    $this->shorten();
    /*
     * https://youtu.be/WMRGA-ZnBxo
    $ytshorturl = 'youtu.be/';
    $ytlongurl = 'www.youtube.com/watch?v=';
    if (strpos($url,$yturl) !== false) {
      $url = str_replace($ytshorturl, $ytlongurl, $url);
    }
    */
    return $this->url;
  }
  private function trimmer() {
    if(strpos($this->url , 'youtube=') === 0) {
      $this->url = str_replace('youtube=', '', $this->url);
    }
  }
  private function shorten() {
    // Get into https://youtu.be/WMRGA-ZnBxo format
    //

    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->url, $match);
    $youtube_id = $match[1];
    if($youtube_id) {
      $this->url= 'https://youtu.be/'. $youtube_id ;
    }
  }
  private function extractUTubeVidId($url){
	/*
	* type1: http://www.youtube.com/watch?v=H1ImndT0fC8
	* type2: http://www.youtube.com/watch?v=4nrxbHyJp9k&feature=related
	* type3: http://youtu.be/H1ImndT0fC8
	*/
	$vid_id = "";
	$flag = false;
	if(isset($url) && !empty($url)){
		/*case1 and 2*/
		$parts = explode("?", $url);
    var_dump($parts);
		if(isset($parts) && !empty($parts) && is_array($parts) && count($parts)>1){
			$params = explode("&", $parts[1]);
			if(isset($params) && !empty($params) && is_array($params)){
				foreach($params as $param){
					$kv = explode("=", $param);
					if(isset($kv) && !empty($kv) && is_array($kv) && count($kv)>1){
						if($kv[0]=='v'){
							$vid_id = $kv[1];
							$flag = true;
							break;
						}
					}
				}
			}
		}
		
		/*case 3*/
		if(!$flag){
			$needle = "youtu.be/";
			$pos = null;
			$pos = strpos($url, $needle);
			if ($pos !== false) {
				$start = $pos + strlen($needle);
				$vid_id = substr($url, $start, 11);
				$flag = true;
			}
		}
	}
	return $vid_id;
}

}
