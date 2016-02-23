# Kirby 2 Package Manager

Work in progress.

To test, just put everything into `site/widgets/package-manager/`.

## Options

Check if updates available:
```php
c::set('packages.check.updates', true)
```

Caching of version checking:
```php
c::set('packages.cache', true)
```

Cache lifetime (in minutes):
```php
c::set('packages.cache.age', 60 * 24)
```
