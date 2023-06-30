<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-wpstream-player
 *
 * @author cretu
 */
class Wpstream_Player{
    public function __construct($plugin_main) {
        $this->main = $plugin_main;
          
        add_filter( 'the_content',array($this, 'wpstream_filter_the_title') );
        add_action( 'woocommerce_before_single_product', array($this,'wpstream_user_logged_in_product_already_bought') );
        
        add_action( 'wp_ajax_wpstream_player_check_status', array($this,'wpstream_player_check_status') );  
        add_action('wp_ajax_nopriv_wpstream_player_check_status', array($this,'wpstream_player_check_status'));
     
        
   
        
        
    }
    
    
        
  
    
        
    /**
     * 
     * edited 4.0
     * 
    * check player status
    *
    * @author cretu
    */
    
    public function wpstream_player_check_status(){
        $channel_id                   =   intval($_POST['channel_id']);
     
        
        $transient_name             =   'event_data_to_return_'.   $channel_id;
        $event_data_for_transient   =   get_transient( $transient_name );
       
    

        if ( false ===  $event_data_for_transient || $event_data_for_transient=='' ) { //ws || $hls_to_return==''        
            $notes                      =   'wpstream_player_check_status_note_from_js';   
            $event_status               =   $this->main->wpstream_live_connection-> wpstream_check_event_status_api_call($channel_id,$notes);
            $event_data_for_transient   =   $event_status;

            set_transient($transient_name,$event_data_for_transient,45);
        }
        
        
        if( isset($event_data_for_transient['hls_playback_url']) && $event_data_for_transient['hls_playback_url']!=''){
            echo json_encode(   array(
               
                    'started'               =>  'yes',
                    'channel_id'            =>  $channel_id,
                    'event_uri'             =>  $event_data_for_transient['hls_playback_url'],
                    'live_conect_views'     =>  $event_data_for_transient['stats_url'],
                    'chat_url'              =>  $event_data_for_transient['chat_url'],
                    '$event_data_for_transient'=>$event_data_for_transient
                 
                                   
            ));
            update_post_meta($channel_id,'stream_name',$event_data_for_transient['stream_name']);
            update_post_meta($channel_id,'hls_key_retrieval_url',$event_data_for_transient['hls_key_retrieval_url']);
            delete_transient(  'free_event_streamName_'.$event_data_for_transient['stream_name']);

        }else{
            echo json_encode(   array(
                    'started'               =>  'no',
                    'server_id'             =>  '',
                    'channel_id'              =>  $channel_id,
                    'event_uri'             =>  '',
                    'live_conect_views'     =>  '',
                    'chat_url'              =>  '',
                                   
            ));
       
        }
        
        die();
    }
    
    
    
    /**
    * Insert player in page
    *
    * @author cretu
    */
    public function wpstream_filter_the_title( $content   ) {
            if(function_exists('remove_wpstream_filter')){
                return $content;
            }

            if( is_singular('wpstream_product') || is_singular('wpstream_product_vod') ){
                global $post;
                $args=array('id'=>$post->ID);
                $custom_content = $this->wpstream_insert_player_inpage($args);
                $content = '<div class="wpestream_inserted_player">'.$custom_content.'</div>'.$content;
                return $content;
            }else{
                return $content;
            }
    }
    
    /**
    * Insert player in page
    *
    * @author cretu
    */

    public function wpstream_insert_player_inpage($attributes, $content = null){
        $product_id     =   '';
        $return_string  =   '';
        $attributes =   shortcode_atts( 
            array(
                'id'                       => 0,
            ), $attributes) ;


        if ( isset($attributes['id']) ){
            $product_id=$attributes['id'];
        }
         
        if(intval($product_id)==0){
            $product_id= $this->wpstream_player_retrive_first_id();
        }

        ob_start();
     
        $this->wpstream_video_player_shortcode($product_id);
        $return_string= ob_get_contents();
        ob_end_clean(); 

        return $return_string;
    }

    
    
    
    /**
    * Video Player shortcode
    *
    * @author cretu
    */

