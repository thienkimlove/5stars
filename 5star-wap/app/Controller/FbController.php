<?php
class FbController extends AppController
{

    public function newYear()
    {
        $this->autoLayout = false;
    }

    //call ajax check newYear
    public function fetch($access_token, $server)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if (isset($access_token) || isset($server)) {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $url = 'http://bakhi.5stars.vn/index/code1/' . $access_token . '/' . $server;
            $response = $HttpSocket->get($url);
            return $response->body;
        } else {
            return '{"error":"Thiếu dữ liệu"}';
        }
    }

    public function dailyCode()
    {
        $this->autoLayout = false;
        $signedRequest = isset($this->request->data['signed_request']) ? $this->request->data['signed_request'] : '';
        if (empty($signedRequest)) {
            $data = null;
        } else {
            list($sig, $payload) = explode('.', $signedRequest, 2);
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        }
        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
        $url = 'http://bakhi.5stars.vn/fb/';
        $response = $HttpSocket->get($url);
        $rs = $response->body;
        $this->set(array('response' => $rs, 'data' => $data));
    }

    public function getDailyCode($access_token=null, $day=null)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if (isset($access_token) || isset($day)) {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $url = 'http://bakhi.5stars.vn/fb/dailyCode/' . $access_token . '/' . $day;
            $response = $HttpSocket->get($url);
            return $response->body;
        } else {
            return '{"error":"Thiếu dữ liệu"}';
        }
    }
}