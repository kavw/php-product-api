<?php

declare(strict_types=1);

namespace App\Infra\Doctrine;

use App\Defaults;
use App\Infra\Doctrine\Types\MoneyType;
use App\Infra\Secret\SecretProviderInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Ramsey\Uuid\Doctrine\UuidType;

use function App\env;

final class EntityManagerFactory
{
    private ?EntityManagerInterface $entityManager = null;

    public function __construct(
        /** @var string[] */
        readonly private array $paths,
        readonly private SecretProviderInterface $secretProvider,
    ) {
    }

    public function __invoke(): EntityManagerInterface
    {
        return $this->getEntityManger();
    }

    private function getEntityManger(): EntityManagerInterface
    {
        if ($this->entityManager !== null) {
            return $this->entityManager;
        }

        $dbParams = [
            'host' => $this->secretProvider->get(Defaults::ENV_POSTGRES_HOST_PARAM),
            'dbname' => $this->secretProvider->get(Defaults::ENV_POSTGRES_DB_PARAM),
            'user' => $this->secretProvider->get(Defaults::ENV_POSTGRES_USER_PARAM),
            'password' => $this->secretProvider->get(Defaults::ENV_POSTGRES_PASS_PARAM),
            'driver' => 'pdo_pgsql',
        ];

        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $this->paths,
            true
        );

        Type::addType(AppTypes::UUID, UuidType::class);
        Type::addType(AppTypes::MONEY, MoneyType::class);

        return $this->entityManager = new EntityManager(
            DriverManager::getConnection($dbParams, $ormConfig),
            $ormConfig
        );
    }
}
