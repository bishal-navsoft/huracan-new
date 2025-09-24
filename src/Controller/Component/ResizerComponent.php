<?php
	class ResizerComponent extends Component {

		//define ("MAX_SIZE","400");	
		public function upload($tmp_name,$name,$dest1,$dest2,$given_width,$given_hight)
		{
			$image =$name;
			$uploadedfile = $tmp_name;

			$filename = stripslashes($name);
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
			{
				echo ' Unknown Image extension ';
				$errors=1;
			}

			if($extension=="jpg" || $extension=="jpeg" )
			{
				$uploadedfile = $tmp_name;
				$src = imagecreatefromjpeg($uploadedfile);
			}
			else if($extension=="png")
			{
				$uploadedfile = $tmp_name;
				$src = imagecreatefrompng($uploadedfile);
			}
			else 
			{
				$src = imagecreatefromgif($uploadedfile);
			}

			list($width,$height)=getimagesize($uploadedfile);

			/*$newwidth=$width;
			$newheight=$height;
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
			$filename = $dest1."big_".$name;
			imagejpeg($tmp,$filename,100);*/

			$newwidth1=35;
			$newheight1=35;
			$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
			imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
			//$filename1 = $dest2."small_".$name;
			$filename1 = $dest2.$name;
			imagejpeg($tmp1,$filename1,100);

			imagedestroy($src);
			imagedestroy($tmp);
			imagedestroy($tmp1);
		}

		function getExtension($str)
		{
			$i = strrpos($str,".");
			if (!$i) { return ""; } 

			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		}	
	}
?>