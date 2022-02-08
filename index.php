<?php 
include('header.php');?>
<title>coderszine.com : Demo Drag And Drop File Upload with jQuery and PHP</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="dropzone/dropzone.css" />
<script type="text/javascript" src="dropzone/dropzone.js"></script>
<script type="text/javascript" src="js/upload.js"></script>
<?php include('container.php');?>
<div class="container">
	<div class="dropzone">
		<div class="dz-message needsclick">
			<span>Drop files here or click to upload.</span>
		</div>
	</div>		
</div>
<?php include('footer.php');?>