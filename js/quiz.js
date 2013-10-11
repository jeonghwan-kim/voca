var vocas = "";
var pos = 0;
var total_nums = "";

/**
 * 문제 옵션 설정
 */
$(window).load(function() {
	var book = "";
	var date = "";
	var score = "";
	var voca_list = "";

	$("#select-book a").click(function() {
		$("#select-book a").removeClass("selected");
		$(this).addClass("selected");
		book = $(this).text();
		id = $(this).attr("id");

		$("#select-date").show();
		$("#select-date ul").hide();
		$("#date-of-"+id).show();
		$("#select-level").hide();

		$.get("ajax/get-voca.php?book="+book, function(data, status) {
			$("#quiz-voca-list").empty();
			$("#quiz-option").show();
			vacas = data;
			show_voca(data);
		});
	});

	$("#select-date a").click(function() {
		$("#select-date a").removeClass("selected");
		$(this).addClass("selected");
		date = $(this).text();
		var id = $(this).attr('id');

		$("#select-level").show();
		$("#select-level ul").hide();
		$("#date-of-"+id).show();

		$.get("ajax/get-voca.php?book="+book+"&date="+date, function(data, status) {
			$("#quiz-voca-list").empty();
			vocas = data;
			show_voca(data);
		});		
	});

	$("#select-level a").click(function() {
		$("#select-level a").removeClass("selected");
		$(this).addClass("selected");
		score = $(this).text();

		console.log(book);
		console.log(date);
		console.log(score);
		
		$.get("ajax/get-voca.php?book="+book+"&date="+date+"&score="+score, 
			function(data, status) {
			$("#quiz-voca-list").empty();
			vocas = data;
			show_voca(data);
		});
	});

	$("#start").click(function() {	
		start();
	});

	$("#remember").click(function() {
		update_voca(vocas[pos].id, (Number)(vocas[pos].score)+1);

		vocas.splice(pos, 1);

		show_progress_bar();

		if (is_end()) {
			console.log("end");
			$('#myModal').modal();
		}
		else {
			pos = pos % vocas.length;
			show_voca_in_quiz();
		}
	});

	$("#hint").click(function() {
		$("#hint-div").show();
	});

	$("#pass").click(function() {
		pos = (pos + 1) % vocas.length;
		show_progress_bar();
		show_voca_in_quiz();
	});

	$("#go-to-quiz-home").click(function() {
		$(location).attr('href', 'quiz.php');
	});

});

function show_voca(data) {
	var html = '<table class="table">';
	html += '<thead><tr>';
	html += '<th class="word">VOCA</th>';
	html += '<th>MEANING</th>';
	html += '<th>EXAMPLE</th>';
	html += '<th class="score">SCORE</th></tr></thead>';

	for (key in data) {
		html += '<tr>';
		html += '<td class="word">' + data[key].word + '</td>';
		html += '<td>' + data[key].meaning + '</td>';
		html += '<td>' + data[key].example + '</td>';
		html += '<td class="score">' + data[key].score + '</td>';
		html += '</tr>';
	}

	html += '</table>';

	$("#quiz-voca-list").append(html);
}


/**
 * 퀴즈 부분
 */

function start() {
	$("#quiz-option").hide();
	$("#quiz-content").show();
	total_nums = vocas.length;
	show_voca_in_quiz();
}

function show_progress_bar() {
	$("#bar").css("width", ((total_nums-vocas.length)/total_nums*100)+'%');
	$("#bar").text( (total_nums-vocas.length)+'/'+total_nums );
}

function show_voca_in_quiz() {
	var html = '<h1>' + vocas[pos].word + '</h1>';
	html += '<div id="hint-div"><p>' + vocas[pos].meaning + '</p>';
	html += '<p>' + vocas[pos].example + '</p></div>';

	$("#the-quiz").empty();
	$("#the-quiz").append(html);
}

function is_end() {
	return !vocas.length;
}

function update_voca(id, score) {
	console.log(id);
	console.log(score);
	$.post("ajax/update-score.php", {id: id, score: score}, null);
}
