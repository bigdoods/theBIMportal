<?php

	/* net functions
		post_to and get_from are basic curl wrappers to set up for
		two http request methods, mainly used for bimsync api interaction
	*/
	function post_to($url, $options=array(), &$ch=null){
		if(is_null($ch))
			$ch = curl_init();

		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,

/*			CURLOPT_STDERR         => fopen('/var/log/dev_console.log', 'a+'),
			CURLOPT_VERBOSE        => true*/
		);

		// merge passed in curl params with defaults
		curl_setopt_array($ch, real_array_merge_recursive(
			$defaults,
			$options
		));

		$response = curl_exec($ch);

		return $response;
	}
	function get_from($url, $options=array(), &$ch=null){
		if(is_null($ch))
			$ch = curl_init();

		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => false,
			CURLOPT_RETURNTRANSFER => true,

/*			CURLOPT_STDERR         => fopen('/var/log/dev_console.log', 'a+'),
			CURLOPT_VERBOSE        => true
*/		);

		// merge passed in curl params with defaults
		curl_setopt_array($ch, real_array_merge_recursive(
			$defaults,
			$options
		));

		$response = curl_exec($ch);

		return $response;
	}

?>