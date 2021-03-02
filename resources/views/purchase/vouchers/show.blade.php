@extends('navbarerp')

@section('main')
<p>
    凭证号: {{ $voucher->voucher_no }}
</p>
<p>
    金额: {{ $voucher->amount }}
</p>
<p>
    到账日期: {{ $voucher->post_date }}
</p>
<p>
    创建人: {{ $voucher->creator_user->name }}
</p>
<p>
    更新人: {{ $voucher->updater_user->name }}
</p>
<p>创建日期: {{ $voucher->created_at }}</p>
<p>修改日期: {{ $voucher->updated_at }}</p>
<p>备注: {{ $voucher->remark }}</p>
@stop