$(document).ready(function () {
	//this line sucks; it will produce something like http://localhost/apps/
	var baseUrl = window.location.href.substr(0, window.location.href.indexOf('nombitype.php'));

	var timeLeft;

	//need better solution to store configuration data from php to javascript
	$.get(baseUrl+'nombitype.php/main/get_timelimit', function(data) {
		timeLeft = data;
		$('#timer span').text(timeLeft);
	})

	var questions = new Array();
	var numOfNumbers = 0;//we will assign it to questions.length later

	var index = 0;//index of questions/answers

	//handle popup menu
	$('.popup').hide();
	$('.loading').hide();
	$('#popup-background').hide();
	$('.help').hide();
	$('.preferences').hide();
	$('.about').hide();
	$('#timer').hide();
	
	var popupShown = false;

	$('#help').click(function () {
		$('.popup').html($('.help').html());
		loadPopup();
	})

	$('#preferences').click(function () {
		$('.popup').html($('.preferences').html());
		loadPopup();
	})

	$('#about').click(function () {
		loadPopup();
		$('.popup').html($('.about').html());
	})


	function loadPopup() {
		if (!popupShown) {
			$('#popup-background').fadeIn();
			$('.popup').fadeIn();
			popupShown = true;
		}
	}

	function unloadPopup() {
		if (popupShown){
			$('#popup-background').fadeOut();
			$('.popup').fadeOut();
			popupShown = false;
		}
	}

	$('#popup-background').click(function () {
		unloadPopup();
	})
	
	//unload popup when Esc key pressed
	$(document).keyup(function (e){
		if (e.keyCode == 27 && popupShown){
			unloadPopup();
		}
	})

	//start button clicked
	$('.numbers').hide();
	$('.start-button').click(function () {
		$('.start-button').hide();		
		$('.loading').css({
			'position': 'absolute',
			'top': '100px',
			'left': (($(window).width() - $('.loading').outerWidth()) /2)
		});

		$('.loading').show();

		$.ajax({
			type: 'GET',
			url: baseUrl + 'nombitype.php/main/get_numbers',
			dataType: 'json',
			success: function (json) {
				$.each (json.numbers, function (i, number) {
					questions[i] = number;
				})

				numOfNumbers = questions.length;

				$('.loading').hide();
				$('.numbers').show();
				timerOn();
				rotateNumbers();
			},
			error: function (xhr, status, errorThrown) {
				$('.loading').hide();
				alert ('Oops, an error occured: ' + xhr + "|"+ status+ "|" + errorThrown);
			},
			
			timeout: 5000 // 5 seconds
		})//end of .ajax()
	})

	function rotateNumbers()
	{
		$('#answer').focus().val('');
		$('#question2').val(questions[index+1]);
		$('#question1').val(questions[index]);
		index ++;

		if (index > numOfNumbers) {
			timerOff();
		}
		
	}

	//doesn't allow user to Ctrl+X/C/V
	$(function () {
		$('#answer').keydown(function (event) {
			var forbiddenKeys = ',x,c,v,';
			var keyCode = (event.keyCode) ? event.keyCode : event.which;
			var isCtrl;
			isCtrl = event.ctrlKey;
			if (isCtrl) {
				if (forbiddenKeys.indexOf(',' + String.fromCharCode(keyCode).toLowerCase() + ',') > -1) {
					return false;
				}
			}
			return true;
		})
	})

	//don't allow user to right-click
	$('#answer').get(0).oncontextmenu = function (){ return false};
	$('#question1').get(0).oncontextmenu = function (){ return false};
	$('#question2').get(0).oncontextmenu = function (){ return false};
	
	//disable text selection on question1
	$('#question1').attr('unselectable', 'on')
			.css({'-moz-user-select': 'none',
				  '-webkit-user-select':'none'})
			.each(function () {
				this.onselectstart = function () {return false}
			});

	//allow numbers only
	$('#answer').bind('keypress', function (event){
		var keyCode = event.keyCode ? event.keyCode : event.which;
		var nums = ',0,1,2,3,4,5,6,7,8,9,';
		if (nums.indexOf(',' + String.fromCharCode(keyCode) + ',') > -1){
			return true;
		}else {
			return false;
		}
	})

	//enter key pressed
	$('#answer').keypress(function (event) {
		var keyCode = event.keyCode ? event.keyCode : event.which;
		if (keyCode == 13) {
			var a = $('#answer').val() ? $('#answer').val() : '0';
			$('#answers').val($('#answers').val() + ':' + a);
			rotateNumbers();
		}
	})

	function timerOn() {
		$('#timer').show();
		$(document).everyTime (1000, 'timeFunction', function (i) {
			$('#timer span').text(--timeLeft);
			if (timeLeft == 1) {
				$('#timer').html('<span>1</span> second left');
			}else if (timeLeft <= 0){
				timerOff();
			}
		})
		
	}//end timerOn()

	function timerOff() {
		$(document).stopTime('timeFunction');
		$('#timeLeft').val(timeLeft >0 ? timeLeft : 0);
		submitForm();
	}

	function submitForm () {
		if ($('#answers').val() == '') {
			alert('Please don\'t go or fall asleep');
			window.location = "http://localhost/apps/nombitype.php";
		}else {
			$('form').submit();
		}
	}

})