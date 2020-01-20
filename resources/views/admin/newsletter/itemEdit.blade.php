@extends('layouts.master')

@section('title', 'Newsletter Item Edit')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['newsletter', 'item', 'edit']"
                             :menuLinks="['', route('admin.newsletter.item.index'), '']" />
    </div>
@endsection

@section('content')
    <x-layout.portlet active="1" label="Edit Newsletter Item">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form id="edit_form" method="post" enctype="multipart/form-data"
                      action="{{route('admin.newsletter.item.update', ['slug' => $item->slug, 'type' => 'details'])}}">
                    <div class="modal-body">
                        @csrf
                        <x-form.input name="name" value="{{$item->name}}" label="Name" />

                        <x-form.input name="subject" value="{{$item->subject}}" label="Subject" />

                        <x-form.textarea label="Description" name="description">{{$item->description}}</x-form.textarea>
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                           href="{{route('admin.newsletter.item.index')}}">Cancel
                        </a>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </x-layout.portlet>
@endsection
@section('script')
    <script src="{{asset('assets/js/admin/newsletter/itemEdit.js')}}"></script>
@endsection
