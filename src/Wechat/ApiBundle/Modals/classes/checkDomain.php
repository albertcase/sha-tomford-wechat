<?php

namespace Wechat\ApiBundle\Modals\classes;

use Wechat\ApiBundle\Modals\classes\WechatResponse;

class checkDomain{

    private $domianList = array();

    public function __construct(array $doaminList){
        $this->domianList = $doaminList;
    }

    #
    public function checkDomainAllow($url) {
    	$parse_url = parse_url($url);
    	//var_dump($parse_url['host'], $this->domianList);
        return in_array($parse_url['host'], $this->domianList);
    }

}
