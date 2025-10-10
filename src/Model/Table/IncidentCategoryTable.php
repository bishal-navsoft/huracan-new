<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class IncidentCategoryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('incident_categories');
        $this->setPrimaryKey('id');
        $this->setDisplayField('type');
    }
	
}
?>
