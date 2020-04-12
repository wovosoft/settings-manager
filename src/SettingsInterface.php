<?php

namespace Wovosoft\SettingsManager;

interface SettingsInterface
{
    /**
     * @param number | string | array $key When Number (ID) is provided Query is operated by find() method, else by
     *                                      where('key',$key)->first(); for array returns key=>value paired aray
     * @param string|null $group
     * @param bool $getModel If true returns Model instead value
     * @return string | array | null
     */
    public function get($key, string $group = null, bool $getModel = false);

    /**
     * @param string | number $key Should be unique value. NOTE: Do not use number for creating new Settings.
     *                      Only use it when updating. Otherwise, the numeric value will be used as key's value
     * @param mixed $value mixed Arrays will be parsed as JSON
     * @param string|null $group Group Name of the Settings Option
     * @param string $type bootstrap-vue form fields or any kind of fields you want for front-end. This doesn't
     *                      related to backend. In this package we are aiming bootstrap-vue framework for front-end.
     * @param array $options Options usable for Front-End Manipulation. Like Enum DataType
     * @param bool $getModel If true returns Model instead value
     * @param null $model Due the the issue of "key as numeric", $item Model parameter is added. The function
     *                  should get the model by id or check if it is instance of the Settings.php Model. If it is already a
     *                  model do not find it, just perform the operation
     * @return mixed
     */
    public function set($key, $value, string $group = null, $type = "b-form-input", $options = [], bool $getModel = false, $model = null);

    /**
     * @param array $item ['key'=>string | number,'value'=> mixed, 'group'=>string, type'=>string, 'options'=>string | array]
     * @param bool $getModel If true returns Model instead value
     * @return mixed
     */
    public function setArray($item = [], bool $getModel = false);

    /**
     * @param array $key_values [['key'=>string | number, 'value'=>mixed, 'group'=>string, type'=>string, 'options'=>string | array]]
     * @param bool $getModel If true returns Model instead value
     * @return bool
     */
    public function setBatch($key_values = [], bool $getModel = false);

    /**
     * @param string $key
     * @param string|null $group
     * @param bool $getModel If true returns Model instead value
     * @return bool
     */
    public function has($key, string $group = null, bool $getModel = false);

    /**
     * @param string | number $key number from ID and $key for key
     * @param string|null $group
     * @param bool $getModel If true returns Model instead value
     * @return bool Found && Deleted Condition applicable.
     */
    public function delete($key, string $group = null, bool $getModel = false);

    /**
     * @param string|null $group
     * @param bool $getModel If true returns Model instead value
     * @return mixed
     */
    public function all(string $group = null, bool $getModel = false);

    /**
     * @param bool $getModel
     * @return mixed
     */
    public function allGrouped(bool $getModel = false);
}
