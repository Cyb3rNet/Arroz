<?php


////
//// CLASS - XML OBJECT
////
//
class CXMLObject
{
	private $_sStartNode;
	private $_sEndNode;

	private $_sContent;

	private $_aAttrs;
	
	public function __construct($sTagName)
	{
		$this->_sTagName = $sTagName;
	
		$this->_sStartNode = "";
		$this->_sEndNode = "";
		
		$this->_sContent = "";
		
		$this->_aAttrs = array();
	}
	
	public function AddAttr($sName, $sValue)
	{
		$this->_aAttrs[$sName] = $sValue;
	}
	
	public function AppendContent($sContent)
	{
		$this->_sContent .= $sContent;
	}
	
	public function ReplaceContent($sContent)
	{
		$this->_sContent = $sContent;
	}
	
	private function _ComposeStartNode()
	{
		$sAttrs = "";
	
		foreach ($this->_aAttrs as $k => $v)
		{
			$sAttrs .= $k.'="'.$v.'" ';
		}
	
		return "<".$this->_sTagName." ".$sAttrs.">";
	}
	
	public function __toString()
	{
		$this->_sStartNode = $this->_ComposeStartNode();
		$this->_sEndNode = "</".$this->_sTagName.">";
	
		return $this->_sStartNode.$this->_sContent.$this->_sEndNode;
	}
}


////
//// CLASS - HIGHRISE XML COMPANY
////
//
/*
<company>
  <id type="integer">1</id>
  <name>Doe Inc.</name>
  <background>A popular company for random data</background>
  <created-at type="datetime">2007-02-27T03:11:52Z</created-at>
  <updated-at type="datetime">2007-03-10T15:11:52Z</updated-at>
  <visible-to>#{Everyone || Owner || NamedGroup}</visible-to>
  <owner-id type="integer">#{ user_id -- when visble-to is "Owner"}</owner-id>
  <group-id type="integer">#{ group_id -- when visble-to is "NamedGroup"}</group-id>
  <author-id type="integer">3</author-id>
  <contact-data>
    ...
  </contact-data>
</company>
*/
class CHighriseXMLCompany extends CXMLObject
{
	private $_oXMLNodeName;
	private $_oXMLNodeBackground;
	private $_oXMLNodeVisibleTo;

	const sVisibilityAll = 'Everyone';
	const sVisibilityOwn = 'Owner';
	
	public function __construct()
	{
		parent::__construct("company");
		
		$this->_oXMLNodeName = new CXMLObject("name");
		$this->_oXMLNodeBackground = new CXMLObject("background");
		$this->_oXMLNodeVisibleTo = new CXMLObject("visible-to");
		
		// Default visibility : Everyone
		$this->_oXMLNodeVisibleTo->ReplaceContent(self::sVisibilityAll);
	}
	
	public function SetCompanyName($sCompanyName)
	{
		$this->_oXMLNodeName->ReplaceContent($sCompanyName);
	}
	
	public function SetBackground($sBackground)
	{
		$this->_oXMLNodeBackground->ReplaceContent($sBackground);
	}
	
	public function SetVisibility($sVisibility)
	{
		switch ($sVisibility)
		{
			case self::sVisibilityAll:
				$this->_oXMLNodeVisibleTo->ReplaceContent(self::sVisibilityAll);
			break;
			
			case self::sVisibilityOwn:
				$this->_oXMLNodeVisibleTo->ReplaceContent(self::sVisibilityOwn);
			break;
			
			default: // Named group
				$this->_oXMLNodeVisibleTo->ReplaceContent($sVisibility);
		}
	}
	
	public function SetXMLContactData($oXMLContactData)
	{
		if ($oXMLContactData instanceof CHighriseXMLContactData)
		{
			parent::AppendContent($oXMLContactData);
		}
	}
	
	public function __toString()
	{
		parent::AppendContent($this->_oXMLNodeName);
		parent::AppendContent($this->_oXMLNodeBackground);
		parent::AppendContent($this->_oXMLNodeVisibleTo);
		
		return parent::__toString();
	}
}


