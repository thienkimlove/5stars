<?php
class MhvController extends AppController
{
    //view of app facebook fanpage mhv
    public function newbie()
    {
        $this->autoLayout = false;
    }

    //action get code newbie from mhv
    public function newbie_fetch($access_token, $server)
    {
        $this->autoLayout = false;
        $this->autoRender = false;
        if (isset($access_token) || isset($server)) {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $url = 'http://myhauvuong.5stars.vn/fanpage/newbie/' . $access_token . '/' . $server;
            $response = $HttpSocket->get($url);
            return $response->body;
        } else {
            return '{"error":"Thiếu dữ liệu"}';
        }
    }
    public function homepage(){
        $this->autoLayout = false;
        $this->autoRender = false;
        echo '<script>top.window.location ="http://myhauvuong.5stars.vn"</script>';
    }
    public function download(){
        $this->autoLayout = false;
        $this->autoRender = false;
        echo '<script>top.window.location ="http://myhauvuong.5stars.vn/posts/details/64/guide"</script>';
    }
    public function group(){
        $this->autoLayout = false;
        $this->autoRender = false;
        echo '<script>top.window.location ="https://www.facebook.com/groups/276287242579638/?fref=ts"</script>';
    }
}