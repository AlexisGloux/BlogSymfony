<?php


namespace App\client;


use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PunkApiClient
{

    /**
     * @var HttpClientInterface
     */
    private $client;

    private $endpoint = 'https://api.punkapi.com/v2';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function random()
    {
        $response = $this->client->request('GET', $this->endpoint.'/beers/random');
        try {
            return json_decode($response->getContent());
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            return $e->getMessage();
        }
    }
}