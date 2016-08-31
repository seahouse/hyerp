@if ($items->count())
    @foreach($items as $item)
    <div class="reimbList list-group">
        <a href="{{ url($item->url . $item->id . $href_suffix) }}" class="list-group-item">
            {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}
            @if (Auth::user()->dingtalkGetUser()->avatar == '')
                <div class='col-xs-2 col-sm-2 name'>{{ Auth::user()->dingtalkGetUser()->name }}</div>
            @else
                <div class='col-xs-2 col-sm-2'><img class="name img" src="{{ Auth::user()->dingtalkGetUser()->avatar }}" /></div>
            @endif
            <div class='col-xs-7 col-sm-7 content'>
                <div title="{{ $item->applicant_name }}的报销" class="title">{{ $item->applicant_name }}的{{ $item->type }}</div>
                {{-- 以下的代码判断说明：如果审批id大于0，则显示“待审批”，否则显示“审批完成”。 --}}
                {{-- 当状态为“审批完成”时，字体为灰色 --}}
                @if ($item->status > 0)
                    <div class="statusTodo">待审批</div>
                @else
                    <div class="statusDone">审批完成</div>      {{-- 此时，字体要修改为灰色 --}}
                @endif
            </div>
            <div class='col-xs-3 col-sm-3 time'><span >{{ $item->created_at }}</span></div>
        </a>
    </div>     
    @endforeach
@endif
