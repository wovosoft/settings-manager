<?php

namespace Wovosoft\SettingsManager;

use Wovosoft\SettingsManager\Models\Settings;

class SettingsManager implements SettingsInterface
{
    private $cache_key;

    public function __construct()
    {
        $this->cache_key = config('settings-manager.cache-key');
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, string $group = null, bool $getModel = false)
    {
        if (is_numeric($key)) {
            /**
             * Its a numeric value, that means ID of model, so need to check other fields.
             */
            $item = Settings::find($key);
        } elseif (is_array($key)) {
            /**
             * We check it here because the $key is array, and we don't want to pass the execution to the next condition.
             */
            if (! count($key)) {
                return;
            }
            $items = Settings::query()->whereIn('key', $key);

            if ($group) {
                $items->where('group', $group);
            }

            return $items->get()->map(function ($item) use ($getModel) {
                return [$item->key => $getModel ? $item : $item->value];
            })->collapse();
        } else {
            $item = Settings::where('key', $key);
            if ($group) {
                $item->where('group', $group);
            }
            $item = $item->first();
        }
        /**
         * For the first and last conditions.
         */
        if ($item) {
            return $getModel ? $item : $item->value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, string $group = null, $type = 'text', $options = [], bool $getModel = false, $model = null)
    {
        if ($model) {
            if (is_numeric($model)) {
                $item = Settings::find($model);
            } elseif ($model instanceof Settings) {
                $item = $model;
            }
        } elseif (is_numeric($key)) {
            $item = Settings::find($key);
        } else {
            $item = Settings::query()
                ->where('key', $key)
                ->where('group', $group)
                ->first();
        }
        if (! $item) {
            $item = new Settings();
        }
        $item->key = $key;
        $item->value = $value;
        $item->group = $group;
        $item->type = $type ?? 'b-form-input';
        $item->options = $options ?? [];
        if ($item->saveOrFail()) {
            return $getModel ? $item : true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setArray($item = [], bool $getModel = false)
    {
        if (! isset($item['key'])) {
            return false;
        }

        return $this->set(
            $item['key'],
            isset($item['value']) ? $item['value'] : null,
            isset($item['group']) ? $item['group'] : null,
            isset($item['type']) ? $item['type'] : null,
            isset($item['options']) ? $item['options'] : null,
            $getModel
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setBatch($key_values = [], bool $getModel = false)
    {
        $status = [];
        foreach ($key_values as $key_value) {
            if (! ($status[$key_value['key']] = $this->setArray($key_value, $getModel))) {
                $status[$key_value['key']] = false;
            }
        }

        return $status;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key, string $group = null, bool $getModel = false)
    {
        if (is_numeric($key) && $item = Settings::find($key)) {
            return $getModel ? $item : true;
        } elseif ($item = Settings::where('key', $key)->group('group', $group)->first()) {
            return $getModel ? $item : true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, string $group = null, bool $getModel = false)
    {
        if (! $key) {
            return false;
        }
        if (is_numeric($key)) {
            $item = Settings::find($key);
        } else {
            $item = Settings::query()
                ->where('key', $key)
                ->where('group', $group)
                ->first();
        }
        if ($status = $item && $item->delete()) {
            return $getModel ? $item : $status;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $group = null, bool $getModel = false)
    {
        $items = Settings::query()
            ->where('group', $group)
            ->get();
        if ($getModel) {
            return $items->map(function ($item) use ($getModel) {
                return [$item['key'] => $item];
            })->collapse();
        }

        return $items->pluck('value', 'key');
    }

    /**
     * {@inheritdoc}
     */
    public function allGrouped(bool $getModel = false)
    {
        $items = Settings::all()
            ->groupBy('group')
            ->map(function ($item) use ($getModel) {
                return $item->mapWithKeys(function ($grouped_item) use ($getModel) {
                    return [$grouped_item['key'] => $getModel ? $grouped_item : $grouped_item['value']];
                });
            });

        return $items;
    }
}
