<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class CountryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('countries');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');
    }
}
?>
