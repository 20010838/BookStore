<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get total sales
        $totalSales = Order::where('status', '!=', 'cancelled')
            ->sum('total_amount');
            
        // Get total orders
        $totalOrders = Order::count();
        
        // Get total users
        $totalUsers = User::count();
        
        // Get total books
        $totalBooks = Book::count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get low stock books
        $lowStockBooks = Book::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
            
        // Get sales data for chart (last 7 days)
        $salesData = $this->getSalesData();
        
        // Get top selling books
        $topSellingBooks = $this->getTopSellingBooks();
        
        return view('admin.dashboard', compact(
            'totalSales', 
            'totalOrders', 
            'totalUsers', 
            'totalBooks', 
            'recentOrders', 
            'lowStockBooks',
            'salesData',
            'topSellingBooks'
        ));
    }
    
    /**
     * Get sales data for the last 7 days.
     */
    private function getSalesData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $salesData = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Fill in missing dates with zero sales
        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $found = false;
            
            foreach ($salesData as $data) {
                if ($data->date == $date) {
                    $result[] = [
                        'date' => $date,
                        'total' => $data->total,
                    ];
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $result[] = [
                    'date' => $date,
                    'total' => 0,
                ];
            }
        }
        
        return $result;
    }
    
    /**
     * Get top selling books.
     */
    private function getTopSellingBooks()
    {
        return DB::table('order_items')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'books.id',
                'books.title',
                'books.cover_image',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->groupBy('books.id', 'books.title', 'books.cover_image')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Display sales report.
     */
    public function salesReport(Request $request)
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
    
    /**
     * Display inventory report.
     */
    public function inventoryReport()
    {
        $books = Book::with(['author', 'category'])
            ->orderBy('stock', 'asc')
            ->get();
            
        $totalBooks = $books->count();
        $outOfStock = $books->where('stock', 0)->count();
        $lowStock = $books->where('stock', '>', 0)->where('stock', '<', 10)->count();
        $inStock = $books->where('stock', '>=', 10)->count();
        
        return view('admin.reports.inventory', compact(
            'books', 
            'totalBooks', 
            'outOfStock', 
            'lowStock', 
            'inStock'
        ));
    }
}
