<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Route as RouteModel;

class AddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        RouteModel::truncate();
        foreach($routes as $route){
            $uri = $route->uri();
            if(strstr($uri, '_')) continue;
            if(strstr($uri, 'api')) continue;
            if(strstr($uri, 'csrf')) continue;
            $routeModel =  new RouteModel();
            $routeModel->name = $uri;
            $routeModel->save();  
        }
        if(Role::count()==0){
            Role::create(["name"=>"Admin"]);
            Role::create(["name"=>"Guest"]);
        }

        $modelRoutes = RouteModel::all();
        $adminRole = Role::where('name', 'Admin')->first();
        $guestRole = Role::where('name', 'Guest')->first();

        foreach ($modelRoutes as $route) {
            Permission::create([
                "route_id" => $route->id,
                "role_id" => $adminRole->id
            ]);
        }
        foreach ($modelRoutes as $route) {
            if (strstr($route->name, 'admin')) continue;

            Permission::create([
                "route_id" => $route->id,
                "role_id" => $guestRole->id
            ]);
        }
    }
}
