<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Prepayset extends Controller
{
    public function ajaxPrePay(Request $request){

        $user = User::where('email', $request->email)->first();

        echo '<table align="center" border="1">';
        echo '<tr><th>名前</th><th>メールアドレス</th></tr>';
        echo '<tr><td>' . $user->name . '</td><td>' . $user->email . '</td></tr>';
        echo '</table>';
    }
}
