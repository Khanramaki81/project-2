<?php
namespace app\Helpers;
class ACLHelper{
    public static function ACL($permissions){
        if(!auth()->user()->can($permissions)){
            abort(403,'the user does not have this permission.');
        }
    }
}
