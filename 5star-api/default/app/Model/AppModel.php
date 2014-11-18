<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {       
	   
	   public $defaultOptions = array(
		   'limit' => 30,
		   'offset' => 0
	   );
	   
	   public function beforeValidate() {
		   foreach(array('modified', 'created', 'updated') as $field) {
			unset($this->data[$this->name][$field]);
		   }
	   }
	   
	   public function unbindAll($reset = true) {
		   $this->unbindAllExcept('', $reset);
	   }
	   
	   public function deleteAll($conditions, $cascade = true, $callbacks = true) {
		   parent::deleteAll($conditions, $cascade, $callbacks);
	   }
	   
	   public function unbindAllExcept($relations, $reset = true) {
		   if(!is_array($relations)) {
			   $relations = array($relations);
		   }
		$toUnbind = array();
		foreach(array('hasMany', 'belongsTo', 'hasOne', 'hasAndBelongsToMany') as $assoc) {
			if(property_exists($this, $assoc)) {
				$toUnbind[$assoc] = array();
				foreach ($this->{$assoc} as $key => $val) {
					$toUnbind[$assoc] []= $key;
				}
			}
		}
		   $this->unbindModel($toUnbind, $reset);
	   }
	   
	   public function buildOptions($data) {
		   $options = array('conditions' => $this->buildConditions($data));
		   if(isset($data['limit']) && $data['limit'] > 0) {
			   $options['limit'] = (int) $data['limit'];
		   } else {
			   $options['limit'] = $this->defaultOptions['limit'];
		   }
		   if(isset($data['offset']) && $data['limit'] > -1) {
			   $options['offset'] = (int) $data['offset'];
		   } else {
			   $options['offset'] = $this->defaultOptions['offset'];
		   }
		   return $options;
	   }
	   
	   public function buildConditions($data) {
		   $conditions = array();
		   foreach ($data as $param => $value) {
			   if($this->hasField($param, true)) {
				   $conditions[$this->name . '.' . $param] = $value;
			   }
		   }
		   return $conditions;
	   }
}
