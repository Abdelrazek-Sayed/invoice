<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    

    function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }
    public function index()
    {
        $products = Product::all();
        $sections = Section::all();
        return view('products.product', compact('products', 'sections'));
    }


    public function store(StoreProductRequest $request)
    {
        try {
            $request_data = $request->all();
            Product::create($request_data);

            return redirect()->route('product.index')->with(['notify_success' => 'تمت اضافة المنتج بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('product.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(

            [
                'product_name' => 'required',
                'product_id' => 'required|exists:products,id',
                'description' => 'nullable',
            ],
            [
                'product_name.required' => ' اسم المنتج مطلوب',
                'product_name.unique' => 'اسم المنتج موجود مسبقا',

            ]
        );

        try {

            $section_id = Section::where('name', $request->section_name)->first()->id;

            $product_id = $request->product_id;
            $product = Product::findOrFail($product_id);
            if (!$product) {
                return redirect()->route('product.index')->with(['error' => ' هذا المنتج غير موجود']);
            }


            $product->update([
                'name' => $request->product_name,
                'description' => $request->description,
                'section_id' => $section_id,
            ]);


            return redirect()->route('product.index')->with(['notify_success' => 'تم تعديل القسم بنجاح']);
        } catch (Exception $e) {
            return $e;
            return redirect()->route('product.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }


    public function destroy(Request $request)
    {
        $product_id = $request->product_id;
        try {
            $product = Product::findOrFail($product_id);
            if (!$product) {
                return redirect()->route('product.index')->with(['error' => ' هذا المنتج غير موجود']);
            }
            $product->delete();
            return redirect()->route('product.index')->with(['notify_success' => 'تمت حذف المنتج بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('product.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }
}
