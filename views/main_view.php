<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>nombitype: How Fast Do You Type Numbers?</title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>nombitype/views/styles/style-general.css" />
<script type="text/javascript" src="<?php echo base_url()?>nombitype/views/scripts/jquery-1.4.4.min.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>nombitype/views/scripts/jquery.timers-1.2.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>nombitype/views/scripts/script-general.js" language="javascript"></script>

</head>
<body><div class="popup"></div>
	<div class="container" >
		<div class="header" ><?php echo anchor(site_url(), 'nombi.<strong>type</strong>')?><br />
			<div class="tagline" >type these numbers as fast as you can</div>
		</div>

		
		<div id="popup-background"></div>
		<div class="help" >click start, type fast</div>
		<div class="preferences" >Sorry, this feature <strike>will never be released</strike> is under development</div>
		<div class="about" >Prabowo Murti thanks to<br />CI 2.0 and<br />jQuery 1.4.4</div>

		<div class="menu" >
			<a href="#" id="help">Help</a> |
			<a href="#" id="preferences">Preferences</a> |
			<a href="#" id="about">About</a>
		</div>
		<div class="clear" ></div>
		<div class="numbers">
			<!--<form action="main/process" method="POST" autocomplete="off" > -->
			<?php echo form_open('main/result', array('autocomplete'=> 'off')); ?>
				<div class="questions" >
					<input type="text" id="question2" class="right" value="" readonly="readonly" /><br />
					<input type="text" id="question1" class="right" value="" readonly="readonly" />
				</div>
				<div class="clear" ></div>
				<input type="text" id="answer" class="right" />
				<input type="hidden" name="answers" id="answers" value="" />
				<input type="hidden" name="timeLeft" id="timeLeft" value="" />
			
			<?php echo form_close()?>
			<div class="clear" ></div>
		</div>
		<div align="center">
			<a href="#" class="start-button" >Start</a>
		</div>
		<div class="loading" >
			loading numbers...
		</div>
		<div id="timer" ><span></span> seconds left</div>
		<div class="footer" ><?php echo anchor('http://prabowomurti.com','Prabowo Murti')?></div>
	</div>
</body>
</html>
