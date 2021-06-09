<?php

namespace App\Http\Controllers;

use notify;
use Exception;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.section', compact('sections'));
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
    public function store(SectionRequest $request)
    {
        // $validated = $request->validated();
        try {
            $request_data = $request->all();
            $creator =  Auth::user()->name;
            $request_data['created_by'] = $creator;
            Section::create($request_data);

            // notify()->success('تمت اضافة القسم بنجاح');

            return redirect()->route('section.index')->with(['success' => 'تمت اضافة القسم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('section.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name' => "required|unique:sections,name,$id",
            'description' => 'nullable',
        ], [

            'name.required' => 'يرجي ادخال اسم القسم',
            'name.unique' => 'اسم القسم مسجل مسبقا',
            // 'description.required' =>'يرجي ادخال البيان',
        ]);

        try {
            $section = Section::findOrFail($id);
            if (!$section) {
                return redirect()->route('section.index')->with(['error' => ' هذا القسم غير موجود']);
            }
            $section->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return redirect()->route('section.index')->with(['success' => 'تمت تعديل القسم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('section.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
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
        $id = $request->id;
        try {
            $section = Section::findOrFail($id);
            if (!$section) {
                return redirect()->route('section.index')->with(['error' => ' هذا القسم غير موجود']);
            }
            $section->delete();
            return redirect()->route('section.index')->with(['success' => 'تمت حذف القسم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('section.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        try {
            $section = Section::findOrFail($id);
            if (!$section) {
                return redirect()->route('section.index')->with(['error' => ' هذا القسم غير موجود']);
            }
            $section->delete();
            return redirect()->route('section.index')->with(['success' => 'تمت حذف القسم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('section.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }
}
