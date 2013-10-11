<?php

require_once ('settings.php');


/**
 * DB 연결 함수 
 */
function connectDB() {
	global $db_host;
	global $user;
	global $password;
	global $database;
	
	$dbc = mysqli_connect($db_host, $user, $password, $database)
	  	   or die('Error connecting to MySQL server.');
	  	 
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	mysqli_query($dbc, "set names utf8"); // DB 한글 설정

	return $dbc;
}	  

/**
 * 쿼리 전송
 */
function send_query($query) {
	$dbc = connectDB();
	$result = mysqli_query($dbc, $query) or error_log("error in send_query() in functions.php");
	mysqli_close( $dbc );
	return $result;
}

/**
 * 최근 추가 단어 호출 함수 
 *     입력: n (호출 갯수)
 */
function get_recent_voca( $num ) {
	$q = 'SELECT * FROM wordlist ORDER BY date DESC LIMIT 0, ' . $num;
	return send_query( $q );
}

/**
 * 자주 찾은 단어 호출 함수 
 *     입력: n (호출 갯수)
 */
function get_freq_voca( $num ) {
	$q = 'SELECT keyword as word, count(*) AS cnt FROM log GROUP BY keyword ORDER BY cnt DESC LIMIT 0 , '. $num;
	return send_query( $q );
}


/**
 * 자주 저장한 단어 호출 함수 
 *     입력: n (호출 갯수)
 */
function get_several_saved_voca( $num ) {
	$q = 'SELECT word, count(*) AS cnt FROM wordlist GROUP BY word ORDER BY cnt DESC LIMIT 0 , ' . $num;
	return send_query( $q );
}

/**
 * 최근 검색한 단어 호출 함수 
 *     입력: n (호출 갯수)
 */
function get_recent_search_voca( $num ) {
	$q = 'SELECT keyword as word, DATE(date) as date FROM log ORDER BY date DESC LIMIT 0 , ' . $num;
	return send_query( $q );
}

/**
 * 보카 총 저장 갯수 계산 
 *     입력: n (호출 갯수)
 */
function get_total_num_voca() {
	$q = 'SELECT count(*) FROM wordlist';
	$r = send_query( $q );	
	return number_format( mysqli_fetch_array($r)[0] );
}


/**
 * 검색 결과 하일라이트 (단어)
 */
function create_html_of_word($word, $keyword) {
	return str_replace($keyword, '<span class="label label-important">'.$keyword.'</span>', $word);
}

/**
 * 검색 결과 하일라이트 (예문)
 */
function crate_html_of_example($example, $keyword) {
	if ( !(strpos($example, "http") === FALSE) ) { // 이미지 URL 이 있는 경우
		$example = '<img class="img-rounded" src="' .  $example . '" >';
	} 
	else { // url이 아닌 example만 있을 경우
		$example = str_replace($keyword, '<span class="label label-important">'.$keyword.'</span>', $example);	
	}

	return $example;
}

/**
 * 검색 기록 로깅 
 */
function write_log($keyword, $ip) {
	$query = "insert into log (date, keyword, ip) values (NOW(), '$keyword', '$ip')";
	$result = send_query($query);
}

/**
 * 클라이언트 ip주소 얻기
 */
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


/**
 * 도서 리스트 반환 
 */
function get_books() {
	$q = "SELECT book FROM wordlist GROUP BY book ORDER BY book ASC ";
	$r = send_query( $q );

	$books = array();
	while ( $row = mysqli_fetch_array( $r ) ) {
		array_push($books, $row[0]);
	}

	return $books;
}


/**
 *  날짜 리스트 반환 
 */
function get_dates( $book ) {
	$q = "SELECT date FROM wordlist WHERE book LIKE '$book' GROUP BY date ORDER BY date DESC";
	$r = send_query( $q );

	$dates = array();
	while ( $row = mysqli_fetch_array( $r ) ) {
		array_push($dates, $row[0]);
	}

	return $dates;
}

/**
 *  레벨 리스트 반환 
 */
function get_levels( $book, $date ) {
	$q = "SELECT score FROM wordlist WHERE book LIKE '$book' AND date LIKE '$date' GROUP BY score ORDER BY score ASC";
	$r = send_query( $q );

	$levels = array();
	while ( $row = mysqli_fetch_array( $r ) ) {
		array_push($levels, $row[0]);
	}

	return $levels;
}

/**
 *  퀴즈정보 출력
 */
function get_quiz_info1() {
	$r = array();
	$books = get_books();

	foreach ($books as $book) {
		$dates = get_dates($book);
		 foreach ($dates as $date) {
		 	$levels = get_levels($book, $date);
		 	$r[$book][$date] = $levels;
		 }
	}

	// 도서명 슬러그 추가 (공백제거: javascrip id 값으로 활용)
	foreach ($r as $book => $value) {
		$slug = str_replace(' ', '', $book);
		$r[$book]['slug'] = $slug;
	}
	return $r;
}


