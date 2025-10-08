<?php
namespace App\View\Helper;

use Cake\View\Helper;

class WebrootHelper extends Helper
{
    /**
     * Get full webroot path for a file
     *
     * @param string $file File path relative to webroot
     * @return string
     */
    public function getPath($file = '')
    {
        return $this->getView()->getRequest()->getAttribute('webroot') . $file;
    }

    /**
     * Optional alias for old CakePHP 2 `$this->webroot`
     */
    public function webroot($file = '')
    {
        return $this->getPath($file);
    }
}
