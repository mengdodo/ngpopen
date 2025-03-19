<?php

namespace Mengdodo\Ngpopen\Library;

class NgpOpenSdk
{
    public function pTypeList($param)
    {
        $method = 'ngp.ptype.list';
        return Action::api($method, $param);
    }

    public function eshopSaleOrderUpload($param){
        $method = 'ngp.eshopsaleorder.upload';
        return Action::api($method, $param);
    }

    public function addressAnalyzer($param){
        $method = 'ngp.address.analyzer.simple';
        return Action::api($method, $param);
    }

    public function processInfoQuery($param){
        $method = 'ngp.eshopsaleorder.processinfolist';
        return Action::api($method, $param);
    }
}
