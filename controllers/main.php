<?php

class Main extends CI_Controller {

	private $limit = 30;//how many numbers sent
	private $timelimit = 60;//1 minute
	const TABLE_BADGES = "badges";

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url_helper', 'form_helper'));
		$this->load->database();
		$this->load->library('session');
	}
	
	function index()
	{
		$this->session->sess_destroy();
		$this->load->view('main_view');
	}

	/**
	 * Random numbers, save to sessions, and send with json
	 */
	function get_numbers()
	{
		
		$numbers = array();

		//we give 6 digits random numbers
		for ($i=1; $i<=$this->limit; $i++)
		{
			array_push($numbers, rand(100000, 999999));
		}

		$this->session->set_userdata(array('questions' => $numbers));

		echo json_encode(array('numbers'=>$numbers));
		//echo json_encode(array('numbers'=>array(1,2,3,4)));

	}

	/**
	 * Show result of test
	 */
	function result()
	{
		/**
		$data = array('accuracy'=>92,
			'rightAnswer'=>13,
			'nAnswered'=>15,
			'elapsedTime'=>60,
			'score'=>7200,
			'id'=>1);

		$this->load->view('result_view', $data);
		*/
		
		$answers = explode(':', substr($this->input->post('answers'), 1));
		$questions = $this->session->userdata('questions');
		$timeLeft = intval($this->input->post('timeLeft'));
		$rightAnswer = 0;

		if ($questions === "" OR
			(count($answers) <= 1 && empty($answers[0])) OR
			$timeLeft >= $this->timelimit)//how could this be?
		{
			$this->session->sess_destroy();
			redirect(site_url());
		}
		
		foreach ( $answers as $key=>$answer)
		{
			if ($questions[$key] == intval($answer))
			{
				$rightAnswer ++;
			}
		}

		$nAnswered = count($answers);
		$accuracy = intval( $rightAnswer/$nAnswered * 100 );

		$data['accuracy'] = $accuracy;
		$data['rightAnswer'] = $rightAnswer;
		$data['nAnswered'] = $nAnswered;
		$data['elapsedTime'] = $this->timelimit - $timeLeft;
		
		//this calculate keystroke per hour
		$data['score'] = (int) ($rightAnswer * 6 * 60 * 60/ ($this->timelimit - $timeLeft));

		//this will create random string (as a 'code' for particular badge ID)
		$chars = "0123456789abcdefghijklmnopqrstuvwxyz";
		$data['code'] = substr( str_shuffle($chars), 0, 5);
		$this->db->insert(self::TABLE_BADGES, array('code'=>$data['code'], 'score'=>$data['score']));
		$data['id'] = $this->db->insert_id();
		
		$this->session->sess_destroy();

		$this->load->view('result_view', $data);
	}

	/**
	 * Return timelimit value (used in js code)
	 */
	function get_timelimit ()
	{
		echo $this->timelimit;
	}

	/**
	 * Return image / badge contains user's score
	 * @param <integer> $id score id
	 */
	function get_badge ()
	{
		$id = $this->uri->segment(3);
		$code = $this->uri->segment(4);
		if (strlen($code) < 5){ die(); };
		
		$this->db->where('id', intval($id));
		$this->db->where('code', $code);
		$query = $this->db->get(self::TABLE_BADGES);
		
		if ( $query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$score = $row->score;
			}
		}
		else
		{
			die();
		}
		
		$image = imagecreate(200, 100);

		//define some colors
		$color_ccc	= imagecolorallocate($image, 0xcc, 0xcc, 0xcc);//ccc
		$black	= imagecolorallocate($image, 0, 0, 0);//black
		$white	= imagecolorallocate($image, 0xFF, 0xFf, 0xFF);//white

		//fill square with white, and then create "nifty rounded corners" with arc
		imagefill($image, 0, 0, $white);
		imagefilledarc($image, 100, 50, 236, 170, 0, 360, $color_ccc, IMG_ARC_PIE);
		
		$this->ImageStringCenter($image, 2, 15, "I got", $black);
		$this->ImageStringCenter($image, 5, 32, $score, $black);
		$this->ImageStringCenter($image, 2, 45, "keystroke/hour", $black);
		$this->ImageStringCenter($image, 1, 85, "What's yours?", $black);

		header("Content-type: image/png");
		imagepng ($image);
		imagecolordeallocate($image, $color_ccc);
		imagecolordeallocate($image, $white);
		imagecolordeallocate($image, $black);
		imagedestroy($image);
		
	}

	private function ImageStringCenter($image, $font, $y, $str, $col, $ImageString = 'ImageString')
	{
		//http://www.puremango.co.uk/2009/04/php-imagestringright-center-italic/

		$font_width = ImageFontWidth($font);
		$str_width = strlen($str)*$font_width;
		if(!function_exists($ImageString) || $ImageString==__FUNCTION__)
		{
			// don't allow recursion
			$ImageString = 'ImageString';
		}
		$ImageString($image, $font, intval((ImageSX($image)-$str_width)/2), $y, $str, $col);
	}

	function stat990 () 
	{
		echo $this->db->count_all(self::TABLE_BADGES);
	}

}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */
