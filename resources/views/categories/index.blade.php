@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('app.categories_title') }}</h2>
    @if(auth()->check() && auth()->user()->role_id === 1)
        <a href="{{ route('categories.create') }}" class="btn btn-danger">{{ __('app.new_category') }}</a>
    @endif
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>{{ __('app.name') }}</th>
            <th>{{ __('app.slug') }}</th>
            <th>{{ __('app.description') }}</th>
            <th>{{ __('app.products') }}</th>
            @if(auth()->check() && auth()->user()->role_id === 1)
                <th>{{ __('app.actions') }}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ app()->getLocale() == 'en' ? ($category->name_en ?? $category->name) : $category->name }}</td>
            <td><code>{{ $category->slug }}</code></td>
            <td>{{ Str::limit(app()->getLocale() == 'en' ? ($category->description_en ?? $category->description) : $category->description, 50) }}</td>
            <td>{{ $category->products->count() }}</td>
            @if(auth()->check() && auth()->user()->role_id === 1)
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">{{ __('app.edit') }}</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit"
                            onclick="return confirm('¿{{ __('app.delete') }} categoría?')">{{ __('app.delete') }}</button>
                    </form>
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

{{ $categories->links() }}
@endsection