<?php
namespace App\Traites;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Route;
Trait Traite{
    public function getAllRoute(){
        return Route::all();
    }
    public function getAllRole(){
        return Role::all();
    }

    public function hasPermission($role){
        
    }
}