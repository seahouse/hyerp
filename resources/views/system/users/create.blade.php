@extends('navbarerp')

@section('main')
    <h1>添加用户</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/users']) !!}
        @include('system.users._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    


    
    @include('errors.list')
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {

            {{--$("#position").blur(function() {--}}
                {{--$.ajax({--}}
                    {{--type: "POST",--}}
                    {{--url: "{{ url('system/users/test') }}",--}}
{{--//                    data: $("form#formRetract").serialize(),--}}
{{--//                    contentType:"application/x-www-form-urlencoded",--}}
                    {{--error: function(xhr, ajaxOptions, thrownError) {--}}
                        {{--alert($("form#formRetract").serialize());--}}
                        {{--alert('error');--}}
                        {{--alert(xhr.status);--}}
                        {{--alert(xhr.responseText);--}}
                        {{--alert(ajaxOptions);--}}
                        {{--alert(thrownError);--}}
                    {{--},--}}
                    {{--success: function(result) {--}}
                        {{--alert('操作完成.');--}}
                    {{--},--}}
                {{--});--}}
            {{--});--}}

        });
    </script>

@endsection