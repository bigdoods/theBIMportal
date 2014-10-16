<?php

	function bimsync_project_viewer_url($model=null){
		$CI =  &get_instance();

		// fetch project information for the current project
		$project = $CI->Projects->getAllProject(getActiveProject());

		$post_body = array();
		if(! empty($model))
			$post_body[] = $model;

		$auth_url = sprintf(
			'%s/viewer/access?project_id=%s',
			$CI->config->item('bimsync_api_url_prefix'),
			$project[0]['bimsync_id']
		);

		// responses are always in json so request and decode
		$response = json_decode(post_to($auth_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $CI->config->item('bimsync_api_token')),
			CURLOPT_POSTFIELDS => (count($post_body) ==0 ? '' : json_encode($post_body))
		)));

		return $response->url;
	}

	function bimsync_projects(){
		$CI =  &get_instance();

		$api_url = sprintf(
			'%s/projects',
			$CI->config->item('bimsync_api_url_prefix')
		);

		// responses are always in json so request and decode
		$response = json_decode(get_from($api_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $CI->config->item('bimsync_api_token'))
		)));

		return (array) $response;
	}

	function bimsync_project_product($object_id, $format='json'){
		$CI =  &get_instance();

		// fetch project information for the current project
		$project = $CI->Projects->getAllProject(getActiveProject());

		$api_url = sprintf(
			'%s/project/product?project_id=%s&object_id=%s',
			$CI->config->item('bimsync_api_url_prefix'),
			$project[0]['bimsync_id'],
			$object_id
		);

		// responses are always in json so request and decode
		$response = post_to($api_url, array(
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '. $CI->config->item('bimsync_api_token'),
				'Accept: text/'.$format
			),
			CURLOPT_POSTFIELDS => ''
		));

		if($format == 'json')
			$response = json_decode($response);

		return $response;
	}

	function bimsync_model_structure($model_id){
		$CI =  &get_instance();

		$response_array = array();
		$response_json = null;
		$response = null;
		$per_page=1000;
		$page=1;

		while( (is_null($response_json) || $response_json != '[]') && (is_null($response) || count($response) < $per_page)){
			$api_url = sprintf(
				'%s/revision/structure?model_id=%s&page=%d&per_page=%d',
				$CI->config->item('bimsync_api_url_prefix'),
				$model_id,
				$page,
				$per_page
			);

			// responses are always in json so request and decode
			$response_json = get_from($api_url, array(
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer '. $CI->config->item('bimsync_api_token')
				)
			));

			$response = json_decode($response_json);
			foreach($response as $element){
				$response_array[] = $element;
			}

			$page++;
		}
		

		return $response_array;
	}

	function bimsync_project_models(){
		$CI =  &get_instance();

		// fetch project information for the current project
		$project = $CI->Projects->getAllProject(getActiveProject());

		$api_url = sprintf(
			'%s/models?project_id=%s',
			$CI->config->item('bimsync_api_url_prefix'),
			$project[0]['bimsync_id']
		);

		// responses are always in json so request and decode
		$response = json_decode(get_from($api_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $CI->config->item('bimsync_api_token'))
		)));

		return (array) $response;
	}

	function bimsync_model_revisions($model_id=null){
		$CI =  &get_instance();

		// if we don't have a model yet, return an empty array
		if(is_null($model_id))
			return array();

		$api_url = sprintf(
			'%s/revisions?model_id=%s',
			$CI->config->item('bimsync_api_url_prefix'),
			$model_id
		);

		// responses are always in json so request and decode
		$response = json_decode(get_from($api_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $CI->config->item('bimsync_api_token'))
		)));

		return (array) $response;
	}

	function bimsync_revision_products($model_id, $revision_id){
		$CI =  &get_instance();

		// if we don't have a model yet, return an empty array
		if(is_null($model_id))
			return array();

		$response_array = array();
		$response_json = null;
		$response = null;
		$per_page=1000;
		$page=1;

		while( (is_null($response_json) || $response_json != '[]') && (is_null($response) || count($response) < $per_page)){

			$api_url = sprintf(
				'%s/revision/products?model_id=%s&revision_id=%d&page=%d&per_page=%d',
				$CI->config->item('bimsync_api_url_prefix'),
				$model_id,
				$revision_id,
				$page,
				$per_page
			);

			// responses are always in json so request and decode
			$response_json = post_to($api_url, array(
				CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $CI->config->item('bimsync_api_token')),
				CURLOPT_POSTFIELDS => ''
			));

			$response = json_decode($response_json);
			foreach($response as $element){
				$response_array[] = $element;
			}

			$page++;
		}
		

		return $response_array;
	}



?>