<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>VOCA</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="style.css" rel="stylesheet">
</head>

<body>
	<?php require_once ('functions.php');  ?>

	<div class="container">		
		<nav>
			<ul class="nav nav-pills pull-right">
			  <li class="active"><a href="index.php">HOME</a></li>
			  <li class=""><a href="dictionary.php">DICTIONARY</a></li>
			  <li class=""><a href="quiz.php">QUIZ</a></li>
			  <li class=""><a href="summary.php">SUMMARY</a></li>
			  <li class=""><a href="upload.php">UPLOAD</a></li>
			</ul>
			<h1 class="muted page-header">VOCA</h1>
		</nav>

		<!-- Search From -->
		<div class="text-center search-div">
	    <form class="input-append" action="dictionary.php">
		    <input class="span8" id="keyword" type="text" name="keyword" />
		    <button id="submit" class="btn">Search</button>
			</form>
	    </div>

		<div class="row-fluid">
	    	<div class="span6">
	    		<p class="label">최근 추가한 VOCA</p>
	    		<?php $vocas = get_recent_voca( 9 ); ?>
	    		
	    		<table class="table table-condensed table-hover">
	    			<thead><tr><th>단어</th><th>의미</th><th>추가일</th></tr></thead>
	    		<?php foreach ($vocas as $v) : ?>
	    			<tr><td><?php echo $v['word']; ?></td>
	    			    <td><?php echo $v['meaning']; ?></td>
	    				<td><?php echo $v['date']; ?></td></tr>
	    		<?php endforeach; ?>
		    	</table>
	    	</div>

	    	<div class="span6">
	    		<p class="label">최근 검색 VOCA</p>
	    		<?php $vocas = get_recent_search_voca( 9 ); ?>
	    		
	    		<table class="table table-condensed table-hover">
	    			<thead><tr><th>단어</th><th>조회일</th></tr></thead>
	    		<?php foreach ($vocas as $v) : ?>
	    			<tr><td><?php echo $v['word']; ?></td>
	    			    <td><?php echo $v['date']; ?></td></tr>
	    		<?php endforeach; ?>
		    	</table>
	    	</div>
	    </div>

		<div class="row-fluid">
	    	<div class="span6">
	    		<p class="label">중복 저장한 VOCA</p>
	    		<?php $vocas = get_several_saved_voca( 9 ); ?>
	    		
	    		<table class="table table-condensed table-hover">
	    			<thead><tr><th>단어</th><th>저장 횟수</th></tr></thead>
	    		<?php foreach ($vocas as $v) : ?>
	    			<tr><td><?php echo $v['word']; ?></td>
	    			    <td><?php echo $v['cnt']; ?></td></tr>
	    		<?php endforeach; ?>
		    	</table>
	    	</div>

	    	<div class="span6">
	    		<p class="label">자주 찾은 VOCA</p>
	    		<?php $vocas = get_freq_voca( 9 ); ?>
	    		
	    		<table class="table table-condensed table-hover">
	    			<thead><tr><th>단어</th><th>검색 횟수</th></tr></thead>
	    		<?php foreach ($vocas as $v) : ?>
	    			<tr><td><?php echo $v['word']; ?></td>
	    			    <td><?php echo $v['cnt']; ?></td></tr>
	    		<?php endforeach; ?>
		    	</table>
	    	</div>
	    </div>

	    <footer class="alert alert-info text-center">
			<p><strong>총 <?php echo  get_total_num_voca(); ?> 개</strong> 보카가 저장되었습니다.
				(<?php echo date("Y-m-d");?> 기준)</p>
		</footer>

	</div> <!-- /container -->

</body>
</html>


