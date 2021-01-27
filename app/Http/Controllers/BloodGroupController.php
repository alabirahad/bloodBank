<?php

namespace App\Http\Controllers;

use Validator;
use App\BloodGroup;
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

class BloodGroupController extends Controller {

    private $controller = 'BloodGroupController';

    public function __construct() {

    }

    public function index(Request $request) {

        $nameArr = BloodGroup::select('name')->orderBy('order', 'asc')->get();

        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = BloodGroup::select('blood_group.id', 'blood_group.name', 'blood_group.order')
                ->orderBy('blood_group.order', 'asc');

        //begin filtering
        $searchText = $request->fil_search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('blood_group.name', 'LIKE', '%' . $searchText . '%');
            });
        }


        //end filtering

        if ($request->download == 'pdf') {
            $targetArr = $targetArr->get();
        } else {
            $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        }


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/bloodGroup?page=' . $page);
        }

        if ($request->download == 'pdf') {
            $bloodGroupCode = bloodGroup::select('name')->where('id', Auth::user()->blood_group_id)->first();
            $pdf = PDF::loadView('bloodGroup.printBloodGroup', compact('targetArr', 'bloodGroupCode'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('bloodGroupList.pdf');
        } else {
            return view('bloodGroup.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
        }
    }
    
    public function filter(Request $request) {
        $url = 'fil_search=' . urlencode($request->fil_search);
        return Redirect::to('bloodGroup?' . $url);
    }

}
