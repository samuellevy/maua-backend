<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\BelongsTo $Stores
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id'
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id'
        ]);
        $this->hasMany('CourseProgress', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('ComercialStores', [
            'className'=>'ComercialStores',
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('ComercialStoresAmarelo', [
            'className'=>'ComercialStores',
            'foreignKey' => 'user_id',
            'conditions'=>[
                'Stores.category'=>'p'
            ]
        ]);
        $this->hasMany('ComercialStoresVerde', [
            'className'=>'ComercialStores',
            'foreignKey' => 'user_id',
            'conditions'=>[
                'Stores.category'=>'m'
            ]
        ]);
        $this->hasMany('ComercialStoresPreto', [
            'className'=>'ComercialStores',
            'foreignKey' => 'user_id',
            'conditions'=>[
                'Stores.category'=>'g'
            ]
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
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->allowEmpty('password');

        $validator
          ->add(
            'confirm_password',
            'compareWith', [
              'rule' => ['compareWith', 'password'],
              'message' => 'Passwords not equal.'
            ]
          )
          ->allowEmpty('confirm_password');


        $validator
            ->boolean('active')
            ->allowEmpty('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));

        return $rules;
    }
}
