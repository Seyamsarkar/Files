<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionLog;
use App\Models\NotificationLog;
use App\Models\ReviewerLogin;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller {
    public function transaction(Request $request) {
        $pageTitle    = 'Transaction Logs';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::searchable(['trx', 'user:username'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function loginHistory(Request $request) {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function reviewerLoginHistory(Request $request) {
        $loginLogs = ReviewerLogin::orderBy('id', 'desc')->with('reviewer');
        $pageTitle = 'Reviewer Login History';

        if ($request->search) {
            $search    = $request->search;
            $pageTitle = 'Reviewer Login History - ' . $search;
            $loginLogs = $loginLogs->whereHas('reviewer', function ($query) use ($search) {
                $query->where('username', $search);
            });
        }

        $loginLogs = $loginLogs->paginate(getPaginate());
        return view('admin.reports.reviewer_logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip) {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));

    }

    public function notificationHistory(Request $request) {
        $pageTitle = 'Notification History';
        $logs      = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id) {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function commissionHistory(Request $request) {
        $pageTitle = 'Commission History';
        $logs      = CommissionLog::where('type', 'deposit_commission');

        if ($request->search) {
            $search = $request->search;
            $logs   = $logs->where('trx', $search)->orWhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        }

        $logs = $logs->with(['user', 'byWho'])->latest()->paginate(getPaginate());
        return view('admin.reports.commission_history', compact('pageTitle', 'logs'));
    }

}
