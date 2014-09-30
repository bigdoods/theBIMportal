<?php
/**
 * The ticketing app 
 */
 
 class Back_to extends Bim_Appmodule{
 	
	/**
	 * The constructor
	 */
	function __construct(){
		parent :: start();
	}
	
	function init(){
		$this->outputStart();
		unsetActiveProject();		
		redirect($this->_root);
		$this->outputEnd();
	}
	
	function viewAllTicket(){
	
	}
	
	
 }
?>