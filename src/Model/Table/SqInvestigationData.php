<?PHP
class SqInvestigationData extends AppModel
{
	public $name = 'SqInvestigationData';
	public $belongsTo = array(
		'SqReportIncident' => array(
		    'className'    => 'SqReportIncident',
		    'foreignKey'   => 'incident_id'
		    
		),
	);
}
?>