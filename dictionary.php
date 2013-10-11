<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>Vocabulary - Dictionary</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<?php require_once ('functions.php');  ?>

	<?php $keyword = ""; ?>
	<?php if ( isset($_GET['keyword']) && ($_GET['keyword'] != "") ) {
		$keyword = $_GET['keyword']; // 검색 키워드

		$ip = getRealIpAddr(); // 리모트 IP
		write_log($keyword, $ip); // 검색 기록 로깅

		$daum_dic_link = 'http://dic.daum.net/search.do?q=' . $keyword;
		$naver_dic_link = 'http://endic.naver.com/search.nhn?dicQuery=get&query=' . $keyword .'&target=dic&ie=utf8&query_utf=&isOnlyViewEE=N';
	}?>
    	
	<div class="container">

		<nav>
			<ul class="nav nav-pills pull-right">
			  <li class=""><a href="index.php">HOME</a></li>
			  <li class="active"><a href="dictionary.php">DICTIONARY</a></li>
			  <li class=""><a href="quiz.php">QUIZ</a></li>
			  <li class=""><a href="summary.php">SUMMARY</a></li>
			  <li class=""><a href="upload.php">UPLOAD</a></li>
			</ul>
			<h1 class="muted page-header">VOCA</h1>
		</nav>

		<!-- Search From -->
		<div class="text-center search-div search-div-grey">
	    <form class="input-append">
		    <input class="span8" id="keyword" type="text" name="keyword" value="<?php echo $keyword; ?>"/>
		    <button id="submit" class="btn">Search</button>
			</form>
    </div>

		<!-- 최근 저장 보카 출력  -->
		<?php if ($keyword == ""): ?>
			<?php $vocas = get_recent_voca( 15 ); ?>
			<section>
				<table class="table  table-hover">
					<thead><tr><th class="date">Date</th>
						<th class="book">Book</th>
						<th class="ref">Page</th>
						<th class="score">RLV</th>
						<th class="word">Word</th>
						<th class="meaning">Meaning</th>
						<th class="example">Example</th>
					</tr></thead>
					<?php foreach ($vocas as $v) : ?>
						<tr><td class="date"> 	<?php echo $v['date']; ?>	</td>
							<td class="book"> 	<?php echo $v['book']; ?>	</td>
							<td class="ref">  	<?php echo $v['ref']; ?>		</td>
							<td class="score">	<?php echo $v['score']; ?>	</td>
							<td class="word"><strong><?php echo $v['word']; ?></strong></td>
							<td class="meaning"><?php echo $v['meaning']; ?>	</td>
							<td class="example"><?php echo $v['example']; ?>	</td>
						</tr> 
					<?php endforeach; ?>	
				</table>
			</section>

		<!-- 검색결과 출력 -->
	    <?php elseif ($keyword != "") : ?>
				<?php $result_same = send_query("select * from wordlist where word = '$keyword'"); // 일치단어
				$result_like = send_query("select * from wordlist where word != '$keyword' and word like '%$keyword%'"); // 유사어
				if ($result_same->num_rows || $result_like->num_rows) :?>
		    	<!-- 1. 저장된 단어 출력 -->
			    	<section>
						<table class="table  table-hover">
							<thead><tr><th class="date">Date</th>
								<th class="book">Book</th>
								<th class="ref">Page</th>
								<th class="score">RLV</th>
								<th class="word">Word</th>
								<th class="meaning">Meaning</th>
								<th class="example">Example</th>
							</tr></thead>						
							<!-- 동일어 출력 -->
							<?php while ($row = mysqli_fetch_array($result_same)) : ?>
								<?php $word = create_html_of_word($row['word'], $keyword); ?>
								<?php $example = crate_html_of_example($row['example'], $keyword); ?>
									<tr><td class="date"> 	<?php echo $row['date']; ?>	</td>
										<td class="book"> 	<?php echo $row['book']; ?>		</td>
										<td class="ref">  	<?php echo $row['ref']; ?>		</td>
										<td class="score">	<?php echo $row['score']; ?>	</td>
										<td class="word"><?php echo $word;?></td>
										<td class="meaning"><?php echo $row['meaning']; ?>	</td>
										<td class="example"><?php echo $example; ?>	</td></tr> 
								<?php endwhile; ?>

							<!-- 유사어 출력 -->
							<?php while ($row = mysqli_fetch_array($result_like)) : ?>
								<?php $word = create_html_of_word($row['word'], $keyword); ?>
								<?php $example = crate_html_of_example($row['example'], $keyword); ?>
									<tr><td class="date"> 	<?php echo $row['date']; ?>	</td>
										<td class="book"> 	<?php echo $row['book']; ?>		</td>
										<td class="ref">  	<?php echo $row['ref']; ?>		</td>
										<td class="score">	<?php echo $row['score']; ?>	</td>
										<td class="word"> 	<?php echo $word; ?>		</td>
										<td class="meaning"><?php echo $row['meaning']; ?>	</td>
										<td class="example"><?php echo $example; ?>	</td></tr>  
								<?php endwhile; ?>
						</table>		 
					</section>
				<?php endif; ?>

				<!-- 2. 사전 및 google image 출력  -->
				<div class="row-fluid">
					<div class="span4">
						<h3 class="muted page-header">DAUM DIC</h3>
						<iframe src="<?php echo $daum_dic_link; ?>" scrolling="no"></iframe>		    	
					</div>
					<div class="span4">
						<h3 class="muted page-header">NAVER DIC</h3>
						<iframe src="<?php echo $naver_dic_link; ?>" scrolling="no"></iframe>
					</div>
					<div class="span4">
						<h3 class="muted page-header">GOOGLE IMAGE</h3>
						<div class="" id="google-img"></div>
						<!-- <input type="hidden" id="search-key" value="<?php echo $keyword; ?>" /> -->
					</div>
				</div>
			<?php endif; ?>

			<footer class="alert alert-info text-center">
				<p><strong>총 <?php echo  get_total_num_voca(); ?> 개</strong> 보카가 저장되었습니다.
					(<?php echo date("Y-m-d");?> 기준)</p>
			</footer>

		</div>

	</div> <!-- /container -->

	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="http://www.google.com/jsapi?key=AIzaSyA5m1Nc8ws2BbmPRwKu5gFradvD_hgq6G0" type="text/javascript"></script>
    <script type="text/javascript">
		var keyword = "";

		$(document).ready(function() {
		  $("#keyword").focus();
		  keyword = $("#keyword").val();
		});

		$(window).load(function() {
		  $("#keyword").focus();
		});

		$("body").keypress(function() {
		  $("#keyword").focus();
		});

		google.load('search', '1');

		function OnLoad() {
		  var searchControl = new google.search.SearchControl();
		  var imageSearch = new google.search.ImageSearch();

		  searchControl.addSearcher(imageSearch);
		  searchControl.draw(document.getElementById("google-img"));
		  searchControl.execute( keyword );
		}
		google.setOnLoadCallback(OnLoad);
  </script>

</body>
</html>