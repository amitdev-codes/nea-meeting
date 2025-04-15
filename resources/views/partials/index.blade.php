@extends('layouts.contentNavbarLayout')

@section('content')
    <x-breadcrumb :title="$title" :items="[['label' => $title, 'route' => $routeName]]" />
    <x-data-table :id="Str::slug($title)" :routeName="$routeName" :columns="$columns" :columnsDefinition="$columnsDefinition" :title="$title" model="$model" />
@endsection
