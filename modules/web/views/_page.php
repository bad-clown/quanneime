<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php
$pageCount = intval($pageCount);
$page  = intval($page);
?>
<?php  if($pageCount>1){
?>
<nav class="text-center">
  <ul class="pagination">
    <li class="<?= $page==1?"disabled":"" ?>"><a href="<?=Url::to(array_merge([""],$_GET,["page"=>1]))?>" ><span>&laquo;</span></a></li>
<?php
if($pageCount<=5){
?>
<?php
for ( $i = 1; $i <= $pageCount; $i++ ) {
?>
    <li class="<?= $page ==$i?"active ":"" ?>"><a href="<?=Url::to(array_merge([""],$_GET,["page"=>$i]))?>"><?=$i?><span class="sr-only"></span></a></li>
<?php
}
}else{
?>

    <li class="<?= $page ==1?"active ":"" ?>"><a href="<?=Url::to(array_merge([""],$_GET,["page"=>1]))?>">1<span class="sr-only"></span></a></li>
    <li class="<?= $page ==2?"active ":"" ?>"><a href="<?=Url::to(array_merge([""],$_GET,["page"=>2]))?>">2<span class="sr-only"></span></a></li>
<?php
    if($page-5>0){
?>

    <li class="disabled"><a href="#">...<span class="sr-only"></span></a></li>
<?php
    }

    for ( $i = $page-2; $i <=$page+2; $i++ ) {
        if($i>2 && $i<=$pageCount){
?>
    <li class="<?= $page ==$i?"active ":"" ?>"><a href="<?=Url::to(array_merge([""],$_GET,["page"=>$i]))?>"><?=$i?><span class="sr-only"></span></a></li>

<?php
    }
    }
    if($pageCount-$page-2>0){
?>
    <li class="disabled"><a href="#">...<span class="sr-only"></span></a></li>


<?php
    }
?>


<?php

}
?>
    <li class="<?= $page==$pageCount?"disabled":"" ?>"><a href="<?=Url::to(array_merge([""],$_GET,["page"=>$pageCount]))?>" ><span>&raquo;</span></a></li>
  </ul>
      </nav>
<?php
}// end if pageCount>1
?>
