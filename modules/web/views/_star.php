<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: /home/work/ibangbuy/modules/web/views/_star.php
 * $Id: /home/work/ibangbuy/modules/web/views/_star.php v 1.0 2016-01-13 21:38:19 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-13 22:42:23 $
 * @brief
 *
 ******************************************************************/
$star = intval($star);
for ( $i = 1; $i <= 5; $i++ ) {
    echo "<span class=\"".(($i*2<=$star)?"star":(($star-($i*2))==-1?"halfstar":"star unstar"))."\"></span>";
}
?>


