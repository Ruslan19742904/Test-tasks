@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Описание:</strong> {{ $product->description }}</p>
            <p><strong>Цена:</strong> {{ number_format($product->price, 2) }} ₽</p>
            <p><strong>Количество:</strong> {{ $product->quantity }}</p>
            <p><strong>Категория:</strong> {{ $product->category->name }}</p>
            <p><strong>Создан:</strong> {{ $product->created_at->format('d.m.Y H:i') }}</p>
            <p><strong>Обновлен:</strong> {{ $product->updated_at->format('d.m.Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
