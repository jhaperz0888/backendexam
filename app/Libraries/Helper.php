<?php 
namespace App\Libraries;
use DateTime;
use DateTimeZone;
use DB;
use Auth;
class Helper {

	public static function validateErrors($request,$msg)
	{
	    $arr = [];
	    foreach ($request->all() as $key => $value) {
	    	if( !empty( $msg->first($key) ) )
	        $arr[$key] = $msg->first($key);
	    }
	    return $arr;
	}

	public static function getDateTimePHP()
	{
	    $datetime = new DateTime(null, new DateTimeZone('Asia/Manila'));
	    return $datetime->format('Y-m-d H:i:s');
	}

	public static function pr($data)
	{
	    echo "<pre>";
	    print_r($data);
	    exit;
	}

	/** Convert all objects to arrays **/
	public static function toArray($obj)
	{
	    if (is_object($obj)) $obj = (array)$obj;
	    if (is_array($obj)) {
	        $new = array();
	        foreach ($obj as $key => $val) {
	            $new[$key] = self::toArray($val);
	        }
	    } else {
	        $new = $obj;
	    }

	    return $new;
	}

/** Search array with value and key return the searched array **/
	public static function searchArray($array, $key, $value, $key1, $value1)
	{
	    $results = array();

	    if (is_array($array)) {
	        if (isset($array[$key]) && $array[$key] == $value && isset($array[$key1]) && $array[$key1] == $value1) {
	            $results[] = $array;
	        }

	        foreach ($array as $subarray) {
	            $results = array_merge($results, self::searchArray($subarray, $key, $value, $key1, $value1));
	        }
	    }

	    return $results;
	}

	// public static function search($array, $key, $value)
	// {
	//     $results = array();
	//     Helper::search_r($array, $key, $value, $results);
	//     return $results;
	// }

	// public static function search_r($array, $key, $value, &$results)
	// {
	//     if (!is_array($array)) {
	//         return;
	//     }

	//     if (isset($array[$key]) && $array[$key] == $value) {
	//         $results[] = $array;
	//     }

	//     foreach ($array as $subarray) {
	//         Helper::search_r($subarray, $key, $value, $results);
	//     }
	// }

/** Search array with key and value return the key **/
	public static function searchSubArray(Array $array, $key, $value, $key1, $value1) {
		//$arr=[];
	    foreach ($array as $keys => $subarray){
	        if (isset($subarray[$key]) && $subarray[$key] == $value && isset($subarray[$key1]) && $subarray[$key1] == $value1)
	          return $keys;
	    }
	}
   // return $arr;

	public static function arraySearch(Array $array, $key) {
		//$arr=[];
	    foreach ($array as $keys => $subarray){
	        if (isset($array[$key]))
	          return $array[$key];
	    }
	}

/** BEGIN Array search with a key and value **/
	public static function searchArray2($array, $key, $value)
	{
	    $results = array();

	    if (is_array($array)) {
	        if (isset($array[$key]) && $array[$key] == $value) {
	            $results[] = $array;
	        }

	        foreach ($array as $subarray) {
	            $results = array_merge($results, self::searchArray2($subarray, $key, $value));
	        }
	    }

	    return $results;
	}

	public static function searchSubArray2(Array $array, $key, $value) {
		//$arr=[];
	    foreach ($array as $keys => $subarray){
	        if (isset($subarray[$key]) && $subarray[$key] == $value)
	          return $keys;
	    }
	}
/** END Array search with a key and value **/

	public static function searchKey(Array $array, $key) { // search key from multi-dimensional array and return the results.
	    foreach ($array as $keys => $subarray){
	        if (isset($array[$keys]))
	          return $array[$keys];
	    }
	}

	public static function generateEAN($prefix,$number)
	{
		$code = $prefix . str_pad($number, 12-strlen($prefix), '0',STR_PAD_LEFT);
		$weightflag = true;
		$sum = 0;
		// Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit. 
		// loop backwards to make the loop length-agnostic. The same basic functionality 
		// will work for codes of different lengths.
		for ($i = strlen($code) - 1; $i >= 0; $i--)
		{
		$sum += (int)$code[$i] * ($weightflag?3:1);
		$weightflag = !$weightflag;
		}
		$code .= (10 - ($sum % 10)) % 10;
		return $code;
	}

	public static function user_access($page_id,$action_id)
	{
		$access = DB::table('user_access')
					->where('action_id',$action_id)
					->where('page_id',$page_id)
					->where('user_group_id',Auth::user()->user_group_id)
					->first();
		if($access) {
			return true;
		}
		return false;
	}

	public static function user_access_childs($page_id,$action_id)
	{
		$access = DB::table('user_access')
					->where('action_id',$action_id)
					->whereIn('page_id',$page_id)
					->where('user_group_id',Auth::user()->user_group_id)
					->first();
		if($access) {
			return true;
		}
		return false;
	}

	public static function activity_logs($data)
	{
		return DB::table('activity_logs')->insert($data);
	}

	public static function createDateRangeArray($strDateFrom,$strDateTo)
	{
	    $aryRange=array();

	    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	    if ($iDateTo>=$iDateFrom)
	    {
	        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
	        while ($iDateFrom<$iDateTo)
	        {
	            $iDateFrom+=86400; // add 24 hours
	            array_push($aryRange,date('Y-m-d',$iDateFrom));
	        }
	    }
	    return $aryRange;
	}
	public static function sanitize($string) {
       // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.

       return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function resize_image($file, $w, $h, $crop=FALSE) {
	    list($width, $height) = getimagesize($file);
	    $r = $width / $height;
	    if ($crop) {
	        if ($width > $height) {
	            $width = ceil($width-($width*abs($r-$w/$h)));
	        } else {
	            $height = ceil($height-($height*abs($r-$w/$h)));
	        }
	        $newwidth = $w;
	        $newheight = $h;
	    } else {
	        if ($w/$h > $r) {
	            $newwidth = $h*$r;
	            $newheight = $h;
	        } else {
	            $newheight = $w/$r;
	            $newwidth = $w;
	        }
	    }
	    $src = imagecreatefromjpeg($file);
	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	    return $dst;
	}
}