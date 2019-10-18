@extends('app')

@section('title', '销售订单金额数据统计')

@section('main')
	@include('sales.salesorders._statistics')

	{{-- pdf 预览 --}}
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title" id="myModalLabel">
						PDF 预览标题
					</h4>
				</div>
				<div class="modal-body" >
					<a class="media" id="pdfContainer"
					   @if (null !== $sohead->soheaddocs->where('type', 'swht')->first()) href="{{ config('custom.hxold.purchase_businesscontract_webdir') . 'swht/' . $sohead->id . '/' . $sohead->soheaddocs->where('type', 'swht')->first()->name }}" @else href="" @endif>

					</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>

	<div class="modal fade" id="myModal_jsxy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title" id="myModalLabel">
						PDF 预览标题
					</h4>
				</div>
				<div class="modal-body" >
					<a class="media" id="pdfContainer_jsxy"
					   @if (null !== $sohead->soheaddocs->where('type', 'jsxy')->first()) href="{{ config('custom.hxold.purchase_businesscontract_webdir') . 'jsxy/' . $sohead->id . '/' . $sohead->soheaddocs->where('type', 'jsxy')->first()->name }}" @else href="" @endif>

					</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>
@endsection

@section('script')
	<script type="text/javascript">
	    var tempNav = "tab1";
	    var temp = "tabid1";
	    var scrollPosMap = {tab1 : 0, tab2 : 0};
	    var nav1 = document.getElementById('tab1'), 
	    	nav2 = document.getElementById('tab2'),
	    	content1 = document.getElementById('tabid1'),
	    	content2 = document.getElementById('tabid2');
	    function changeTab(n){
			if (n == 1) {
				scrollPosMap['tab2'] = window.pageYOffset || document.documentElement.scrollTop;
				nav1.className="text selected";
				content1.style.display="block";
				nav2.className="text";
				content2.style.display="none";
				window.scrollTo(0, scrollPosMap['tab1']);
				console.log(scrollPosMap)
			}
			else {
				scrollPosMap['tab1'] = window.pageYOffset || document.documentElement.scrollTop;
				nav2.className="text selected";
				content2.style.display="block";
				nav1.className="text";
				content1.style.display="none";
				window.scrollTo(0, scrollPosMap['tab2']);
				console.log(scrollPosMap)
			}
			return false;
	    };
	</script>

	@if (Agent::isDesktop())
		<script src="/js/jquery.media.js"></script>

		<script src="http://g.alicdn.com/dingding/dingtalk-pc-api/2.5.0/index.js"></script>
		<script type="text/javascript">
            jQuery(document).ready(function(e) {
                $("a").attr("target", "_self");

                {{-- 不需要config和ready，直接通过DingTalkPC.ua.isInDingTalk来判断 --}}

                console.log(DingTalkPC.ua.isInDingTalk);
                if (DingTalkPC.ua.isInDingTalk)
                {
                    $("#showPdf").click(function() {
                        location.href = 'http://www.huaxing-east.cn:2015/pdfjs/build/generic/web/viewer.html?file=' + $("#showPdf").attr("href");
                        return false;
                    });

                    $("#showPdf_jsxy").click(function() {
                        location.href = 'http://www.huaxing-east.cn:2015/pdfjs/build/generic/web/viewer.html?file=' + $("#showPdf_jsxy").attr("href");
                        return false;
                    });
                }
                else
                {
                    $(function() {
                        $('#pdfContainer').media({width:'100%', height:800});
                        $('#pdfContainer_jsxy').media({width:'100%', height:800});
                    });

                    $("#showPdf").click(function() {
                        $('#myModal').modal();
                        return false;
                    });
                    $("#showPdf_jsxy").click(function() {
                        $('#myModal_jsxy').modal();
                        return false;
                    });
                }


                function showPdf() {
                    var container = document.getElementById("container");
                    container.style.display = "block";
                    var url = 'http://www.huaxing-east.cn:2015/HxCgFiles/swht/7592/S30C-916092615220%EF%BC%88%E5%8D%8E%E4%BA%9A%E7%94%B5%E8%A2%8B%E9%99%A4%E5%B0%98%E5%90%88%E5%90%8C%EF%BC%89.pdf';
                    PDFJS.workerSrc = '/js/pdf.worker.min.js';
                    PDFJS.getDocument(url).then(function getPdfHelloWorld(pdf) {
                        pdf.getPage(1).then(function getPageHelloWorld(page) {
                            var scale = 1;
                            var viewport = page.getViewport(scale);
                            var canvas = document.getElementById('the-canvas');
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            page.render(renderContext);
                        });
                    });
                }

                $("#btnTest").click(function() {
                    showPdf();

                });
            });
		</script>
	@elseif(Agent::isMobile())
		<script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
		<script type="text/javascript">
            $("#showPdf").click(function() {
                location.href = 'http://www.huaxing-east.cn:2015/pdfjs/build/generic/web/viewer.html?file=' + $("#showPdf").attr("href");
                return false;
            });
		</script>
	@endif
@endsection

