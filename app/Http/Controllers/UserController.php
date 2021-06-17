<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:قائمة المستخدمين', ['only' => ['index']]);
        $this->middleware('permission:اضافة مستخدم', ['only' => ['create', 'store']]);
        $this->middleware('permission:تعديل مستخدم', ['only' => ['edit', 'update']]);
        $this->middleware('permission:حذف مستخدم', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->get();
        return view('users.show_users', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.Add_user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password|min:8|max:15',
            'roles_name' => 'required'
        ], [

            'required' => 'هذا الحقل مطلوب',
            'unique' => 'هذا الحقل موجود مسبقا',
            'password.same' => 'كلمات المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب الا تقل عن 8 احرف ',
            'password.max' => 'كلمة المرور يجب الا تزيد عن 15 احرف ',

        ]);
        try {

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->assignRole($request->input('roles_name'));

            return redirect()->route('users.index')
                ->with(['notify_success' => 'تم اضافة المستخدم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('users.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
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
        $user = User::find($id);
        if (!$user) {
            return back()->with(['error' => ' المستخدم غير موجود']);
        }
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with(['error' => ' المستخدم غير موجود']);
        }
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|same:confirm-password',
            'roles_name' => 'required'
        ], [

            'required' => 'هذا الحقل مطلوب',
            'unique' => 'هذا الحقل موجود مسبقا',
            'password.same' => 'كلمات المرور غير متطابقة',
        ]);
        try {
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }

            $user = User::find($id);
            if (!$user) {
                return back()->with(['error' => ' المستخدم غير موجود']);
            }
            $user->update($input);
            DB::table('model_has_roles')->where('model_id', $id)->delete();

            $user->assignRole($request->input('roles_name'));

            return redirect()->route('users.index')
                ->with(['notify_success' => 'تم تعديل المستخدم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('users.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
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
        try {
            $user_id = $request->user_id;
            $user =   User::find($user_id);
            if (!$user) {
                return back()->with(['error' => ' المستخدم غير موجود']);
            }
            $user->delete();
            return redirect()->route('users.index')
                ->with(['notify_delete' => 'تم حذف المستخدم بنجاح']);
        } catch (Exception $e) {
            // return $e;
            return redirect()->route('users.index')->with(['error' => 'هناك خطأ ما يرجى الاتصال بمزود الخدمة']);
        }
    }
}
