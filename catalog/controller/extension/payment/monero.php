<?php

class ControllerExtensionPaymentMonero extends Controller {
    private $payment_module_name  = 'monero';
	public function index() {
        	
    		$this->load->model('payment/monero');
		$this->load->model('checkout/order');
		$order_id = $this->session->data['order_id'];
		$order = $this->model_checkout_order->getOrder($order_id);
		$current_default_currency = $this->config->get('config_currency');
		$order_total = $order['total'];
		$order_currency = $order['currency'];
		$amount_xmr = $this->changeto($order_total, $currency);
		
		$data['amount_xmr'] = $amount_xmr;
		$data['integrated_address'] = "";
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/monero.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/monero.tpl';
		} else {
			$this->template = 'default/template/payment/monero.tpl';
		}	
		        return $this->load->view('extension/payment/monero', $data);

		

	}
	
	public function changeto($order_total, $currency){
		$xmr_live_price = $this->xmrliveprice($currency);
		$amount_in_xmr = $order_total / $xmr_live_price;
		retrun $amount_in_xmr;
	}
	
	public function xmrliveprice($currency){
		$xmr_price = file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=XMR&tsyms=BTC,USD,EUR,CAD,INR,GBP&extraParams=monero_opencart");
		$price = json_decode($xmr_price, TRUE);
		
        switch ($currency) {
            case 'USD':
                return $price['USD'];
            case 'EUR':
                return $price['EUR'];
            case 'CAD':
                return $price['CAD'];
            case 'GBP':
                return $price['GBP'];
            case 'INR':
                return $price['INR'];
            case 'XMR':
                $price = '1';
                return $price;
        }
	}
	
	public function make_integrated_address(){
		    $this->load->library('jsonrpclibrary');
		    $this->load->library('monero');
		$host = $this->config->get("monero_wallet_rpc_host");
		$port = $this->config->get("monero_wallet_rpc_port");
		$monero = new Monero_Payments($host, $port);
		$integrated_address = $monero->make_integrated_address();
		return $integrated_address;
	}
	
	/*
		Here function for prices, integrated address, connection between opencart and wallet rpc
	*/
	
}
