<?php 
namespace engine\net;

class Uri {
    /**
     * @var array $framgents
     */
    private $fragments = array();

    /**
     * @var array $queryParams
     */
    private $queryParams = array();

    /**
     * counstruction
     */
    public function __construct(){
        //pubt the string into array
        $this->fragments = explode('/', (isset($_GET['r'])&&!empty($_GET['r']) ? $_GET['r'] : 'index') );

        //fetch query string params
        $this->initializeParams($_GET);
    }

    /**
     * get property value
     * @param string $name property name
     * @return mixed
     */
    public function __get($name) {
        return $this->$name ? $this->$name: null;
    }

    /**
     *
     * initialize query params
     * @param array $params
     */
    public function initializeParams(array $params) {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->queryParams[$key] = $value;
            }
        }
    }

    /**
     * @get uri fragment
     * @param string $index The index of the array
     * @return mixed
     */
    public function fragment($index) {
        if (array_key_exists($index, $this->fragments)) {
            return $this->fragments[$index];
        }
        return false;
    }

    /**
     * return total number of route segments
     */
    public function totalFragments() {
        return count($this->fragments);
    }

    /**
     * @__clone
     */
    public function __clone(){
    }
}