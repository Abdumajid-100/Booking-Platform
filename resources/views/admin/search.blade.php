@extends('admin.layouts.app')

@section('title', 'Search | Booking')

@section('header')
    <p class="text-muted mb-0 mt-1">Search results for pages inside the admin panel.</p>
@endsection

@section('content')
    <div class="mt-4">
        @if ($query === '')
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-2">Search</h5>
                    <p class="text-muted mb-0">Enter a page name in the top search bar to find available sections.</p>
                </div>
            </div>
        @elseif ($results->isEmpty())
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-2">No results found</h5>
                    <p class="text-muted mb-0">No admin pages matched "<strong>{{ $query }}</strong>".</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach ($results as $result)
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ $result['title'] }}</h5>
                                <p class="text-muted mb-3">{{ $result['description'] }}</p>
                                <a href="{{ $result['route'] }}" class="btn btn-primary">Open</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
