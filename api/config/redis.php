<?php
    //获取redis的实例，单例模式
    function get_redis(){
        static $redis;
        if( !is_object($redis) ){
            $redis = new Redis();
            $redis->connect('192.168.16.200', 6379);
        }
        return $redis;
}
