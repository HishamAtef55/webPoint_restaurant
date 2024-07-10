@php $title='المخازن';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class='store'>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <a class="btn btn-primary mb-2" href="{{ route('stock.stores.create') }}">
                        إنشاء مخزن
                    </a>
                    <div class="table-responsive rounded">
                        <table class="table table-light text-center table-data">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">name</th>
                                    <th scope="col">phone</th>
                                    <th scope="col">address</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>


                                @if ($stores->isNotEmpty())
                                    @foreach ($stores as $store)
                                        <tr>
                                            <th>{{ $store->id }}</th>
                                            <td>{{ $store->name }}</td>
                                            <td>{{ $store->phone ?? '-' }}</td>
                                            <td>{{ $store->address ?? '-' }}</td>
                                            <td>
                                                <button title="تعديل" class="btn btn-success">

                                                    <i class="far fa-edit"></i>
                                                </button>

                                                <button title="عرض" class="btn btn-primary">

                                                    <i class="fa fa-eye" aria-hidden="true"></i>

                                                </button>
                                                <button class="btn btn-danger" data-id="{{ $store->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>

                                        <td colspan="5"> لا يوجد مخازن </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </section>
@endsection
