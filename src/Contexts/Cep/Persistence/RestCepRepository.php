<?php

namespace PhotoContainer\PhotoContainer\Contexts\Cep\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\Cep;
use PhotoContainer\PhotoContainer\Contexts\Cep\Domain\CepRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Exception\PersistenceException;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\RestDatabaseProvider;

class RestCepRepository implements CepRepository
{
    /**
     * @var RestDatabaseProvider
     */
    private $provider;

    /**
     * RestCepRepository constructor.
     * @param RestDatabaseProvider $provider
     */
    public function __construct(RestDatabaseProvider $provider)
    {
        $this->provider = $provider;
    }

    public function findCep(string $zipcode): Cep
    {
        try {
            $response = $this->provider->client->get("{$zipcode}/json");

            $cepData = json_decode($response->getBody()->getContents());

            if (property_exists($cepData, 'erro') && $cepData->erro) {
                throw new \Exception('Erro no retorno do CEP.');
            }

            return new Cep(
                $zipcode,
                'Brasil',
                $cepData->uf,
                $cepData->localidade,
                $cepData->bairro,
                $cepData->logradouro,
                $cepData->complemento
            );
        } catch (\Exception $e) {
            throw new PersistenceException('CEP não encontrado.', $e->getMessage());
        }
    }

    public function findStates(int $country_id): array
    {
        throw new \Exception('Nâo implementado.');
    }

    public function findCities(int $state_id): array
    {
        throw new \Exception('Nâo implementado.');
    }

    public function getCountries(): array
    {
        throw new \Exception('Nâo implementado.');
    }
}
