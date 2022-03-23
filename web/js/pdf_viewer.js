document.oncontextmenu = function () {
    return false;
}

document.addEventListener("keyup", function (e) {
    if ((e.keyCode === 44) || (e.key === 'PrintScreen') || (e.keyCode === 16)) {
        stopPrntScr();
    } else {
        return false;
    }
});
function stopPrint(e){
    window.clipboardData.setData('text', '');
}
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

setInterval('AccessClipboardData()', 1);

function AccessClipboardData() {
    try {
        window.clipboardData.setData('text', "Access   Restricted");
    } catch (err) {
    }
}

function resizeIframe(obj) {
    obj.contentWindow.document.body.oncontextmenu = function () {
        return false;
    }
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 250 + 'px';

    obj.contentWindow.document.body.addEventListener("keydown", function (event) {
        if (event.keyCode == 123) {
            event.preventDefault();
            alert('You are not allowed');
        }
        //event.ctrlKey = check ctrl key is press or not
        //event.which = check for F7
        // event.which =check for v key
        if (event.ctrlKey==true && (event.which == '118' || event.which == '86')) {
            event.preventDefault();
            alert('You are not allowed');
        }
        // Prevent Ctrl+a = disable select all
        // Prevent Ctrl+u = disable view page source
        // Prevent Ctrl+s = disable save
        else if (event.ctrlKey && (event.keyCode === 85 || event.keyCode === 83 || event.keyCode ===65 )) {
            event.preventDefault();
            alert('You are not allowed');
        }
        // Prevent Ctrl+Shift+I = disabled debugger console using keys open
        else if (event.ctrlKey && event.shiftKey && event.keyCode === 73)
        {
            event.preventDefault();
            alert('You are not allowed');
        }
        else if((event.keyCode === 17) || (event.keyCode === 16) || (event.keyCode === 80) || (event.keyCode === 83) || (event.keyCode === 44)) {
            event.preventDefault();
            alert('You are not allowed');
        }
    });
}

