@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Error del servidor') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <h1 class="text-9xl font-bold text-red-500">500</h1>
                    <p class="text-2xl mt-4">Algo ha salido mal en nuestros fogones. Por favor, inténtalo de nuevo más
                        tarde.</p>
                    <a href="{{ route('home') }}"
                        class="mt-8 inline-block bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection