<?php
abstract class Soap_Model {
    public function get_as_soap() {
        foreach($this as $key=>&$value) {
            $this->prepare_soap_recursive($this->$key);
        }
        return $this;
    }

    private function prepare_soap_recursive(&$element) {
        if(is_array($element)) {
            foreach($element as $key=>&$val) {
                $this->prepare_soap_recursive($val);
            }
            $element=new SoapVar($element,SOAP_ENC_ARRAY);
        }elseif(is_object($element)) {
            if($element instanceof model) {
                $element->get_as_soap();
            }
            $element=new SoapVar($element,SOAP_ENC_OBJECT);
        }
    }
}