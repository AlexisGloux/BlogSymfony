## Commandes :

### Création d'un projet :

#### Composer :
```
composer create-project symfony/website-skeleton my_project_name
composer create-project symfony/skeleton my_project_name
```

#### Symfony :
```
symfony new my_project_name --full
symfony new my_project_name
```

### Serveur locale
```
symfony serve
symfony server:stop
```

### Doctrine
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console make:entity
php bin/console make:form
php bin/console make:controller
php bin/console make:migration
php bin/console make:validator
php bin/console make:user
php bin/console make:auth
php bin/console make:voter
```

### Dépendances ajoutées dans ce projet
```
composer require debug --dev
composer require doctrine/annotations
composer require twig
composer require doctrine
composer require make --dev
composer require sensio/framework-extra-bundle
composer require symfony/form
composer require symfony/security-csrf
composer require validator
composer require asset
composer require security
```

### Documentation
- [Symfony](https://symfony.com/doc/current/index.html)
- [Packagist](https://packagist.org/)
