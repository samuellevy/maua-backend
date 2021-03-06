<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Courses Model
 *
 * @property \App\Model\Table\QuestionsTable|\Cake\ORM\Association\HasMany $Questions
 * @property \App\Model\Table\QuizTable|\Cake\ORM\Association\HasMany $Quiz
 *
 * @method \App\Model\Entity\Course get($primaryKey, $options = [])
 * @method \App\Model\Entity\Course newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Course[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Course|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Course|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Course patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Course[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Course findOrCreate($search, callable $callback = null, $options = [])
 */
class CoursesTable extends Table
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

        $this->setTable('courses');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->hasMany('Questions', [
            'foreignKey' => 'course_id'
        ]);
        
        $this->hasMany('Quiz', [
            'foreignKey' => 'course_id'
        ]);

        $this->hasOne('CourseProgress', [
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmpty('title');

        $validator
            ->scalar('subtitle')
            ->maxLength('subtitle', 255)
            ->allowEmpty('subtitle');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('video_url')
            ->maxLength('video_url', 255)
            ->allowEmpty('video_url');

        $validator
            ->integer('active')
            ->allowEmpty('active');

        $validator
            ->integer('right')
            ->allowEmpty('right');

        return $validator;
    }
}
