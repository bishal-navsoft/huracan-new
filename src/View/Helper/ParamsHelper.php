<?php
namespace App\View\Helper;

use Cake\View\Helper;

class ParamsHelper extends Helper
{
    public function get($key = null, $default = null)
    {
        $request = $this->getView()->getRequest();
        
        if ($key === null) {
            return $request->getQueryParams();
        }
        
        return $request->getQuery($key, $default);
    }
    
    public function pass($index = null)
    {
        $request = $this->getView()->getRequest();
        $pass = $request->getParam('pass', []);
        
        if ($index === null) {
            return $pass;
        }
        
        return isset($pass[$index]) ? $pass[$index] : null;
    }
}
