<?php
namespace App\Traits;

use App\Models\ComponentsItems;
use App\Models\detailsComponent;
use App\Models\DetailsItem;
use App\Models\Item;
use App\Models\MainComponents;
use App\Models\mainDetailsComponent;
use App\Models\mainMaterialRecipe;
use App\Models\material;
use App\Models\materialRecipe;
use App\Models\Units;

Trait MainFunction {
    public function storeImage($image,$path){
        $file = $image -> getClientOriginalExtension();
        $no_rand = rand(10,1000);
        $file_name =  time() . $no_rand .  '.' . $file;
        $image -> move($path, $file_name);
        return asset($path) .'/'. $file_name;
    }
    public function changeMaterialCost($code,$cost)
    {
        $updateMaterialCost = material::limit(1)->where(['code' => $code])->first();
        $updateMaterialCost->cost = $cost;
        $updateMaterialCost->save();
        $itemId = 0;
        $recipeItem = 0;
        if ($updateMaterialCost) {
            $getSmallUnit = Units::limit(1)->where(['name'=>$updateMaterialCost->unit])->pluck('size')->first();
            // Update Recipe For Item
            $updateComponentItem = ComponentsItems::where(['material_id' => $code])->get();
            foreach ($updateComponentItem as $material) {
                $material->cost = $material->quantity * ($cost/$getSmallUnit);
                $material->save();
                $recipeItem = ComponentsItems::where(['branch'=>$material->branch,'item_id'=>$material->item_id])->sum('cost');
                $itemPrice = Item::limit(1)->where('id',$material->item_id)->pluck('price')->first();
                $updateMainRecipe = MainComponents::limit(1)->where(['branch'=>$material->branch,'item'=>$material->item_id])->first();
                $updateMainRecipe->cost = $recipeItem;
                $updateMainRecipe->percentage = ($recipeItem / $itemPrice) * 100;
                $updateMainRecipe->save();
            }
            // Update Recipe For Details Item
            $updateRecipeDetailsItem = detailsComponent::where(['material_id' => $code])->get();
            foreach ($updateRecipeDetailsItem as $material){
                $material->cost = $material->quantity * ($cost/$getSmallUnit);
                $material->save();
                $recipeDetails = detailsComponent::where(['main_id'=>$material->main_id])->sum('cost');
                $detailsPrice = DetailsItem::limit(1)->where(['item_id'=>$material->item,'detail_id'=>$material->details])->pluck('price')->first();
                if($detailsPrice == 0)
                    $newPercentage = 100;
                else
                    $newPercentage = ($recipeDetails / $detailsPrice) * 100;
                $updateMainRecipeDetails = mainDetailsComponent::limit(1)->where(['id'=>$material->main_id])->first();
                $updateMainRecipeDetails->cost = $recipeDetails;
                $updateMainRecipeDetails->percentage = $newPercentage;
                $updateMainRecipeDetails->save();
            }
            // Update Recipe For Material Recipe
            $updateMaterialRecipe = materialRecipe::where(['material_id'=>$code])->get();
            foreach ($updateMaterialRecipe as $material){
                $material->cost = $material->quantity * ($cost/$getSmallUnit);
                $material->save();
                $materialRecipe = materialRecipe::where(['main_id'=>$material->main_id])->sum('cost');
                $newPercentage = ($materialRecipe / $cost) * 100;
                $updateMainMaterialRecipe = mainMaterialRecipe::limit(1)->where(['id'=>$material->main_id])->first();
                $updateMainMaterialRecipe->cost = $materialRecipe;
                $updateMainMaterialRecipe->percentage = $newPercentage;
                $updateMainMaterialRecipe->save();
            }
        }
    }
}
?>
