<?php
class Upload{
	private $errorNum;//错误码
	private $errorMsg;//错误信息
	private $filename;//文件名
	private $ext;//文件扩展名
	private $allow_ext;//白名单
	private $file_size;
	private $allow_size;
	private $dir;
	private $sub_dir;
	private $new_name;
	private $tmp_path;
	public function __construct($file,$dir = 'uploads',$allow_ext=['gif','jpg','png','jpeg'],$allow_size = 2*1024*1024){
		$this->errorNum = $file['error'];
		$this->filename = $file['name'];
		$this->ext = pathInfo($this->filename,PATHINFO_EXTENSION);
		$this->allow_ext = ['gif','jpg','png','jpeg'];
		$this->file_size = $file['size'];
		$this->allow_size = $allow_size;
		$this->dir = $dir;
		$this->sub_dir = $dir.DIRECTORY_SEPARATOR.date('Y-m-d');
		$this->new_name = $this->sub_dir.DIRECTORY_SEPARATOR.date('YmdHis').rand(1000,9999).'.'.$this->ext;
		$this->tmp_path = $file['tmp_name'];
	}
	public function upload(){
		if($this->checkFile() == false){
			return [false,$this->errorMsg];
		}
		$this->createDir();
		return [true,$this->move()];
	}
	
	private function checkFile(){
		if($this->checkError() == false){
			$this->errorMsg = "上传图片有问题";
			return false;
		}
		if($this->checkExt() == false){
			$this->errorMsg = "上传图片的扩展名有问题";
			return false;
		}
		if($this->checkSize()==false){
			$this->errorMsg = "上传图片的大小有问题";
			return false;
		}
		return true;
	}
	//检测上传信息
	private function checkError(){
		return $this->errorNum == 0;
	}
	//检测扩展名
	private function checkExt(){
		return in_array($this->ext,$this->allow_ext);
		
	}
	//检测大小
	private function checkSize(){
		return $this->file_size <= $this->allow_size;
	
	}
	//创建目录
	private function createDir(){
		if(!is_dir('uploads')){
			mkdir('uploads');
		}
		if(!is_dir($this->sub_dir)){
			mkdir($this->sub_dir);
		}
	}
	//移动文件到指定目录
	private function move(){
		move_uploaded_file($this->tmp_path,$this->new_name);
		return $this->new_name;
	}
	public function show_picture(){
		echo '<img src = '.$this->new_name.'>';
	}
}
// $file = $_FILES['img'];
// $upload = new Upload($file);
// echo $upload->upload();
// $upload->show_picture();

/*
echo '<pre/>';
var_dump($_FILES);
echo '<pre/>';
*/
/*
$fileInfo=$_FILES['img'];
if($file['error']!=0){
	exit("上传图片有问题");
}
//验证文件的扩展名
$ext = pathInfo($fileInfo['name'],PATHINFO_EXTENSION);
$allow_ext = ['jpg','png','jpeg','gif'];//白名单
if(!in_array($ext,$allow_ext)){
	exit('该类型文件不可以上传,请上传jpg/png/jpeg/gif类型的图片');}
//验证文件的大小
$allow_size = 1024*1024 * 2;//单位字节 2MB
if($fileInfo['size'] > $allow_size){
	exit("文件太大,无法上传");
}
//创建上传目录
$dir = 'uploads';

//$sub_dir = $dir.'/'.date('Y-m-d');
$sub_dir = $dir.DIRECTORY_SEPARATOR.date('Y-m-d');

if(!is_dir($dir)){
	mkdir($dir);
}
if(!is_dir($sub_dir)){
	mkdir($sub_dir);
}
//$img_name = date('YmsHis').rand(1000,9999).'.'.$ext;
$img_name = date('YmsHis').rand(1000,9999).'.'.$ext;
move_uploaded_file($fileInfo['tmp_name'],$sub_dir.DIRECTORY_SEPARATOR.$img_name);
*/

?>
