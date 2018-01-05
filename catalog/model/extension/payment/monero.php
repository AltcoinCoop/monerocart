<?php
class ModelExtensionPaymentMonero extends Model {
  	public function getMethod($address, $total) {
		
      		$method_data = array( 
        		'code'         	=> 'monero',
        		'title'      	=> 'Monero Payment Gateway',
      		'sort_order' => $this->config->get('custom_sort_order'),
      		'terms' => 'by serhack'
      		);
    	
   
    	return $method_data;
  	}
}
