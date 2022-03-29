const helpers = {
	urlize: function(url){
		return `<a href="${url}" target="_blank">${url}</a>`;
	},
	timestampspan: function(timestamp){
		return `<span class="noselect">[${timestamp}]&nbsp;</span>`;
	}
};

let loadCounter = 1;
let ignorePreview = [];
let includePreview = [];
let uploadexpires = 0;

const populateLineContentString = (upload) => {
	let theUrl = helpers.urlize(upload.url);
	let timestampSpan = helpers.timestampspan(upload.timestamp);
	let remaining = '';
	if (uploadexpires !== 0){
		let days = (upload.expires / 60 / 60 / 24).toLocaleString(undefined, { maximumFractionDigits: 1 });
		let hours = (upload.expires / 60 / 60).toLocaleString(undefined, { maximumFractionDigits: 1 });
		let choiceUnit = "hrs";
		let choiceExpiry = hours;
		if(days > 0){
			choiceUnit = "days";
			choiceExpiry = days;	
		}
		remaining = `&nbsp;(${choiceExpiry} ${choiceUnit})`;
	}
	
	return `${timestampSpan}${theUrl}${remaining}`;
}

const populateInitial = (list) => {
	list.forEach(upload => {
		$("#uploads ul").append(`<li>${populateLineContentString(upload)}</li>`);
	});
}

const populateEarlier = (list) => {
	$("#uploads ul").prepend(`<li><hr /></li>`);

	list.forEach(upload => {
		$("#uploads ul").prepend(`<li class="new">${populateLineContentString(upload)}</li>`);
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

const showPreviewIframe = (event, target) => {
	$("#previewer").attr("src", target.href);
	$("#previewer").css({
		top: event.pageY - 200,
		left: event.pageX + 40
	});

	$("#previewer").show();
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

	function loadFileList() {
		$.ajax("./filelist.php", {
			method: "get",
			type: "json",
			success: function (data) {
				if (data.code === 200) {
					populateInitial(data.uploads);
					shouldShowLoadBtn(data.uploads.length, data.limit);
				}
			}
		});
	}

	function loadConfigs(){
		$.ajax("./ignore.php", {
			method: "get",
			type: "json",
			success: function (data) {
				if (data.code === 200) {
					$readme = $("p#readme");

					ignorePreview = data.ignorepreview;
					includePreview = data.includepreview;

					if (data.uploadexpires !== 0) {
						uploadexpires = data.uploadexpires;
						$readme.children("code").html(data.uploadexpires.toLocaleString());
						$readme.show(1000);
					}
				}
			},
			complete: function () {
				loadFileList();
			}
		});
	}

	// start!
	loadConfigs();

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
				let ext = target.href.split(".").pop().toLowerCase();
				// console.log(ext, includePreview, ignorePreview);
				if (includePreview.includes(ext) && !ignorePreview.includes(ext)){
					showPreviewIframe(event, target);
				}
				break;
			default:
				$("#previewer").attr("src", "");
				$("#previewer").hide();
				// hide popup
				// console.log('hiding popup');
		}
	});

	$("#btnJumpUp").on("click",() => {
		window.scrollTo(0,0);
	});
	$("#btnJumpDown").on("click",() => {
		window.scrollTo(0,document.body.scrollHeight);
	});
});