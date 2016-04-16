<?php
class indexController extends baseController {
    
    
    
    
    
    
    
    
    
    
    
    public function index($arg = array()) {
        
        // session_start();
        if ($this->checkLogin ()) {
            $this->redirect ( "user" );
            exit ( 0 );
        }
        if (isset ( $_POST ['login'] ) && $_POST ['login'] == 'login') {
            $user = $_POST ['username_newUserName'];
            $pass = $_POST ['password_newPassword_branchMaster'];
            $model = $this->model->get ( 'UserModel' );
            $account = $model->checkLogin ( $user, $pass );
            
            if ($account == false) {
                $error = 'Username and password wrong.';
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
    public function index($arg = array()) {
        
        // session_start();
        if ($this->checkLogin ()) {
            $this->redirect ( "user" );
            exit ( 0 );
        }
        if (isset ( $_POST ['login'] ) && $_POST ['login'] == 'login') {
            $user = $_POST ['username_newUserName'];
            $pass = $_POST ['password_newPassword_branchMaster'];
            $model = $this->model->get ( 'UserModel' );
            $account = $model->checkLogin ( $user, $pass );
            
            if ($account == false) {
                $error = 'Username and password wrong.';
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