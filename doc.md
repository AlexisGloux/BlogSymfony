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

### Server locale
```
symfony serve
symfony server:stop
```

### Composer
```
composer require <nom_du_package>
composer require debug
```

### Doctrine
```
php bin/console doctrine:database:create
php bin/console make:entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Dépendances ajouter dans ce projet
```
composer require debug --dev
composer require doctrine/annotations
composer require twig
composer require doctrine
composer require make --dev
composer require sensio/framework-extra-bundle
composer require symfony/form
composer require symfony/security-csrf
```

### Documentation
- [Symfony](https://symfony.com/doc/current/index.html)
- [Packagist](https://packagist.org/)
