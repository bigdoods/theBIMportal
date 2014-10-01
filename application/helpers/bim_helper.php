<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a helper function
 * Which make the debuging controll in just one place
 */
function v_dump($var,$end = 0,$option = 1){
	echo '<pre>';
		if($option){
			var_dump($var);
		}else{
			print_r($var);
		}
	echo '</pre>';
	if($end)
		exit(__FILE__ . __LINE__);
}

function debug(){
	foreach((array) func_get_args() as $arg){
		error_log('('. gettype($arg) .') '. print_r($arg, true));
	}
}

function real_array_merge_recursive($arr1, $arr2){
  foreach($arr2 as $key => $value){
    if(array_key_exists($key, $arr1) && is_array($value))
      $arr1[$key] = real_array_merge_recursive($arr1[$key], $arr2[$key]);
    else
      $arr1[$key] = $value;
  }

  return $arr1;
}

/**
 * Get radom passowrd
 */
function getRandomPass(){
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789~!@#$%^&*()_-=+";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

/**
 * Send email freom one place
 */
 
function sendMail($to, $subject, $message){
	$CI =& get_instance();
    $config =$CI->config->item('email_config');
      $CI->load->library('email', $config);
      $CI->email->set_newline("\r\n");
      $CI->email->from('pritam@aqualeafitsol.com'); // change it to yours
      $CI->email->to( $to );// change it to yours
      $CI->email->subject( $subject );
      $CI->email->message($message);
	  try{
		  if($CI->email->send()){
				return true;
		  }else{
				return false;
		  }
	}catch(Exception $e){
		return false;
	}

}

/**
 * Load app class in the context
 */

function load_app(){
	global $app_id,$app ;
	$CI = get_instance();
	if( !isset( $app_id ) ||   !$app_id ){
		$app_id = $CI->config->item( 'default_app_id' );
	}
	$app_details = $CI->Apps->getAllApps(1, $app_id);
	if($app_details){
	/* Include the app file */
	require_once(APPPATH .'app/'.$app_details[$app_id]['appfilepath']);
	if( class_exists($app_details[$app_id]['classname']) ){
		$class_name = $app_details[$app_id]['classname'];
		$app = new $class_name;		
	}else{
		$app = new Bim_Appmodule;	// load the parent class
	}
	}	
}

/**
 * This is a test function 
 * To test the helper is accessible from 
 */	
 function hTest(){
	 echo "I AM ACCESSIBLE HELPER" .__LINE__;
 }
 
 /**
 * This function wil be called with the global appid 
 */
 
 
function load_app_content(){
	global $app;
	$CI = get_instance();	
	$function = $CI->input->get('f');
	if($function){
		$app->$function();
	}else{
		$app->init();
	}
}
/**
 * This function specifically load app classes
 */
 //spl_autoload_register('my_autoload');
function __autoload( $clas_name = ''){	
	if(strpos( $clas_name, "CI_") !== 0){
		$CI = &get_instance();
		$CI->load->model('Apps');
		$data = $CI->Apps->getAppByClassname( $clas_name );
		if( $data ){
			require_once( APPPATH.'app/'.$data['appfilepath']);
		}
	}
}

/**
 *Get active project 
 * If the user is normal user
 * Then retrive the actp cookie
 * else retive the admin class private static property 
 * active project
 */
 
function getActiveProject(){
	
	switch( getCurrentUserRole() ){
		case 1:
			return (int) Admin::getActiveProject();
			break;
		case 2:
			return (int) isset($_COOKIE['actp']) ? $_COOKIE['actp'] : -1;
			break;
	}
}
 
 /**
  * Get current user role
  */
function getCurrentUserRole(){
	  return (int)@$_SESSION['userdata']['role'];
}

function getCurrentuserId(){
	return (int)@$_SESSION['userdata']['id'];
}

/**
 * This function checks if the user
 * User is admin or not
 */
function isCurrentUserAdmin(){
	if(getCurrentUserRole() === 1)
		return true;
	return false;
}

/**
 * Unset active project
 */
function unsetActiveProject(){
	/**
	 * When ever an user back to project dashboard
	 * The unset the active projects
	 */
	 setcookie('actp' , 0, time()-2592000, '/');
}
/* EOF */

/**
 * Get app type
 */
 
 function getAppType(){
 	return array('Core Apps', 'Project Data Apps','BIM Apps', 'BIMscript Technology Apps','Others');
 }
 
 /**
  * Get current user project context  
  * return the array of projectid for the user
  * This funciton is prepared to putinto where clause of sql with in for checking the project context
  * @return array(),
  * defaut array(1-)
  */
  function getCurrentProjectContext(){
  	if(getCurrentUserRole() == 1){// t he user is admin
		if(Admin::getActiveProject()){// the project context is set by code for a page load
			return array(Admin::getActiveProject());
		}else{
			global $app;
			if($app){
				return $app->_project_id_arr;
			}else{
				return array();
			}
		}
	}else if(getCurrentUserRole() == 2){
			global $app;
			if($app){
				return $app->getProjectContext();
			}else if(getActiveProject() !== -1){
				return array(getActiveProject());
			}else{
				return array();
			}
	
	}
  }
  
  /**
   * Get the option for decisipline
   */
  function getDicisiplineOption($option = 'html', $selected=''){
		$array = array('enginner', 'planner', 'designer', 'architect', 'other') ;
		if($option !== 'html')	return $array;
		
		$html = '';
		
		foreach($array as $option){
			$html .='<option value="'.$option.'" '.($selected == $option ? 'selected="selected"' : '').'>'.ucfirst($option).'</option>';
		}
		
		return $html;
	
  }