<?php require_once ('html-auth.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Upload | Vocabulary</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
</head>

<body>

	<div class="container">

		<nav>
			<ul class="nav nav-pills pull-right">
			  <li class=""><a href="index.php">HOME</a></li>
			  <li class=""><a href="dictionary.php">DICTIONARY</a></li>
			  <li class=""><a href="quiz.php">QUIZ</a></li>
			  <li class=""><a href="summary.php">SUMMARY</a></li>
			  <li class="active"><a href="upload.php">UPLOAD</a></li>
			</ul>
			<h1 class="muted page-header">VOCA</h1>
		</nav>
		
		<!-- File Upload Form -->
		<div class="text-center">
			<form action="upload.php" method="post" enctype="multipart/form-data" >
        <input type="file" name="file" id="file" >
        <input type="submit" name="submit" value="Upload" class="btn">
	    </form>	        	
			
			<?php include 'functions.php'; ?>
			
			<?php if (isset($_POST['submit']) ) : ?>
				<div class="result-console alert">
        	<?php if ($_FILES["file"]["error"] > 0) : ?>
              	<p class="text-error">Error: <?php echo $_FILES["file"]["error"]; ?> - Choose the file.</p>
        	<?php else : ?>
        			<!-- 1. Convert file data into aray -->
          		<?php $wordlist = file_to_array($_FILES["file"]["tmp_name"]); ?>
          		<?php $cnt = count($wordlist); ?>
          		<p> <?php echo $_FILES["file"]["name"] .' has '.$cnt; ?> words.</p>
          		
          		<!-- 2. Filter: 중복된 파일은 삭제 -->
          		<?php $wordlist = filter_duplication($wordlist); ?>
          		<?php if (count($wordlist) == 0) die('<p class="text-error">> Nothing has been uploaded.</p>'); ?>

          		<!-- 파일내용을 array에 별도 저장한 것은 저장된 데이터 내용을 보여주기 위함 -->

          		<!-- 3. Save array to db -->
          		<?php $result = array_to_db($wordlist); ?>
          		<?php if ($result == true) : ?>
          			<p>Success to save data into the database!</p>
          			<p>You can use "Quize", "Dictionary".</p>
          		<?php else : ?>
          			<p class="text-error">> Fail to save data into the database.</p>
          		<?php endif; ?>
        		<?php endif; ?>
	      </div> <!-- /result-console -->
  		<?php endif; ?>

    </div>  <!-- /test-center -->
	</div> <!-- /container -->

</body>
</html>
