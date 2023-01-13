<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Sell;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SellController extends Controller
{
    public function all(Request $request)
    {
        $pageTitle = 'All Sells';
        $sells  = $this->paymentData();
        return view('admin.sell.history', compact('pageTitle', 'sells'));
    }
    public function pending(Request $request)
    {
        $pageTitle = 'Pending Sells';
        $sells  = $this->paymentData('pending');
        return view('admin.sell.history', compact('pageTitle', 'sells'));
    }

    public function approved(Request $request)
    {
        $pageTitle = 'Successful Sells';
        $sells  = $this->paymentData('approved');
        return view('admin.sell.history', compact('pageTitle', 'sells'));
    }

    public function rejected(Request $request)
    {
        $pageTitle = 'Rejected Sells';
        $sells  = $this->paymentData('rejected');
        return view('admin.sell.history', compact('pageTitle', 'sells'));
    }
    protected function paymentData($scope = null)
    {
        $sells = Sell::where('status', '!=', Status::SELL_INITIATE)->searchable(['trx', 'user:username']);
        if ($scope) {
            $sells = $sells->$scope();
        }
        $sells = $sells->dateFilter()->with(['product', 'author', 'user', 'deposit.gateway'])->latest()->paginate(getPaginate());
        return $sells;
    }
}
