<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;
/**
 * Description of BasicEntity
 *
 * @author Jose
 */
class BasicEntity {

   private $_bNew;
    
    function __construct() {
       $this->_bNew = true;
   }
    public function isNew() {return $this->_bNew;}

   public function setNew($bNew) {$this->_bNew = $bNew;}
}
