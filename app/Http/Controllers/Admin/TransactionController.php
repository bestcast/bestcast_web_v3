<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Meta;
use App\Models\Subscription;
use App\Models\Transaction;
use Auth;
use Field;
use Lib;
use Paymentgateway;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $data = Transaction::getList();
        return view('admin.transaction.index', ['data'=>$data]);
    }
}
