<?php

namespace App\Models\Approval;

use App\Models\System\User;
use Illuminate\Database\Eloquent\Model;

class Epcseceningoptrecord extends Model
{
    //
    protected $fillable = [
        'epcsecening_id',
        'userid',
        'date',
        'operation_type',
        'operation_result',
        'remark',
    ];

    public function operator() {
        return $this->belongsTo(User::class, 'userid', 'dtuserid');
    }

    // 操作类型：中文形式
    public function operation_type_zh() {
        $operation_type_zh = '';
        switch ($this->getAttribute('operation_type')) {
            case 'EXECUTE_TASK_NORMAL':
                $operation_type_zh = '正常执行任务';
                break;
            case 'EXECUTE_TASK_AGENT':
                $operation_type_zh = '代理人执行任务';
                break;
            case 'APPEND_TASK_BEFORE':
                $operation_type_zh = '前加签任务';
                break;
            case 'APPEND_TASK_AFTER':
                $operation_type_zh = '后加签任务';
                break;
            case 'REDIRECT_TASK':
                $operation_type_zh = '转交任务';
                break;
            case 'START_PROCESS_INSTANCE':
                $operation_type_zh = '发起流程';
                break;
            case 'TERMINATE_PROCESS_INSTANCE':
                $operation_type_zh = '终止(撤销)流程';
                break;
            case 'FINISH_PROCESS_INSTANCE':
                $operation_type_zh = '结束流程实例';
                break;
            case 'ADD_REMARK':
                $operation_type_zh = '添加评论';
                break;
            case 'redirect_process':
                $operation_type_zh = '审批退回';
                break;
        }
        return $operation_type_zh;
    }

    // 操作结果：中文形式
    public function operation_result_zh() {
        $operation_result_zh = '';
        switch ($this->getAttribute('operation_result')) {
            case 'AGREE':
                $operation_result_zh = '同意';
                break;
            case 'REFUSE':
                $operation_result_zh = '拒绝';
                break;
        }
        return $operation_result_zh;
    }
}
