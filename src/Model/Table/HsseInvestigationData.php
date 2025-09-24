<?PHP
class HsseInvestigationData extends AppModel
{
	public $name = 'HsseInvestigationData';
	public $belongsTo = array(
		'HsseIncident' => array(
		    'className'    => 'HsseIncident',
		    'foreignKey'   => 'incident_id'
		    
		),
	);
	
	
}
?>