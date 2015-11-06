<?php

class Default_Form_Admin extends Core_Form_Form {
    
    public function init() {
        
        $obj = new Application_Model_DbTable_Admin();
        $primaryKey = $obj->getPrimaryKey();
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setAttrib('id', $primaryKey);
        $this->setAction('/admin/save');
        
//        $e = new Zend_Form_Element_Hidden($primaryKey);
//        $e = new Zend_Form_Element_Hidden('admin');
//        $this->addElement($e);
        
        $e = new Zend_Form_Element_Hidden('picture');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('name');
        $e->setAttrib('class','form-control input-sm');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('lastnamepaternal');
        $e->setAttrib('class','form-control input-sm');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('lastnamematernal');
        $e->setAttrib('class','form-control input-sm');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('phone');
        $e->setAttrib('class','form-control input-sm');
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('email');
        $e->setAttrib('readonly', 'true');
        $e->setAttrib('class','form-control input-sm');
        $this->addElement($e);
        
        foreach ($this->getElements() as $element) {
            $element->removeDecorator('Label');
            $element->removeDecorator('DtDdWrapper');
            $element->removeDecorator('HtmlTag');
        }
    }

    public function populate(array $values) {
        if (isset($values['flagAct']))
            $values['flagAct'] = $values['flagAct'] == 1 ? 1 : 0;

        parent::populate($values);
    }

}
