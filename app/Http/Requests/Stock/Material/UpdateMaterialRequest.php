<?php

namespace App\Http\Requests\Stock\Material;

use App\Enums\Unit;
use App\Enums\StorageType;
use App\Enums\MaterialType;
use App\Models\Stock\Material;
use Illuminate\Validation\Rule;
use App\Models\Stock\StockGroup;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
{
    public const INTIAL_MATERIAL_NR = '001';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return
            [
                'name'   => [
                    'required', 'string', 'max:100',
                    Rule::unique('stock_materials')->ignore($this->route('material'))
                ],
                'cost' => ['nullable', 'integer'],
                'price' => ['nullable', 'integer'],
                'unit' => ['nullable', 'string', new Enum(Unit::class)],
                'loss_ratio' => ['nullable', 'integer'],
                'min_store' => ['nullable', 'integer'],
                'max_store' => ['nullable', 'integer'],
                'min_section' => ['nullable', 'integer'],
                'max_section' => ['nullable', 'integer'],
                'storage_type' => ['nullable', 'string', new Enum(StorageType::class)],
                'material_type' => ['nullable', 'string', new Enum(MaterialType::class)],
                'expire_date' => ['nullable', 'date'],
                'group_id'  => ['required', 'integer', 'exists:stock_groups,id'],
                'branch_id' => ['required', 'integer', 'exists:branchs,id'],
                'sectionIds' => ['required', 'array'],
                'sectionIds.*' => ['exists:stock_sections,id'],
            ];
    }
    public function messages()
    {
        return
            [
                'name.required'   => __('برجاء ادخال اسم الخامة'),
                'group_id.required' => __('برجاء اختيار المجموعة'),
                'sectionIds.required'  => __('برجاء اختيار الأقسام'),
            ];
    }

    /**
     * generateMaterialSerialNr
     *
     * @param  Material $material
     * @return void
     */
    public function updateMaterialSerialNr()
    {
        $material = $this->route('material');
        $newgroupId = $this->input('group_id');
        \Log::debug([
            "material" => $this->route('material')->group_id,
            "newgroupId" => $this->input('group_id'),
        ]);
        if ($material->group_id == $newgroupId) {
            return $material->serial_nr;
        } else {
            $stockGroup = StockGroup::find($newgroupId);
            $material = Material::where('group_id', $newgroupId)->latest('serial_nr')->first();
            if ($material) {

                $stockGroupSerialNr = $material->serial_nr;
                $lastDigits = substr($stockGroupSerialNr, -1); //01001
                $nextSerialNr = (int)$lastDigits + 1;
            } else {
                $nextSerialNr =  static::INTIAL_MATERIAL_NR; // 01
            }
            return $stockGroup->serial_nr . str_pad($nextSerialNr, strlen(self::INTIAL_MATERIAL_NR), '0', STR_PAD_LEFT);
        }
    }
}