////
//// CLASS - HIGHRISE XML CONTACT-DATA
////
//
/*
<contact-data>
  <email-addresses>
    <email-address>
      <id type="integer">1</id>
      <address>john.doe@example.com</address>
      <location>#{ Work || Home || Other }</location>
    </email-address>
  </email-addresses>
  <phone-numbers>
    <phone-number>
      <id type="integer">2</id>
      <number>555-555-5555</number>
      <location>#{ Work || Mobile || Fax || Pager || Home || Other }</location>
    </phone-number>
    <phone-number>
      <id type="integer">3</id>
      <number>555-666-6666</number>
      <location>Home</location>
    </phone-number>
  </phone-numbers>
  <addresses>
    <address>
      <id type="integer">1</id>
      <city>Sampleville</city>
      <country>United States</country>
      <state>IL</state>
      <street>123 Example Ave</street>
      <zip>55555</zip>
      <location>#{ Work || Home || Other }</location>
    </address>
  </addresses>
  <instant-messengers>
    <instant-messenger>
      <id type="integer">1</id>
      <address>example</address>
      <protocol>#{
        AIM || MSN || ICQ || Jabber || Yahoo || Skype || QQ ||
        Sametime || Gadu-Gadu || Google Talk || other
      }</protocol>
      <location>#{ Work || Personal || Other }</location>
    </instant-messenger>
  </instant-messengers>
  <web-addresses>
    <web-address>
      <id type="integer">1</id>
      <url>http://www.example.com</url>
      <location>#{ Work || Personal || Other }</location>
    </web-address>
  </web-addresses>
</contact-data>
*/
class CHighriseXMLContactData extends CXMLObject
{
	const sEmailAddressLocWork = 'Work';
	const sEmailAddressLocHome = 'Home';
	const sEmailAddressLocOther = 'Other';

	const sPhoneNumberLocWork = 'Work';
	const sPhoneNumberLocMobile = 'Mobile';
	const sPhoneNumberLocFax = 'Fax';
	const sPhoneNumberLocPager = 'Pager';
	const sPhoneNumberLocHome = 'Home';
	const sPhoneNumberLocOther = 'Other';
	
	const sAddressLocWork = 'Work';
	const sAddressLocHome = 'Home';
	const sAddressLocOther = 'Other';
	
	const sIMProtocolAIM = 'AIM';
	const sIMProtocolMSN = 'MSN';
	const sIMProtocolICQ = 'ICQ';
	const sIMProtocolJabber = 'Jabber';
	const sIMProtocolYahoo = 'Yahoo';
	const sIMProtocolSkype = 'Skype';
	const sIMProtocolQQ = 'QQ';
	const sIMProtocolSametime = 'Sametime';
	const sIMProtocolGaduGadu = 'Gadu-Gadu';
	const sIMProtocolGoogleTalk = 'Google Talk';
	const sIMProtocolOther = 'other';
	
	const sIMLocWork = 'Work';
	const sIMLocPersonal = 'Personal';
	const sIMLocOther = 'Other';
	
	const sWebAddressLocWork = 'Work';
	const sWebAddressLocPersonal = 'Personal';
	const sWebAddressLocOther = 'Other';

	private $_oXMLNodeEmailaddresses;
	private $_oXMLNodePhoneNumbers;
	private $_oXMLNodeAddresses;
	private $_oXMLNodeInstantMessengers;
	private $_oXMLNodeWebAddresses;
	
	public function __construct()
	{
		parent::__construct("contact-data");
		
		$this->_oXMLNodeEmailaddresses = new CXMLObject("email-addresses");
		$this->_oXMLNodePhoneNumbers = new CXMLObject("phone-numbers");
		$this->_oXMLNodeAddresses = new CXMLObject("addresses");
		$this->_oXMLNodeInstantMessengers = new CXMLObject("instant-messengers");
		$this->_oXMLNodeWebAddresses = new CXMLObject("web-addresses");
	}

