<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, orientation=landscape">

    <title>__TITLENAME__</title>

    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            overscroll-behavior-y: contain; /* 縦方向のオーバースクロールを防ぐ */
            touch-action: none; /* タッチアクションを無効化 */
            user-select: none; /* テキスト選択を無効にする */
            -webkit-user-select: none; /* テキスト選択を無効にする（Safari対応） */
            -ms-user-select: none; /* テキスト選択を無効にする（IE対応） */
            -moz-user-select: none; /* テキスト選択を無効にする（旧Firefox対応） */
            touch-action: none; /* タッチアクションを無効にする */


        }
        #canvas {
            border: 1px solid #000;
            cursor: crosshair;
            width: 90vw; /* 横幅の90%に設定 */
            height: 30vh; /* 縦幅の30%に設定 */
            max-width: 1000px; /* 最大幅を設定 */
            max-height: 300px; /* 最大高さを設定 */
        }
        .controls {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }
    button {
        padding: 8px 14px; /* ボタンのサイズを小さめに調整 */
        font-size: 14px; /* フォントサイズを少し小さめに */
        border: 2px solid #007bff; /* スタイリッシュな青色の枠線 */
        background-color: #b7eafb; /* 背景色を白に設定 */
        color: #0d0d0e; /* 文字色を青に設定 */
        border-radius: 5px; /* 角を丸くしてモダンなデザインに */
        cursor: pointer;
        transition: all 0.3s ease; /* スムーズなエフェクト */
        box-shadow: 0px 4px 6px rgba(0, 123, 255, 0.3); /* 軽い影を追加 */
    }

    button:hover {
        background-color: #007bff; /* ホバー時の背景色を青に */
        color: white; /* ホバー時の文字色を白に */
        box-shadow: 0px 6px 8px rgba(0, 123, 255, 0.5); /* ホバー時に影を強調 */
    }

    button:active {
        background-color: #0056b3; /* クリック時にさらに濃い青に */
        box-shadow: 0px 2px 4px rgba(0, 86, 179, 0.5); /* クリック時に影を少し縮小 */
    }

    #rotateMessage{
        display:none;
    }
    @media screen and (orientation: landscape) {
        /* 横向きのスタイル */
        body {
            background-color: #f0f0f0;
        }
        #canvas {
            width: 90vw;
            height: 40vh;
        }
    }
    .room_info{
        display:block;
        text-align:center;
        font-size:16px;
        margin-bottom:10px
    }

    @media screen and (orientation: portrait) {
        /* 縦向きのスタイルや警告メッセージ */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffcccc;
        }
        #canvas {
            display: none; /* 縦向きではサインキャンバスを非表示 */
        }
        #rotateMessage {
            display: block;
            font-size: 0.9rem;
            color: #333;
            padding:10px;
            line-height:1.4;
            border:solid 1px #cccccc;
            background-color:#fff;
            width:75%;
            text-align:center;
            position: absolute;
            top: 35vw;
            left: 50%;
            transform: translateX(-50%);
        }
    }



        
    </style>
</head>
<body>
    <div class="room_info">__wBuildingName__  __wID__号室</div>
    <canvas id="canvas"></canvas>
    <div class="controls">
         <button id="save">保存する</button>
        <button id="clear">クリアする</button>
        <button id="back" onclick="back()">もどる</button>
    </div>
    <p id="status"></p>
    <div id="rotateMessage">スマートフォンを横向きにして、サインを受領してください。</div>






</body>
    <script>
        // スクロールを無効にする
        document.body.addEventListener('touchmove', function(event) {
            event.preventDefault();
        }, { passive: false });
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // 右クリックメニューを無効化
        });

        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        function resizeCanvas() {
            canvas.width = canvas.clientWidth;
            canvas.height = canvas.clientHeight;
        }

        function startDrawing(e) {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.touches ? e.touches[0].clientX - canvas.offsetLeft : e.clientX - canvas.offsetLeft, 
                       e.touches ? e.touches[0].clientY - canvas.offsetTop : e.clientY - canvas.offsetTop);
        }

        function draw(e) {
            if (!drawing) return;
            ctx.lineTo(e.touches ? e.touches[0].clientX - canvas.offsetLeft : e.clientX - canvas.offsetLeft, 
                       e.touches ? e.touches[0].clientY - canvas.offsetTop : e.clientY - canvas.offsetTop);
            ctx.stroke();
        }

        function stopDrawing() {
            drawing = false;
            ctx.closePath();
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveCanvas() {
            if(isCanvasEmpty()){
                alert("サインを受領できていません");
            }else{
                const dataURL = canvas.toDataURL('image/png');
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 's_sign_regist.php?editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wID=__wID__&wUserCD=__wUserCD__&rKey=__rKey__&m=__m__', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('status').textContent = 'サインが保存されました';
                            // back();
                            setTimeout(back, 1000);
                    }
                };
                xhr.send('imgData=' + encodeURIComponent(dataURL));
            }
        }

        function isCanvasEmpty() {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            return canvas.toDataURL() === blank.toDataURL();
        }        

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);
        canvas.addEventListener('touchcancel', stopDrawing);

        document.getElementById('clear').addEventListener('click', clearCanvas);
        document.getElementById('save').addEventListener('click', saveCanvas);

        function back() {
            const editBukkenCD = '__editBukkenCD__';
            const editBuildingCD = '__editBuildingCD__';
            const rKey = '__rKey__';
            const m = '__m__';
            window.location.href = `s_kanryo.php?editBukkenCD=${editBukkenCD}&editBuildingCD=${editBuildingCD}&rKey=${rKey}&m=${m}`;
        }




    </script>

</html>
