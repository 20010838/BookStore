@extends('layouts.admin')

@section('title', 'Báo cáo tồn kho')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Báo cáo tồn kho</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> In báo cáo
        </button>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số sách</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Còn hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inStock }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Sắp hết hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStock }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Hết hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outOfStock }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tình trạng tồn kho</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Còn hàng
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> Sắp hết hàng
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> Hết hàng
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tồn kho theo danh mục</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách sách</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Lọc:</div>
                    <a class="dropdown-item" href="{{ route('admin.reports.inventory', ['filter' => 'all']) }}">Tất cả</a>
                    <a class="dropdown-item" href="{{ route('admin.reports.inventory', ['filter' => 'in_stock']) }}">Còn hàng</a>
                    <a class="dropdown-item" href="{{ route('admin.reports.inventory', ['filter' => 'low_stock']) }}">Sắp hết hàng</a>
                    <a class="dropdown-item" href="{{ route('admin.reports.inventory', ['filter' => 'out_of_stock']) }}">Hết hàng</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Ảnh bìa</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books ?? [] as $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-thumbnail" style="width: 50px; height: 70px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary" style="width: 50px; height: 70px;"></div>
                                @endif
                            </td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author->name ?? 'N/A' }}</td>
                            <td>{{ $book->category->name ?? 'N/A' }}</td>
                            <td>{{ number_format($book->price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @if($book->stock == 0)
                                    <span class="badge bg-danger">Hết hàng</span>
                                @elseif($book->stock < 10)
                                    <span class="badge bg-warning">{{ $book->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $book->stock }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Cập nhật
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có sách nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($books) && $books->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Inventory Chart
    var ctx = document.getElementById('inventoryChart').getContext('2d');
    var inventoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Còn hàng', 'Sắp hết hàng', 'Hết hàng'],
            datasets: [{
                data: [{{ $inStock }}, {{ $lowStock }}, {{ $outOfStock }}],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#c23321'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%',
        }
    });

    // Category Chart
    var categoryCtx = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($books->groupBy('category.name') as $category => $categoryBooks)
                    '{{ $category ?? "Không có danh mục" }}',
                @endforeach
            ],
            datasets: [{
                label: 'Số lượng sách',
                data: [
                    @foreach($books->groupBy('category.name') as $category => $categoryBooks)
                        {{ $categoryBooks->count() }},
                    @endforeach
                ],
                backgroundColor: '#4e73df',
                hoverBackgroundColor: '#2e59d9',
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush 