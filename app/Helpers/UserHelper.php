<?php
use Illuminate\Support\Facades\Auth;



function isAdminOrHead($type=''){
    if($type==''){
        if(Auth::id()==config('constant.ID.USERS.BA_HEAD') ||Auth::id() == config('constant.ID.USERS.ADMIN')){
            return true;
        }else{
            return false;
        }
    } else if($type=='admin'){
        if(Auth::id() == config('constant.ID.USERS.ADMIN')){
            return true;
        }else{
            return false;
        }
    } else if($type=='head'){
        if(Auth::id()==config('constant.ID.USERS.BA_HEAD')){
            return true;
        }else{
            return false;
        }
    }
    
}