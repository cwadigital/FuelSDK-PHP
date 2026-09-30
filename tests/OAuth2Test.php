<?php
namespace FuelSdk\Test;
use FuelSdk\ET_Client;
use FuelSdk\ET_Asset;
use PHPUnit\Framework\TestCase;

final class OAuth2Test extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        try {
            $this->client = new ET_Client(true);
        } catch (\Throwable $e) {
            $this->markTestSkipped('ET_Client could not be initialized (missing config.php): ' . $e->getMessage());
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function testIfAuthTokenAndRefreshTokenDifferIfRefreshTokenIsEnforced()
    {
        $reflection = new \ReflectionClass(get_class($this->client));
        $clientid = $reflection->getProperty("clientId");
        $token = $this->client->getAuthToken();
        $refreshToken = $this->client->getRefreshToken(null);
        $this->client->refreshTokenWithOAuth2(true);

        $newtoken = $this->client->getAuthToken();
        $newrefreshToken = $this->client->getRefreshToken(null);

        $this->assertTrue($refreshToken != $newrefreshToken && $token != $newtoken);
    }
}