	public function AddEmail($sAddress, $cEmailLocation)
	{
		$oXMLNodeEmailAddress = new CXMLObject("email-address");
			
			$oXMLNodeAddress = new CXMLObject("address");
			
			$oXMLNodeAddress->AppendContent($sAddress);
		
		$oXMLNodeEmailAddress->AppendContent($oXMLNodeAddress);
		
			$oXMLNodeLocation = new CXMLObject("location");
			
			$oXMLNodeLocation->AppendContent($cEmailLocation);
		
		$oXMLNodeEmailAddress->AppendContent($oXMLNodeLocation);
			
		$this->_oXMLNodeEmailaddresses->AppendContent($oXMLNodeEmailAddress);
	}

	public function AddPhone($sNumber, $cPhoneLocation)
	{
		$oXMLNodePhoneNumber = new CXMLObject("phone-number");
		
			$oXMLNodeNumber = new CXMLObject("number");
			
			$oXMLNodeNumber->AppendContent($sNumber);
		
		$oXMLNodePhoneNumber->AppendContent($oXMLNodeNumber);
			
			$oXMLNodeLocation = new CXMLObject("location");
			
			$oXMLNodeLocation->AppendContent($cPhoneLocation);
		
		$oXMLNodePhoneNumber->AppendContent($oXMLNodeLocation);
	
		$this->_oXMLNodePhoneNumbers->AppendContent($oXMLNodePhoneNumber);
	}
	
	public function AddAddress($sCity, $sCountry, $sState, $sStreet, $sZip, $cAddressLocation)
	{
		$oXMLNodeAdress = new CXMLObject("address");
		
			$oXMLNodeCity = new CXMLObject("city");
			
			$oXMLNodeCity->AppendContent($sCity);
		
		$oXMLNodeAdress->AppendContent($oXMLNodeCity);
				
			$oXMLNodeCountry = new CXMLObject("country");
			
			$oXMLNodeCountry->AppendContent($sCountry);
		
		$oXMLNodeAdress->AppendContent($oXMLNodeCountry);
				
			$oXMLNodeState = new CXMLObject("state");
			
			$oXMLNodeState->AppendContent($sState);
		
		$oXMLNodeAdress->AppendContent($oXMLNodeState);
		
			$oXMLNodeStreet = new CXMLObject("street");
			
			$oXMLNodeStreet->AppendContent($sStreet);
		
		$oXMLNodeAdress->AppendContent($oXMLNodeStreet);
		
			$oXMLNodeZip = new CXMLObject("zip");
			
			$oXMLNodeZip->AppendContent($sZip);
		
		$oXMLNodeAdress->AppendContent($oXMLNodeZip);
		
			$oXMLNodeLocation = new CXMLObject("location");
			
			$oXMLNodeLocation->AppendContent($cAddressLocation);
		
		$oXMLNodeAdress->AppendContent($oXMLNodeLocation);
		
		$this->_oXMLNodeAddresses->AppendContent($oXMLNodeAdress);
	}
	
	public function AddInstantMessenger($sAddress, $cProtocol, $cIMLocation)
	{
		$oXMLNodeInstantMessenger = new CXMLObject("instant-messenger");
		
			$oXMLNodeAddress = new CXMLObject("address");
			
			$oXMLNodeAddress->AppendContent($sAddress);
		
		$oXMLNodeInstantMessenger->AppendContent($oXMLNodeAddress);

			$oXMLNodeProtocol = new CXMLObject("protocol");
			
			$oXMLNodeProtocol->AppendContent($cProtocol);
		
		$oXMLNodeInstantMessenger->AppendContent($oXMLNodeProtocol);

			$oXMLNodeLocation = new CXMLObject("location");
			
			$oXMLNodeLocation->AppendContent($cIMLocation);
		
		$oXMLNodeInstantMessenger->AppendContent($oXMLNodeLocation);
		
		$this->_oXMLNodeInstantMessengers->AppendContent($oXMLNodeInstantMessenger);
	}

