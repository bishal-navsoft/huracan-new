<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity
 *
 * @property int $id
 * @property string $report_no
 * @property string|null $client
 * @property \Cake\I18n\FrozenDate|null $event_date
 * @property string|null $summary
 * @property int|null $created_by
 * @property string $isdeleted
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Report extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'report_no' => true,
        'client' => true,
        'event_date' => true,
        'summary' => true,
        'created_by' => true,
        'isdeleted' => true,
        'created' => true,
        'modified' => true,
    ];
}
