<?php

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatePriceTest extends WebTestCase
{
    public function testRequestSuccessfulResult(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'POST',
            uri: '/api/v1/calculate/price',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"product": 1, "taxNumber": "DE123456789", "couponCode": "D15"}'
        );
        $jsonResult = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($jsonResult['data'], ['price' => '101.15']);

        $this->assertResponseIsSuccessful();
    }

    public function testRequestNoSuccessfulResult(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'POST',
            uri: '/api/v1/calculate/price',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"product": 1, "taxNumber": "DEP123", "couponCode": "R15"}'
        );
        $jsonResult = json_decode($client->getResponse()->getContent(), true);

        $errors = [
            'taxNumber' => [
                'Не верно указан код страны в поле «taxNumber».',
                'Формат налогового номера в поле «taxNumber» не соответствует коду страны.'
            ],
            'couponCode' => [
                'Значение поля «couponCode» не соответствует кодировке.',
                'Не верно указан номинал скидки в поле «couponCode».'
            ]
        ];

        $this->assertEquals($jsonResult['errors'], $errors);
        $this->assertResponseIsSuccessful();
    }
}
