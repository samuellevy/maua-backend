<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Text;

class FilesTable extends Table
{
  
  public function initialize(array $config)
  {
    parent::initialize($config);
    
    $this->setTable('files');
    $this->setDisplayField('id');
    $this->setPrimaryKey('id');
    
    //die(debug(WWW_ROOT.'uploads'.DS.'files'));
    
    $this->addBehavior('Timestamp');
  }
  
  public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
  {
    if (!empty($data['filename']['name'])) {
      $file = $data['filename'];
      $filename = $file['name'];
      $file_tmp_name = $file['tmp_name'];
      
      $file_type = substr( strrchr( $filename , '.') , 1 );
      $newname = Text::uuid().".".$file_type;
      
      if($file_type=='pdf'){
        $dir = WWW_ROOT.'documents';
      }else{
        $dir = WWW_ROOT.'uploads/files';
      }
      
      if(isset($data['model_id'])){
        $same_items = $this->find('all', ['conditions'=>['entity'=>$data['entity'], 'obs'=>$data['obs'], 'model_id'=>$data['model_id']]]);
        if(empty($same_items->all())){
          
        }
        else{
          foreach($same_items as $item){
            $testimonial = $this->get($item->id);
            $this->delete($testimonial);
          }
          
        }
      }
      
      
      
      $allowed = array('pdf', 'png', 'jpg', 'jpeg');
      if ( !in_array( substr( strrchr( $filename , '.') , 1 ) , $allowed) ) {
        throw new InternalErrorException("Error Processing Request.", 1);
      }elseif( is_uploaded_file( $file_tmp_name ) ){
        move_uploaded_file($file_tmp_name, $dir.DS.$newname);
      }
      
      $data['filename'] = $newname;
    }
  }
  
  public function validationDefault(Validator $validator)
  {
    $validator
    ->integer('id')
    ->allowEmpty('id', 'create');
    
    $validator
    ->allowEmpty('entity');
    
    $validator
    ->allowEmpty('filename');
    
    $validator
    ->allowEmpty('type');
    
    return $validator;
  }
  
  public function beforeDelete(Event $event)
  {
    $entity = $event->getData()['entity'];
    //die(debug($entity));
    $dir = WWW_ROOT.'uploads'.DS.'files';
    if($entity['filename'] != ''){
      unlink($dir.DS.$entity['filename']);
    }
    return true;
  }
  
  public function removeFile(){
    
  }
}
