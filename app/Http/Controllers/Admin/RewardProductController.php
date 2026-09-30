<?php

namespace App\Http\Controllers\Admin;

use App\RewardCatalogCategory;
use App\RewardProduct;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Redirect;
use Validator;
use View;

class RewardProductController extends CodespurController
{
    protected $manager_name = 'Reward Products';
    protected $folder_name  = 'reward-products';
    protected $upload_dir   = '/reward-products';

    public function index()
    {
        $manager_name   = $this->manager_name;
        $PARENT_ID      = 28;
        $route_create   = route('admin.reward-products.create');
        $route_data     = route('admin.reward-products.data');
        $route_category = route('admin.reward-catalog-categories');
        $categories     = RewardCatalogCategory::orderBy('name', 'asc')->get();

        return view('admin.'.$this->folder_name.'.list', compact(
            'PARENT_ID', 'manager_name', 'route_create', 'route_data', 'route_category', 'categories'
        ));
    }

    public function data(Request $request)
    {
        $upload_dir = $this->upload_dir;

        $data = RewardProduct::leftjoin('reward_catalog_categories', 'reward_catalog_categories.id', 'reward_products.category_id')
            ->select(
                'reward_products.*',
                'reward_catalog_categories.name as category_name',
                DB::raw("DATE_FORMAT(tbl_reward_products.created_at, '%d %M %Y') as add_date")
            );

        $categoryId = $request->input('category_id');
        if (!empty($categoryId)) {
            $data = $data->where('reward_products.category_id', $categoryId);
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('image', function ($product) use ($upload_dir) {
                if (empty($product->image)) {
                    return '<span class="text-muted">No Image</span>';
                }

                return '<img src="'.url('uploads'.$upload_dir.'/'.$product->image).'" width="45" height="45" />';
            })
            ->editColumn('price', function ($product) {
                return $product->price === null ? '-' : number_format($product->price, 2);
            })
            ->editColumn('status', function ($product) {
                return $product->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', '<div class="btn-group">
                <a class="btn btn-primary" href="{{ route(\'admin.reward-products.edit\', [\'id\' => $id]) }}" title="Edit"><i class="fa fa-edit"></i></a>
                <a class="btn btn-info" data-toggle="modal" data-target="#modal-regular" href="{{ route(\'admin.reward-products.confirm-status\', [\'id\' => $id]) }}" title="Change Status"><i class="fa fa-info-circle"></i></a>
                <a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{ route(\'admin.reward-products.confirm-delete\', [\'id\' => $id]) }}" title="Delete"><i class="fa fa-trash"></i></a>
            </div>')
            ->rawColumns(['image', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $manager_name = $this->manager_name;
        $PARENT_ID    = 28;
        $route_url    = route('admin.reward-products.store');
        $categories   = RewardCatalogCategory::orderBy('name', 'asc')->get();

        return view('admin.'.$this->folder_name.'.create', compact(
            'PARENT_ID', 'manager_name', 'route_url', 'categories'
        ));
    }

    public function store(Request $request)
    {
        return $this->saveProduct($request);
    }

    public function edit($id)
    {
        $data = RewardProduct::find($id);
        if (empty($data)) {
            return redirect()->route('admin.reward-products')
                ->with('error', 'This is not a valid action, data not found.');
        }

        $manager_name = $this->manager_name;
        $PARENT_ID    = 28;
        $route_url    = route('admin.reward-products.update', ['id' => $id]);
        $categories   = RewardCatalogCategory::orderBy('name', 'asc')->get();

        return view('admin.'.$this->folder_name.'.create', compact(
            'data', 'PARENT_ID', 'manager_name', 'route_url', 'categories'
        ));
    }

    public function update(Request $request, $id)
    {
        return $this->saveProduct($request, $id);
    }

    protected function saveProduct(Request $request, $id = null)
    {
        $product = $id ? RewardProduct::find($id) : new RewardProduct();
        if (empty($product)) {
            return response()->json([
                'status'     => 'error',
                'error_msg'  => 'Reward product not found.',
                'slideToTop' => 'yes',
            ]);
        }

        $rules = [
            'category_id'       => 'required|integer|exists:reward_catalog_categories,id',
            'name'              => 'required|max:255',
            'sku'               => 'nullable|max:100',
            'short_description' => 'nullable|max:500',
            'description'       => 'nullable',
            'points_required'   => 'required|integer|min:1',
            'price'             => 'nullable|numeric|min:0',
            'stock'             => 'nullable|integer|min:0',
            'sort_order'        => 'nullable|integer|min:0',
            'status'            => 'nullable|boolean',
            'image'             => ($id ? 'nullable' : 'required').'|image|mimes:jpg,jpeg,png,gif',
        ];

        $attributes = [
            'category_id'     => 'product category',
            'points_required' => 'reward points',
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return response()->json([
                'errorArray' => $validator->errors(),
                'error_msg'  => 'Opps ! Please fill required fields.',
                'slideToTop' => 'yes',
            ]);
        }

        $slug = Str::slug($request->name);
        $slugValidator = Validator::make(
            ['slug' => $slug],
            ['slug' => 'required|max:255|unique:reward_products,slug'.($id ? ','.$id : '')]
        );
        if ($slugValidator->fails()) {
            return response()->json([
                'errorArray' => ['name' => ['This product name is already used.']],
                'error_msg'  => 'Opps ! This product name is already used.',
                'slideToTop' => 'yes',
            ]);
        }

        $product->category_id       = $request->category_id;
        $product->name              = $request->name;
        $product->slug              = $slug;
        $product->sku               = $request->sku;
        $product->short_description = $request->short_description;
        $product->description       = $request->description;
        $product->points_required   = $request->points_required;
        $product->price             = $request->filled('price') ? $request->price : null;
        $product->stock             = $request->filled('stock') ? $request->stock : null;
        $product->sort_order        = $request->filled('sort_order') ? $request->sort_order : 0;
        $product->status            = $request->filled('status') ? $request->status : 1;

        if ($file = $request->file('image')) {
            $safeName = Str::random(20).'.'.$file->getClientOriginalExtension();
            Storage::disk('uploads')->putFileAs($this->upload_dir, $file, $safeName);

            if ($product->image) {
                Storage::disk('uploads')->delete($this->upload_dir.'/'.$product->image);
            }
            $product->image = $safeName;
        }

        $product->save();

        $message = $id
            ? 'Reward Product Updated Successfully'
            : 'Reward Product Created Successfully';

        return response()->json([
            'status'      => 'success',
            'msgType'     => 'success',
            'success'     => true,
            'slideToTop'  => true,
            'success_msg' => $message,
            'url'         => route('admin.reward-products'),
        ]);
    }

    public function getModalStatus($id)
    {
        $detail = RewardProduct::find($id);
        $model  = $this->manager_name;
        $error  = empty($detail) ? 'Data Not Found' : null;
        $type   = !empty($detail) && $detail->status ? 'Deactivate' : 'Activate';
        $confirm_route = route('admin.reward-products.status', ['id' => $id]);

        return View('admin/layouts/status_modal_confirmation', compact(
            'error', 'type', 'confirm_route', 'model'
        ));
    }

    public function changeStatus($id)
    {
        $detail = RewardProduct::find($id);
        if (empty($detail)) {
            return redirect()->route('admin.reward-products')
                ->with('error', 'Oops! Something went wrong.');
        }

        $detail->status = !$detail->status;
        $detail->save();

        return redirect()->route('admin.reward-products')
            ->with('success', 'Status changed successfully.');
    }

    public function getModalDelete($id)
    {
        $detail = RewardProduct::find($id);
        $model  = $this->manager_name;
        $error  = empty($detail) ? 'Data Not Found' : null;
        $confirm_route = route('admin.reward-products.destroy', ['id' => $id]);

        return View('admin/layouts/delete_modal_confirmation', compact(
            'error', 'model', 'confirm_route'
        ));
    }

    public function destroy($id)
    {
        $detail = RewardProduct::find($id);
        if (empty($detail)) {
            return redirect()->route('admin.reward-products')
                ->with('error', 'Data Not Found.');
        }

        $detail->delete();

        return redirect()->route('admin.reward-products')
            ->with('success', 'Reward Product Deleted Successfully');
    }
}
