<?php
class RedisComponent extends Component {
    
    public $redis;
    public function initialize(\Controller $controller) {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        
        parent::initialize($controller);
        
    }
    
    public function setRedisKey( $key, $value ){
        $this->redis->set($key, $value);
    }
    
    public function updateRedisKey( $key, $value ){
        $this->redis->set($key, $value);
    }
    
    public function expireRedisKey( $key, $duration ){
        $this->redis->expire( $key, $duration );
    }
    
    public function deleteRedisKey($key){
        $this->redis->delete( $key );
    }
    
    public function ttlRedisKey($key){
        $this->redis->ttl( $key );
    }
}