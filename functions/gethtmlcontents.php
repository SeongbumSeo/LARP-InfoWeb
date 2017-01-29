<?php
print(iconv("EUC-KR", "UTF-8", file_get_contents($_POST['url'])));
?>