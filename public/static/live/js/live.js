var wsUrl = "ws://127.0.0.1:8811";
var websocket = new WebSocket(wsUrl);

//实例对象的 onopen 属性
websocket.onopen = function (evt) {
    console.log("connected-swoole-success");
};

//实例化 onmessage
websocket.onmessage = function (evt) {
    push(evt.data);
    console.log("ws-server-return-date:" + evt.data);
};

//实例化 onclose
websocket.onclose = function (evt) {
    console.log("close-live.js")
};

//onerror
websocket.onerror = function (evt, e) {
    console.log("error:" + evt.data);
};

function push(data) {
    data = JSON.parse(data);
    html = '<div class="frame">';
    html += '<h3 class="frame-header">';
    html += '<i class="icon iconfont icon-shijian"></i>第' + data.type + '节 02:30';
    html += '</h3>';
    html += '<div class="frame-item">';
    html += '<span class="frame-dot"></span>';
    html += '<div class="frame-item-author">';
    if(data.logo) {
        html += '<img src="' +data.logo + '" width="20px" height="20px" />';
    }
    html += data.title;
    html += '</div>';
    html += '<p>' + data.content + '</p>';
    // 还可以将 image 的结构补充到这里，但是之前 image 没有上传成功因此不做演示 todo
    html += '</div>';
    html += '</div>';

    $('#match-result').prepend(html);
}