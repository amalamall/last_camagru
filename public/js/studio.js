
const snap = document.getElementById('snap');
const video = document.getElementById('video');
var width = 1400,
	height = 1000,
	streaming = false;

/// get stream
if (navigator.mediaDevices === undefined) {
	navigator.mediaDevices = {};
}
if (navigator.mediaDevices.getUserMedia === undefined) {
	navigator.mediaDevices.getUserMedia = function (constraints) {
		var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
		if (!getUserMedia) {
			return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
		}
		return new Promise(function (resolve, reject) {
			getUserMedia.call(navigator, constraints, resolve, reject);
		});
	}
}
navigator.mediaDevices.getUserMedia({ video: true, audio: false })
	.then(function (stream) {
		if ("srcObject" in video)
			video.srcObject = stream;
		else {
			video.src = window.URL.createObjectURL(stream);
		}
		video.onloadedmetadata = function (e) {
			video.play();
		};
	})
	.catch(function (err) {
		console.log(`ERROR:  ${err}`);
	});
video.addEventListener('canplay', function (e) {
	if (!streaming) {
		video.setAttribute('width', width);
		video.setAttribute('height', height);
		streaming = true;
	}
}, false);


snap.disabled = true;

const message = document.getElementById('message');
message.style.display = "none";
 function savepic() {
	 document.getElementById('snap').disabled = true;
	var filter;
	if (document.querySelector('.checkbox:checked') !== null)
		filter = document.querySelector('input[name=filter]:checked').value;
	var imgObj = new Image();
	imgObj.src = filter;
	const canvas = document.createElement('canvas');
	const canvas_f = document.createElement('canvas');
	const picturevalue = document.getElementById('picturevalue');
	//give a size compatible with the width of video webcam 
	width = video.clientWidth;
	height = video.clientHeight;
	canvas_f.width = width / 4.5;
	canvas_f.height = height / 3.2;
	canvas.width = width;
	canvas.height = height;
	var context = canvas.getContext('2d');
	var context_f = canvas_f.getContext('2d');
	// draw the filter
	context_f.drawImage(imgObj, 0, 0, canvas_f.width, canvas_f.height);
	// begin draw all the picture with filter
	context.drawImage(video, 0, 0, width, height);
	// stock the value database 64 for the image before drawing filter
	var img = canvas.toDataURL();
	context.drawImage(imgObj, 0, 0, canvas_f.width, canvas_f.height);


	// picture and filter
	var valueimage = canvas.toDataURL();
	// filter
	var valuefilter = canvas_f.toDataURL();

	if (fileSizeValidate(img) && fileSizeValidate(valuefilter)) {
		
		var formdata = new FormData()
		formdata.append("filter",valuefilter)
		formdata.append("picture", img)
		console.log(document.getElementById("description").value);
		formdata.append("description",document.getElementById("description").value )
		formdata.append("title", document.getElementById("title").value)
		var req = new XMLHttpRequest()
		req.open("POST", "http://165.227.175.72/camagru/posts/saveimage")
		req.onload = function(e) {
			if(req.status == 200){
				document.getElementById('snap').disabled = false;

				location.reload()
			}
		}
		req.send(formdata)
	}
}




function add_filter() {
	//message.style.display = "none";
	// get the value of filter if checked
	var checked = document.querySelector('input[name=filter]:checked');
	// verify the value of checked filter is empty or not
	var filter = checked ? checked.value : "";
	//alert(video.height)
	// check the camera if is on or not
	if (video.height > 0 && video.width > 0) {
		// on protect from deleting the attribut value 
		if (filter != 'on' && filter != '') {
			// active the button snap
			document.getElementById('snap').disabled = false;
		}
	}
	else {
		// display the message if the camera is not working
		message.style.display = "block";
		// deselect the filter
		var ele = document.getElementsByName("filter");
		for (var i = 0; i < ele.length; i++)
			ele[i].checked = false;
	}

}


//function for validate file size 

function fileSizeValidate(fdata) {
	if (fdata) {
		var stringLength = fdata.length - 'data:image/png;base64,'.length;
		if (fdata.endsWith("==")) padding = 2;
		else if (fdata.endsWith("=")) padding = 1;
		else padding = 0;
		var sizeInBytes = (stringLength / 4) * 3 - padding;
		var sizeInKb = sizeInBytes / 1000;
		if (sizeInKb <= 1.2)
			return 0;
		else
			return 1;
	}
}









