<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stores Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Store get($primaryKey, $options = [])
 * @method \App\Model\Entity\Store newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Store[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Store|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Store patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Store[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Store findOrCreate($search, callable $callback = null, $options = [])
 */
class StoresTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('stores');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Users', [
            'foreignKey' => 'store_id'
        ]);

        $this->hasMany('Sales', [
            'foreignKey' => 'store_id'
        ]);

        $this->hasMany('Points', [
            'foreignKey' => 'store_id'
        ]);

        $this->hasOne('ComercialStores',[
            'foreignKey' => 'store_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmpty('name');

        $validator
            ->scalar('category')
            ->maxLength('category', 11)
            ->allowEmpty('category');

        return $validator;
    }

    public function ranking($user_id=null, $category=null, $limit=null){
        if($limit==false){
            $ranking = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'contain'=>'ComercialStores'])->toArray();
        }else{
            $ranking = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'limit'=>$limit, 'contain'=>'ComercialStores'])->toArray();
        }

        return $ranking;
    }

    public function count_stores($category=null, $type=null){
        if($type == null){
            $total = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'contain'=>'ComercialStores'])->all();
            $total = count($total);
        }
        elseif($type=='enabled'){
            $total = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'contain'=>['ComercialStores', 'Users']])->all()->toArray();
            
            foreach($total as $key=>$store){
                // die(debug($total[$key]['users'][0]['first_access']));
                if(isset($total[$key]['users'][0])){
                    if($total[$key]['users'][0]['first_access']){
                        unset($total[$key]);
                    }
                }else{
                    unset($total[$key]);
                }
            }
            $total = count($total);
        }
        return $total;
    }
}
