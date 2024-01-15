<?php 
class Demo {
    public function demoFunction() {
        $std = new stdClass();
        $std->user= 'kate';
        $std->age = '12';
        var_dump($std);
    }

    public function __toString(){
        return 'Demo';
    }
}
 ?>