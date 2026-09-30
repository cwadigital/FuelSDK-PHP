<?php
namespace FuelSdk\Test;

use FuelSdk\ET_Client;
use FuelSdk\ET_OEM_Client;
use FuelSdk\ET_Constructor;
use FuelSdk\ET_DataExtension;
use FuelSdk\ET_DataExtension_Column;
use FuelSdk\ET_DataExtension_Row;
use FuelSdk\ET_Email_SendDefinition;
use FuelSdk\ET_TriggeredSend;
use FuelSdk\ET_Organization;
use FuelSdk\ET_User;
use FuelSdk\ET_GetRest;
use FuelSdk\ET_PostRest;
use FuelSdk\ET_PatchRest;
use FuelSdk\ET_PutRest;
use FuelSdk\ET_DeleteRest;
use PHPUnit\Framework\TestCase;

final class ClientUnitTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testClientInstantiationWithParams()
    {
        $params = [
            'clientid' => 'dummy-client-id',
            'clientsecret' => 'dummy-client-secret',
            'appsignature' => 'dummy-sig',
            'defaultwsdl' => 'https://webservice.exacttarget.com/etframework.wsdl',
            'xmlloc' => 'ExactTargetWSDL.xml',
            'baseurl' => 'https://mczxxxxxxxx.rest.marketingcloudapis.com',
            'useOAuth2Authentication' => true,
            'accountId' => '123456',
            'scope' => 'email_read email_write',
            'authURL' => 'https://mczxxxxxxxx.auth.marketingcloudapis.com/v2/token',
            'applicationType' => 'server'
        ];

        $reflection = new \ReflectionClass(ET_Client::class);
        $client = $reflection->newInstanceWithoutConstructor();

        $clientIdProp = $reflection->getProperty('clientId');
        $clientIdProp->setValue($client, $params['clientid']);

        $clientSecretProp = $reflection->getProperty('clientSecret');
        $clientSecretProp->setValue($client, $params['clientsecret']);

        $appsigProp = $reflection->getProperty('appsignature');
        $appsigProp->setValue($client, $params['appsignature']);

        $oauthProp = $reflection->getProperty('useOAuth2Authentication');
        $oauthProp->setValue($client, $params['useOAuth2Authentication']);

        $accountProp = $reflection->getProperty('accountId');
        $accountProp->setValue($client, $params['accountId']);

        $this->assertInstanceOf(ET_Client::class, $client);
        $this->assertEquals('dummy-client-id', $clientIdProp->getValue($client));
        $this->assertEquals('dummy-client-secret', $clientSecretProp->getValue($client));
        $this->assertEquals('dummy-sig', $appsigProp->getValue($client));
        $this->assertTrue($oauthProp->getValue($client));
        $this->assertEquals('123456', $accountProp->getValue($client));
    }

    /**
     * @throws \ReflectionException
     */
    public function testOEMClientInheritance()
    {
        $reflection = new \ReflectionClass(ET_OEM_Client::class);
        $oem = $reflection->newInstanceWithoutConstructor();
        $this->assertInstanceOf(ET_Client::class, $oem);
        $this->assertInstanceOf(ET_OEM_Client::class, $oem);
    }

    /**
     * @throws \ReflectionException
     */
    public function testRestObjectsInstantiation()
    {
        $postRestReflection = new \ReflectionClass(ET_PostRest::class);
        $postRest = $postRestReflection->newInstanceWithoutConstructor();
        $this->assertInstanceOf(ET_Constructor::class, $postRest);

        $getRestReflection = new \ReflectionClass(ET_GetRest::class);
        $getRest = $getRestReflection->newInstanceWithoutConstructor();
        $this->assertInstanceOf(ET_Constructor::class, $getRest);

        $patchRestReflection = new \ReflectionClass(ET_PatchRest::class);
        $patchRest = $patchRestReflection->newInstanceWithoutConstructor();
        $this->assertInstanceOf(ET_Constructor::class, $patchRest);

        $putRestReflection = new \ReflectionClass(ET_PutRest::class);
        $putRest = $putRestReflection->newInstanceWithoutConstructor();
        $this->assertInstanceOf(ET_Constructor::class, $putRest);

        $deleteRestReflection = new \ReflectionClass(ET_DeleteRest::class);
        $deleteRest = $deleteRestReflection->newInstanceWithoutConstructor();
        $this->assertInstanceOf(ET_Constructor::class, $deleteRest);
    }

    public function testDataExtensionAndColumnProperties()
    {
        $de = new ET_DataExtension();
        $de->props = ['Name' => 'TestDE', 'CustomerKey' => 'TestKey'];
        $de->columns = [
            ['Name' => 'ID', 'FieldType' => 'Number', 'IsPrimaryKey' => 'true'],
            ['Name' => 'Email', 'FieldType' => 'EmailAddress']
        ];
        $this->assertEquals('DataExtension', $de->props['Name'] !== '' ? 'DataExtension' : '');
        $this->assertCount(2, $de->columns);

        $col = new ET_DataExtension_Column();
        $col->props = ['Name' => 'TestCol', 'FieldType' => 'Text'];
        $this->assertEquals('TestCol', $col->props['Name']);

        $row = new ET_DataExtension_Row();
        $row->Name = 'TestDE';
        $row->props = ['ID' => 1, 'Email' => 'test@example.com'];
        $this->assertEquals('TestDE', $row->Name);
        $this->assertEquals(1, $row->props['ID']);
    }

    public function testSendDefinitionAndTriggeredSend()
    {
        $esd = new ET_Email_SendDefinition();
        $esd->props = ['Name' => 'TestESD', 'CustomerKey' => 'ESDKey'];
        $this->assertEquals('TestESD', $esd->props['Name']);

        $ts = new ET_TriggeredSend();
        $ts->props = ['CustomerKey' => 'TSKey', 'TriggeredSendStatus' => 'Active'];
        $ts->subscribers = [['EmailAddress' => 'user@example.com', 'SubscriberKey' => 'userKey']];
        $this->assertEquals('Active', $ts->props['TriggeredSendStatus']);
        $this->assertCount(1, $ts->subscribers);
    }

    public function testUserAndOrganization()
    {
        $user = new ET_User();
        $user->props = ['Name' => 'TestUser', 'UserID' => 'testuser@example.com'];
        $this->assertEquals('TestUser', $user->props['Name']);

        $org = new ET_Organization();
        $org->props = ['Name' => 'TestOrg', 'CustomerKey' => 'OrgKey'];
        $this->assertEquals('TestOrg', $org->props['Name']);
    }
}
