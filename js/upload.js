const helpers = {
	urlize: function(url){
		return `<a href="${url}" target="_blank">${url}</a>`;
	},
	timestampspan: function(timestamp){
		return `<span class="noselect">[${timestamp}]&nbsp;</span>`;
	}
};

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
				theUrl = helpers.urlize(theUrl);

				$("#uploads ul").append(`<li>${helpers.timestampspan(response.timestamp)}${theUrl}</li>`);
			}else{
				var additional = "";
				if(response.message !== undefined)
					additional += `: ${response.message}`;

				$("#uploads ul").append(`<li>file wasn't uploaded${additional}</li>`);
			}
			
			$("#initialMessage").hide();
			// console.log(response);
		}
	});

	$.ajax("./filelist.php", {
		method: "get",
		type: "json",
		success: function(data){
			if(data.code === 200){
				data.uploads.forEach(upload => {
					let theUrl = helpers.urlize(upload.url);
					$("#uploads ul").append(`<li>${helpers.timestampspan(upload.timestamp)}${theUrl}</li>`);
				});
			}
		}
	});	

	$("#uploads").on("mouseover mouseout", "li a", (event) => {
		let target = event.currentTarget;

		switch(event.type){
			case "mouseover":
				// show popup
				// console.log('showing popup', event.pageX, event.pageY);
				$("#previewer").attr("src", target.href);
				$("#previewer").css({
					top: event.pageY - 200,
					left: event.pageX + 40
				});

				$("#previewer").show();
				break;
			default:
				$("#previewer").hide();
				// hide popup
				// console.log('hiding popup');
		}
	});
});