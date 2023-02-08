<?php
use Illuminate\Support\Facades\Auth;



function isAdminOrHead(){
if(Auth::id()==config('constant.ID.USERS.BA_HEAD') ||Auth::id() == config('constant.ID.USERS.ADMIN')){
    return true;
}else{
    return false;
}
}