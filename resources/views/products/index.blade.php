@extends('layouts.app')
@section('content')
  <main class="container">
    <section>
      <div class="titlebar">
        <h1>Products</h1>
        <a href="{{ route('products.create') }}" class="btn-link">Add product</a>
      </div>
      @if ($massage = Session::get('success'))
        <script type="text/javascript">
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
          Toast.fire({
            icon: 'success',
            title: '{{ $massage }}'
          })
        </script>
      @endif
      <div class="table">
        <div class="table-filter">
          <div>
            <ul class="table-filter-list">
              <li>
                <p class="table-filter-link link-active">All</p>
              </li>
            </ul>
          </div>
        </div>
        <form method="get" action="{{ route('products.index') }}" accept-charset="UTF-8" role="search">
          <div class="table-search">
            <div>
              <button class="search-select">
                Search Product
              </button>
              <span class="search-select-arrow">
                <i class="fas fa-caret-down"></i>
              </span>
            </div>
            <div class="relative">
              <input class="search-input" type="text" name="search" placeholder="Search product..."
                     value="{{ request('search') }}">
            </div>
          </div>
        </form>
        <div class="table-product-head">
          <p>Image</p>
          <p>Name</p>
          <p>Category</p>
          <p>Inventory</p>
          <p>Actions</p>
        </div>
        @if (count($products) > 0)
          @foreach ($products as $product)
            <div class="table-product-body">
              <img src="{{ asset('images/' . $product->image) }}"/>
              <p>{{ $product->name }}</p>
              <p>{{ $product->category }}</p>
              <p>{{ $product->quantity }}</p>
              <div class="flex-center">
                <a class="flex-center" style="text-decoration: none; margin-right: 4px;" href="{{ route('products.edit', $product->id) }}">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                </a>
                <form class="flex-center" action="{{ route("products.destroy", $product->id) }}" method="POST">
                  @csrf
                  @method('delete')
                  <input type="hidden">
                  <button class="btn btn-danger" onclick="deleteConfirm(event)">
                    <i class="far fa-trash-alt"></i>
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        @else
          <p style="color: black; text-align: center;">
            No products
          </p>
        @endif
        <div class="table-paginate">
          {{ $products->links('layouts.pagination') }}
        </div>
      </div>
    </section>
  </main>
  <script>
    window.deleteConfirm = (e) => {
      e.preventDefault()
      console.log(e.target.form)
      let form = e.target.form;
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      })
    }
  </script>
@endsection
