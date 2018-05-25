var wsUrl = "ws://127.0.0.1:8812";
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
    console.log("close-char.js")
};

//onerror
websocket.onerror = function (evt, e) {
    console.log("error:" + evt.data);
};

function push(data) {
    data = JSON.parse(data);
    html = '<div class="comment">';
    html += '<span>' + data.user + '</span>';
    html += '<span>' + data.content + '</span>';
    html += '</div>';

    $('#comments').append(html);
}