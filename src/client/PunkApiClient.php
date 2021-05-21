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
        try {
            $response = $this->client->request('GET', $this->endpoint.'/beers/random');

            if ($response->getStatusCode() >= 400)
                throw new \Exception('Erreur');

            return json_decode($response->getContent());
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            return $e->getMessage();
        }
    }

    public function search(int $ibuMin = 0, int $ibuMax = 100)
    {
        $path = '/beers?ibu_gt='.$ibuMin.'&ibu_lt='.$ibuMax;
        try {
            $response = $this->client->request('GET', $this->endpoint.$path);

            if ($response->getStatusCode() >= 400)
                throw new \Exception('Erreur');

            return json_decode($response->getContent());
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            return $e->getMessage();
        }
    }
}