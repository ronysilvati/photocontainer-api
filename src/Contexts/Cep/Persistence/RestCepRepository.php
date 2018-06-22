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

    /**
     * @param string $zipcode
     * @return Cep
     * @throws PersistenceException
     */
    public function findCep(string $zipcode): Cep
    {
        try {
            $response = $this->provider->client->get("{$zipcode}/json");

            $cepData = json_decode($response->getBody()->getContents());

            if (property_exists($cepData, 'erro') && $cepData->erro) {
                throw new \RuntimeException('Erro no retorno do CEP.');
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

    /**
     * @param int $country_id
     * @return array
     */
    public function findStates(int $country_id): array
    {
        throw new \RuntimeException('Nâo implementado.');
    }

    /**
     * @param int $state_id
     * @return array
     */
    public function findCities(int $state_id): array
    {
        throw new \RuntimeException('Nâo implementado.');
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {
        throw new \RuntimeException('Nâo implementado.');
    }
}
