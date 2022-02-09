const helpers = {
	urlize: function(url){
		return `<a href="${url}" target="_blank">${url}</a>`;
	},
	timestampspan: function(timestamp){
		return `<span class="noselect">[${timestamp}]&nbsp;</span>`;
	}
};

let loadCounter = 1;

const populateInitial = (list) => {
	list.forEach(upload => {
		let theUrl = helpers.urlize(upload.url);
		$("#uploads ul").append(`<li>${helpers.timestampspan(upload.timestamp)}${theUrl}</li>`);
	});
}

const populateEarlier = (list) => {
	$("#uploads ul").prepend(`<li><hr /></li>`);

	list.forEach(upload => {
		let theUrl = helpers.urlize(upload.url);
		$("#uploads ul").prepend(`<li class="new">${helpers.timestampspan(upload.timestamp)}${theUrl}</li>`);
	});

	$("#uploads ul li.new").hide();
	$("#uploads ul li.new").fadeIn(1000);
	$("#uploads ul li.new").removeClass("new");
}

const shouldShowLoadBtn = (leCount, leLimit) => {
	if (leCount === leLimit) {
		if (!$("#btnLoad").is(":visible"))
			$("#btnLoad").fadeIn(3000);

		$("#btnLoad").attr("disabled",false);
	}else{
		$("#btnLoad").attr("disabled",true);
		$("#btnLoad").fadeOut(3000);
	}
}

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
				populateInitial(data.uploads);
				shouldShowLoadBtn(data.uploads.length, data.limit);
			}
		}
	});	

	$("#btnLoad").on("click", () => {
		$("#btnLoad").attr("disabled", "disabled");
		$.ajax(`./filelist.php?page=${loadCounter}`)
			.then(data => {
				populateEarlier(data.uploads);
				shouldShowLoadBtn(data.uploads.length, data.limit);
				loadCounter++;
			});

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
				$("#previewer").attr("src", "");
				$("#previewer").hide();
				// hide popup
				// console.log('hiding popup');
		}
	});
});