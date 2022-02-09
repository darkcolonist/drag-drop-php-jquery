<?php 
include('header.php');?>
<title>FILE UPLOAD DUMP</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="dropzone/dropzone.css" />
<script type="text/javascript" src="dropzone/dropzone.js"></script>
<script type="text/javascript" src="js/upload.js"></script>
<?php include('container.php');?>
<div class="container">
	<p>files older than <code class="bg-dark">432,000</code> seconds will be deleted</p>

	<iframe id="previewer" title="preview"
		style="border: none; width: 400px; height: 200px; position: absolute; display: none"
	></iframe>

	<div class="dropzone">
		<div class="dz-message needsclick">
			<span>Drop files here or click to upload.</span>
		</div>
	</div>	
	<div id="uploads">
		<p><button id="btnLoad" class="btn btn-primary">load earlier</button></p>
		<em id="initialMessage">uploaded file urls will be displayed here</em>
		<ul></ul>
	</div>	
</div>
<?php include('footer.php');?>