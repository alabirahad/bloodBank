<?php

namespace App\Http\Controllers;

use Validator;
use App\BloodGroup;
use App\Blood;
use App\Service;
use Session;
use Redirect;
use Helper;
use Response;
use App;
use View;
use PDF;
use Auth;
use Input;
use Illuminate\Http\Request;

class AvailableBloodController extends Controller {

    private $controller = 'AvailableBloodController';

    public function __construct() {
        
    }

    public function index(Request $request) {

        $targetArr = Blood::leftJoin('blood_group', 'blood_group.id', '=', 'blood.blood_group_id')
                ->select('blood.id', 'blood_group.name', 'blood.available')
                ->get();
        
        return view('availableBlood.index')->with(compact('targetArr'));
    }

}
