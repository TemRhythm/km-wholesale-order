<?php

class Soap_Product
{
    public $Sku;
    public $Price;
    public $Quantity;
    public function getTotal(){
        return $this->Price * $this->Quantity;
    }
}