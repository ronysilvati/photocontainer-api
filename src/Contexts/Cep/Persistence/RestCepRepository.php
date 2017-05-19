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

    public function findCep(Cep $cep): Cep
    {
        try {
            $response = $this->provider->client->get("{$cep->getZipcode()}/json");

            $cepData = json_decode($response->getBody()->getContents());

            if (property_exists($cepData, 'erro') && $cepData->erro) {
                throw new \Exception("Erro no retorno do CEP.");
            }

            $cep->changeCountry('Brasil');
            $cep->changeState($cepData->uf);
            $cep->changeCity($cepData->localidade);
            $cep->changeNeighborhood($cepData->bairro);
            $cep->changeStreet($cepData->logradouro);
            $cep->changeComplement($cepData->complemento);

            return $cep;
        } catch (\Exception $e) {
            throw new PersistenceException("CEP não encontrado.", $e->getMessage());
        }
    }

    public function findStates(Cep $cep)
    {
        throw new \Exception("Nâo implementado.");
    }

    public function findCities(Cep $cep)
    {
        throw new \Exception("Nâo implementado.");
    }

    public function getCountries(): array
    {
        throw new \Exception("Nâo implementado.");
    }
}
