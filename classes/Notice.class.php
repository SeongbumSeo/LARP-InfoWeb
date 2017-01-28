<?php
class Notice {
	private $html = null;
	private $notice = array();
	
	public function __construct($url, $date_index, $max_notices) {
		$this->html = iconv("EUC-KR", "UTF-8", file_get_contents($url));
		$this->setHTMLStack(explode("<!-- 공지기능 적용끝  -->", $this->getHTMLStack())[1]);
		for($i = 0; $i < $max_notices; $i++) {
			$line = $this->getContents("tr", $i);
            $subject = explode("<a href=\"javascript:;\"", $this->getContentsFromPart($line, "td", 2))[0];
			$subject = str_replace("href=\"", "href=\"http://cafe.daum.net", $subject);
			$subject = str_replace("<a ", "<a target=\"_blank\" ", $subject);
			$subject = str_replace("color=\"#000000\"", "", $subject);
			$date = $this->getContentsFromPart($line, "td", $date_index);
			$this->notice[$i] = array('subject' => $subject, 'date' => $date);
		}
	}

	public function getNotice() {
		return $this->notice;
	}
	
	private function getHTMLStack() {
		return $this->html;
	}
	
	private function setHTMLStack($data) {
		$this->html = $data;
	}
	
	private function getContents() {
		$args = func_get_args();
		$numargs = func_num_args();
		$index = $args[$numargs-1];
		
		$temp = $this->html;
		for($i = 0; $i < $numargs-2; $i++)
			$temp = explode("<$args[$i]", $temp, 2)[1];
		$temp = explode("<$args[$i]", $temp, $index+2)[$index+1];
		$temp = explode(">", $temp, 2)[1];
		$temp = explode("</".$args[$i].">", $temp, 2)[0];
		return $temp;
	}
	
	private function getContentsFromPart($data) {
		$args = func_get_args();
		$numargs = func_num_args();
		$index = $args[$numargs-1];
		
		$temp = $data;
		for($i = 1; $i < $numargs-2; $i++)
			$temp = explode("<$args[$i]", $temp, 2)[1];
		$temp = explode("<$args[$i]", $temp, $index+2)[$index+1];
		$temp = explode(">", $temp, 2)[1];
		$temp = explode("</".$args[$i].">", $temp, 2)[0];
		return $temp;
	}
}
?>