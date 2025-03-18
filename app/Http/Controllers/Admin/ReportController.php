<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display inventory report.
     */
    public function inventory()
    {
        $books = Book::with(['author', 'category'])
            ->orderBy('stock', 'asc')
            ->paginate(10);
            
        $totalBooks = Book::count();
        $outOfStock = Book::where('stock', 0)->count();
        $lowStock = Book::where('stock', '>', 0)->where('stock', '<', 10)->count();
        $inStock = Book::where('stock', '>=', 10)->count();
        
        return view('admin.reports.inventory', compact(
            'books', 
            'totalBooks', 
            'outOfStock', 
            'lowStock', 
            'inStock'
        ));
    }

    /**
     * Display sales report.
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $orders = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        
        // Group by payment method
        $paymentMethodStats = $orders->groupBy('payment_method')
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('total_amount'),
                ];
            });
            
        // Add delivered orders count
        $paymentMethodStats['delivered'] = [
            'count' => $orders->where('status', 'delivered')->count(),
            'total' => $orders->where('status', 'delivered')->sum('total_amount'),
        ];
            
        // Group by day
        $dailySales = $orders->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('total_amount'),
                ];
            });
            
        return view('admin.reports.sales', compact(
            'orders', 
            'totalSales', 
            'totalOrders', 
            'paymentMethodStats', 
            'dailySales',
            'startDate',
            'endDate'
        ));
    }
}
