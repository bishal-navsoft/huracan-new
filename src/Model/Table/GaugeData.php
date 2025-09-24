<?PHP
class GaugeData extends AppModel
{
	public $name = 'GaugeData';
	public $belongsTo = array(
		  'TecCable' => array(
			    'className'    => 'TecCable',
			    'foreignKey'   => 'tec_cable'
			    
			),
		    'YSplitter' => array(
		           'className'    => 'YSplitter',
		           'foreignKey'   => 'ysplitre'
		    
		       ),
		    'TempRange' => array(
		    	'className'    => 'TempRange',
			'foreignKey'   => 'temp_range'
		    
		      ),
		     'PressRange' => array(
		    	'className'    => 'PressRange',
			'foreignKey'   => 'press_range'
		    
		      ),
		       'WhoConnector' => array(
		    	'className'    => 'WhoConnector',
			'foreignKey'   => 'who_connector'
		    
		      ),
		        'Sau' => array(
		    	'className'    => 'Sau',
			'foreignKey'   => 'sau'
		    
		      ),
			'GaugeType' => array(
		    	'className'    => 'GaugeType',
			'foreignKey'   => 'gauge_type'
		    
		      ),
			'Manufacture' => array(
		    	'className'    => 'Manufacture',
			'foreignKey'   => 'manufacture'
		    
		      )
		    
		);
	
}
?>
