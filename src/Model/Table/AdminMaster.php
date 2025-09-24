<?PHP
class AdminMaster extends AppModel
{
	public $name = 'AdminMaster';
	public $belongsTo = 'RoleMaster';
	
	public $hasMany = array(
		'SqReportMain' => array(
		    'className'    => 'SqReportMain',
		    'foreignKey'   => 'created_by',
		     'conditions'   => array('isdeleted'=>'N')
		    
		),
		'Report' => array(
		    'className'    => 'Report',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'JnReportMain' => array(
		    'className'    => 'JnReportMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'AuditReportMain' => array(
		    'className'    => 'AuditReportMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'JobReportMain' => array(
		    'className'    => 'JobReportMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'LessonMain' => array(
		    'className'    => 'LessonMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'DocumentMain' => array(
		    'className'    => 'DocumentMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'SuggestionMain' => array(
		    'className'    => 'SuggestionMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		),
		'JhaMain' => array(
		    'className'    => 'JhaMain',
		    'foreignKey'   => 'created_by',
		    'conditions'   => array('isdeleted'=>'N')
		    
		)
		
	);
}
?>