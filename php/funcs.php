<!--便利な関数をどんどん入れよう  -->
<?php
//XSS対応関数
function h($val){
  return htmlspecialchars($val,ENT_QUOTES);
}



?>
