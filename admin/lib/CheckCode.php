<?php

class CheckCode{
	private $width;
	private $height;
	private $hImg;
	private $str;
	private $code;
	private $code_len;
	public function __construct($width,$height,$str,$code_len){
		$this->width = $width;
		$this->height = $height;
		$this->hImg = imagecreate($this->width,$this->height);
		$this->str = $str;
		$this->code_len = $code_len;
	}


	public function draw(){
		$this->draw_bk();
		$this->set_code();
		$this->set_session();
		$this->draw_code();
		$this->draw_salt();
		ob_end_clean();
		header("Content-type:image/jpeg");
		imagejpeg($this->hImg);
		imagedestroy($this->hImg);
	
	}
	private function draw_bk(){
		imagecolorallocate($this->hImg,0,0,0);
	}
	private function set_code(){
		for($i=0;$i<$this->code_len;$i++){
			$this->code .= $this->str[mt_rand(0,strlen($this->str)-1)];
		}
	}
	private function set_session(){
		session_start();
		$_SESSION['code'] = strtolower($this->code);
	}
	private function draw_code(){
		for($i=0;$i<$this->code_len;$i++){
			$size = 18;
			$x = $i * ($this->width / $this->code_len) + $size;
			$y = mt_rand($size,$this->height-$size);
			$color = imagecolorallocate($this->hImg,0,255,0);
			$angle = mt_rand(-25,25);
			imagettftext($this->hImg,$size,$angle,$x,$y,$color,'code2.ttf',$this->code[$i]);
		}
	}
	private function draw_salt(){
		for($i=0;$i<5;$i++){
			$bx = 0;
			$by = mt_rand(0,$this->height);
			$ex = $this->width;
			$ey = mt_rand(0,$this->height);
			$lcolor = imagecolorallocate($this->hImg,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imageline($this->hImg,$bx,$by,$ex,$ey,$lcolor);
		}
		for($i=0;$i<100;$i++){
			$x = mt_rand(0,$this->width);
			$y = mt_rand(0,$this->height);
			$pcolor = imagecolorallocate($this->hImg,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($this->hImg,$x,$y,$pcolor);
		}
	}

}

$str = 'abcdefghjkmnpqrstuvwxyz23456789ABCDEFGHJKMNPQRSTUVWXYZ';
$cc = new CheckCode(200,50,$str,4);
$cc->draw();

?>
