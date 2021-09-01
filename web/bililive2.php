<html>
<body>
    <link rel="stylesheet" type="text/css" href="demo.css" />
<?php
$uid = $_GET["uid"];
if (isset($_GET['uid'])) {
    $handle = fopen("http://api.bilibili.com/x/space/acc/info?mid=$uid", "rb");
    $upname_url = "";
    while (!feof($handle)) {
        $upname_url.= fread($handle, 10000);
    }
    fclose($handle);
    $getname = json_decode($upname_url, true);
    $getname = $getname['data'];
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
}

$getjson=str_replace('"','',stripslashes($getjson));
?>
<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>flv直播流播放</title>

    <style>
        .mainContainer {
            display: block;
            width: 1024px;
            margin-left: auto;
            margin-right: auto;
        }

        .urlInput {
            display: block;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .centeredVideo {
            display: block;
            width: 100%;
            height: 576px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: auto;
        }

        .controls {
            display: block;
            width: 100%;
            text-align: left;
            margin-left: auto;
            margin-right: auto;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .logcatBox {
            border-color: #CCCCCC;
            font-size: 11px;
            font-family: Menlo, Consolas, monospace;
            display: block;
            width: 100%;
            text-align: left;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    
    <div class="mainContainer">
        <video muted name="videoElement" class="centeredVideo" id="videoElement" controls width="1024" height="576" autoplay>
            Your browser is too old which doesn't support HTML5 video.
        </video>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/flv.js@1.5.0/dist/flv.min.js"></script>
    
    <script>
         if (flvjs.isSupported()) {
            startVideo()
        }

        function startVideo(){
            var videoElement = document.getElementById('videoElement');
            var flvPlayer = flvjs.createPlayer({
                type: 'flv',
                isLive: true,
                hasAudio: true,
                hasVideo: true,
                enableStashBuffer: true,
                url: '<?php echo ($getjson) ?>'
            });
            flvPlayer.attachMediaElement(videoElement);
            flvPlayer.load();
            flvPlayer.play();
        }

        videoElement.addEventListener('click', function(){
            alert( ' 是直播？：' + flvjs.getFeatureList().mseLiveFlvPlayback )
        })

        function destoryVideo(){
            flvPlayer.pause();
            flvPlayer.unload();
            flvPlayer.detachMediaElement();
            flvPlayer.destroy();
            flvPlayer = null;
        }

        function reloadVideo(){
            destoryVideo()
            startVideo()
        }
    </script>
    
</body>

</html>