    public function wpstream_video_player_shortcode($from_sh_id='') {

 

        if ( is_user_logged_in() ) {
            global $product;
            $current_user   =   wp_get_current_user();
            $product_id     =   intval($from_sh_id);
            $term_list      =   wp_get_post_terms($product_id, 'product_type');
            
         



            if ( ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ) 
            || get_post_type($product_id)=='wpstream_product' || get_post_type($product_id)=='wpstream_product_vod' ){
                global $product;
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode"><div class="wpstream_player_container">';


                if( get_post_type($product_id) == 'wpstream_product' ){
                    $this->wpstream_live_event_player($product_id);
                }else if( get_post_type($product_id) == 'wpstream_product_vod' ){
                    $this->wpstream_video_on_demand_player($product_id);
                }else{
               
                    $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));

                    if( $term_list[0]->name=='live_stream' || ( $term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' ) ){
                        $this->wpstream_live_event_player($product_id);
                    }else if( $term_list[0]->name=='video_on_demand'  || ($term_list[0]->name=='subscription' && $is_subscription_live_event=='no' ) ){
                        $this->wpstream_video_on_demand_player($product_id);
                    }
                }


                echo '</div></div>';
            }else{
                
                if( get_post_type($product_id) == 'product' ){
                    echo '<div class="wpstream_player_wrapper wpstream_player_shortcode no_buy"><div class="wpstream_player_container">';
                    $message =esc_html( get_option('wpstream_product_not_bought','You did not yet purchase this item.')) ;
                    echo '<div class="wpstream_notice" style="background:#e16767;">'.esc_html($message).'</div>';
                    echo '</div></div>';
                }
            }

         
        }else{
            
            $product_id     =   intval($from_sh_id);
            $term_list      =   wp_get_post_terms($product_id, 'product_type');
            
            if( get_post_type($product_id) == 'product' && ($term_list[0]->name=='live_stream' || $term_list[0]->name=='video_on_demand') ){
                
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode no_buy"><div class="wpstream_player_container">';
                $message= esc_html( get_option('wpstream_product_not_login','You must be logged in to watch this video.')) ;


                echo '<div class="wpstream_notice" style="background:#e16767;">'.esc_html($message).'</div>';
                echo '</div></div>';
            }elseif( get_post_type($product_id) == 'wpstream_product' ){
                $this->wpstream_live_event_player($product_id);                    
            } else if( get_post_type($product_id) == 'wpstream_product_vod' ){
                $this->wpstream_video_on_demand_player($product_id);
            }
        }
    }

    
    
    /**
    * Video Player shortcode - low latency
    *
    * @author cretu
    */

    public function wpstream_video_player_shortcode_low_latency($from_sh_id='') {

        if ( is_user_logged_in() ) {
            global $product;
            $current_user   =   wp_get_current_user();
            $product_id     =   intval($from_sh_id);


            if ( ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ) || get_post_type($product_id)=='wpstream_product' ){
                global $product;
                echo '<div class="wpstream_player_wrapper wpstream_player_shortcode"><div class="wpstream_player_container">';


                if( get_post_type($product_id) == 'wpstream_product' ){
                    $this->wpstream_live_event_player_low_latency($product_id);               
                }else{
                    $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                    $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));

                    if( $term_list[0]->name=='live_stream' || ( $term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' ) ){
                        $this->wpstream_live_event_player_low_latency($product_id);
                    }
                }


                echo '</div></div>';
            }else{
                
                if( get_post_type($product_id) == 'product' ){
                    echo '<div class="wpstream_player_wrapper wpstream_player_shortcode no_buy"><div class="wpstream_player_container">';
              
                    $message =esc_html( get_option('wpstream_product_not_bought','You did not yet purchase this item.')) ;
                    echo '<div class="wpstream_notice" style="background:#e16767;">'.$message.'</div>';
                    echo '</div></div>';
                }
            }

         
        }else{
            $product_id     =   intval($from_sh_id);
            if( get_post_type($product_id) == 'wpstream_product' ){
                $this->wpstream_live_event_player_low_latency($product_id);                  
            }
        }
    }

    
    
    
    
    /**
    * Live Event Player
    *
    * @author cretu
    */
    function remove_http($url) {
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
           if(strpos($url, $d) === 0) {
              return str_replace($d, '', $url);
           }
        }
        return $url;
    }
      
    /**
    * Get event settings
    *
    * @author cretu
    */
    
    function wpestream_return_event_settings($product_id){
            
        $local_event_options =   get_post_meta($product_id,'local_event_options',true);
        if(!is_array($local_event_options)){
            $local_event_options =   get_option('wpstream_user_streaming_global_channel_options') ;
        }
        
        return $local_event_options;
    }
    
    
    
    /**
     * edited in 4.0
     * 
    * Live Event Player
    *
    * @author cretu
    */
    
    function wpstream_live_event_player($channel_id,$poster_show='',$use_chat=''){
           
            $now                =   time().rand(0,1000000);
            $thumb_id           =   get_post_thumbnail_id($channel_id);
            $thumb              =   wp_get_attachment_image_src($thumb_id,'small');
            $usernamestream     =   esc_html ( get_option('wpstream_api_username','') );
            $autoplay           =   'autoplay';
            
            $event_settings     =   $this->wpestream_return_event_settings($channel_id);
            $notes              =   'wpstream_live_event_player_note';
            $event_status       =   $this->main->wpstream_live_connection-> wpstream_check_event_status_api_call($channel_id,$notes);
            $hls_playback_url     =   '';
            $live_conect_views  =   '';
            
            if(isset($event_status['status']) && $event_status['status']=='active'){
                //live event
                if(isset($event_status['hls_playback_url'])){
                    $hls_playback_url        =   $event_status['hls_playback_url'];
                    
                    update_post_meta($channel_id,'stream_name',$event_status['stream_name']);
                    update_post_meta($channel_id,'hls_key_retrieval_url',$event_status['hls_key_retrieval_url']);
                    delete_transient(  'free_event_streamName_'.$event_status['stream_name']);

                    $live_conect_array      =   explode('live.streamer.wpstream.net',$hls_playback_url);
                    $live_conect_views      =   $live_conect_array[0].'live.streamer.wpstream.net';
                    $live_conect_views      =   $this->remove_http($live_conect_views);
            
                }
                 if(isset($event_status['chat_url'])){
                    $chat_url = $event_status['chat_url'];
                }
                         
            }else{
                // event not live
            }
            
            
         
         
          
      
            
            if(isset($event_settings['autoplay']) && intval($event_settings['autoplay'])==0){
                $autoplay='no_autoplay';
            }
 
            echo '<div class="wpstream_live_player_wrapper function_wpstream_live_event_player" data-now="'.$now.'" data-me="'.esc_attr($usernamestream).'" data-product-id="'.$channel_id.'" id="wpstream_live_player_wrapper'.$now.'" > ';
                    
                if( ( isset($event_settings['view_count'] ) && intval($event_settings['view_count'])==1 ) || !isset($event_settings['view_count']) ){
                    echo '<div id="wpestream_live_counting" class="wpestream_live_counting"></div>';
                }
                
                $show_wpstream_not_live_mess=' style="display:none;" ';
                if(trim($hls_playback_url) ==''){

                    $show_wpstream_not_live_mess=''; 
                }
          
                $message_show= esc_html( get_option('wpstream_you_are_not_live','We are not live at this moment. Please check back later.')) ;
                print '<div class="wpstream_not_live_mess" '.$show_wpstream_not_live_mess.'><div class="wpstream_not_live_mess_back"></div><div class="wpstream_not_live_mess_mess">'.esc_html($message_show).'</div></div>';
                     
                $poster_data='';
                if(isset($thumb[0])){
                    $poster_data=' poster="'.$thumb[0].'" ';
                }
                if($poster_show=='no'){
                    $poster_data='';
                }

                $is_muted='';
                if( isset($event_settings['mute']) && intval($event_settings['mute'])==1){
                    $is_muted=' muted ';
                }
                    
                   
              
                    echo'
                    <video id="wpstream-video'.$now.'"     '.$poster_data.'  class="video-js vjs-default-skin  vjs-16-9 vjs-wpstream" playsinline="true" '.$is_muted.' controls data-autoplay='.$autoplay.'>
                    <source
                        src="'.$hls_playback_url.'"
                        type="application/x-mpegURL">
                    </video>';

                    print '<script type="text/javascript">
                                //<![CDATA[
                                    jQuery(document).ready(function(){
                                        wpstream_player_initialize("'.$now.'","'.$hls_playback_url.'","'.$live_conect_views.'","'.$autoplay.'");';
                                    print'});
                                //]]>
                            </script>';
                print '</div>';   
               
               
                if(trim($hls_playback_url) ==''){
                    // $show_wpstream_not_live_mess=''; 
                }else{
                       print '<script type="text/javascript">
                            //<![CDATA[
                                jQuery(document).ready(function(){
                                    var player_wrapper =   jQuery(".wpstream_live_player_wrapper");
                                    wpstream_read_websocket_info("'.$channel_id.'","wpstream_live_player_wrapper'.$now.'", player_wrapper ,"'.$chat_url.'", "'.$hls_playback_url.'");
                                });
                            //]]>
                        </script>';
                }
               
               
               if($use_chat=="yes"){
                    $this->wpstream_connect_to_chat($channel_id);
               }
               
               usleep (10000);

        }



    /**
    * 
    * Edited in 4.0
    * 
    * 
    * Live Event Player
    *
    * @author cretu
    */
    
    function wpstream_live_event_player_low_latency($channel_id,$poster_show='',$use_chat=''){
            $usernamestream         =   esc_html ( get_option('wpstream_api_username','') );
            $thumb_id               =   get_post_thumbnail_id($channel_id);
            $thumb                  =   wp_get_attachment_image_src($thumb_id,'small');
            
            $event_settings     =   $this->wpestream_return_event_settings($channel_id);
            $notes              =   'wpstream_live_event_player_low_latency_note';
            $event_status       =   $this->main->wpstream_live_connection-> wpstream_check_event_status_api_call($channel_id,$notes);
            $hls_playback_url     =   '';
            $live_conect_views  =   '';
            $now                =   time().rand(0,10);
            
            

            if(isset($event_status['status']) && $event_status['status']=='active'){
                //live event
                if(isset($event_status['sldp_playback_url'])){
                    $hls_playback_url         =   $event_status['sldp_playback_url'];
                      
                    $live_conect_array      =   explode('live.streamer.wpstream.net',$hls_playback_url);
                    $live_conect_views      =   $live_conect_array[0].'live.streamer.wpstream.net';
                    $live_conect_views      =   $this->remove_http($live_conect_views);
            
                }
                 if(isset($event_status['chat_url'])){
                    $chat_url =$event_status['chat_url'];
                }
                      
            }else{
                // event not live
            }
            
            
          
            
            echo '<div class="wpstream_live_player_wrapper function_wpstream_live_event_player_low_latency wpstream_low_latency" data-now="'.$now.'" data-me="'.esc_attr($usernamestream).'" data-product-id="'.$channel_id.'" id="wpstream_live_player_wrapper'.$now.'" > ';
                    
                   
                    if( ( isset($event_settings['view_count'] ) && intval($event_settings['view_count'])==1 ) || !isset($event_settings['view_count']) ){
                        echo '<div id="wpestream_live_counting" class="wpestream_live_counting"></div>';
                    }
                  
                    $show_wpstream_not_live_mess=' style="display:none;" ';
                    if(trim($hls_playback_url) ==''){
                        $show_wpstream_not_live_mess=''; 
                    }
            
                    $message_show= esc_html( get_option('wpstream_you_are_not_live','We are not live at this moment. Please check back later.')) ;
                    print '<div class="wpstream_not_live_mess " '.$show_wpstream_not_live_mess.' ><div class="wpstream_not_live_mess_back"></div><div class="wpstream_not_live_mess_mess">'. $message_show.'</div></div>';
                     
                    
                    $poster_data=' poster="'.$thumb[0].'" ';
                    if($poster_show=='no'){
                        $poster_data='';
                    }
                    
                 
                    $is_muted='';
                    if( isset($event_settings['mute']) && intval($event_settings['mute'])==1){
                        $is_muted=' muted ';
                    }
                    
                    
                    $autoplay='autoplay';
                    if(isset($event_settings['autoplay']) && intval($event_settings['autoplay'])==0){
                        $autoplay='no_autoplay';
                    }
                    
                    echo'
                    <div  iccd="player" id="wpstream-video'.$now.'"   '.$poster_data.' '.$is_muted.' class="" >
                    </div>';

                    print '<script type="text/javascript">
                                //<![CDATA[
                                    jQuery(document).ready(function(){
                                        var low_latencyid="wpstream-video'.$now.'";
                                        document.addEventListener("DOMContentLoaded", initPlayer(low_latencyid, "'.$hls_playback_url.'","'.$is_muted.'","'.$autoplay.'" ) ); ';
                                    print'});
                                //]]>
                            </script>';
                 
               
               
                if(trim($hls_playback_url) ==''){
                    // $show_wpstream_not_live_mess=''; 
                }else{
                       print '<script type="text/javascript">
                            //<![CDATA[
                                jQuery(document).ready(function(){
                                    var player_wrapper =   jQuery(".wpstream_live_player_wrapper");
                                    wpstream_read_websocket_info("'.$channel_id.'","wpstream_live_player_wrapper'.$now.'", player_wrapper ,"'.$chat_url.'", "'.$hls_playback_url.'");
                                });
                            //]]>
                        </script>';
                }
               
               
               if($use_chat=="yes"){
                    $this->wpstream_connect_to_chat($channel_id);
               }
               
               usleep (10000);

        }


        
    
        
        /*
        * 
        * Request HLS player
        * 
        * 
        *  
        */
        public function  wpstream_request_video_on_demand_hls_player($video_name,$product_id){
            if($video_name==''){
                return '';
            }
            
            $transient_name =   'wpstream_video_on_demand_'.$video_name;
            $hls_to_return  =   get_transient( $transient_name );
            $hls_to_return  =   false;

            
            if($hls_to_return==false){
             
                $access_token   =   $this->main->wpstream_live_connection->wpstream_get_token();
                $url            =   'video/info';

                //corsorigin de check
                $local_event_options    =   get_option('wpstream_user_streaming_global_channel_options') ;
                $domain                 =   parse_url ( get_site_url() );
                $domain_scheme          =   'http';
                if(is_ssl()){
                    $domain_scheme='https';
                }

                $corsorigin='';
                if( isset($local_event_options['vod_domain_lock']) ){
                    if (intval( $local_event_options['vod_domain_lock']) ==0 ){
                        $corsorigin='*';
                    }
                }else{
                    $corsorigin='*';
                }

                if($corsorigin!='*'){
                    $corsorigin=$domain_scheme.'://'.$domain['host'];
                }       

                $is_encrypt="false";
                if(  isset($local_event_options['vod_encrypt']) && intval( $local_event_options['vod_encrypt']) ==1 ){
                    $is_encrypt="true";
                }

                $hlsKeysUrlPrefix    =  get_site_url().'?wpstream_voddrm=';
                $encrypt             =  $is_encrypt;
                $debugDrm            =  false;

                $curl_post_fields=array( 
                    'access_token'      =>  $access_token,
                    'name'              =>  $video_name,
                    'corsOrigin'        =>  $corsorigin,
                    'encryptHls'        =>  $encrypt,
                    'hlsKeysUrlPrefix'  =>  $hlsKeysUrlPrefix,
                    'debugDrm'          =>  $debugDrm,
                );

       
                $curl_response          =   $this->main->wpstream_live_connection->wpstream_baker_do_curl_base($url,$curl_post_fields);
                $curl_response_decoded  =   json_decode($curl_response,JSON_OBJECT_AS_ARRAY);
       
                if($curl_response_decoded['success']){
                    set_transient(  $transient_name, $curl_response_decoded['hlsUrl'] ,300);
                    $hls_to_return =  $curl_response_decoded['hlsUrl'];
                    if( isset($curl_response_decoded['hlsDecryptionKey']) && isset($curl_response_decoded['hlsDecryptionKeyIndex']) ){
                        update_post_meta($product_id,'hlsDecryptionKey',$curl_response_decoded['hlsDecryptionKey']);
                        update_post_meta($product_id,'hlsDecryptionKeyIndex',$curl_response_decoded['hlsDecryptionKeyIndex']);
                    }else{
                        delete_post_meta($product_id,'hlsDecryptionKey');
                        delete_post_meta($product_id,'hlsDecryptionKeyIndex');
                    }
                    
                }else{
                    return '';
                }
               
            }
            
            return $hls_to_return;
                  
        }

        
        
        
        /**
        * VODPlayer uri details
        *
        * @author cretu
        */
        public function wpstream_video_on_demand_player_uri_request($product_id){
           
                $wpstream_data_setup    =   '  data-setup="{}" ';
                
                /* free_video_type
                 * 1 - free live channel
                 * 2 - free video encrypted
                 * 3 - free video -not encrypted
                 */
                
                
                $free_video_type        =   intval( get_post_meta($product_id, 'wpstream_product_type', true));                
                if( ( get_post_type($product_id)=='wpstream_product_vod' && $free_video_type==2 ) || get_post_type($product_id)=='product' ){
                    
                    /* 
                    * IF vide is encrypted-  readed from vod,streaner
                    *
                    */
                   
                    
                    $video_type         =   'application/x-mpegURL';
                    $video_path         =   get_post_meta($product_id,'_movie_url',true); 
                    if(get_post_type($product_id)=='wpstream_product_vod'){
                        $video_path =    esc_html(get_post_meta($product_id, 'wpstream_free_video', true));
                    }
                    $video_path_final = $this->wpstream_request_video_on_demand_hls_player($video_path,$product_id);

                    
                }else if( get_post_type($product_id)=='wpstream_product_vod'  && $free_video_type==3 ){
                    
                    /* Video is unecrypted - read from local or youtube / vimeo
                    */
                    
                    $video_type         =   'video/mp4';
                    $video_path_final=esc_html(get_post_meta($product_id, 'wpstream_free_video_external', true));

                    if (strpos($video_path_final, 'www.youtube') !== false) {
                        $wpstream_data_setup= '    data-setup=\'{ "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "'.$video_path_final.'"}] }\'   '; 
                        $video_path_final='';
                    }
                    if (strpos($video_path_final, 'vimeo.com') !== false) {
                        $wpstream_data_setup= '   data-setup=\'{"techOrder": ["vimeo"], "sources": [{ "type": "video/vimeo",  "src": "'.$video_path_final.'"}], "vimeo": { "color": "#fbc51b"} }\'   '; 
                        $video_path_final='';
                    }

                }
                
            $return_array=array();
            $return_array['video_path_final']   =   $video_path_final;
            $return_array['wpstream_data_setup']=   $wpstream_data_setup;
            $return_array['video_type']         =   $video_type;
            return $return_array;
 }
     
 
 
         /**
        * VODPlayer url
        *
        * @author cretu
        */

        public function wpstream_video_on_demand_player($product_id){
            
                    $uri_details        =   $this->wpstream_video_on_demand_player_uri_request($product_id);
                    $video_path_final   =   $uri_details['video_path_final'];
                    $wpstream_data_setup =  $uri_details['wpstream_data_setup'];
                    $video_type          =  $uri_details['video_type'];
                    
                    $thumb_id               =   get_post_thumbnail_id($product_id);
                    $thumb                  =   wp_get_attachment_image_src($thumb_id,'small');
                    $usernamestream         =   esc_html ( get_option('wpstream_api_username','') );
                    
                    $poster_thumb           =   '';
                    if(isset($thumb[0])){
                        $poster_thumb=$thumb[0];
                    } 

                    $hlsDecryptionKey       =   get_post_meta($product_id,'hlsDecryptionKey',true);
                    $hlsDecryptionKeyIndex  =   get_post_meta($product_id,'hlsDecryptionKeyIndex',true);
                    

                    $pack = $this->main->wpstream_live_connection->wpstream_request_pack_data_per_user();
            
                    
                    if(isset($pack['available_data_mb']) && $pack['available_data_mb']>0){
                        
                        if($video_path_final==''){
                            print '<div class="wpstream_vod_notice">This video does not exist or it has been deleted!</div>';
                        }
                        
                        echo '<video id="wpstream-video'.time().'" class="video-js vjs-default-skin  vjs-16-9 kuk wpstream_video_on_demand vjs-wpstream"  data-me="'.esc_attr($usernamestream).'" data-product-id="'.$product_id.'"  controls preload="auto"
                                poster="'.esc_url($poster_thumb).'" '.$wpstream_data_setup.'>

                                <source src="'.trim($video_path_final).'"  type="'.$video_type.'">
                                <p class="vjs-no-js">
                                  To view this video please enable JavaScript, and consider upgrading to a web browser that
                                  <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                </p>
                            </video>';
                    }else{
                        print esc_html_e('Insufficient resources to stream this title','wpstream');
                    }

        }


        
        
        
        
        /**
         * 
         * edited 4.0
         * 
        * Retreive username for vod path
        *
        * @author cretu
        */
        private function wpstream_retrive_username(){
            
            return  get_option('wpstream_api_username_from_token');
        }
        
          /**
	 * check if the we can add display the player
	 *
	 * @since     3.12
         * returns html of the player
	*/
        
        public function wpstream_check_if_player_can_dsplay($product_id){
            if ( is_user_logged_in() ) {
                
                $term_list              =       wp_get_post_terms($product_id, 'product_type');
                $current_user           =       wp_get_current_user();
                $subscription_model     =       intval( get_option('wpstream_global_sub','')) ;
                
               
                if($subscription_model==1){ // if we have Neflix mode               
                    if( $term_list[0]->name=='subscription' ){ // if the product loaded is a subscription and we are on netflix mode
                        return false;
                    }
                    if($this->wpstream_in_plugin_check_global_subscription_model($product_id)){
                        return true;
                    }
                }else{
                    // ppv mode
                    if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id) ||
                        ( function_exists('wcs_user_has_subscription') && wcs_user_has_subscription( $current_user->ID, $product_id ,'active') ) ){
                        return true;
                    }
                }
                return false;
               
            }
            return false;
            
        }
        
        
        
        /**
	 * in plugin - check if global subscription model is enabled
	 *
	 * @since     3.12
         * 
	*/
        public   function wpstream_in_plugin_check_global_subscription_model($product_id) { 
        
            //  $selected_sub= get_post_meta($post->ID,'_wpstream_parent_sub',true);
            if( is_user_logged_in()  && function_exists('wcs_user_has_subscription') ){
      
                global $woocommerce;
                $current_user   =       wp_get_current_user(); 
        
                $subscription_model =   intval( get_option('wpstream_global_sub','')) ;
                $main_subscription  =   intval(  get_option('wpstream_global_sub_id',''));
        
                if($subscription_model==1){
                    $selected_sub=  get_post_meta($product_id,'_wpstream_parent_sub',true)  ;
                    
                    if( is_array($selected_sub) ){
                 
                        // we have per product sub
                        foreach($selected_sub as $key=>$subscrition_id ):
                            if( wcs_user_has_subscription( $current_user->ID, $subscrition_id ,'active') ) {
                                return true;
                            }
                        endforeach;
                        
                    } else if($main_subscription!=0){ 
                        
                        // if we have one main subscription
                        if( wcs_user_has_subscription( $current_user->ID, $main_subscription ,'active') ) {
                            return true;
                        }
                    }
                    
                    
                }
                return false;
        
            }
            // there is no woo subscription or user not logged in
            return false;
        }
        
        
        
        
        
        
        
        
        
        /**
         * 
         * 
         * 
         * 
	 * check if the user bought the product and display the player - TO REDo
	 *
	 * @since     3.0.1
         * returns html of the player
         * 
         * 
         * 
         * 
         * 
	*/
          public function wpstream_user_logged_in_product_already_bought($from_sh_id='') {
            global $product;
            $product_id     =       $product->get_id();
            $current_user           =       wp_get_current_user();

            if ( is_user_logged_in() ) {
                
           
                if($this->wpstream_check_if_player_can_dsplay($product_id) ){
                    
                    echo '<div class="wpstream_player_wrapper "><div class="wpstream_player_container">';

                    $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));
                    $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                   

                    if( $term_list[0]->name=='live_stream' || ($term_list[0]->name=='subscription' && $is_subscription_live_event=='yes' )  ){
                        $this->wpstream_live_event_player($product_id);
                    }else if( $term_list[0]->name=='video_on_demand'  || ($term_list[0]->name=='subscription' && $is_subscription_live_event=='no' ) ){
                        $this->wpstream_video_on_demand_player($product_id);
                    }
                    echo '</div></div>';
                }else{
      
                    
                    $term_list                  =   wp_get_post_terms($product_id, 'product_type');
              
                    if( $term_list[0]->name=='subscription' ){
              
                        if( !wcs_user_has_subscription( $current_user->ID, $product_id ,'active') ) {
                            $this->wpstream_display_no_buy_message('nobuy',$product_id);
                        }
                             
                    }else{
                        $this->wpstream_display_no_buy_message('nobuy',$product_id);
                    }
                      
                
                    
                }

               
            }else{
               
                $this->wpstream_display_no_buy_message('nolog',$product_id);
            }
        }
        
        
        
        
        
        /**
         *
         *   
	 * check if the user bought the product and display the player - TO REDo
	 *
	 * @since     3.0.1
         * returns html of the player
	*/
        
        public function wpstream_chat_wrapper($product_id){
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/wpstream_chat_template.php';
        }

        
        
        
        
        
        
        
          /**
	 * coonect to chat
	 *
	 * @since     1.12.2
         * 
	*/
        
        
        
        public  function wpstream_connect_to_chat($product_id){
        $current_user           =   wp_get_current_user();
        $userID                 =   $current_user->ID;
        $user_login             =   $current_user->user_login;

        $key='';

        $chat_url                =   get_post_meta($product_id,'chat_url',true);
               

        wp_enqueue_script( 'sockjs-0.3.min' );
        wp_enqueue_script( 'emojione.min.js' );
        wp_enqueue_script( "jquery-effects-core");
        wp_enqueue_script( 'jquery.linkify.min.js');
        wp_enqueue_script( 'ripples.min.js');
        wp_enqueue_script( 'material.min.js');
        wp_enqueue_script( 'chat.js');



        wp_enqueue_style( 'chat.css');
        wp_enqueue_style( 'ripples.css');
        wp_enqueue_style( 'emojione.min.css');


        
       
       if(!is_user_logged_in()){
           $user_login='';
           $chat_url='';
       }

       
       print '<script type="text/javascript">
            //<![CDATA[
                jQuery(document).ready(function(){
                    username = "'.$user_login.'";
                    key="'.$key.'";
                   
                });
            //]]>
        </script>';
      
    }
    
     
        /**
	 * display no buy Message
	 *
	 * @since     3.12.2
         * 
	*/
        public function wpstream_display_no_buy_message($log,$product_id) {
      
            if($log=='sub_active'){
                $message= esc_html( get_option('wpstream_subscription_active','Your Subscription is Active.')) ;    
                echo '<div class="wpstream_player_wrapper no_buy"><div class="wpstream_player_container">';
                echo '<div class="wpstream_notice"> '.$message.'</div>';
                echo '</div></div>';
                return;
                
            }else if($log=='nolog'){
               $message= esc_html( get_option('wpstream_product_not_login','You must be logged in to watch this video.')) ;
            }else{
                $message =esc_html( get_option('wpstream_product_not_bought','You did not yet purchase this item.')) ;
            }
            $subscription_model     =       intval( get_option('wpstream_global_sub','')) ;
            if($subscription_model==1){
                $message =esc_html( get_option('wpstream_product_not_subscribe','You did not yet subscribe to this item.'));
            }
            
            
            if( get_post_type($product_id) == 'product' && $subscription_model==0 ){
                $product                    =   wc_get_product($product_id);
                $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                $product_type               =   $product->get_type();
                $is_subscription_live_event =   esc_html(get_post_meta($product_id,'_subscript_live_event',true));

                

                if( $term_list[0]->name=='video_on_demand' ||  $term_list[0]->name=='live_stream' || $product_type=='subscription'){
                    
                    echo '<div class="wpstream_player_wrapper no_buy"><div class="wpstream_player_container">';
                    echo '<div class="wpstream_notice">'.$message.'</div>';
                    echo '</div></div>';
                        
                }
                        
            }else  if( get_post_type($product_id) == 'product' && $subscription_model==1 ){
                $term_list                  =   wp_get_post_terms($product_id, 'product_type');
                if( $term_list[0]->name!=='simple'){
                    echo '<div class="wpstream_player_wrapper no_buy"><div class="wpstream_player_container">';
                    echo '<div class="wpstream_notice">  '.$message.'</div>';
                    echo '</div></div>';
                }
            }
            
            
            

                    
                    
        }
   
    
}