	public function AddWebAddress($sURL, $cWebAddressLocation)
	{
		$oXMLNodeWebAddress = new CXMLObject("web-address");

			$oXMLNodeURL = new CXMLObject("url");
			
			$oXMLNodeURL->AppendContent($sURL);
		
		$oXMLNodeWebAddress->AppendContent($oXMLNodeURL);

			$oXMLNodeLocation = new CXMLObject("location");
			
			$oXMLNodeLocation->AppendContent($cWebAddressLocation);
		
		$oXMLNodeWebAddress->AppendContent($oXMLNodeLocation);
		
		$this->_oXMLNodeWebAddresses->AppendContent($oXMLNodeWebAddress);
	}
	
	public function __toString()
	{
		parent::AppendContent($this->_oXMLNodeEmailaddresses);
		parent::AppendContent($this->_oXMLNodePhoneNumbers);
		parent::AppendContent($this->_oXMLNodeAddresses);
		parent::AppendContent($this->_oXMLNodeInstantMessengers);
		parent::AppendContent($this->_oXMLNodeWebAddresses);
	
		return parent::__toString();
	}
}


////
//// CLASS - HIGHRISE XML PERSON
////
//
/*
<person>
  <id type="integer">1</id>
  <first-name>John</first-name>
  <last-name>Doe</last-name>
  <title>Stand-in</title>
  <background>A popular guy for random data</background>
  <company-id type="integer">2</company-id>
  <created-at type="datetime">2007-02-27T03:11:52Z</created-at>
  <updated-at type="datetime">2007-03-10T15:11:52Z</updated-at>
  <visible-to>#{Everyone || Owner || NamedGroup}</visible-to>
  <owner-id type="integer">#{ user_id -- when visble-to is "Owner"}</owner-id>
  <group-id type="integer">#{ group_id -- when visble-to is "NamedGroup"}</group-id>
  <author-id type="integer">3</author-id>
  <contact-data>
    ...
  </contact-data>
</person>
*/
class CHighriseXMLPerson extends CXMLObject
{
	private $_oXMLNodeFirstName;
	private $_oXMLNodeLastName;
	private $_oXMLNodeTitle;
	private $_oXMLNodeBackground;
	private $_oXMLNodeCompanyId;
	
	public function __construct()
	{
		parent::__construct("person");
		
		$this->_oXMLNodeFirstName = new CXMLObject("first-name");
		$this->_oXMLNodeLastName = new CXMLObject("last-name");
		$this->_oXMLNodeTitle = new CXMLObject("title");
		$this->_oXMLNodeBackground = new CXMLObject("background");
		
		// Optional
		$this->_oXMLNodeCompanyId = new CXMLObject("company-id");
		$this->_oXMLNodeCompanyId->AddAttr("type", "integer");
	}

	public function SetFirstName($sFirstName)
	{
		$this->_oXMLNodeFirstName->AppendContent($sFirstName);
	}

	public function SetLastName($sLastName)
	{
		$this->_oXMLNodeLastName->AppendContent($sLastName);
	}
	
	public function SetTitle($sTitle)
	{
		$this->_oXMLNodeTitle->AppendContent($sTitle);
	}
	
	public function SetBackground($sBackground)
	{
		$this->_oXMLNodeBackground->AppendContent($sBackground);
	}
	
	public function SetCompanyId($iCompanyId)
	{
		$this->_oXMLNodeCompanyId->AppendContent($iCompanyId);
	}
	
	public function SetXMLContactData($oXMLContactData)
	{
		if ($oXMLContactData instanceof CHighriseXMLContactData)
		{
			parent::AppendContent($oXMLContactData);
		}	
	}
	
