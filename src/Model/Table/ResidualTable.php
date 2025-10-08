<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class ResidualTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('residuals');
        $this->setPrimaryKey('id');
        $this->setDisplayField('type');
    }
}
?>
