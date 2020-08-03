<?php

namespace Extensions\Plugins\Support_alfiory__930442654\App\Controllers\Admin;

use Illuminate\Http\Request;
use App\System\Extensions\Plugin\Core\PluginController as AdminController;
use Extensions\Plugins\Support_alfiory__930442654\App\Models\SupportAlfioryCategory as SupportCategory;

class CategoriesController extends AdminController
{
    public $admin = true;

    public function index () {
        $categories = SupportCategory::get();

        return $this->view(
            'admin.categories',
            trans('support_alfiory::admin.support') . ' - ' . trans('support_alfiory::admin.categories'),
            compact('categories')
        );
    }

    public function add_category (Request $request) {
        $request->validate([
            "name" => ['required', 'string'],
            "color" => ['required', 'string', 'regex:/^#?([0-9a-f]{3}){1,2}$/i']
        ],
        [
            "name.required" => trans('support_alfiory::admin.required'),
            "color.required" => trans('support_alfiory::admin.required'),
            "color.regex" => trans('support_alfiory::admin.color_wrong_format')
        ]);

        $categoryId = SupportCategory::insertGetId([
            'name' => $request->name,
            'color' => str_replace("#", "", $request->color),
            'created_at' => now()
        ]);

        return ['message' => trans('support_alfiory::admin.added_category'), 'id' => $categoryId];
    }


    public function delete_category (Request $request) {
        $request->validate([
            "id" => ['required', 'integer'],
        ]);

        SupportCategory::findOrFail($request->id)->delete();

        return ['message' => trans('support_alfiory::admin.deleted_category')];
    }

    public function edit_category (Request $request) {
        $request->validate([
            "id" => ['required', 'integer'],
            "name" => ['required', 'string'],
            "color" => ['required', 'string', 'regex:/^#?([0-9a-f]{3}){1,2}$/i']
        ],
        [
            "name.required" => trans('support_alfiory::admin.required'),
            "color.required" => trans('support_alfiory::admin.required'),
            "color.regex" => trans('support_alfiory::admin.color_wrong_format')
        ]);

        SupportCategory::findOrFail($request->id)->update([
            'name' => $request->name,
            'color' => str_replace("#", "", $request->color),
            'updated_at' => now()
        ]);

        return ['message' => trans('support_alfiory::admin.edited_category')];
    }
}
