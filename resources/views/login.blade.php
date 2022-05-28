@extends('layout')

@section('title')Авторизация@endsection

@section('main_content')
    <div class="h-screen bg-white flex flex-col space-y-10 justify-center items-center">
        <div class="bg-white w-96 shadow-xl rounded p-5">
            <h1 class="text-3xl font-medium">Вход</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('login_process') }}" class="space-y-5 mt-5">
                @csrf
                <input name="email" type="text" class="w-full h-12 border border-red-500 rounded px-3" placeholder="Email" />

                <input name="password" type="password" class="w-full h-12 border border-gray-800 rounded px-3" placeholder="Пароль" />

                {{--<div>--}}
                    {{--<a href="#" class="font-medium text-blue-900 hover:bg-blue-300 rounded-md p-2">Забыли пароль?</a>--}}
                {{--</div>--}}

                <div>
                    <a href="{{ route('register') }}" class="font-medium text-blue-900 hover:bg-blue-300 rounded-md p-2">Регистрация</a>
                </div>

                <button type="submit" class="text-center w-full bg-blue-900 rounded-md text-white py-3 font-medium">Войти</button>
            </form>
        </div>
    </div>
@endsection
