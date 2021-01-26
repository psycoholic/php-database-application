<?php 
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;
// add this use statement right below the namespace declaration to import
// the Validator class
use Cake\Validation\Validator;
use Cake\ORM\Table;

class ArticlesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)
    
            ->notEmptyString('body')
            ->minLength('body', 10);
    
        return $validator;
    }
}