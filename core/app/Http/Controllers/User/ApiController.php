<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ApiIp;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        $pageTitle = "API and IPs";
        $ips       = ApiIp::where('user_id', auth()->id())->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.api_and_ip', compact('pageTitle', 'ips'));
    }
    public function reset()
    {
        $user = auth()->user();
        $checkUnique = true;
        while ($checkUnique) {
            $user->api_key = getTrx(31);
            $checkUnique = User::where('api_key', $user->api_key)->exists();
        }
        $user->save();

        $notify[] = ['success', 'Key regenerated successfully'];
        return back()->withNotify($notify);
    }
    public function whitelistIp(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip'
        ]);
        $apiIp          = new ApiIp();
        $apiIp->ip      = $request->ip;
        $apiIp->user_id = auth()->id();
        $apiIp->save();
        $notify[]               = ['success', 'Ip whitelisted successfully'];
        return back()->withNotify($notify);
    }
    public function whitelistIpRemove(Request $request, $ipId)
    {
        $apiIp          = ApiIp::findOrFail($ipId);
        $apiIp->delete();
        $notify[]               = ['success', 'IP removed successfully'];
        return back()->withNotify($notify);
    }
    public function documentation()
    {
        $pageTitle = "API Documentations";
        return view($this->activeTemplate . 'user.api_documentations', compact('pageTitle'));
    }
}
