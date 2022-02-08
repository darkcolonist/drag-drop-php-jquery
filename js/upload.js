$(document).ready(function(){
	$(".dropzone").dropzone({
	  url: 'upload.php',
	  width: 300,
	  height: 300, 
	  progressBarWidth: '100%',
	  maxFilesize: 5000,
		success: function(params){
			var response = JSON.parse(params.xhr.response);

			if(response.code === 200){
				var theUrl = response.url + response.file;
				theUrl = `<a href="${theUrl}" target="_blank">${theUrl}</a>`;

				$("#uploads ul").append(`<li>[${response.timestamp}] ${theUrl}</li>`);
			}else{
				var additional = "";
				if(response.message !== undefined)
					additional += `: ${response.message}`;

				$("#uploads ul").append(`<li>file wasn't uploaded${additional}</li>`);
			}
			
			$("#initialMessage").hide();
			// console.log(response);
		}
	})
});