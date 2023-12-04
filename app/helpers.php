<?php
use App\Models\Branch;
use App\Models\stocksection;
use App\Models\Stores;
function branchName($id){
    $branch = Branch::find($id);
    return $branch->name;
}
function sectionName($id){
    $section = stocksection::find($id);
    return $section->name;
}
function storeName($id){
    $section = Stores::find($id);
    return $section->name;
}
function saveImage($image , $path){
    $file = $image -> getClientOriginalExtension();
    $no_rand = rand(10,1000);
    $file_name =  time() . $no_rand .  '.' . $file;
    $image -> move($path, $file_name);
    return $path .'/'. $file_name;
}
