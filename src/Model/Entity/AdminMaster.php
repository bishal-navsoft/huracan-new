<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class AdminMaster extends Entity
{
    // // Allow mass assignment for these fields
    // protected array $_accessible = [
    //     'admin_user'       => true,
    //     'admin_pass'       => true,
    //     'admin_email'          => true,
    //     'role_master_id' => true,
    //     'created'        => true,
    //     'modified'       => true,
    //     'role_master'    => true, // for the association
    // ];

    // // Hide sensitive fields from JSON/array output
    // protected array $_hidden = [
    //     'password',
    // ];

     // Allow mass assignment for these fields
    protected $_accessible = [
        'admin_user'     => true,
        'admin_pass'     => true,
        'admin_email'    => true,
        'role_master_id' => true,
        'created'        => true,
        'modified'       => true,
        'role_master'    => true, // for the association
    ];

    // Hide sensitive fields from JSON/array output
    protected $_hidden = [
        'password',
    ];
}

