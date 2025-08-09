@extends('layouts.master')

@section('content')
    <section class="py-4">
        <div class="container">
            <h2 class="text-center mb-4">Find Your IP Telephone Number</h2>

            <!-- Filter & Search -->
            <form id="filterForm" method="GET" class="row mb-3">
                <div class="col-sm-4 col-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                           <i class="fas fa-filter"></i>
                        </span>
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                                Available
                            </option>
                            <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>
                                Unavailable
                            </option>
                        </select>
                        <select name="price" id="priceFilter" class="form-select">
                            <option value="all" {{ request('price') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Free</option>
                            <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-12 ms-auto d-flex">
                    <input type="text" name="search" id="searchInput" class="form-control me-2"
                           placeholder="Search by Number" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary" id="searchBtn">Search</button>
                </div>
            </form>

            <!-- Responsive Table -->
            <div id="tableContainerWrapper" class="position-relative">
                <!-- Loader overlay -->
                <div id="tableLoader"
                     class="d-none position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex justify-content-center align-items-center"
                     style="z-index: 10;">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <!-- Table -->
                <div id="tableContainer" class="table-responsive">
                    @include('website.partials.ip_table', ['ipNumbers' => $ipNumbers])
                </div>
            </div>

        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            const statusFilter = document.getElementById('statusFilter');
            const priceFilter = document.getElementById('priceFilter');
            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('searchInput');
            const tableContainer = document.getElementById('tableContainer');

            // AJAX form submit function
            function submitFormAjax() {
                document.getElementById('tableLoader').classList.remove('d-none');
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData).toString();

                fetch("{{ route('search_number') }}?" + params, {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        document.getElementById('tableLoader').classList.add('d-none');
                    })
                    .catch(err => console.error(err));
            }

            // Change filter triggers AJAX
            statusFilter.addEventListener('change', function () {
                submitFormAjax();
            });
            priceFilter.addEventListener('change', function () {
                submitFormAjax();
            });

            // Search button click
            searchBtn.addEventListener('click', function (e) {
                e.preventDefault();
                submitFormAjax();
            });

            // Press Enter in search input triggers search
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitFormAjax();
                }
            });
        });
    </script>
@endsection

