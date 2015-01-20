<?php
class AutoController extends AppController
{
   public function index() {
      $this->autoRender = false; 
   } 
   public function canvans()  {
       $this->autoRender = false;
       $this->redirect('http://muauto.5stars.vn');
   }
}
