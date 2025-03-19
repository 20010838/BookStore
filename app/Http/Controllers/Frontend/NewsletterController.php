<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Đăng ký nhận bản tin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Lưu email vào session hoặc database
        // Trong trường hợp thực tế, bạn có thể muốn tạo một model Newsletter
        // và lưu dữ liệu vào cơ sở dữ liệu

        // Trường hợp đơn giản: lưu vào session
        session()->flash('newsletter_emails', $request->email);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đăng ký nhận bản tin!');
    }
}
