
<div class="form-group">
    {!! Form::label('lblcharass', '属性:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        <table class="table" id="charassTable">
            <thead>
                <tr>
                    <th>属性</th>
                    <th>值</th>
                    <th>默认</th>
                    <th>价格</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->charasses as $charass)
                    <tr>
                        <td>{!! $charass->char->name !!}</td>
                        <td>{!! $charass->value !!}</td>
                        <td>@if($charass->default) {{ '是' }}  @else {{ '否' }} @endif</td>
                        <td>{!! $charass->price !!}</td>
                        <td><button type="button" id="btnRemoveCharass" class="glyphicon glyphicon-remove" onclick="removecharass({!! $charass->id !!})"></button></td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
        <td><button type="button" class="btn btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="{!! $item->id !!}">+</button></td>
 {{--                       <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button></td>--}}

    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">添加属性</h4>
      </div>
      <div class="modal-body">
        <form method="post" id="formCharass" class="form-horizontal">
            {!! csrf_field() !!}
          <div class="form-group">
            {!! Form::label('char_id', '属性:', ['class' => 'col-sm-2 control-label']) !!}
{{--            <label for="recipient-name" class="control-label">属性:</label> --}}
            <div class='col-sm-10'>
            {!! Form::select('char_id', $charIList, null, ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('value', '值:', ['class' => 'col-sm-2 control-label']) !!}
{{--            <label for="message-text" class="control-label">值:</label> --}}
            <div class='col-sm-10'>
            {!! Form::text('value', null, ['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('price', '价格:', ['class' => 'col-sm-2 control-label']) !!}
{{--            <label for="message-text" class="control-label">价格:</label> --}}
            <div class='col-sm-10'>
            {!! Form::text('price', '0.0', ['class' => 'form-control']) !!}
            </div>
          </div>
          {!! Form::hidden('target_type', 'I', ['class' => 'form-control']) !!}
          {!! Form::hidden('target_id', $item->id, ['class' => 'form-control']) !!}
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnAddCharass">确定</button>
      </div>
    </div>
  </div>
</div>