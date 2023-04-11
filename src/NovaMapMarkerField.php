<?php

namespace Takashato\NovaMapMarkerField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Symfony\Component\HttpFoundation\InputBag;

class NovaMapMarkerField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-map-marker-field';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'accessToken' => config('services.mapbox.public_token'),
            'zoom' => 13,
        ]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        if (is_callable($attribute)) {
            $point = call_user_func($attribute, $resource);
        } else {
            $point = $resource->{$attribute};
        }

        if (!$point) {
            $this->withMeta([
                'longitude' => '0',
                'latitude' => '0',
            ]);
            return;
        }

        if (!$point instanceof Point) {
            return null;
        }

        $this->withMeta([
            'latitude' => $point->latitude,
            'longitude' => $point->longitude,
        ]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $value = new InputBag(json_decode($request[$requestAttribute], true));

        $point = new Point($value->get('latitude'), $value->get('longitude'));

        $model->{$attribute} = $point;
    }

    public function mapElementId($id): self
    {
        $this->withMeta([
            'mapId' => $id,
        ]);

        return $this;
    }

    public function zoom($zoom): self
    {
        $this->withMeta([
            'zoom' => $zoom,
        ]);

        return $this;
    }
}
