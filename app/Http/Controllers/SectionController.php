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
   

    function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }
    public function index()
    {
        $sections = Section::all();
        return view('sections.section', compact('sections'));
    }


    public function store(SectionRequest $request)
    {
        // $validated = $request->validated();
        try {
            $request_data = $request->all();
            $creator =  Auth::user()->name;
            $request_data['created_by'] = $creator;
            Section::create($request_data);

            // notify()->success('تمت اضافة القسم بنجاح');

            return redirect()->route('section.index')->with(['notify_success' => 'تمت اضافة القسم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('section.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }


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
            return redirect()->route('section.index')->with(['notify_success' => 'تمت تعديل القسم بنجاح']);
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
            return redirect()->route('section.index')->with(['notify_success' => 'تم حذف القسم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('section.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }
}
