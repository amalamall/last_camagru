var i = 0;
var ok = 0;
var element = document.getElementById("loaded_content");

function loadXMLDoc(i) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) { // XMLHttpRequest.DONE == 4
            if (xmlhttp.status == 200) {
                if (xmlhttp.responseText) {
                    element.innerHTML += xmlhttp.responseText;
                    i += 4;
                }
            } 
        }
    };

    xmlhttp.open("GET", "http://165.227.175.72/camagru/posts/getposts?offset=" + i + "&limit=4", true);
    xmlhttp.send();
}

document.addEventListener('scroll', function () {
    if (window.scrollY >= document.body.clientHeight - window.innerHeight) {
        if (ok == 0) {
            i += 4;
            loadXMLDoc(i);
        }

    }
}, false);
loadXMLDoc(i);