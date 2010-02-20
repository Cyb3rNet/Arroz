<?php

include("hr.connection.inc.php");

////
//// CLASS - HIGHRISE CREATE COMPANY
////
//
class CHighriseCreateCompany extends CHighriseCurlConnection
{
	public function __construct()
	{
		$this->_sURLPostfix = "/companies.xml";
		
		parent::__construct();
	}
}


////
//// CLASS - HIGHRISE CREATE PERSON
////
//
class CHighriseCreatePerson extends CHighriseCurlConnection
{
	public function __construct()
	{
		$this->_sURLPostfix = "/people.xml";
		
		parent::__construct();
	}
}

////
//// CLASS - HIGHRISE CREATE NOTE
////
//
class CHighriseCreateNote extends CHighriseCurlConnection
{
	public function __construct($iPersonId)
	{
		$this->_sURLPostfix = "/people/".$iPersonId."/notes.xml";
		
		parent::__construct();
	}
}

////
//// CLASS - HIGHRISE CREATE NOTE
////
//
class CHighriseCreateTask extends CHighriseCurlConnection
{
	public function __construct()
	{
		$this->_sURLPostfix = "/tasks.xml";
		
		parent::__construct();
	}
}

?>