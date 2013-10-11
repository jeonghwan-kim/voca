<?php require_once ('html-auth.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"> 
	<title>Quiz | Voca</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<?php require_once ('functions.php'); ?>

	<div class="container">

		<!-- 네비게이션  -->
		<nav>
			<ul class="nav nav-pills pull-right">
			  <li class=""><a href="index.php">HOME</a></li>
			  <li class=""><a href="dictionary.php">DICTIONARY</a></li>
			  <li class="active"><a href="quiz.php">QUIZ</a></li>
			  <li class=""><a href="summary.php">SUMMARY</a></li>
			  <li class=""><a href="upload.php">UPLOAD</a></li>
			</ul>
			<h1 class="muted page-header">VOCA</h1>
		</nav>

		<!-- 문제 생성 옵션 부분  -->
		<?php $info = get_quiz_info(); ?>

		<div class="row-fluid">

			<div class="span4" id="select-book">
				<h3 class="muted">Select Book</h3>

				<ul class="nav nav-tabs nav-stacked">
				  	<?php foreach( $info as $book ) : ?>
				  		<li><a href="#select-date" id="<?php echo $book['slug']; ?>">
				  			<?php echo $book['book']; ?></a></li>
				  	<?php endforeach; ?>
				</ul>
			</div>

			<div class="span4" id="select-date">
				<h3 class="muted">Select Date</h3>
			  	
			  	<?php foreach( $info as $book ) : ?>
					<ul class="nav nav-tabs nav-stacked" id="date-of-<?php echo $book['slug'];?>">
					  	<?php foreach( $book['dates'] as $date => $v) : ?>
					  		<li><a href="#select-level" id="<?php echo $book['slug'].'-'.$date;?>" >
					  		<?php echo $date; ?></a></li>
					  	<?php endforeach; ?>
					</ul>
			  	<?php endforeach; ?>
			</div>

			<div class="span4" id="select-level">
				<h3 class="muted">Select Score</h3>
	
				<?php foreach( $info as $book ) : ?>
				  	<?php foreach( $book['dates'] as $date => $v) : ?>
						<ul class="nav nav-tabs nav-stacked" 
						id="date-of-<?php echo $book['slug'];?>-<?php echo $date;?>">
						  	<?php foreach( $book['dates'][$date] as $level => $v) : ?>
						  		<li><a href="#voca" id="<?php echo $book['slug'].'&'.$date.'&'.$level;?>" >
						  			<?php echo $v; ?></a></li>
						  	<?php endforeach; ?>
						</ul>
				  	<?php endforeach; ?>
			  	<?php endforeach; ?>
			</div>
			
		</div> <!-- 문제 생성 부분 끝  -->


		<!-- 퀴즈 부분  -->
		<div>
			<!-- 옵셥 -->
			<div id="quiz-option">
				<div id="quiz-voca-list"></div>
				<a class="btn btn-large btn-danger" id="start" href="#quiz-content">Start Quiz!</a>
			</div>

			<!-- 문제 -->
			<div id="quiz-content" class="text-center alert alert-info">
				<div class="progress">
					<div class="bar" id="bar"></div>
				</div>

				<div class="btn-group">
					<button id="remember" class="btn btn-large">Remembered!</button>
					<button id="hint" class="btn btn-large">Hint</button>
					<button id="pass" class="btn btn-large">Pass</button>
				</div>

				<div id="the-quiz"></div>
			</div>

			<!-- Modal -->
			<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel">End</h3>
			  </div>
			  <div class="modal-body">
			    <p>Go to quiz home.</p>
			  </div>
			  <div class="modal-footer">
			    <button id="go-to-quiz-home" class="btn btn-primary">OK</button>
			  </div>
			</div>
		</div>

		<footer class="alert alert-info text-center">
			<p><strong>총 <?php echo  get_total_num_voca(); ?> 개</strong> 보카가 저장되었습니다.
				(<?php echo date("Y-m-d");?> 기준)</p>
		</footer>

	</div> <!-- /container -->

	<script src="./js/jquery.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/quiz.js"></script>

</body>
</html>