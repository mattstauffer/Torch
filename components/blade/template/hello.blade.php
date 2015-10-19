@extends('layouts.Master')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar. {{ $value2 }}</p>
@endsection

@section('content')
    <p>This is my body content. {{ $value }}</p>
@endsection