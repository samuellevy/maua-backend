<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * CourseProgress Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\BelongsTo $Courses
 *
 * @method \App\Model\Entity\CourseProgres get($primaryKey, $options = [])
 * @method \App\Model\Entity\CourseProgres newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CourseProgres[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CourseProgres|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CourseProgres|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CourseProgres patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CourseProgres[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CourseProgres findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CourseProgressTable extends Table
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

        $this->setTable('course_progress');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id'
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
            ->integer('progress')
            ->allowEmpty('progress');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['course_id'], 'Courses'));

        return $rules;
    }

    public function getResults($course_id=null,$store_id=null){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT cp.*, users.name, users.store_id, stores.name as store_name
                FROM course_progress as cp
                RIGHT JOIN courses ON course_id = $course_id
                JOIN users ON cp.user_id=users.id
                JOIN stores ON users.store_id=stores.id 
            
                WHERE users.store_id=$store_id AND users.active=1 AND users.role_id = 6
            GROUP BY cp.id"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }
        return $results;
    }
}
