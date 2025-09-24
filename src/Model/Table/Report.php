<?PHP
class Report extends AppModel
{
	public $name = 'Report';
	//public $useTable = 'contacts' ;
	 public $belongsTo = array(
        'IncidentSeverity' => array(
            'className'    => 'IncidentSeverity',
            'foreignKey'   => 'incident_severity'
        ),
	'Client' => array(
            'className'    => 'Client',
            'foreignKey'   => 'client'
        ),
	'AdminMaster' => array(
            'className'    => 'AdminMaster',
            'foreignKey'   => 'created_by'
        )
);
	public $hasMany = array(
        'HsseRemidial' => array(
            'className'    => 'HsseRemidial',
            'foreignKey'   => 'report_no'
        )
    );

}
?>
