<?php

namespace App\Http\Controllers;

use App\UserGroup;
use App\RequestBlood;
use App\User;
use Session;
use Response;
use PDF;
use Auth;
use Illuminate\Http\Request;

class HomePageController extends Controller {

    private $controller = 'HomePageController';

    public function __construct() {
        
    }

    public function index(Request $request) {
        $requestBloodArr = RequestBlood::leftJoin('blood_group', 'blood_group.id', '=', 'request_blood.blood_group_id')
                ->leftJoin('division', 'division.id', '=', 'request_blood.division_id')
                ->whereIn('status', ['0','1'])
                ->select('blood_group.name as blood', 'request_blood.quantity', 'division.name as division'
                        , 'request_blood.date', 'request_blood.id', 'request_blood.status')
                ->get();


        return view('homePage.index')->with(compact('requestBloodArr'));
    }

    public function requestAccepet(Request $request) {
        
        $update = RequestBlood::where('id', $request->request_id)
                ->update(['status' => '1']);
//        $userAvailableForDonate = 
        $requestedUser = User::join('request_blood', 'request_blood.blood_group_id', '=', 'users.blood_group_id')
                ->update(['users.blood_request' => '1']);
//        echo '<pre>';        print_r($requestedUser->toArray()); exit;

        if ($update && $requestedUser) {
            return Response::json(['success' => true, 'message' => 'Pending Request'], 200);
        } else {
            return Response::json(array('success' => false, 'message' => 'Pending Failed'), 401);
        }
    }
    public function requestCancel(Request $request) {
        
        $update = RequestBlood::where('id', $request->request_id)
                ->update(['status' => '3']);
//        echo '<pre>';        print_r($update); exit;

        if ($update) {
            return Response::json(['success' => true, 'message' => 'Canceled Request'], 200);
        } else {
            return Response::json(array('success' => false, 'message' => 'Canceled Failed'), 401);
        }
    }
    public function requestDonet(Request $request) {
        
        $update = RequestBlood::where('id', $request->request_id)
                ->update(['status' => '2']);
        
//        $available = \App\Blood::join('request_blood','request_blood.blood_group_id', '=', 'blood.blood_group_id')
//                ->where('request_blood.id', $request->request_id);
//        echo '<pre>';        print_r($update); exit;

        if ($update) {
            return Response::json(['success' => true, 'message' => 'Donete Successfully'], 200);
        } else {
            return Response::json(array('success' => false, 'message' => 'Donate Failed'), 401);
        }
    }

}
