<html>
<body>

<!--表单部分-->

<form method="post">
up主UID： <input type="text" name="uid"><br>
<input type="submit">
</form>

<hr>


<!--下面为获取up主名字-->


<?php
$uid = $_POST["uid"];
if (isset($_POST['uid'])) {
    $handle = fopen("http://api.bilibili.com/x/space/acc/info?mid=$uid", "rb");
    $upname_url = "";
    while (!feof($handle)) {
        $upname_url.= fread($handle, 10000);
    }
    fclose($handle);
    $getname = json_decode($upname_url, true);
    $getname = $getname['data'];
    echo ("<br>up主名字：&nbsp");
    echo ($getname['name']);
    //下面为获取up主直播间UID
    $handle = fopen("http://api.live.bilibili.com/bili/living_v2/$uid", "rb");
    $liveid_get = "";
    while (!feof($handle)) {
        $liveid_get.= fread($handle, 10000);
    }
    fclose($handle);
    $getlive = json_decode($liveid_get, true);
    $getlive = $getlive['data'];
    $getlive = $getlive['url'];
    $getlive = substr($getlive, 26, 15);
    echo ("<br>up主LiveUID：&nbsp");
    echo ($getlive);
    //下面为获取up主直播间推流地址
    $handle = fopen("https://api.live.bilibili.com/room/v1/Room/playUrl?cid=+$getlive+&qn=0&platform=web", "rb");
    $get = "";
    while (!feof($handle)) {
        $get.= fread($handle, 10000);
    }
    fclose($handle);
    $getjson = json_decode($get, true);
    $getjson = $getjson['data'];
    $getjson = $getjson['durl'];
    $getjson = json_encode($getjson[1]['url']);
    echo ("<br>up主直播间推流地址：&nbsp");
    echo (stripslashes($getjson));
}
?>

<!--
此页面其实就是把python的代码移植到PHP做成了网站版，你可以稍作修改即可上线
但是还希望您能浏览python来知道这些的原理和使用理由来帮助你优化代码
-->




</body>
</html>