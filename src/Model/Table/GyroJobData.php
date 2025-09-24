<?PHP
class GyroJobData extends AppModel
{
	public $name = 'GyroJobData';
	public $belongsTo = array(
		  'Conveyance' => array(
			    'className'    => 'Conveyance',
			    'foreignKey'   => 'conveyance'
			    
			),
		    'Conveyed' => array(
		           'className'    => 'Conveyed',
		           'foreignKey'   => 'conveyance_by'
		    
		       ),
		    'ConveyanceType' => array(
		    	'className'    => 'ConveyanceType',
			'foreignKey'   => 'conveyance_type'
		    
		      ),
		     'GyroSn' => array(
		    	'className'    => 'GyroSn',
			'foreignKey'   => 'gyro_sn'
		    
		      )
		    
		);
	
}
?>
