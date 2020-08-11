<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>nombitype &raquo; Result</title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>nombitype/views/styles/style-general.css" />

</head>
<body>
	<div class="container" >
		<div class="header" ><?php echo anchor(site_url(), 'nombi.<strong>type</strong>')?><br />
			<div class="tagline" >type these numbers as fast as you can</div>
		</div>
		<div class="result" >
			<strong>Your result</strong><br />
			Accuracy : <?php echo $accuracy; ?> &#37;<br />
			Right Answer : <?php echo $rightAnswer; ?> / <?php echo $nAnswered?><br />
			Elapsed Time : <?php echo $elapsedTime; echo ($elapsedTime > 1? ' seconds' : ' second')?><br />
			Speed : <?php echo $score; ?> keystroke/hour<br />
			
		</div>
		<div class="badge-image" >
			<img src="<?php echo site_url(). '/main/get_badge/' . $id .'/' .$code ?>" alt="My Score" />
		</div>
		<div class="clear" ></div>

		<div class="badge" >
			<textarea cols="30" rows="5"><?php echo anchor(site_url(),
				'<img src="'. site_url().'/main/get_badge/' . $id .'/' .$code . '" alt="'.$score.'" />')?></textarea>
		</div>
		<div class="footer" ><?php echo anchor('http://prabowomurti.com','Prabowo Murti')?></div>
	</div>
</body>
</html>
