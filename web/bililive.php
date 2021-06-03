<html>
<body>
    <link rel="stylesheet" type="text/css" href="demo.css" />
<!--表单部分-->
<center>
<form method="post">
up主UID： <input type="text" name="uid"><br>
<input type="submit">
</form>
</center>
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
<br>
<p>点击跳转</p>
<a href=<?php echo (stripslashes($getjson)) ?> >直播流</a>
<hr>
<!--
此页面其实就是把python的代码移植到PHP做成了网站版，你可以稍作修改即可上线
但是还希望您能浏览python来知道这些的原理和使用理由来帮助你优化代码
-->
<h3>预览</h3>
<p>此预览的flv播放器是使用的bilibili的flv.js，请先点击load才能播放，如果停顿为正常现象，请点击load即可刷新</p>
<br><br><br>
<script src="https://cdn.jsdelivr.net/npm/flv.js@1.5.0/dist/flv.min.js"></script>
    <div class="mainContainer">
        <div>
            <div class="controls">
            <button onclick="flv_load()">Load</button>
            <button onclick="flv_start()">Start</button>
            <button onclick="flv_pause()">Pause</button>
            <button onclick="flv_destroy()">Destroy</button>
            <input style="width:100px" type="text" name="seekpoint"/>
            <button onclick="flv_seekto()">SeekTo</button>
        </div>
            <div id="streamURL">
                <div class="options">
                    <input type="checkbox" id="enableStashBuffer" onchange="saveSettings()" checked/>
                    <label for="enableStashBuffer">enableStashBuffer</label>
                    <input type="checkbox" id="isLive" onchange="saveSettings()" checked/>
                    <label for="isLive">isLive</label>
                    <input type="checkbox" id="withCredentials" onchange="saveSettings()" />
                    <label for="withCredentials">withCredentials</label>
                    <input type="checkbox" id="hasAudio" onchange="saveSettings()" checked />
                    <label for="hasAudio">hasAudio</label>
                    <input type="checkbox" id="hasVideo" onchange="saveSettings()" checked />
                    <label for="hasVideo">hasVideo</label>
                    <label for="stashInitialSize">stashInitialSize:</label>
                    <input id="stashInitialSize" type="text" value="10" />
                </div>
            </div>  
            <div id="mediaSourceURL" class="hidden">
                <div class="url-input">
                    <label for="msURL">MediaDataSource JsonURL:</label>
                    <input id="msURL" type="text" value="http://127.0.0.1/flv/7182741.json" />
                    <button onclick="switch_url()">Switch to URL</button>
                </div>
            </div>
        </div>
        <div class="video-container">
            <div>
                <video name="videoElement" class="centeredVideo" controls autoplay>
                    Your browser is too old which doesn't support HTML5 video.
                </video>
            </div>
        </div>
        
        <textarea name="logcatbox" class="logcatBox" rows="10" readonly></textarea>
    </div>

  
    
    <script>
        var checkBoxFields = ['enableStashBuffer', 'isLive', 'withCredentials', 'hasAudio', 'hasVideo'];
        var streamURL, mediaSourceURL;

        function flv_load() {
            console.log('isSupported: ' + flvjs.isSupported());
            if (mediaSourceURL.className === '') {
                var url = document.getElementById('msURL').value;
    
                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.onload = function (e) {
                    var mediaDataSource = JSON.parse(xhr.response);
                    flv_load_mds(mediaDataSource);
                }
                xhr.send();
            } else {
                var i;
                var mediaDataSource = {
                    type: 'flv'
                };
                for (i = 0; i < checkBoxFields.length; i++) {
                    var field = checkBoxFields[i];
                    /** @type {HTMLInputElement} */
                    var checkbox = document.getElementById(field);
                    mediaDataSource[field] = checkbox.checked;
                }
                mediaDataSource['url'] =<?php echo (stripslashes($getjson)) ?>;
                console.log('MediaDataSource', mediaDataSource);
                flv_load_mds(mediaDataSource);
            }
        }

        function flv_load_mds(mediaDataSource) {
            var element = document.getElementsByName('videoElement')[0];
            if (typeof player !== "undefined") {
                if (player != null) {
                    player.unload();
                    player.detachMediaElement();
                    player.destroy();
                    player = null;
                }
            }
            player = flvjs.createPlayer(mediaDataSource, {
                enableWorker: false,
                lazyLoadMaxDuration: 3 * 60,
                seekType: 'range',
            });
            player.attachMediaElement(element);
            player.load();
        }

        function flv_start() {
            player.play();
        }

        function flv_pause() {
            player.pause();
        }

        function flv_destroy() {
            player.pause();
            player.unload();
            player.detachMediaElement();
            player.destroy();
            player = null;
        }

        function flv_seekto() {
            var input = document.getElementsByName('seekpoint')[0];
            player.currentTime = parseFloat(input.value);
        }

        function switch_url() {
            streamURL.className = '';
            mediaSourceURL.className = 'hidden';
            saveSettings();
        }

        function switch_mds() {
            streamURL.className = 'hidden';
            mediaSourceURL.className = '';
            saveSettings();
        }

        function ls_get(key, def) {
            try {
                var ret = localStorage.getItem('flvjs_demo.' + key);
                if (ret === null) {
                    ret = def;
                }
                return ret;
            } catch (e) {}
            return def;
        }

        function ls_set(key, value) {
            try {
                localStorage.setItem('flvjs_demo.' + key, value);
            } catch (e) {}
        }

        function saveSettings() {
            if (mediaSourceURL.className === '') {
                ls_set('inputMode', 'MediaDataSource');
            } else {
                ls_set('inputMode', 'StreamURL');
            }
            var i;
            for (i = 0; i < checkBoxFields.length; i++) {
                var field = checkBoxFields[i];
                /** @type {HTMLInputElement} */
                var checkbox = document.getElementById(field);
                ls_set(field, checkbox.checked ? '1' : '0');
            }
            var msURL = document.getElementById('msURL');
            var sURL = document.getElementById('sURL');
            var stashInitSize = document.getElementById('stashInitialSize');
            ls_set('msURL', msURL.value);
            ls_set('sURL', sURL.value);
            ls_set('stashInitialSize', stashInitSize.value);
            console.log('save');
        }

        function loadSettings() {
            var i;
            for (i = 0; i < checkBoxFields.length; i++) {
                var field = checkBoxFields[i];
                /** @type {HTMLInputElement} */
                var checkbox = document.getElementById(field);
                var c = ls_get(field, checkbox.checked ? '1' : '0');
                checkbox.checked = c === '1' ? true : false;
            }

            var msURL = document.getElementById('msURL');
            var sURL = document.getElementById('sURL');
            msURL.value = ls_get('msURL', msURL.value);
            sURL.value = ls_get('sURL', sURL.value);
            if (ls_get('inputMode', 'StreamURL') === 'StreamURL') {
                switch_url();
            } else {
                switch_mds();
            }
        }

        function showVersion() {
            var version = flvjs.version;
            document.title = document.title + " (v" + version + ")";
        }

        var logcatbox = document.getElementsByName('logcatbox')[0];
        flvjs.LoggingControl.addLogListener(function(type, str) {
            logcatbox.value = logcatbox.value + str + '\n';
            logcatbox.scrollTop = logcatbox.scrollHeight;
        });

        document.addEventListener('DOMContentLoaded', function () {
            streamURL = document.getElementById('streamURL');
            mediaSourceURL = document.getElementById('mediaSourceURL');
            loadSettings();
            showVersion();
            flv_load();
        });
    </script>

</body>
</html>