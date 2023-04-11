# Nova Map Marker Field
This package allows adding fields with Mapbox, to select a location (by long - latitude).\
\
**This page is still under development, it is basicually functioning but not guaranted working perfectly or match multiple use cases**

## Prerequisite
This field requires attributes using the package [MatanYadaev/laravel-eloquent-spatial](https://github.com/MatanYadaev/laravel-eloquent-spatial)
- Nova ^4.x.x
- Laravel 8+ (due to the dependencies of `MatanYadaev/laravel-eloquent-spatial:2.x.x`

## Installation
```
composer require takashato/nova-map-marker-field
```

## Usage
```php
NovaMapMarkerField::make(__('Field label'), 'attribute_name')
  ->hideFromIndex()
  ->zoom($zoomValue),
```

- `$zoomValue` default zoom value of Mapbox (= 10 if empty)
- `attribute_name` the attribute of current resource / model
This attribute must implement an `Point` object by the package [MatanYadaev/laravel-eloquent-spatial](https://github.com/MatanYadaev/laravel-eloquent-spatial)

## Authors
- me
- (and may be you xD)

## Contribution
Feel free to made PR or put an issue for this package. I will see for them when having free time xD
