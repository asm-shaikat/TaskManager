@extends('welcome')
@section('title','Dashboard')
@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 ">
    <div class="flex justify-between gap-6">
        <div class="card w-96 bg-emerald-400 text-white">
            <div class="card-body flex justify-center items-center">
                <div class="flex gap-40">
                    <div class="mr-4">
                        <img src="{{ asset('assets/images/svg/user-solid.svg') }}" class="w-10" style="filter: invert(100%);" alt="user-svg">
                    </div>
                    <div class="font-extrabold">
                        <p class="text-3xl">{{ count($users) }}</p>
                        <small>Users</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card w-96 bg-cyan-500 text-white">
            <div class="card-body flex justify-center items-center">
                <div class="flex gap-40">
                    <div class="svg-section mr-4">
                        <img src="{{ asset('assets/images/svg/list-check-solid.svg') }}" class="w-10" style="filter: invert(100%);" alt="user-svg">
                    </div>
                    <div class="font-extrabold">
                        <p class="text-3xl">{{ count($tasks) }}</p>
                        <small>Tasks</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection