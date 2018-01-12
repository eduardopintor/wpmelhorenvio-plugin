<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Created by PhpStorm.
 * User: Melhor Envio
 * Date: 14/12/2017
 * Time: 10:30
 */

function wpmelhorenvio_data_getTracking($id){
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}tracking_codes_wpme where order_id = '{$id}'";
    return $wpdb->get_results($sql);
}

function wpmelhorenvio_data_getAllTrackings(){
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}tracking_codes_wpme where status = 'waiting' limit 1000";
    return $wpdb->get_results($sql);
}

function wpmelhorenvio_data_insertTracking($id,$tracking,$service){
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
    $sql = "INSERT INTO {$wpdb->prefix}tracking_codes_wpme (order_id,tracking_id,service_id,status) VALUES ('{$id}','{$tracking}','{$service}','cart')";
    return $wpdb->query($sql);
}

function wpmelhorenvio_data_deleteTracking($tracking){
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
//    $sql = "DELETE FROM {$wpdb->prefix}tracking_codes_wpme WHERE tracking_id = '{$tracking}'";
    return $wpdb->delete($wpdb->prefix.'tracking_codes_wpme' , array('tracking_id' => $tracking));
}

function wpmelhorenvio_data_updateTracking($tracking,$valor){
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $wpdb;
    $sql = "UPDATE {$wpdb->prefix}tracking_codes_wpme SET status='{$valor}' WHERE tracking_id = '{$tracking}'";
    return $wpdb->query($sql);
}