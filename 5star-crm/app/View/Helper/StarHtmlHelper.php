<?php 

App::uses('HtmlHelper', 'View/Helper');

class StarHtmlHelper extends HtmlHelper {

   public function imageUrl($url = null) {
	   $result = parent::assetUrl($url, array('pathPrefix' => IMAGES_URL));
	   // if end with / we remove the timestamp
	   if (substr_compare($url, '/', -1, 1) === 0) {
		   $result = preg_replace('/\?.*/', '', $result);
	   }
	   return $result;
   }

}

?>