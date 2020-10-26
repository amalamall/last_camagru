var width, height;

var picture = document.getElementById("pictureupload");
// initialisation des variable
width = picture.clientWidth;
height = picture.clientHeight;

const message = document.getElementById('message');
message.style.display = "none";




document.getElementById('savepicture').disabled = true;

var pictureupload = document.getElementById('pictureupload');
var ok = 0;



function savepic() {
	
	var checked = document.querySelector('input[name=filter]:checked');
	var filter = checked ? checked.value : "";
	if (ok == 1) {
		message.style.display = "none";
		if (filter != 'on' && filter != '') {
			// const myNode = document.getElementById("filter_canvas");
			// myNode.removeChild(myNode.firstChild);
			width = pictureupload.clientWidth;
			height = pictureupload.clientHeight;
			const canvas = document.createElement('canvas');
			canvas.id = 'canvas_f';
			canvas.width = width / 4.5;
			canvas.height = height / 3.2;
			var imgObj = new Image();
			imgObj.src = filter;
			var context = canvas.getContext('2d');
			//Draw the image onto the canvas.
			context.drawImage(imgObj, 0, 0, canvas.width, canvas.height);
			// document.getElementById('filter_canvas').appendChild(context.canvas);
			picture_upload = document.getElementById("pictureupload").src;
			const canvas_i = document.createElement('canvas');
			canvas_i.id = 'canvas_i';
			canvas_i.width = width;
			canvas_i.height = height;

			var img = new Image();
			img.src = picture_upload;

			var context_i = canvas_i.getContext('2d');
			context_i.drawImage(img, 0, 0, width, height);


			if (filter && ok == 1) {
				var formdata = new FormData()
				formdata.append("filterupload",canvas.toDataURL())
				formdata.append("picture", canvas_i.toDataURL())
				formdata.append("description",document.getElementById("description").value )
				formdata.append("titleupload", document.getElementById("titleupload").value)
				var req = new XMLHttpRequest()
				req.open("POST", "http://165.227.175.72/camagru/posts/saveimage")
				req.onload = function(e) {
					if(req.status == 200){
						document.getElementById('savepicture').disabled = false;

						location.reload()		
					}
				}
				req.send(formdata)
				
			}

		}
	}
	else {
		message.style.display = "block";
		var ele = document.getElementsByName("filter");
		for (var i = 0; i < ele.length; i++)
			ele[i].checked = false;
	}
	//add_filter_resized();
}

function add_filter() {
	var checked = document.querySelector('input[name=filter]:checked');
	var filter = checked ? checked.value : "";
	if (ok == 1) {
		message.style.display = "none";
		if (filter != 'on' && filter != '') {
			if (filter && ok == 1) {
				document.getElementById('savepicture').disabled = false;
			}

		}
	}
	else {
		message.style.display = "block";
		var ele = document.getElementsByName("filter");
		for (var i = 0; i < ele.length; i++)
			ele[i].checked = false;
	}

}

function triggerClick() {
	document.querySelector('#pictureselect').click();
}

//display the piscture selected
function displayImage(e) {
	if (e.files[0]) {
		if (fileExtValidate(e.files[0].name)) {
			if (fileSizeValidate(e.files[0])) {
				var reader = new FileReader();

				reader.onload = function (e) {
					document.querySelector('#pictureupload').setAttribute('src', e.target.result);
				}
				reader.readAsDataURL(e.files[0]);
				// picture validated
				ok = 1;
			}
		}
	}
}
// function for  validate file extension
var validExt = ".png, .jpeg,.jpg";
function fileExtValidate(fdata) {
	var filePath = fdata;
	var getFileExt = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
	var pos = validExt.indexOf(getFileExt);
	if (pos < 0) {
		message.style.display = "block";
		return false;
	} else {
		return true;
	}
}
//function for validate file size 
var maxSize = '10';
function fileSizeValidate(fdata) {
	if (fdata) {
		var fsizek = fdata.size / 1024;
		var fsizem = fsizek / 1024;
		if (fsizem >= maxSize) {
			message.style.display = "block";
			return false;
		}
		else if (fsizek < 1.2) {
			return false;
		}
		else {
			return true;
		}
	}
}

/*window.addEventListener('resize', function (event) {
	add_filter_resize();
});*/








