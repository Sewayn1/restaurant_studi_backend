<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    public function testApiDocUrlIsSucessfull(): void
    {
        $client = self::createClient(); //self equivalent de $this mais en static
        $client->request("GET", "/api/doc");

        self::assertResponseIsSuccessful();
    }

    // public function testLoginRouteCanConnectValidUser(): void
    // {
    //     $client = self::createClient();
    //     $client->followRedirects(false);

    //     $client->request("POST", "/api/register", [], [], [
    //         "CONTENT_TYPE" => "application/json",
    //     ], json_encode([
    //             'firstname' => 'Toto',
    //             'lastname' => 'Ro',
    //             'email' => 'toto@toto.fr',
    //             'password' => 'toto123',
    //         ], JSON_THROW_ON_ERROR));


    //     $client->request("POST", "/api/login", [], [], [
    //         "CONTENT_TYPE" => "application/json",
    //     ], json_encode([
    //             'username' => 'toto@toto.fr',
    //             'password' => 'toto123',
    //         ], JSON_THROW_ON_ERROR));

    //     $statusCode = $client->getResponse()->getStatusCode();
    //     dd($statusCode);
    // }
}