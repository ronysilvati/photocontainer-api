<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PhotoContainer\PhotoContainer\Infrastructure\Web\Slim\SlimApp;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\User;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Details;
use PhotoContainer\PhotoContainer\Contexts\User\Action\CreateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Persistence\EloquentUserRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\EloquentDatabaseProvider;
use PhotoContainer\PhotoContainer\Contexts\User\Action\UpdateUser;
use PhotoContainer\PhotoContainer\Contexts\User\Domain\Profile;
use PhotoContainer\PhotoContainer\Contexts\User\Action\FindUser;
use PhotoContainer\PhotoContainer\Contexts\Auth\Action\AuthenticateUser;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\BcryptHashing;
use PhotoContainer\PhotoContainer\Contexts\Auth\Persistence\EloquentAuthRepository;
USE PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth;
use PhotoContainer\PhotoContainer\Infrastructure\Crypto\JwtGenerator;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Event;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Photographer;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\CreateEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Persistence\EloquentEventRepository;
use PhotoContainer\PhotoContainer\Contexts\Event\Action\FindEvent;
use PhotoContainer\PhotoContainer\Contexts\Event\Domain\Search;
use \PhotoContainer\PhotoContainer\Contexts\Event\Domain\EventCategory;

require '../vendor/autoload.php';

$app = new \Slim\App();
$container = $app->getContainer();

$container['DatabaseProvider'] = function ($c) {
    $database = new EloquentDatabaseProvider([
        'host' => '192.168.99.100',
        'port' => '32706',
        'database' => 'photocontainer',
        'user' => 'photocontainer',
        'pwd' => '1234',
    ]);
    return $database;
};

$container['EloquentUserRepository'] = function ($c) {
    return new EloquentUserRepository();
};

$container['CryptoMethod'] = function ($c) {
    return new BcryptHashing();
};

$webApp = new SlimApp($app);

$webApp->bootstrap(
    [
        'secret' => 'secret',
        "api_path" => ["/"],
        "auth_whitelist" => ["/login", "/users", "/events"],
    ]
);

$webApp->app->post('/login', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
    $data = $request->getParsedBody();

    $action = new AuthenticateUser(new EloquentAuthRepository(), $container['CryptoMethod']);
    $domainResponse =  $action->handle(new Auth($data['user'], $data['password']), new JwtGenerator('secret'));

    return $response->withJson($domainResponse, $domainResponse->getHttpStatus());
});

$webApp->app->get('/users', function (ServerRequestInterface $request, ResponseInterface $response) {
    $args = $request->getQueryParams();

    $id = isset($args['id']) ? $args['id'] : null;
    $email = isset($args['email']) ? $args['email'] : null;

    $action = new FindUser(new EloquentUserRepository());
    $actionResponse = $action->handle($id, $email);

    return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
});

$webApp->app->post('/users', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
    try {
        $data = $request->getParsedBody();

        $details = new Details(
            null,
            isset($data['details']['blog']) ? $data['details']['blog'] : '',
            isset($data['details']['instagram']) ? $data['details']['instagram'] : '',
            isset($data['details']['facebook']) ? $data['details']['facebook'] : '',
            isset($data['details']['linkedin']) ? $data['details']['linkedin'] : '',
            isset($data['details']['site']) ? $data['details']['site'] : '',
            isset($data['details']['gender']) ? $data['details']['gender'] : '',
            isset($data['details']['phone']) ? $data['details']['phone'] : '',
            isset($data['details']['birth']) ? $data['details']['birth'] : ''
        );

        $profile = new Profile(null, null, (int) $data['profile'], null);
        $user = new User(null, $data['name'], $data['email'], $data['password'], $details, $profile);

        $crypto = $container['CryptoMethod']->hash($data['password']);

        $action = new CreateUser(new EloquentUserRepository());
        $actionResponse = $action->handle($user, $crypto);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    } catch (\Exception $e) {
        return $response->withJson(['message' => $e->getMessage()], 500);
    }
});

$webApp->app->patch('/users/{id}', function (ServerRequestInterface $request, ResponseInterface $response, $args) use ($container) {
    try {
        $data = $request->getParsedBody();

        $crypto = null;
        if (isset($data['password'])) {
            $crypto = $container['CryptoMethod']->hash($data['password']);
        }

        $action = new UpdateUser($container['EloquentUserRepository']);
        $actionResponse = $action->handle($args['id'], $data, $crypto);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    } catch (\Exception $e) {
        return $response->withJson(['message' => $e->getMessage()], 500);
    }
});

$webApp->app->post('/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
    try {
        $data = $request->getParsedBody();

        $user = new Photographer($data['user_id']);

        $allCategories = [];
        foreach ($data['categories'] as $category) {
            $allCategories[] = new EventCategory(null, $category);

        }

        $event = new Event(null, $user, $data['bride'], $data['groom'], $data['eventDate'], $data['title'],
            $data['description'], (bool) $data['terms'], (bool) $data['approval_general'],
            (bool) $data['approval_photographer'], (bool) $data['approval_bride'], $allCategories);

        $action = new CreateEvent(new EloquentEventRepository());
        $actionResponse = $action->handle($event);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    } catch (\Exception $e) {
        return $response->withJson(['message' => $e->getMessage()], 500);
    }
});

$webApp->app->get('/events', function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
    try {
        $args = $request->getQueryParams();

        $keyword = isset($args['keyword']) ? $args['keyword'] : null;
        $photographer = isset($args['photographer']) ? $args['photographer'] : null;

        $search = new Search(null, $photographer, $keyword);

        $action = new FindEvent(new EloquentEventRepository());
        $actionResponse = $action->handle($search);

        return $response->withJson($actionResponse, $actionResponse->getHttpStatus());
    } catch (\Exception $e) {
        return $response->withJson(['message' => $e->getMessage()], 500);
    }
});

$webApp->app->run();