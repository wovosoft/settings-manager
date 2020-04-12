<?php

namespace Wovosoft\SettingsManager\Traits;

trait SettingsModelTrait
{
    private function isJson($string)
    {
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }
}
