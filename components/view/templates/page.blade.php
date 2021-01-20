@extends('layout')

@section('content')

    <h1>{{ $title }}</h1>
    <p>{{ $text }}</p>

    <x-alert type="success" message="Good luck!" />
@endsection
