<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\UserGroup;
use App\BloodGroup;
use App\Division;
use App\District;
use App\Thana;
use Session;
use Redirect;
use Auth;
use File;
use Hash;
use Helper;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct() {
        Validator::extend('complexPassword', function($attribute, $value, $parameters) {
            $password = $parameters[1];

            if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[!@#$%^&*()])(?=\S*[\d])\S*$/', $password)) {
                return true;
            }
            return false;
        });
    }

    public function index(Request $request) {
        $nameArr = User::select('full_name')->where('status', '1')->orderBy('group_id', 'asc')->get();

        $qpArr = $request->all();

        $userGroupArr = UserGroup::orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;

        $targetArr = User::join('user_group', 'user_group.id', '=', 'users.group_id')
                ->select('user_group.name as group_name'
                        , 'users.group_id', 'users.id'
                        , 'users.full_name', 'users.username'
                        , 'users.photo', 'users.email'
                        , 'users.phone')
                ->orderBy('users.status', 'asc')
                ->orderBy('user_group.order', 'asc');

        //begin filtering
        $searchText = $request->fil_search;

//        if (!empty($searchText)) {
        $targetArr->where(function ($query) use ($searchText) {
            $query->where('users.full_name', 'LIKE', '%' . $searchText . '%');
        });
//        }

        if (!empty($request->fil_group_id)) {
            $targetArr = $targetArr->where('users.group_id', '=', $request->fil_group_id);
        }
        //end filtering


        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/user?page=' . $page);
        }


        return view('user.index')->with(compact('qpArr', 'targetArr', 'groupList'
                                , 'nameArr'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $userGroupArr = UserGroup::orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;
        $userNameArr = User::select('users.full_name')->where('status', '1')->orderBy('id', 'desc')->get();
        $bloodGroupList = array('0' => __('label.SELECT_BLOOD_GROUP_OPT')) + BloodGroup::orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', !empty($addressInfo->division_id) ? $addressInfo->division_id : 0)
                        ->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', !empty($addressInfo->district_id) ? $addressInfo->district_id : 0)
                        ->pluck('name', 'id')->toArray();

        return view('user.create')->with(compact('qpArr', 'userNameArr', 'groupList'
                                , 'bloodGroupList', 'divisionList', 'districtList', 'thanaList'));
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $rules = [
            'group_id' => 'required|not_in:0',
//            'blood_group_id' => 'required|not_in:0',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'alternative_phone' => 'different:phone',
            'full_name' => 'required',
            'username' => 'required|alpha_dash|unique:users',
            'password' => 'required|complex_password:,' . $request->password,
            'conf_password' => 'required|same:password',
            'religion' => 'required',
            'date_of_birth' => 'required',
            'weight' => 'required',
        ];

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
            'full_name.required' => __('label.THE_NAME_FIELD_IS_REQUIRED'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('user/create' . $pageNumber)
                            ->withInput($request->except('photo', 'password', 'conf_password'))
                            ->withErrors($validator);
        }



        //file upload
        $file = $request->file('photo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $uploadSuccess = $file->move('public/uploads/user', $fileName);
        }

        $target = new User;
        $target->group_id = $request->group_id;
        $target->blood_group_id = $request->blood_group_id;
        $target->full_name = $request->full_name;
        $target->username = $request->username;
        $target->password = Hash::make($request->password);
        $target->phone = $request->phone;
        $target->alternative_phone = $request->alternative_phone;
        $target->email = $request->email;
        $target->religion = $request->religion;
        $target->date_of_birth = Helper::dateFormatConvert($request->date_of_birth);
        $target->weight = $request->weight;
        $target->photo = !empty($fileName) ? $fileName : '';
        $target->present_division_id = $request->present_division_id;
        $target->present_district_id = $request->present_district_id;
        $target->present_thana_id = $request->present_thana_id;
        $target->present_address_details = $request->present_address_details;
        $target->permanent_division_id = $request->permanent_division_id;
        $target->permanent_district_id = $request->permanent_district_id;
        $target->permanent_thana_id = $request->permanent_thana_id;
        $target->permanent_address_details = $request->permanent_address_details;
        $target->status = 1;


        if ($target->save()) {
            Session::flash('success', __('label.USER_CREATED_SUCCESSFULLY'));
            return redirect('user');
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_CREATED'));
            return redirect('user/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
//        echo '<pre>';        print_r($request->all());exit;
        $qpArr = $request->all();
        $target = User::find($id);
        $bloodGroupList = array('0' => __('label.SELECT_BLOOD_GROUP_OPT')) + BloodGroup::orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        $userNameArr = User::select('users.full_name')->where('status', '1')->orderBy('id', 'desc')->get();

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('user');
        }

        //passing param for custom function
        $userGroupArr = UserGroup::orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $groupList = array('0' => __('label.SELECT_USER_GROUP_OPT')) + $userGroupArr;

        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', !empty($addressInfo->division_id) ? $addressInfo->division_id : 0)
                        ->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', !empty($addressInfo->district_id) ? $addressInfo->district_id : 0)
                        ->pluck('name', 'id')->toArray();

        return view('user.edit')->with(compact('target', 'qpArr', 'groupList', 'bloodGroupList'
                                , 'userNameArr', 'divisionList', 'districtList', 'thanaList'));
    }

    public function update(Request $request, $id) {

//        echo '<pre>';        print_r($request->all());exit;

        $target = User::find($id);
        $previousFileName = $target->photo;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $messages = array(
            'password.complex_password' => __('label.WEAK_PASSWORD_FOLLOW_PASSWORD_INSTRUCTION'),
            'full_name.required' => __('label.THE_NAME_FIELD_IS_REQUIRED'),
        );

        $rules = [
            'group_id' => 'required|not_in:0',
//            'blood_group_id' => 'required|not_in:0',
            'email' => 'required|unique:users,email,' . $id,
            'phone' => 'required',
            'alternative_phone' => 'different:phone',
            'full_name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username,' . $id,
            'religion' => 'required',
            'date_of_birth' => 'required',
            'weight' => 'required',
        ];

        if (!empty($request->password)) {
            $rules['password'] = 'complex_password:,' . $request->password;
            $rules['conf_password'] = 'same:password';
        }

        $validator = Validator::make($request->all(), $rules, $messages);


        if (!empty($request->photo)) {
            $validator->photo = 'max:1024|mimes:jpeg,png,gif,jpg';
        }

        if ($validator->fails()) {
            return redirect('user/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all)
                            ->withErrors($validator);
        }

        if (!empty($request->photo)) {
            $prevfileName = 'public/uploads/user/' . $target->photo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        $file = $request->file('photo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $uploadSuccess = $file->move('public/uploads/user', $fileName);
//            echo '<pre>';print_r($fileName);exit;
        }

        $target->group_id = $request->group_id;
        $target->blood_group_id = $request->blood_group_id;
        $target->full_name = $request->full_name;
        $target->username = $request->username;
        if (!empty($request->password)) {
            $target->password = Hash::make($request->password);
        }
        $target->phone = $request->phone;
        $target->alternative_phone = $request->alternative_phone;
        $target->email = $request->email;
        $target->religion = $request->religion;
        $target->date_of_birth = Helper::dateFormatConvert($request->date_of_birth);
        $target->weight = $request->weight;
        $target->photo = !empty($fileName) ? $fileName : $previousFileName;
        $target->present_division_id = $request->present_division_id;
        $target->present_district_id = $request->present_district_id;
        $target->present_thana_id = $request->present_thana_id;
        $target->present_address_details = $request->present_address_details;
        $target->permanent_division_id = $request->permanent_division_id;
        $target->permanent_district_id = $request->permanent_district_id;
        $target->permanent_thana_id = $request->permanent_thana_id;
        $target->permanent_address_details = $request->permanent_address_details;
        $target->status = 1;


        if ($target->save()) {
            Session::flash('success', __('label.USER_UPDATED_SUCCESSFULLY'));
            return redirect('user' . $pageNumber);
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_UPDATED'));
            return redirect('user/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {

        $target = User::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        $fileName = 'public/uploads/user/' . $target->photo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            Session::flash('error', __('label.USER_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.USER_COULD_NOT_BE_DELETED'));
        }
        return redirect('user' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . urlencode($request->fil_search) . '&fil_group_id=' . $request->fil_group_id;
        return Redirect::to('user?' . $url);
    }

    //For Districts
    public function getDistrict(Request $request) {
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->division_id)
                        ->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')];
        $htmldistrict = view('user.districts')->with(compact('districtList'))->render();
        $htmlThana = view('user.thana')->with(compact('thanaList'))->render();
        return response()->json(['html' => $htmldistrict, 'htmlThana' => $htmlThana]);
        //End getDistrict function
    }

    //For Thana
    public function getThana(Request $request) {
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + THANA::where('district_id', $request->district_id)->pluck('name', 'id')->toArray();
        $htmlThana = view('cm.details.thana')->with(compact('thanaList'))->render();
        return response()->json(['html' => $htmlThana]);
        //End getThana function
    }

}
