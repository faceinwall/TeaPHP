<?php 
namespace engine\net;
use engine\net\Route;

class Router {
    /**
     * @var string $callback
     */
    private $callback;

    /**
     * @var array $params
     */
    private $params = array();

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->$name ? $this->$name : null;
    }

    /**
     * @param mixed $callback The callback 
     * @param array $params
     *
     * @usage: new Router(array('\controller\BlogController', 'actionIndex'), $params); OR
     *       : new Router('\controller\BlogController@actionIndex', $params) 
     */
    public function __construct($callback = null, array $params = array()) {
        //humn,when config null
        if (!is_null($callback)) {
            $this->callback = $callback;
        } else {
            $this->callback = $this->getCallback();
        }
        if (!empty($params)){
            $this->params = $params;
        } else {
            //unset the router string
            $tmp_PARAMS =  $this->getParams();

            unset($tmp_PARAMS['r']);

            $this->params = $tmp_PARAMS;
         }
    }


    /**
     * get the controller
     * @return string
     */
    public function getCallback() {
        $uri = new \engine\net\Uri();

        //get the callback class folder
        $callbackFolder = \Tea::app()->config('controler_folder');

        if (empty($callbackFolder)) {
            $callbackFolder = 'controller';
        }

        if ($uri->totalFragments() > 2){
            //if the controller file in sub directory
            $class  = '\\'.$callbackFolder.'\\'.$uri->fragment(0).'\\'.ucfirst($uri->fragment(1)).'Controller';
            //the controller method
            $method = $uri->fragment(2) ? 'action'.ucfirst($uri->fragment(2)) : 'actionIndex';

            return $class.'@'.$method;
        }
        else if (0 <= $uri->totalFragments()) {
            // if the controller file  in root dir
            $class = '\\'.$callbackFolder.'\\'.ucfirst($uri->fragment(0)).'Controller';
            //method
            $method = $uri->fragment(1) ? 'action'.ucfirst($uri->fragment(1)) : 'actionIndex';

            return $class.'@'.$method;
        }
    }

    /**
     * gets the params
     */
    public function getParams() {
        $uri = new \engine\net\Uri();
        return $uri->queryParams;
    }
}
 ?>