	public function __toString()
	{
		parent::AppendContent($this->_oXMLNodeFirstName);
		parent::AppendContent($this->_oXMLNodeLastName);
		parent::AppendContent($this->_oXMLNodeTitle);
		parent::AppendContent($this->_oXMLNodeBackground);
		parent::AppendContent($this->_oXMLNodeCompanyId);
	
		return parent::__toString();
	}
}


////
//// CLASS - HIGHRISE XML NOTE
////
//
/*
<note>
  <id type="integer">1</id>
  <body>Hello world!</body>
  <author-id type="integer">3</author-id>
  <subject-id type="integer">1</subject-id>
  <subject-type>#{ Party || Deal || Kase }</subject-type>
  <subject-name>John Doe</subject-name>
  <collection-id type="integer">1</subject-id>
  <collection-type>#{ Deal || Kase }</subject-type>
  <visible-to>#{Everyone || Owner || NamedGroup}</visible-to>
  <owner-id type="integer">#{ user_id -- when visble-to is "Owner"}</owner-id>
  <group-id type="integer">#{ group_id -- when visble-to is "NamedGroup"}</group-id>
  <updated-at type="datetime">2007-02-27T18:42:28Z</updated-at>
  <created-at type="datetime">2006-05-16T17:26:00Z</created-at>
  <attachments>
    <attachment>
      <id type="integer">1</id>
      <url>http://example.highrisehq.com/files/1</url>
      <name>picture.png</name>
      <size type="integer">72633</name>
    </attachment>
    <attachment>
      <id type="integer">2</id>
      <url>http://example.highrisehq.com/files/2</url>
      <name>document.txt</name>
      <size type="integer">8837</name>
    </attachment>
  </attachments>
</note>
*/
class CHighriseXMLNote extends CXMLObject
{
	private $_oXMLNodeBody;
	
	public function __construct()
	{
		parent::__construct("note");
		
		$this->_oXMLNodeBody = new CXMLObject("body");
	}
	
	public function ReplaceBody($sContent)
	{
		$this->_oXMLNodeBody->ReplaceContent($sContent);
	}
	
	public function AppendToBody($sContent)
	{
		$this->_oXMLNodeBody->AppendContent($sContent);
	}
	
	public function __toString()
	{
		parent::AppendContent($this->_oXMLNodeBody);
	
		return parent::__toString();
	}
}


////
//// CLASS - HIGHRISE XML TASK
////
//
/*
<task>
  <id type="integer">1</id>
  <author-id type="integer">1</author-id>
  <owner-id type="integer">1</owner-id>
  <recording-id type="integer"></recording-id>
  <subject-id type="integer"></subject-id>
  <subject-type></subject-type>
  <subject-name></subject-name>
  <body>Remember to do something important</body>
  <frame>#{ today || tomorrow || this_week || next_week || later }</frame>
  <alert-at type="datetime"></alert-at>
  <done-at type="datetime"></done-at>
  <category-id type="integer"></category-id>
  <created-at type="datetime"></created-at>
  <updated-at type="datetime">2007-04-26T02:04:03Z</updated-at>
</task>
*/
class CHighriseXMLTask extends CXMLObject
{
	const sFrameToday = 'today';
	const sFrameTomorrow = 'tomorrow';
	const sFrameThisWeek = 'this_week';
	const sFrameNextWeek = 'next_week';
	const sFrameLater = 'later';

	private $_oXMLNodeBody;
	private $_oXMLNodeFrame;
	
	public function __construct()
	{
		parent::__construct("task");
		
		$this->_oXMLNodeBody = new CXMLObject("body");
		$this->_oXMLNodeFrame = new CXMLObject("frame");
	}
	
	public function SetTask($sBody, $cFrame)
	{
		$this->_oXMLNodeBody->AppendContent($sBody);
		$this->_oXMLNodeFrame->AppendContent($cFrame);
	}
	
	public function __toString()
	{
		parent::AppendContent($this->_oXMLNodeBody);
		parent::AppendContent($this->_oXMLNodeFrame);
	
		return parent::__toString();
	}
}

?>