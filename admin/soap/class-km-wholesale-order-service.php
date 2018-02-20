<?php
include_once 'soap-abstracts/soap-client-model.php';
include_once 'soap_models/soap-order-list.php';
include_once 'soap_models/soap-order.php';
include_once 'soap_models/soap-product.php';
class Class_Km_Wholesale_Order_Service extends Soap_Client_Model
{
    public function getOrders(){
        $result = [];
        foreach ($this->client->GetWOrderList()->return->WOrder as $order){
            foreach ($order->enc_value->WProduct as $key => $product){
                if($product->Sku === null)
                    unset($order->enc_value->WProduct[$key]);
            }
            array_push($result, $order->enc_value);
        }
        return $result;
    }

    public function getOrder($order_id){
        foreach ($this->getOrders() as $order){
            if($order->Id === $order_id)
                return $order;
        }
    }

    public function saveOrder($order){
        try {
            return $this->client->SaveWOrder(['WOrder' => $order]);
        }
        catch (Exception $e){
            $req = $this->client->__getLastRequest();
            $reqHeaders = $this->client->__getLastRequestHeaders();
        }
    }
}