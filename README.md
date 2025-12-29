# ChamberOrchestra View Bundle

A lightweight Symfony bundle for building typed, reusable JSON responses. Views encapsulate serialization concerns so controllers can return simple objects instead of `Response`.

## Requirements
- PHP 8.4
- Symfony 8.0 components (http-kernel, serializer, property-access, dependency-injection, config)
- doctrine/common ^3.5

## Installation
```bash
composer require chamber-orchestra/view-bundle:8.0.*
```

Enable the bundle in `config/bundles.php`:
```php
return [
    // ...
    ChamberOrchestra\ViewBundle\ChamberOrchestraViewBundle::class => ['all' => true],
];
```

## Quickstart
Create a view that maps fields from a domain object:
```php
use ChamberOrchestra\ViewBundle\View\BindView;
use ChamberOrchestra\ViewBundle\Attribute\Type;
use ChamberOrchestra\ViewBundle\View\IterableView;

final class UserView extends BindView
{
    public string $id;
    public string $name;

    #[Type(ImageView::class)]
    public IterableView $images;

    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}

final class ImageView extends BindView
{
    public string $path;
}
```

Return a view from a controller:
```php
#[Route('/user/me', methods: ['GET'])]
final class GetMeAction
{
    public function __invoke(): UserView
    {
        return new UserView($this->getUser());
    }
}
```
`ViewSubscriber` converts any `ViewInterface` result into a `JsonResponse`. Non-view results are ignored.

## Core Views
- `ResponseView`: base response with status (200) and headers (`Content-Type: application/json`), overridable in subclasses.
- `DataView`: wraps payload under `data`.
- `BindView`: maps matching properties from a source object to the view; honors `Attribute\Type` on `IterableView` properties for typed collections.
- `IterableView`: maps collections via a callback or view class string.
- `KeyValueView`: returns an associative array for metadata blocks.

## Caching & Build ID
`SetVersionSubscriber` seeds `BindUtils::configure()` with `container.build_id`. When `APP_DEBUG=false`, property accessor caching is enabled with a 24h lifetime and namespace `view_bind`.

## Development & Tests
- Install deps: `composer install`
- Run unit/integration tests: `./bin/phpunit`
- Namespaces live under `ChamberOrchestra\ViewBundle`; autoloaded PSR-4 from `src/`.
