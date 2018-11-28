<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

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
            $ranking = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'contain'=>['ComercialStores', 'Sales']])->toArray();
        }else{
            $ranking = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'limit'=>$limit, 'contain'=>['ComercialStores', 'Sales']])->toArray();
        }

        return $ranking;
    }

    public function store_ranking($store_id=null){
        $my_store = $this->find('all',['conditions'=>['id'=>$store_id]])->first();
        $category = $my_store->category;
        $stores = $this->find('all',['conditions'=>['category'=>$category], 'order'=>'total DESC', 'contain'=>'ComercialStores'])->toArray();
        $position = 0;

        foreach($stores as $key=>$store){
            if($store->id == $my_store->id){
                $position = $key+1;
            }
        }
        // die(debug($position));
        return $position;
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

    public function getAllRanking($category){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT *, ROUND(((sales.quantity * 100)/sales.goal),0) as percentage from sales
            JOIN stores ON stores.id=sales.store_id 
            where stores.category = '$category' and sales.month = 10
            GROUP BY sales.id ORDER BY total DESC, percentage DESC"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }
        return $results;
    }

    public function getComercialStores(){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT comercial_stores.store_id, comercial_stores.user_id from comercial_stores"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }
        return $results;
    }

    public function getMyRanking($category, $store_id){
        $position = 0;
        $connection = ConnectionManager::get('default');
        $month = 10;
        $results = $connection->execute(
            "SELECT id, name, case when total is null then 0 else total end as total, percentage from (SELECT stores.id as id, stores.name, ROUND(((sales.quantity * 100)/sales.goal),2) as percentage from sales
            LEFT JOIN stores ON stores.id=sales.store_id 
            where sales.month = $month AND stores.category='$category'
            GROUP BY sales.id ORDER BY total DESC, percentage DESC) as table1
            
            LEFT JOIN (SELECT stores.id as store_id, sum(points.point) as total from stores
            join points on stores.id = points.store_id
            where points.month = $month
            GROUP BY stores.id
            ORDER BY total DESC) as table2 on store_id = id
            ORDER BY total DESC, percentage DESC"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }

            $store_properties = null;

            foreach($results as $key=>$result){
                $position = $key+1;
                $results[$key]['position']=$position;
                $result['position']=$position;
                if($result['id']==$store_id){
                    $store_properties = $result;
                    break;
                }
            }

        return $store_properties;
    }
    
    /** As chamadas anteriores estão sendo usadas pelo app, por isso as novas chamadas usaremos o prefixo FETCH no lugar de GET */
    # utilizando no app
    public function fetchRankingByMonth($category=null, $month=null){
        $categories = $this->fetchCategories();
        $months = $this->fetchMonths();
        
        $results = [];

        $month = 10;
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT id, name, total, percentage from (SELECT stores.id as id, stores.name, ROUND(((sales.quantity * 100)/sales.goal),2) as percentage from sales
            JOIN stores ON stores.id=sales.store_id 
            where sales.month = $month AND stores.category='$category'
            GROUP BY sales.id ORDER BY total DESC, percentage DESC) as table1
            
            JOIN (SELECT stores.id as store_id, sum(points.point) as total from stores
            join points on stores.id = points.store_id
            where points.month = $month
            GROUP BY stores.id
            ORDER BY total DESC) as table2 on store_id = id
            ORDER BY total DESC, percentage DESC"
            )->fetchAll('assoc');

        return $results;
        // die(debug($results));
    }

    public function fetchRankingBy($category=null){
        $categories = $this->fetchCategories();
        $months = $this->fetchMonths();
        
        $results = [];

        foreach($months as $month){
            $month = $month['name'];
            $connection = ConnectionManager::get('default');
            $results[$month] = $connection->execute(
                "SELECT id, name, sum_points, percentage from (SELECT stores.id as id, stores.name, ROUND(((sales.quantity * 100)/sales.goal),2) as percentage from sales
                JOIN stores ON stores.id=sales.store_id 
                where sales.month = $month AND stores.category='$category'
                GROUP BY sales.id ORDER BY total DESC, percentage DESC) as table1
                
                JOIN (SELECT stores.id as store_id, sum(points.point) as sum_points from stores
                join points on stores.id = points.store_id
                where points.month = $month
                GROUP BY stores.id
                ORDER BY sum_points DESC) as table2 on store_id = id
                ORDER BY sum_points DESC, percentage DESC"
                )->fetchAll('assoc');
        }

        return $results;
        // die(debug($results));
    }


    public function getTotalRanking($category){
        $month = date('m');
        
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT *, ROUND(((sales.quantity * 100)/sales.goal),0) as percentage from sales
            JOIN stores ON stores.id=sales.store_id 
            where sales.month = 10
            GROUP BY sales.id ORDER BY total DESC, percentage DESC"
            )->fetchAll('assoc');

        return $results;
    }


    public function getPoints(){
        $month = date('m');
        
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT stores.id, stores.name, sum(points.point) as sum_points from stores
            join points on stores.id = points.store_id
            where points.month = 10
            GROUP BY stores.id, stores.name
            ORDER BY sum_points DESC"
            )->fetchAll('assoc');

        return $results;
    }

    public function fetchCategories(){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT DISTINCT category as name FROM stores where stores.category IS NOT NULL"
            )->fetchAll('assoc');

        return $results;
    }

    public function fetchMonths(){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT DISTINCT month as name FROM sales"
            )->fetchAll('assoc');

        return $results;
    }

    public function fetchGeneralRanking($category){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT id, name, quantity, goal, percentage, total FROM 
            (SELECT store_id as id, stores.name, sum(quantity) as quantity, sum(goal) as goal, ROUND(((sum(quantity) * 100)/sum(goal)),2) as percentage FROM  sales
            JOIN stores on store_id = stores.id where stores.category = '$category' group by store_id) as table1 
            JOIN (SELECT stores.id as point_id, sum(points.point) as total FROM stores
            JOIN points on stores.id = points.store_id
            GROUP BY stores.id
            ORDER BY store_id ASC) as table2 ON id=point_id
            ORDER by total DESC, percentage DESC"
            )->fetchAll('assoc');

        return $results;
    }
}
