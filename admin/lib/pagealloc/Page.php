<?php
Class Page{
	private $total;//总共显示的条数
	private $size;//每页显示的条数
	private $pageCount;//总的页数
	private $cur;
	private $showPages = 2;//显示的页数
	private $pageStart;
	private $pageEnd;
	public function __construct($total,$size,$cur){
		$this->total = $total;
		$this->size = $size;
		$this->pageCount = ceil($total / $size);
		$this->cur = $cur;
		$this->pageStart = ($this->cur - $this->showPages);
		//start 0 cur 2 end 5 应该是 end+(1-start)
		//strat -2 cur 1 end 4 应该是 7 end + (1-start)
		//start 1  cur 4 end 7
		$this->pageEnd = ($this->cur + $this->showPages);
		//end = 13 cur 11 start 8 应该是7
		//end = 13 cur 12 start 9 应该是7
		//end = 13 cur 13 start 10 应该是 7
		if($this->pageStart > $this->pageCount - 2*$this->showPages ){
		$this->pageStart = $this->pageCount - 2*$this->showPages;
		}
		if($this->pageStart <=0){
			$this->pageEnd = $this->pageEnd + (1-$this->pageStart);
			$this->pageStart = 1;
		}
		if($this->pageEnd > $this->pageCount){
			$this->pageEnd = $this->pageCount;
		}
	}
	public function showPage(){
		if($this->total > $this->size){
		$str = '<div id="page">';
		$str .= $this->homePage();
		$str .= $this->prePage();
		$str .= $this->midPage();
		$str .= $this->nextPage();
		$str .= $this->lastPage();
		$str .= $this->showTotal();
		$str .= '</div>';
		}else{
			$str='';
		}
		return $str;

	}
	//首页
	private function homePage(){
		if($this->cur ==1){
		return '<p>首页</p>';
		}else{
		return '<a href=?page=1>首页</a>';
		}
	}
	//上一页
	private function prePage(){
		if($this->cur == 1){
			return '<p>上一页</p>';
		}else{
			$pre = $this->cur -1;
			return "<a href=?page=$pre>上一页</a>";
		}
	}
	//中间的页码
	private function midPage(){
		$str = '';
		if($this->pageStart != 1){
			$str .= '...';
		}
		for($i=$this->pageStart;$i<=$this->pageEnd;$i++){
			if($this->cur == $i){
				$str.="<a class=$this->cur href=?page=$i> $i </a>";
				
			}else{
				$str.="<a href=?page=$i> $i </a>";
			}
		}
		if($this->pageEnd != $this->pageCount){
			$str.='...';
		}
		return $str;
	}
	private function nextPage(){
		if($this->cur == $this->pageCount){
		return '<p>下一页</p>';
		}else{
		$next = $this->cur +1;
		return "<a href=?page=$next>下一页</a>";
		}
	}
	private function lastPage(){
		if($this->cur == $this->pageCount){
		return '<p>尾页</p>';
		}else{
		$next = $this->cur +1;
		return "<a href=?page=$this->pageCount>尾页</a>";
		}
		
	}
	private function showTotal(){
		return "<p>共<b>$this->pageCount</b>页 共<b>$this->total</b>条数据</p>";
	}

}
?>
