<?php
App::uses ( 'HttpSocket', 'Network/Http' );

class ProxyController extends AppController {
    
    public function index() {
        $HttpSocket = new HttpSocket ();
        
        if (sizeof ( $this->params ['pass'] [0] ) != 1) {
            throw new InvalidArgumentException ();
        }
        if (! empty ( $_SERVER ['PHP_AUTH_USER'] ) && ! empty ( $_SERVER ['PHP_AUTH_PW'] )) {
            $HttpSocket->configAuth ( 'Basic', $_SERVER ['PHP_AUTH_USER'], $_SERVER ['PHP_AUTH_PW'] );
        } elseif (isset ( $this->CurrentUser )) {
            $HttpSocket->configAuth ( 'Basic', $this->Session->read ( 'Auth.User.Login' ), $this->Session->read ( 'Auth.User.Password' ) );
        }
        
        $this->response->type ( 'json' );
        
        $request = array (
            'method' => env ( 'REQUEST_METHOD' ),
            'body' => $this->request->data,
            'uri' => array (
                'scheme' => Configure::read ( 'Api.scheme' ),
                'host' => Configure::read ( 'Api.host' ),
                'port' => 80,
                'path' => Configure::read ( 'Api.path' ) . $this->params ['pass'] [0],
                'query' => $this->params->query 
            ) 
        );
        
        $response = $HttpSocket->request ( $request );
        $this->response->statusCode ( $response->code );
        $this->response->body ( $response->body );
        return $this->response;
    }
}

?>