document.oncontextmenu = function () {
    return false;
}
/*document.onkeydown = function () {
        return false;
}*/

stopPrntScr();

document.addEventListener("keyup", function (e) {
    if ((e.keyCode === 44) || (e.key === 'PrintScreen') || (e.keyCode === 16)) {
        stopPrntScr();
    }else if(e.key === 'PrintScreen'){
        stopPrntScr();
    }else {
        return false;
    }
});

function stopPrntScr() {

    var inpFld = document.createElement("input");
    inpFld.setAttribute("value", ".");
    inpFld.setAttribute("width", "0");
    inpFld.style.height = "0px";
    inpFld.style.width = "0px";
    inpFld.style.border = "0px";
    document.body.appendChild(inpFld);
    inpFld.select();
    document.execCommand("copy");
    inpFld.remove(inpFld);
}

function AccessClipboardData() {
    try {
        window.clipboardData.setData('text', "Access   Restricted");
    } catch (err) {
    }
}

setInterval("AccessClipboardData()", 1);

function resizeIframe(obj) {
    obj.contentWindow.document.body.oncontextmenu = function () {
        return false;
    }
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 250 + 'px';

    obj.contentWindow.document.body.addEventListener("keydown", function (event) {
        if ((event.keyCode === 17) || (event.keyCode === 16) || (event.keyCode === 80) || (event.keyCode === 83) || (event.keyCode === 44)) {
            event.preventDefault();
            alert('You are not allowed');
        }
    });
}


