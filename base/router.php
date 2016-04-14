<?php
/**
 *
 * @author minh_nhut
 *  */
class router
{
    /*
     * @the registry
     */
    private $registry;

	/**
	 * path folder modules
	 * @var string  */
    private $path;

    /**
     * param array
     * @var array  */
    public $args = array();

	/**
	 * path file controller
	 * @var string  */
    public $file;

    /**
     * name module
     * @var string  */
    public $module;

    /**
     * name controller
     * @var string  */
    public $controller;
	/**
	 * name controller
	 * @var string  */
	public $action;


    function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     *
     * @set controller directory path
     *
     * @param string $path
     *
     * @return void
     *
     */
    function setPath($path)
    {

        /**
         * * check if path is a directory **
         */
        if (is_dir($path) == false) {
            throw new Exception('Invalid controller path: `' . $path . '`');
        }
        /**
         * * set the path **
         */
        $this->path = $path;
    }

    /**
     *
     * @load the controller
     *
     * @access public
     *
     * @return void
     *
     */
    public function loader()
    {
        /**
         * check the route
         */
        $this->getController();

        /**
         * if the file is not there diaf **
         */
        if (is_readable($this->file) == false) {
            $this->module 		= 'error';
            $this->file 		= $this->path . '/error/controllers/error404.php';
            $this->controller 	= 'error404';
        }


        /**
         * include the controller
         */

        include $this->file;

        /**
         * a new controller class instance
         */
        $class = $this->controller . 'Controller';

        $controller = new $class( $this->registry );

        /**
         * check if the action is callable **
         */
        if (!is_callable( array($controller, $this->action) )) {
        	$this->action = 'index';
        	$action = 'index';
        } else {
            $action = $this->action;

        }

        // check login and permission and open session
        // start session
        $session = session_id();

        if( empty( $session ) ){
        	session_start();
        }

        $acl = new acl( $this->registry->acl, $this);
        $acl->init();

        /**
         * run the action
         */
		/* @var $controller baseController */
		// select layout

        $this->getLayout($controller);

        // select view from action
        $this->getView($controller);

		$controller->getView()->router = $this;
		$controller->getView()->acl = $acl;

		// === update session from database ===
		/* @var $UserModel UserModel */
		$UserModel 	 	= $controller->model->get('User');
		$userSession	= $_SESSION['acl']['account'];
		$idAcc 			= $userSession->getId();
		// update session
		$users = $UserModel->listTableByWhere( 'User', array(" id = '$idAcc' "));
		/* @var $user User */
		if( isset( $users[0] ) ){
			$user = $users[0];
			$group_id = $user->getGroupId();
			$group = $UserModel->listTableByWhere( 'Group' , array( " id = '$group_id' " ));
			$user->setGroup( $group[0] );
			$_SESSION['acl']['account'] = $users[0];
		}

		if( isset( $_SESSION['acl']['account'] ) ){
			$controller->getView()->user = $_SESSION['acl']['account'];
		}
		// === update session from database ===

        if (! empty($this->args))
            $controller->$action($this->args);
        else
            $controller->$action();

        echo $controller->getView()->render();
    }

	/**
	 * get layout in array config layout
	 *
	 */
    private function getLayout( &$controller ){

    	/* @var $controller baseController */
		$configLayout = $this->registry->layout;
		$controlerName = $this->module . '/' . $this->controller;
		$lowerAction = strtolower($this->action);
		if(isset($configLayout[ $controlerName ])){
			// if exists action layout
			if(isset( $configLayout[ $controlerName ]['actions'][$lowerAction] )){
				$actionLayout = $configLayout[ $controlerName ]['actions'][$lowerAction];
			}
			if(isset($actionLayout)){
				$controller->getView()->setTemplate($actionLayout);
			}else{
				$controller->getView()->setTemplate($configLayout[ $controlerName ][ 'default' ]);
			}
		}else{
				$controller->getView()->setTemplate('layout/defaultLayout');
		}

    }

    /**
     *  select view from action
     *   */
	private function getView( &$controller ){

	    $moduleName = $this->module;
	    $controllerName = $this->controller;
	    $actionName = $this->action;

		$viewPath = 'modules' . '/' . $moduleName . '/' . 'views' . '/' . $controllerName.'Controller' . '/' . $actionName;
		/* @var $content Partial */
		$content = new Partial($viewPath);
		$content->router = $this;
		$viewHelper = new helper();
		$content->viewHelper = $viewHelper;
		$controller->getView()->content = $content;
	}

	/**
	 * generate string url
	 * @param array $option
	 * @param string $arg
	 * @return string  */
	public function url($option,$arg=null){
		$moduleName = $option['module'];
		if(!isset($option['controller'])){
			$controllerName = 'index';
		}else{
			$controllerName = $option['controller'];
		}
		if(!isset($option['action'])){
			$actionName = 'index';
		}else{
			$actionName = $option['action'];
		}

		$uri = __FOLDER . $moduleName.'/'.$controllerName.'/'.$actionName;
		if($arg !=null){
			foreach ($arg as $value){
				$uri.='/'.$value;
			}
		}

		return $uri;
	}

    /**
     *
     *
     * @get the controller
     *
     * @access private
     *
     * @return void
     *
     */
    private function getController()
    {
        /**
         * * get the route from the url **
         */
        $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

        if (empty($route)) {
            $this->module = "login";
            $this->controller = "index";
            $this->action = "index";
        } else {
            /**
             * * get the parts of the route **
             */
            $parts = explode('/', $route);
            $this->module = strtolower($parts[0]);

            if(isset($parts[1])){
            	$this->controller = strtolower($parts[1]);
            }
            if (isset($parts[2])) {
                $this->action = strtolower($parts[2]);
            }
            if (isset($parts[3])) {
                $count_args = count($parts);
                $k = 1;
                $args = array();
                for ($i = 3; $i < $count_args; $i ++)
                    $args[$k ++] = $parts[$i];
                $this->args = $args;
            }
        }

        if(empty($this->module)){
        	$this->controller = 'login';
        }

        if (empty($this->controller)) {
            $this->controller = 'index';
        }

        /**
         * * Get action **
         */
        if (empty($this->action)) {
            $this->action = 'index';
        }

        /**
         * * set the file path **
         */
        $this->file = $this->path . '/' . $this->module . '/controllers/' . $this->controller . 'Controller.php';


    }
}