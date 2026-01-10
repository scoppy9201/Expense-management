@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thống kê chi tiêu</h3>
                <div class="card-menu"><img src="{{ asset('images/plus.png') }}" alt="More"></div>
            </div>
            <div class="chart-placeholder">Biểu đồ thống kê</div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hoạt động gần đây</h3>
                <div class="card-menu"><img src="{{ asset('images/plus.png') }}" alt="More"></div>
            </div>
            <div class="chart-placeholder">Danh sách hoạt động</div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue"><img src="{{ asset('images/wallet.png') }}" alt="Balance"></div>
            <div class="stat-label">Số dư hiện tại</div>
            <div class="stat-value">15,750,000₫</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><img src="{{ asset('images/profits.png') }}" alt="Income"></div>
            <div class="stat-label">Thu nhập tháng này</div>
            <div class="stat-value">25,000,000₫</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><img src="{{ asset('images/budget.png') }}" alt="Expense"></div>
            <div class="stat-label">Chi tiêu tháng này</div>
            <div class="stat-value">12,450,000₫</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><img src="{{ asset('images/saving.png') }}" alt="Saving"></div>
            <div class="stat-label">Tiết kiệm tháng này</div>
            <div class="stat-value">12,550,000₫</div>
        </div>
    </div>
@endsection