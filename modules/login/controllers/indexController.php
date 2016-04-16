<?php
class indexController extends baseController {
    public function index($arg = array()) {
        // session_start();
        if ($this->checkLogin ()) {
            $this->redirect ( "user_n" );
            exit ( 0 );
        }
        if (isset ( $_POST ['login'] ) && $_POST ['login'] == 'login') {
            $user = $_POST ['username'];
            $pass = $_POST ['password'];
            $model = $this->model->get ( 'UserModel' );
            $account = $model->checkLogin ( $user, $pass );
            
            if ($account == false) {
                //[start][12345][12345]
                //$error = 'Username and password wrong.';
                $error = 'Username and password wrong.';
                //[start][12345][12345]
            } else {
                $_SESSION ['acl'] ['account'] = $account;
                $this->redirectUrl ( $this->url ( array (
                        'module' => 'user' 
                ) ) );
                exit ( 0 );
            }
        }
        
        if (isset ( $error )) {
            $this->getView ()->content->error = $error;
        }
    }
    public function logout() {
        
        // session_start();
        if (! $this->checkLogin ()) {
        } else if ($this->checkLogin ()) {
            
            /* @var $account User */
            $_SESSION ['acl'] ['account'] = new User ();
        }
        
        $this->redirect ( "login" );
    }
}
?>