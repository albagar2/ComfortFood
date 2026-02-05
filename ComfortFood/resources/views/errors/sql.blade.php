@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Error de Base de Datos') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <h1 class="text-5xl font-bold text-red-600">Error SQL</h1>
                    <p class="text-2xl mt-4">Parece que hay un problema con la conexión de datos.</p>
                    <p class="text-gray-500 mt-2">Nuestros técnicos están trabajando en ello.</p>
                    <a href="{{ route('home') }}"
                        class="mt-8 inline-block bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection