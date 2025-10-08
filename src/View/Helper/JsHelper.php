<?php
namespace App\View\Helper;

use Cake\View\Helper;

class JsHelper extends Helper
{
    public function object($array)
    {
        return json_encode($array);
    }
}
