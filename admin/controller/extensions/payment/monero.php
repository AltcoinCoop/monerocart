<?php
class ControllerExtensionPaymentMonero extends Controller
{
    private $error = array();
    private $settings = array();
    public function index()
    {
        $this->load->language('extension/payment/monero');
        $this->document->setTitle('Monero Payment Gateway');
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_setting->editSetting('monero', $this->request->post);
            $this->session->data['success'] = "Success! Welcome to Monero!";
            $this->response->redirect($this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'], true));
        }
        $data['heading_title'] = 'Monero Payment Gateway';
        $data['text_enabled'] = 'enabled';
        $data['text_disabled'] = 'disabled';
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_yes'] = 'Yes';
        $data['text_no'] = 'No';
        $data['text_edit'] = 'Edit';
        
        
        $data['monero_address_text'] = 'Monero Address';
        $data['button_save'] = "save";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['help_total'] = $this->language->get('help_total');
        //Errors
        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        
        
       
       // Values for Settings
        $data['monero_address'] = isset($this->request->post['monero_address']) ?
            $this->request->post['monero_address'] : $this->config->get('monero_address');
         $data['monero_status'] = isset($this->request->post['monero_status']) ?
            $this->request->post['monero_status'] : $this->config->get('monero_status');
         $data['monero_wallet_rpc_host'] = isset($this->request->post['monero_wallet_rpc_host']) ?
            $this->request->post['monero_wallet_rpc_host'] : $this->config->get('monero_wallet_rpc_host');
             $data['monero_wallet_rpc_port'] = isset($this->request->post['monero_wallet_rpc_port']) ?
            $this->request->post['monero_wallet_rpc_port'] : $this->config->get('monero_wallet_rpc_port');
       
       
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => 'Monero Payment Gateway',
            'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => 'Monero Payment Gateway',
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => 'Monero Payment Gateway',
            'href' => $this->url->link('extension/payment/monero', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['action'] = $this->url->link('extension/payment/monero', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/payment/monero', $data));
    }
   
    private function validate()
    {
        
        if (!$this->user->hasPermission('modify', 'extension/payment/monero')) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }
        return true;
       
    }
    public function install()
    {
        $this->load->model('extension/payment/monero');
        $this->load->model('setting/setting');
        
        $settings['monero_address'] = "";
        $settings['monero_wallet_rpc_port'] = "18082";
        $settings['monero_wallet_rpc_host'] = "localhost";

        
        $this->model_setting_setting->editSetting('monero', $settings);
        $this->model_extension_payment_monero->createDatabaseTables();
    }
    public function uninstall()
    {
        $this->load->model('extension/payment/monero');
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('monero');
        $this->model_extension_payment_monero->dropDatabaseTables();
    }
}
