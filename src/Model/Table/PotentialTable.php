<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class PotentialTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('potentials');
        $this->setPrimaryKey('id');
        $this->setDisplayField('type');
    }
}
?>
