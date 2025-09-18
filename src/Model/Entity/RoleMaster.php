<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class RoleMaster extends Entity
{
    protected array $_accessible = [
        'role_name'     => true,
        'description'   => true,
        'created'       => true,
        'modified'      => true,
        'admin_masters' => true, // for the association
    ];
}

