<?php

namespace Wovosoft\SettingsManager\Models;

use Illuminate\Database\Eloquent\Model;
use Wovosoft\SettingsManager\Traits\SettingsModelTrait;

class Settings extends Model
{
    use SettingsModelTrait;
    protected $fillable = ["id", "key", "value", "options", "created_at", "updated_at"];
    protected $casts = [
        "options" => "array"
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config("settings-manager.table_name");
    }

    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }

    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    public function getValueAttribute($value)
    {
        if (is_null($value)) return null;
        return $this->isJson($value) ? json_decode($value) : $value;
    }

}
