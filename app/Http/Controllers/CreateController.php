<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CreateController extends Controller
{
    public function create()
    {
        DB::table('buildings')->insert([
            ['address' => 'Улица Ленина, 1', 'latitude' => 55.7558, 'longitude' => 37.6173],
            ['address' => 'Улица Пушкина, 2', 'latitude' => 55.7602, 'longitude' => 37.6173],
            ['address' => 'Улица Гоголя, 3', 'latitude' => 55.7622, 'longitude' => 37.6173],
            ['address' => 'Улица Чехова, 4', 'latitude' => 55.7632, 'longitude' => 37.6173],
            ['address' => 'Улица Достоевского, 5', 'latitude' => 55.7642, 'longitude' => 37.6173],
        ]);

        DB::table('organizations')->insert([
            ['name' => 'Организация А', 'building_id' => 1],
            ['name' => 'Организация Б', 'building_id' => 1],
            ['name' => 'Организация В', 'building_id' => 2],
            ['name' => 'Организация Г', 'building_id' => 2],
            ['name' => 'Организация Д', 'building_id' => 3],
            ['name' => 'Организация Е', 'building_id' => 3],
            ['name' => 'Организация Ж', 'building_id' => 4],
            ['name' => 'Организация З', 'building_id' => 4],
            ['name' => 'Организация И', 'building_id' => 5],
            ['name' => 'Организация К', 'building_id' => 5],
        ]);

        DB::table('phones')->insert([
            ['number' => '+7 (495) 123-45-67', 'organization_id' => 1],
            ['number' => '+7 (495) 234-56-78', 'organization_id' => 1],
            ['number' => '+7 (495) 345-67-89', 'organization_id' => 2],
            ['number' => '+7 (495) 456-78-90', 'organization_id' => 3],
            ['number' => '+7 (495) 567-89-01', 'organization_id' => 4],
            ['number' => '+7 (495) 678-90-12', 'organization_id' => 5],
            ['number' => '+7 (495) 789-01-23', 'organization_id' => 6],
            ['number' => '+7 (495) 890-12-34', 'organization_id' => 7],
            ['number' => '+7 (495) 901-23-45', 'organization_id' => 8],
            ['number' => '+7 (495) 012-34-56', 'organization_id' => 9],
        ]);

        DB::table('activities')->insert([
            ['name' => 'Деятельность А', 'parent_id' => null],
            ['name' => 'Деятельность Б', 'parent_id' => null],
            ['name' => 'Деятельность В', 'parent_id' => null],
            ['name' => 'Деятельность Г', 'parent_id' => 1],
            ['name' => 'Деятельность Д', 'parent_id' => 1], 
        ]);

        DB::table('activity_organization')->insert([
            ['organization_id' => 1, 'activities_id' => 1],
            ['organization_id' => 1, 'activities_id' => 4],
            ['organization_id' => 2, 'activities_id' => 1],
            ['organization_id' => 3, 'activities_id' => 2],
            ['organization_id' => 4, 'activities_id' => 2],
            ['organization_id' => 5, 'activities_id' => 3],
            ['organization_id' => 6, 'activities_id' => 3],
            ['organization_id' => 7, 'activities_id' => 4],
            ['organization_id' => 8, 'activities_id' => 5],
            ['organization_id' => 9, 'activities_id' => 5],
        ]);   
    }
}
