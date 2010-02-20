<?php


include("hr.confs.inc.php");


////
//// CLASS - BASE CURL
////
//
class CCurlBase
{
	protected $_ch;
	
	private $_sURL;

	public function __construct($sURL)
	{
		$this->_ch = curl_init();
		
		$this->_sURL = $sURL;
	}

	public function __destruct()
	{
		//closing the curl
		curl_close($this->_ch);
	}
	
	public function PrepareOptions()
	{
		curl_setopt($this->_ch, CURLOPT_URL, $this->_sURL);
	}

	public function Execute()
	{
		//getting response from server
		$sResponse = curl_exec($this->_ch);
		
		return $sResponse;
	}
}


////
//// CLASS - CURL POST-AUTH
////
//
class CCurlPostAuth extends CCurlBase
{
	private $_sUserName;
	private $_sPassword;
	
	private $_sPost;

	public function __construct($sURL, $sUser, $sPwd)
	{
		parent::__construct($sURL);
		
		$this->_sUserName = $sUser;
		$this->_sPassword = $sPwd;
		
		$this->_sPost = "";
	}

	public function PrepareOptions()
	{
		parent::PrepareOptions();
		
		//curl_setopt($this->_ch, CURLOPT_VERBOSE, true);
		
		curl_setopt($this->_ch, CURLOPT_USERPWD, $this->_sUserName.":".$this->_sPassword);
		curl_setopt($this->_ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, false);
	
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_ch, CURLOPT_POST, true);
	}
	
	public function SetPostString($sPost)
	{
		$this->_sPost = $sPost;
	}

	public function Execute()
	{
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $this->_sPost);

		return parent::Execute();
	}
}


////
//// CLASS - HIGHRISE CURL CONNECTION
////
//
class CHighriseCurlConnection extends CCurlPostAuth
{
	private $_sURLBase;
	private $_sURL;
	private $_sUserName;
	private $_sPassword;
	
	protected $_sURLPostfix;

	public function __construct()
	{
		$this->_sURLBase = HIGHRISE_ACCOUNT_URL;
		
		$this->_sURL = $this->_sURLBase.$this->_sURLPostfix;
		$this->_sUserName = HIGHRISE_USER_TOKEN;
		$this->_sPassword = "X";
	
		parent::__construct($this->_sURL, $this->_sUserName, $this->_sPassword);
	}
	
	public function PrepareOptions()
	{
		parent::PrepareOptions();
		
		curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
	}
}

?>