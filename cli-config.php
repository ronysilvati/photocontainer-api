<?php
require __DIR__ . '/vendor/autoload.php';

use Jgut\Slim\Doctrine\ManagerBuilder;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;

define('ROOT_DIR', __DIR__);
define('CACHE_DIR', ROOT_DIR.'/cache');
define('LOG_DIR', ROOT_DIR.'/logs');
define('DEBUG_MODE', true);

if (is_file(ROOT_DIR.'/public/.env')) {
    $dotenv = new Dotenv\Dotenv(ROOT_DIR.'/public');
    $dotenv->overload();
}

$builder = new \DI\ContainerBuilder();

if (getenv('ENVIRONMENT') === 'prod') {
    $cache = new ApcuCache();
} else {
    $cache = new ArrayCache();
}
$cache->setNamespace('PhotoContainer_cli');

$builder->setDefinitionCache($cache);

$builder->addDefinitions(ROOT_DIR.'/public/config.php');

$builder->writeProxiesToFile(true, CACHE_DIR);

$container = $builder->build();

/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(\Doctrine\ORM\EntityManager::class);


$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');

/** @var \PhotoContainer\PhotoContainer\Contexts\Auth\Persistence\DoctrineAuthRepository $x */
$x = $em->getRepository(\PhotoContainer\PhotoContainer\Contexts\Auth\Domain\Auth::class);
//var_dump($x->findAll());
var_dump($x->findUser('photographer1@teste.com'));
exit;


$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

return $helperSet;

//
//$managerBuilder = (new ManagerBuilder())->loadSettings($container->get('orm_settings'));
//
//return $managerBuilder->getCLIApplication();