function get_quiz_info() {
	$r = array();
	$books = get_books();

	$i = 0;
	foreach ($books as $book) {
		$r[$i]['book'] = $book;
		$r[$i]['slug'] = str_replace(' ', '', $book);

		$dates = get_dates($book);

		foreach ($dates as $date) {
			$levels = get_levels($book, $date);
			$r[$i]['dates'][$date] = $levels;
		}

		$i++;
	}

	return $r;
}




/**
 * pre version 
 */


// function create_query($date, $book, $ref, $score) {
function create_query($date, $book, $score) {
	$query = "select * from wordlist ";

	if ($date  || $book  || $ref  || $score != "" ) {
		// 최소 1개 이상의 파라메터가 있을 경우 where 붙이기
		$query .= " where ";

		if ($date) {
			$query .= " date = '$date'";
		}

		if ($book) {
			if (strstr($query, "date")) $query .= " and book = '$book'";
			else 												$query .= "     book = '$book'";
		}

		// if ($ref) {
		// 	if (strstr($query, "date") 
		// 	 || strstr($query, "book")) $query .= " and ref = '$ref'";
		// 	else 												$query .= "     ref = '$ref'";
		// }

		if ($score != "") {
			if (strstr($query, "date") ||
			    strstr($query, "book") ||
			    strstr($query, "ref"))  $query .= " and score = '$score'";
			else 												$query .= "     score = '$score'";
		}

	} 

	return $query;
}



// wordList 테이블 조회 
function get_wordlist() {
	$result = send_query("select * from wordlist");

	// 조회한 데이터 -> 배열($data)에 저장
	$data = array();
	while ($row = mysqli_fetch_array($result)) {
		$temp = array(
			'date' => $row['date'],
			'book' => $row['book'],
			'ref' => $row['ref'],
			'score' => $row['score'],
			'word' => $row['word'],
			'meaning' => $row['meaning'],
			'example' => $row['example']
			);
		array_push($data, $temp);
	} 
	return $data;
}



/* Upload 관련 함수
============================================ */

// 파일 내용 -> 배열에 저장
function file_to_array($filename) {
	$file = fopen("$filename", "r") or exit("fopen is failed."); // file open
	$row = fgets($file); // 첫행은 컬럼명이므로 제외

	$wordlist = array();
	while (!feof($file) ) {
		if ($row = fgets($file) ) { 
			$row = str_replace("\n", '', $row); // Remove 'return carage'
			$row = explode("\t", $row); // parsing (pivot: 'tab')
			$temp = array(
				'date' 		=> $row[0],
				'book' 		=> $row[1],
				'ref' 		=> $row[2],
				'score' 	=> $row[3],
				'word' 		=> trim_word($row[4]), // 발음기호 제거
				'meaning' 	=> $row[5],
				'example' 	=> $row[6]
				);
			array_push($wordlist, $temp);
		}
	}

	return $wordlist;
}

// array -> db 저장
function array_to_db($arry) {
	foreach ($arry as $row) {
		// DB 저장을 위한 형식으로 변경
		$date 	= str_replace(" ", "", $row['date']); // 모든 공백 제거
		$book 	= trim_quotation($row['book']);
		$ref 	= trim_quotation($row['ref']);
		$score 	= intval($row['score']); // integer value로 변경
		$word 	= trim_quotation($row['word']);
		$meaning = trim_quotation($row['meaning']); 
		$example = trim_quotation($row['example']);

		// 쿼리 실행
		$query 	= "insert into wordlist (date, book, ref, score, word, meaning, example)" .
				 "values ('$date', '$book', '$ref', $score, '$word', '$meaning', '$example'); ";
		send_query($query);
	}
	return true;
}

// $word의 발음기호([kɔ̀(ː)riάgrəfi]) 제거
function trim_word($word) {
	if( $pos = strpos($word, '[') ) {
		$word = trim(substr($word, 0, $pos));
	}
	return $word;
}

// $word의 ' -> \' 로 변경 (db 저장때문)
function trim_quotation($text) {
	return str_replace("'", "\'", $text);
}

// 중복 데이터 제거한 뒤 배열로 반환
function filter_duplication($list) {
	$duplicated_word = 0;
	$new_word = 0;
	$new_list = array();

	foreach ($list as $key => $value) {
		$book = str_replace("'", "\'", $value['book']);
		$ref = str_replace("'", "\'", $value['ref']);
		$word = str_replace("'", "\'", $value['word']);

		$query = "select * from wordlist where book='$book' and ref='$ref' and word='$word'";
		$result = send_query($query);

		if ($result->num_rows > 0) {
			$duplicated_word++;
		}
		else {
			$new_word++;
			array_push($new_list, $value);
		}
	}
	
	if ($duplicated_word > 0) {
		echo "<p class='text-error'>> $duplicated_word words are aleray in the db. </p>" ;
	}
	if ($new_word > 0) {
		echo "<p>> $new_word words are new word. </p>" ;
	}

	return $new_list;
}

?>