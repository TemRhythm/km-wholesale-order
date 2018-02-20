<?php
include_once 'soap-model.php';
abstract class Soap_Client_Model extends Soap_Model{
    protected $client;

    function __construct (){
        $this->client=new SoapClient(ONES_SOAP_URL, array(
            'soap_version'=>SOAP_1_2,
            'trace'=>1,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'authentication' => 'SOAP_AUTHENTICATION_BASIC',
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'login' => ONES_SOAP_USERNAME,
            'password' => ONES_SOAP_PASSWORD,
            'classmap' => array(
                'WOrder' => 'Soap_Order',
                'WOrderList' => 'Soap_Order_List',
                'WProduct' => 'Soap_Product'
            )
        ));
    }
}