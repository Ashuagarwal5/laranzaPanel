<?php

namespace App\Http\Controllers\Admin;

use App\RewardCatalogCategory;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Redirect;
use Validator;
use View;

class RewardCategoryController extends CodespurController
{
    protected $manager_name = 'Product Categories';
    protected $folder_name = 'reward-catalog-categories';

    public function index()
    {
        $manager_name = $this->manager_name;
        $PARENT_ID = 28;
        $route_create = route('admin.reward-catalog-categories.form-modal');
        $route_data = route('admin.reward-catalog-categories.data');

        return view('admin.'.$this->folder_name.'.list', compact(
            'PARENT_ID', 'manager_name', 'route_create', 'route_data'
        ));
    }

    public function data()
    {
        $data = RewardCatalogCategory::select(
            'reward_catalog_categories.*',
            DB::raw("DATE_FORMAT(tbl_reward_catalog_categories.created_at, '%d %M %Y') as add_date")
        );

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', '<div class="btn-group">
                <a class="btn btn-primary category-form-modal" href="javascript:void(0)" data-url="{{ route(\'admin.reward-catalog-categories.form-modal\', [\'id\' => $id]) }}" title="Edit"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{ route(\'admin.reward-catalog-categories.confirm-delete\', [\'id\' => $id]) }}" title="Delete"><i class="fa fa-trash"></i></a>
            </div>')
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Returns the popup create / edit form used on the listing page.
     */
    public function formModal($id = null)
    {
        if ($id) {
            $data = RewardCatalogCategory::find($id);
            if (empty($data)) {
                return '<div class="modal-body">This is not a valid action, data not found.</div>';
            }

            $route_url = route('admin.reward-catalog-categories.update', ['id' => $id]);

            return View('admin.'.$this->folder_name.'._form_modal', compact('data', 'route_url'));
        }

        $route_url = route('admin.reward-catalog-categories.store');

        return View('admin.'.$this->folder_name.'._form_modal', compact('route_url'));
    }

    public function create()
    {
        $manager_name = $this->manager_name;
        $PARENT_ID = 28;
        $route_url = route('admin.reward-catalog-categories.store');

        return view('admin.'.$this->folder_name.'.create', compact(
            'PARENT_ID', 'manager_name', 'route_url'
        ));
    }

    public function store(Request $request)
    {
        return $this->saveCategory($request);
    }

    public function edit($id)
    {
        $data = RewardCatalogCategory::find($id);
        if (empty($data)) {
            return redirect()->route('admin.reward-catalog-categories')
                ->with('error', 'This is not a valid action, data not found.');
        }

        $manager_name = $this->manager_name;
        $PARENT_ID = 28;
        $route_url = route('admin.reward-catalog-categories.update', ['id' => $id]);

        return view('admin.'.$this->folder_name.'.create', compact(
            'data', 'PARENT_ID', 'manager_name', 'route_url'
        ));
    }

    public function update(Request $request, $id)
    {
        return $this->saveCategory($request, $id);
    }

    protected function saveCategory(Request $request, $id = null)
    {
        $category = $id ? RewardCatalogCategory::find($id) : new RewardCatalogCategory();
        if (empty($category)) {
            return response()->json([
                'status' => 'error',
                'error_msg' => 'Reward catalog category not found.',
                'slideToTop' => 'yes',
            ]);
        }

        $rules = [
            'name' => 'required|max:255|unique:reward_catalog_categories,name'.($id ? ','.$id : ''),
            'slug' => 'nullable|max:255|unique:reward_catalog_categories,slug'.($id ? ','.$id : ''),
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'sort_order' => 'nullable|integer|min:0',
        ];

        $attributes = ['name' => 'category name'];

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return response()->json([
                'errorArray' => $validator->errors(),
                'error_msg' => 'Opps ! Please fill required fields.',
                'slideToTop' => 'yes',
            ]);
        }

        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        $slugValidator = Validator::make(
            ['slug' => $slug],
            ['slug' => 'required|max:255|unique:reward_catalog_categories,slug'.($id ? ','.$id : '')]
        );
        if ($slugValidator->fails()) {
            return response()->json([
                'errorArray' => $slugValidator->errors(),
                'error_msg' => 'Opps ! Please use a unique slug.',
                'slideToTop' => 'yes',
            ]);
        }

        $category->name = $request->name;
        $category->slug = $slug;

        // The popup form only posts the name, so keep whatever is already stored.
        if ($request->has('description')) {
            $category->description = $request->description;
        }
        if ($request->filled('sort_order')) {
            $category->sort_order = $request->sort_order;
        } elseif (!$id) {
            $category->sort_order = 0;
        }

        if ($file = $request->file('image')) {
            $folderName = '/reward-catalog-categories';
            $safeName = Str::random(20).'.'.$file->getClientOriginalExtension();
            Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);

            if ($category->image) {
                Storage::disk('uploads')->delete($folderName.'/'.$category->image);
            }
            $category->image = $safeName;
        }

        $category->save();

        $message = $id
            ? 'Product Category Updated Successfully'
            : 'Product Category Created Successfully';

        $output = [
            'status' => 'success',
            'msgType' => 'success',
            'success' => true,
            'slideToTop' => true,
            'success_msg' => $message,
        ];

        // The popup just closes and refreshes the listing behind it.
        if ($request->input('from_modal')) {
            $output['closeModal'] = '#modal-category-form';
            $output['selfReload'] = true;
        } else {
            $output['url'] = route('admin.reward-catalog-categories');
        }

        return response()->json($output);
    }

    public function getModalDelete($id)
    {
        $detail = RewardCatalogCategory::find($id);
        $model = $this->manager_name;
        $error = empty($detail) ? 'Data Not Found' : null;
        $confirm_route = route('admin.reward-catalog-categories.destroy', ['id' => $id]);

        return View('admin/layouts/delete_modal_confirmation', compact(
            'error', 'model', 'confirm_route'
        ));
    }

    public function destroy($id)
    {
        $detail = RewardCatalogCategory::find($id);
        if (empty($detail)) {
            return redirect()->route('admin.reward-catalog-categories')
                ->with('error', 'Data Not Found.');
        }

        $detail->delete();

        return redirect()->route('admin.reward-catalog-categories')
            ->with('success', $this->manager_name.' Deleted Successfully');
    }
}
