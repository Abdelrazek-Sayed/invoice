<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\Section;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $sections = Section::all();
        return view('products.product', compact('products', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate(

                [
                    'name' => 'required',
                    'section_id' => 'required|exists:sections,id',
                    'description' => 'nullable',
                ],
                [
                    'name.required' => ' اسم المنتج مطلوب',
                    'name.unique' => 'اسم المنتج موجود مسبقا',
                    'section_id.required' => ' اسم القسم مطلوب',
                    'section_id.exists' => 'هذا القسم غير موجود',
                ]
            );
            // $validated = $request->validated();

            $request_data = $request->all();
            Product::create($request_data);


            return redirect()->route('product.index')->with(['success' => 'تمت اضافة المنتج بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('product.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
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


            return redirect()->route('product.index')->with(['success' => 'تم تعديل القسم بنجاح']);
        } catch (Exception $e) {
            return $e;
            return redirect()->route('product.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product_id = $request->product_id;
        try {
            $product = Product::findOrFail($product_id);
            if (!$product) {
                return redirect()->route('product.index')->with(['error' => ' هذا المنتج غير موجود']);
            }
            $product->delete();
            return redirect()->route('product.index')->with(['success' => 'تمت حذف المنتج بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('product.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }
}
