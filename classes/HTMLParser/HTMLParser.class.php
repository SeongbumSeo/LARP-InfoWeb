<?php
class HTMLParser {
    private $url = null;
    private $html = null;
    
    public function __construct($url) {
        $this->url = $url;
        $this->html = iconv("EUC-KR", "UTF-8", file_get_contents($url));
    }
    
    public function getHTMLStack() {
        return $this->html;
    }
    
    public function setHTMLStack($data) {
        $this->html = $data;
    }
    
    public function getContents() {
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
    
    public function getContentsFromPart($data) {
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