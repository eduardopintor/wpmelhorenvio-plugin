<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Created by PhpStorm.
 * User: VHSoa
 * Date: 29/11/2017
 * Time: 09:26
 */

include_once ABSPATH.WPINC.'/option.php';

//Função de update dos dados do usuário
function wpmelhorenvio_updateUserData($token)
{
    $params = array('headers'=>['Content-Type' => 'application/json','Accept'=>'application/json','Authorization' => 'Bearer '.$token]);
    $client = new WP_Http();
    $response = $client->get('https://www.melhorenvio.com.br/api/v2/me',$params);
    if(! $response instanceof WP_Error){
        if($response['response']['code'] == "200"){
            updateOptionalData(json_decode($response['body']));
            update_option('wpme_token',$token);
            return true;
        }
    }
    clearOptionalData();
    return false;
}

function wpmelhorenvio_updateOptionalData($api_response)
{
    update_option('wpme_id',$api_response->id);
    update_option('wpme_firstname',$api_response->firstname);
    update_option('wpme_lastname',$api_response->lastname);
    update_option('wpme_email',$api_response->email);
    update_option('wpme_picture',$api_response->picture);
    update_option('wpme_document',$api_response->document);
    update_option('wpme_phone',$api_response->phone->phone);
}

function wpmelhorenvio_clearOptionalData(){
    update_option('wpme_token',"");
    update_option('wpme_id',"");
    update_option('wpme_firstname',"");
    update_option('wpme_lastname',"");
    update_option('wpme_email',"");
    update_option('wpme_picture',"");
    update_option('wpme_document',"");
    update_option('wpme_phone',"");
}

function wpmelhorenvio_getApiAddresses(){
    $token = get_option('wpme_token');
    $params = array('headers'=>['Content-Type' => 'application/json','Accept'=>'application/json','Authorization' => 'Bearer '.$token]);
    $client = new WP_Http();
    $response = $client->get('https://www.melhorenvio.com.br/api/v2/me/addresses',$params);
    if( $response instanceof WP_Error){
        return false;
    }else{
        return (array) json_decode($response['body']);
    }
}

function wpmelhorenvio_getApiCompanies(){
    $token = get_option('wpme_token');
    $params = array('headers'=>['Content-Type' => 'application/json','Accept'=>'application/json','Authorization' => 'Bearer '.$token]);
    $client = new WP_Http();
    $response = $client->get('https://www.melhorenvio.com.br/api/v2/me/companies',$params);
    if( $response instanceof WP_Error){
        return false;
    }else{
        return (array) json_decode($response['body']);
    }
}

function wpmelhorenvio_getApiCompanyAdresses(){
    $companies = getApiCompanies();
    $address = array();
    $token = get_option('wpme_token');
    $params = array('headers'=>['Content-Type' => 'application/json','Accept'=>'application/json','Authorization' => 'Bearer '.$token]);
    $client = new WP_Http();
    foreach ($companies['data'] as $company){
        $response = $client->get('https://www.melhorenvio.com.br/api/v2/me/companies/'.$company->id.'/addresses',$params);
        if( $response instanceof WP_Error){
            return false;
        }else{
            $resposta = json_decode($response['body']);
            foreach ($resposta->data as $endereco){
                $endereco->label = $endereco->label.' ( '.$company->name.' ) ';
                array_push($address,$endereco);
            }
        }
    }
    return $address;

}

function wpmelhorenvio_getApiShippingServices(){
    $token = get_option('wpme_token');
    $params = array('headers'=>['Content-Type' => 'application/json','Accept'=>'application/json','Authorization' => 'Bearer '.$token]);
    $client = new WP_Http();
    $response = $client->get('https://www.melhorenvio.com.br/api/v2/me/shipment/services',$params);
    if( $response instanceof WP_Error){
        return false;
    }else{
        return (array) json_decode($response['body']);
    }
}

function defineConfig($address,$services,$pluginconfig){
    if(
       update_option('wpme_address',$address)
    | update_option('wpme_services',$services)
    | update_option('wpme_pluginconfig',$pluginconfig)
    ){
        return true;
    }
    return false;
}

function getAgencies($country,$state,$city = null){
    $token = get_option('wpme_token');
    if($city != null){
    $params = array(
        'headers'=>[
            'Content-Type' => 'application/json',
            'Accept'=>'application/json',
            'Authorization' => 'Bearer '.$token
        ],
        'body' => [
            'coutry'    => $country,
            'state'     => $state,
            'city'      => $city
        ]);
    }else{
        $params = array(
            'headers'=>[
                'Content-Type' => 'application/json',
                'Accept'=>'application/json',
                'Authorization' => 'Bearer '.$token
            ],
            'body' => [
                'coutry'    => $country,
                'state'     => $state,
            ]);
    }
    $client = new WP_Http();
    $response = $client->get('https://melhorenvio.com.br/api/v2/me/shipment/agencies',$params);
    if( $response instanceof WP_Error){
        return false;
    }else{
        return (array) json_decode($response['body']);
    }
}

function verifyLogin(){

}