@extends('app')

@section('title')
身份验证
@endsection

@section('main')

<html>


<body style="margin: 0;overflow: hidden">
<div id="tmPlayer" class="tmPlayer" style="height: 557px; width: 100%; height: 100%"></div>



</body>
</html>


    @endsection

@section('script')
    <script src="/pdfjs/build/pdf.js"></script>
    <script src="/pdfjs/build/pdf.worker.js"></script>
    <script type="text/javascript">
        {{--var var_filepath = decodeURIComponent("@filepath");//不能跨域--}}
        var var_filepath = '/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf';//不能跨域
        var var_win_height = 500;

        jQuery(document).ready(function () {
            resetPlayerSize();
        });

        $(window).resize(function () {
            resetPlayerSize();
        });

        function resetPlayerSize() {
            var_win_height = $(window).height();
            $(".tmPlayer").css({ "height": var_win_height + "px" });
        }
    </script>

    <script type="text/javascript">
        $('.tmPlayer').html('<iframe frameBorder="0" scrolling="no" src="/pdfjs/build/generic/web/viewer.html?file=' +
            var_filepath +
            '" style="width:100%; height:100%;"></iframe>');
    </script>
@endsection
