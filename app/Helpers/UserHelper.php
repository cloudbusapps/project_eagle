<?php
use Illuminate\Support\Facades\Auth;



function isAdminOrHead($type=''){
    if($type==''){
        if(Auth::id()==config('constant.ID.USERS.BA_HEAD') || Auth::user()->IsAdmin){
            return true;
        }else{
            return false;
        }
    } else if($type=='admin'){
        if(Auth::user()->IsAdmin){
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