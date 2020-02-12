<?php
class Db{
	//存储类的唯一的实例化对象
	private static $instance;
	//存储PDO类的实例化；
	private $db;
	//存储PDOStatement的实例化；
	private $stmt;
	//禁止外部new类
	private function __construct($config){
		try{
			$this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8',$config['user'],$config['password']);
			//$this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo '连接失败:'.$e->getMessage();
		}
	}
	//禁止外部克隆对象。
	private function __clone(){}
	//对外访问的方法 实现类的实例
	public static function getInstance($config){
	//判断是否有实例化，如果没有实例化，就实例化
		if(!self::$instance instanceof Db){
			self::$instance = new self($config);//相当于new Db()
		}
		//已经实例化,直接返回实例化
		return self::$instance;
	}
	//插入数据
	public function insertData($table,$data){
		//1.获得data数组的所有键组成的数组
		$keys = array_keys($data);
		//2.将该数组中的所有元素拼接成字符串。
		$keys_str = implode(',',$keys);
		$values_str =':'.implode(',:',$keys);
		$sql = "INSERT INTO $table ($keys_str) VALUES($values_str)";
		$newData = $this->doData($data);
		$this->exec($sql,$newData);
		if($this->stmt->errorCode() == 00000){
			// echo '插入成功';
			// return $this->db->lastInsertId();
			return true;
		}else{
			// echo '插入失败:';
			// return $this->stmt->errorInfo()[2];
			return false;
		}
	}
	//更新数据
	public function updateData($data,$where){
		$columns = "";
		foreach($data as $k=>$v){
			$columns.=$k.'=:'.$k.',';
		}
		$columns = trim($columns,',');

		$sql = "UPDATE user SET $columns WHERE $where";
		$newData = $this->doData($data);
		$this->exec($sql,$newData);
		if($this->stmt->errorCode() == 0){
			return true;
		}else{
			// print_r($this->stmt->errorInfo());
			return false;
		}
	}
	//查询一条数据
	public function getOne($table,$field,$where){
		if(count($field) >1){
		$field_str = implode(',',$field);
		}else{
		$field_str = $field[0];
		}
		$sql = "SELECT $field_str FROM $table WHERE $where ";
		$this->exec($sql);
		if($this->stmt->errorCode()==0){
			$arr =$this->stmt->fetch();
			if(empty($arr)){
				return false;
			}else{
				return true;
			}	
		}else{
			// echo '查询失败:';
			// print_r($this->stmt->errorInfo());
			return false;
		}
		
	}
	public function returnOne(){
		if($this->stmt->errorCode()==0){
			// print_r($this->stmt->fetch());
			return $this->stmt->fetch();
		}else{
			echo '查询失败:';
			print_r($this->stmt->errorInfo());
		}
	}
	//查询多条数据(有limit版本)
	public function limit_getAll($table,$field,$where,$from,$count){
		if(count($field) >1){
		$field_str = implode(',',$field);
		}else{
		$field_str = $field[0];
		}
		$sql = "SELECT $field_str FROM $table WHERE $where LIMIT $from,$count";
		$this->exec($sql);
		if($this->stmt->errorCode()==0){
			// echo '<pre>';
			//print_r($this->stmt->fetchAll());
			return $this->stmt->fetchAll();
			//return $this->stmt->rowCount();
			// echo '</pre>';
		}else{
			echo '查询失败:';
			print_r($this->stmt->errorInfo());
		}
	
	}
	//查询多条数据(无limit版本)
	public function getAll($table,$field,$where){
		if(count($field) >1){
		$field_str = implode(',',$field);
		}else{
		$field_str = $field[0];
		}
		$sql = "SELECT $field_str FROM $table WHERE $where";
		$this->exec($sql);
		if($this->stmt->errorCode()==0){
			// echo '<pre>';
			//print_r($this->stmt->fetchAll());
			return $this->stmt->fetchAll();
			//return $this->stmt->rowCount();
			// echo '</pre>';
		}else{
			// echo '查询失败:';
			// print_r($this->stmt->errorInfo());
			return [];
		}
	
	}
	public function getAllInfo($page,$size){
		$sql = 'SELECT * FROM user';
		$sql.=' LIMIT '.(($page-1)*$size).','.$size;
		$this->exec($sql);
		$message = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		// echo "<pre>";
		// var_dump($message);
		// echo "</pre>";
		return $message;
	}
	public function getAllMessage($page,$size){
		$sql = 'SELECT u.username,m.content,m.img_url,m.create_time,m.id,m.com_count FROM message as m LEFT JOIN user as u ON m.user_id=u.id ORDER BY m.create_time DESC';
		$sql.=' LIMIT '.(($page-1)*$size).','.$size;
		$this->exec($sql);
		$message = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		// echo "<pre>";
		// var_dump($message);
		// echo "</pre>";
		return $message;
	}
	public function getOneMessage($message_id){
		$sql = 'SELECT u.username,m.content,m.img_url,m.create_time,m.id FROM message as m LEFT JOIN user as u ON m.user_id=u.id WHERE m.id='.$message_id;
		$this->exec($sql);
		$message = $this->stmt->fetch(PDO::FETCH_ASSOC);
		return $message;
	}
	public function getPreId($message_id){
		$sql = "SELECT id FROM message WHERE id<$message_id ORDER BY id DESC LIMIT 1";
		$this->exec($sql);
		$id = $this->stmt->fetch()[0];
		return $id;
	}
	public function getNextId($message_id){
		$sql = "SELECT id FROM message WHERE id>$message_id ORDER BY id LIMIT 1";
		$this->exec($sql);
		$id = $this->stmt->fetch()[0];
		return $id;
	}
	public function getCount($table,$field,$where){
		if(count($field) >1){
		$field_str = implode(',',$field);
		}else{
		$field_str = $field[0];
		}
		$sql = "SELECT $field_str FROM $table WHERE $where";
		$this->exec($sql);
		if($this->stmt->errorCode()==0){
			// echo '<pre>';
			//print_r($this->stmt->fetchAll());
			return $this->stmt->rowCount();
			// echo '</pre>';
		}else{
			// echo '查询失败:';
			// print_r($this->stmt->errorInfo());
			return 0;
		}
	}
	public function infoCount(){
		$sql = "SELECT id FROM user";
		$this->exec($sql);
		$total = $this->stmt->rowCount();
		return $total;
	}
	public function messageCount(){
		$sql = "SELECT id FROM message";
		$this->exec($sql);
		$total = $this->stmt->rowCount();
		return $total;
	}
	public function deleteData($table,$where){
		$sql="DELETE FROM $table WHERE $where";
		$this->exec($sql);
		if($this->stmt->errorCode()==0){
			// echo '删除成功';
			return true;
		}else{
			// echo '删除失败:';
			// print_r($this->stmt->errorInfo());
			return false;
		}
		
	}
	public function setComment($id){
		$sql = "UPDATE message SET com_count=com_count+1 WHERE id=$id";
		$this->exec($sql);
	}
	public function getComments($message_id){
		$sql ="SELECT u.username,c.content FROM comment as c  LEFT JOIN user as u ON c.user_id=u.id WHERE c.message_id=$message_id";
		$this->exec($sql);
		$res = $this->stmt->fetchAll();
		return $res;
	}
	//处理插入数据
	private function doData($data){
		foreach($data as $k=>$v){
			$key = ':'.$k;
			$newData[$key] = $v;
		}
		return $newData;
	}
	//执行sql语句
	private function exec($sql,$data=[]){
		$this->stmt = $this->db->prepare($sql);
		$this->stmt->execute($data);
	}

}
/*
$config = ['host'=>'localhost','dbname'=>'gaokao','user'=>'root','password'=>'root'];
$db1 = Db::getInstance($config);
$data = ['name'=>'卢克韦林','pwd'=>md5('lukeweiling'),'fee'=>100000];
$db1->insertData('user',$data);
$db1->updateData('user',$data,'id = 2');
$field = ['name','pwd','fee'];
$db1->getOne('user',$field,'id = 1');
$db1->getAll('user',$field,'id>=1');
$db1->deleteData('user','id > 9');
*/
?>
