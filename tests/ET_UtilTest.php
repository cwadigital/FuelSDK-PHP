<?php
namespace FuelSdk\Test;

use FuelSdk\ET_Util;
use FuelSdk\ET_Constructor;
use FuelSdk\ET_BaseObject;
use FuelSdk\ET_BaseObjectRest;
use FuelSdk\ET_Asset;
use FuelSdk\ET_Campaign;
use FuelSdk\ET_DataExtension;
use FuelSdk\ET_Email;
use FuelSdk\ET_Folder;
use FuelSdk\ET_List;
use FuelSdk\ET_Subscriber;
use FuelSdk\ET_TriggeredSend;
use FuelSdk\ET_ResultMessage;
use PHPUnit\Framework\TestCase;

final class ET_UtilTest extends TestCase
{
    public function testGetSDKVersion()
    {
        $version = ET_Util::getSDKVersion();
        $this->assertIsString($version);
        $this->assertStringStartsWith('FuelSDK-PHP-', $version);
    }

    public function testIsAssoc()
    {
        $indexed = [1, 2, 3];
        $assoc = ['a' => 1, 'b' => 2];
        $mixed = [0 => 'a', 2 => 'b'];

        $this->assertFalse(ET_Util::isAssoc($indexed));
        $this->assertTrue(ET_Util::isAssoc($assoc));
        $this->assertTrue(ET_Util::isAssoc($mixed));
    }

    public function testShouldVerifySslPeer()
    {
        $this->assertTrue(ET_Util::shouldVerifySslPeer(true));
        $this->assertFalse(ET_Util::shouldVerifySslPeer(false));
        $this->assertFalse(ET_Util::shouldVerifySslPeer(null));
        $this->assertFalse(ET_Util::shouldVerifySslPeer('true'));
        $this->assertFalse(ET_Util::shouldVerifySslPeer(1));
    }

    public function testConstructorClassInitialization()
    {
        $soapResponse = new \SoapFault("Server", "Test Fault");
        $soapResult = new ET_Constructor($soapResponse, 500, false);
        $this->assertFalse($soapResult->status);
        $this->assertEquals(500, $soapResult->code);

        $jsonPayload = json_encode(['items' => [1, 2, 3], 'total' => 3]);
        $restResult = new ET_Constructor($jsonPayload, 200, true);
        $this->assertTrue($restResult->status);
        $this->assertEquals(200, $restResult->code);
        $this->assertCount(3, $restResult->results->items);

        $restFail = new ET_Constructor("Server error", 500, true);
        $this->assertFalse($restFail->status);
        $this->assertEquals("Server error", $restFail->message);
    }

    public function testDynamicPropertiesOnObjects()
    {
        $base = new ET_BaseObject();
        $base->customProp = 'customValue';
        $this->assertEquals('customValue', $base->customProp);

        $baseRest = new ET_BaseObjectRest();
        $baseRest->customField = 12345;
        $this->assertEquals(12345, $baseRest->customField);

        $asset = new ET_Asset();
        $asset->customProp = 'test';
        $this->assertEquals('test', $asset->customProp);

        $campaign = new ET_Campaign();
        $campaign->customProp = 'test';
        $this->assertEquals('test', $campaign->customProp);

        $de = new ET_DataExtension();
        $de->customProp = 'test';
        $this->assertEquals('test', $de->customProp);

        $email = new ET_Email();
        $email->customProp = 'test';
        $this->assertEquals('test', $email->customProp);
        $this->assertEquals('CategoryID', $email->folderProperty);
        $this->assertEquals('email', $email->folderMediaType);

        $folder = new ET_Folder();
        $folder->customProp = 'test';
        $this->assertEquals('test', $folder->customProp);

        $list = new ET_List();
        $list->customProp = 'test';
        $this->assertEquals('test', $list->customProp);
        $this->assertEquals('Category', $list->folderProperty);
        $this->assertEquals('list', $list->folderMediaType);

        $subscriber = new ET_Subscriber();
        $subscriber->customProp = 'test';
        $this->assertEquals('test', $subscriber->customProp);

        $ts = new ET_TriggeredSend();
        $ts->customProp = 'test';
        $this->assertEquals('test', $ts->customProp);
        $this->assertEquals('CategoryID', $ts->folderProperty);
        $this->assertEquals('triggered_send', $ts->folderMediaType);
    }
}
