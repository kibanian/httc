<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Event;
use App\Attend;
use App\Card;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

//参加者の参加優先順位を確定する
class AttenderArrangementService extends CoreService
{

    private $int_properties = [
        'event_id',
        'user_id'
    ];

    private $event_id;
    private $event_data;
    private $attenders;
    private $until_seven_days_ago;
    private $since_seven_days_ago;
    private $selected_user;
    private $newcomer_count = 0;
    private $limit_number = 0;
    private $vacancy_count = 0;
    private $rejected_attenders;
    private $seven_days_ago_from_start_datetime;
    private $maximum_replace_count;
    private $one_card_attenders;
    private $host_id;
    private $attended_newcomer_count;
    private $replaced_count;

    function __construct()
    {
        parent::__construct();
        
        $this->rejected_attenders = collect();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function set_event_data()
    {
        
        $this->event_data = Event::FindById($this->event_id)
            ->FindAppliable()
            ->FindBeforeStarting()
            ->first();

        Log::debug('event:'.$this->event_data);
        
        if($this->event_data == []){ return false; }

        //新規参加者の人数
        $this->newcomer_count = $this->event_data->newcomer_count;
        //参加人数の上限
        $this->limit_number = $this->event_data->member_limit;
        $this->host_id = $this->event_data->host_id;
        //イベント開始日時
        $this->seven_days_ago_from_start_datetime = Carbon::parse($this->event_data->start_datetime)
            ->addDay(-7)
            ->format('Y-m-d H:i:s');

        return true;
    }

    public function set_applied_data()
    {
        //1週間以上前に参加表明した参加希望者を取得
        $this->until_seven_days_ago = Attend::FindByEventId($this->event_id)
            ->FindAppliedUntilSevenDaysAgo($this->seven_days_ago_from_start_datetime)
            ->FindAppliedOrApprovedOrWaiting()
            ->orderByCardCountDesc()
            ->orderByUpdatedAtAsc()
            ->get();
        //1週間以内に参加表明した参加希望者を取得
        $this->since_seven_days_ago = Attend::FindByEventId($this->event_id)
            ->FindAppliedSinceSevenDaysAgo($this->seven_days_ago_from_start_datetime)
            ->FindAppliedOrApprovedOrWaiting()
            ->orderByUpdatedAtAsc()
            ->get();
    }

    //参加希望者の存在チェック
    public function has_attenders(): bool
    {
        Log::debug(sprintf('【%s】LINE:%s Not Empty:%s', __METHOD__, __LINE__,
            $this->attenders->isNotEmpty()
        ));
        return $this->attenders->isNotEmpty();
    }

    //参加者オブジェクトをセット
    public function set_attenders($is_until)
    {
        Log::debug('is_until:'.$is_until);

        $this->attenders = ($is_until)
        ? $this->until_seven_days_ago
        : $this->since_seven_days_ago;

        Log::debug(sprintf('【%s】LINE:%s attenders:%s', __METHOD__, __LINE__,
            $this->attenders->pluck('user_id')
        ));
    }

    //イベントの主催者が参加希望者の中にいるか
    public function has_host_user(): bool
    {
        Log::debug(sprintf('【%s】LINE:%s Host User:%s', __METHOD__, __LINE__, $this->event_data->host_id));
        return $this->attenders->contains('user_id', $this->event_data->host_id);
    }

    //参加者オブジェクトから、指定したユーザーIDの参加者を取得
    private function select_attender_by_user_id(int $user_id)
    {
        $this->selected_user = $this->attenders->where('user_id', $user_id)->first();
        Log::debug(sprintf('【%s】LINE:%s selected_user:%s', __METHOD__, __LINE__, $this->selected_user));
    }

    //指定された回数だけ、参加者オブジェクトから最後の値を削除
    //削除するユーザーは別のプロパティに保存
    private function pop_attender(int $time=1)
    {

        for($i = 0;$i < $time;$i++)
        {
            $this->rejected_attenders->push($this->attenders->pop());
        }

        Log::debug(sprintf('【%s】LINE:%s rejected_attenders:%s', __METHOD__, __LINE__,
            $this->rejected_attenders->pluck('user_id')
        ));
    }

    //指定したユーザーを参加者オブジェクトから削除
    private function reject_attender_by_user_id(int $user_id)
    {
    
        $this->attenders = $this->attenders->reject(function($value) use ($user_id){
            return $value->user_id == $user_id;
        });

    }

    //参加者オブジェクトの先頭に参加者を追加
    private function prepend_attender()
    {
        $this->attenders->prepend($this->selected_user);
    }

    //主催者を参加者オブジェクトの先頭に移動させる
    public function arrange_host_user(bool $is_until)
    {
        Log::debug(sprintf('【%s】LINE:%s attenders:%s', __METHOD__, __LINE__,
            $this->attenders->pluck($is_until ? 'card_count' : 'updated_at', 'user_id')
        ));

        $this->select_attender_by_user_id($this->event_data->host_id);
        $this->reject_attender_by_user_id($this->event_data->host_id);

        Log::debug(sprintf('【%s】LINE:%s attenders:%s', __METHOD__, __LINE__,
            $this->attenders->pluck($is_until ? 'card_count' : 'updated_at', 'user_id')
        ));

        $this->prepend_attender();

        Log::debug(sprintf('【%s】LINE:%s attenders:%s', __METHOD__, __LINE__,
            $this->attenders->pluck($is_until ? 'card_count' : 'updated_at', 'user_id')
        ));

    }

    //練習に参加する新規参加者の数を取得
    //キャンセル時の人数計算に使用
    public function update_attended_newcomer_count()
    {
        try{
            Event::FindById($this->event_id)
                ->update(['attended_newcomer_count' => $this->attended_newcomer_count]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
    }

    //保有カードの枚数が2枚未満のユーザーを参加希望者の中から取得
    //ただし、主催者は除く
    public function get_one_card_attenders()
    {

        $this->one_card_attenders = $this->attenders->filter(function($value){
            return $value->user_id != $this->host_id && $value->card_count < 2;
        });

        Log::debug(sprintf('【%s】LINE:%s one_card_attenders:%s', __METHOD__, __LINE__, $this->one_card_attenders->pluck('user_id')));
    }

    public function set_attended_newcomer_count()
    {
        $this->attended_newcomer_count = ($this->vacancy_count >= 0 ? $this->vacancy_count : 0);
        Log::debug(sprintf('【%s】LINE:%s attended_newcomer_count:%s', __METHOD__, __LINE__, $this->attended_newcomer_count));
    }

    //カードが1枚の参加希望者は新規参加者に置き換えられるので、置き換えられた数を追加
    public function add_replaced_count_to_attended_newcomer_count(int $count)
    {
        $this->attended_newcomer_count += $count;
        Log::debug(sprintf('【%s】LINE:%s attended_newcomer_count:%s', __METHOD__, __LINE__, $this->attended_newcomer_count));
    }

    //新規参加者の数だけ、参加者オブジェクトから最後の参加者を削除
    public function replace_attender_with_newcomer()
    {
        //既存参加者と置き換えを行う数
        Log::debug(sprintf('【%s】LINE:%s newcomer_count:%s', __METHOD__, __LINE__, $this->newcomer_count));
        Log::debug(sprintf('【%s】LINE:%s vacancy_count:%s', __METHOD__, __LINE__, $this->vacancy_count));

        //練習に参加可能な新規参加者の数
        //空席あれば空席の数だけ
        //空席なければ、現時点では0
        $this->set_attended_newcomer_count();

        //空席がない場合でも、カード1枚のユーザーならば置き換え可能なので、
        //vacancy_countが負の数なら0で計算
        $this->maximum_replace_count = $this->newcomer_count - ($this->vacancy_count >= 0 ? $this->vacancy_count : 0);

        Log::debug(sprintf('【%s】LINE:%s maximum_replace_count:%s', __METHOD__, __LINE__, $this->maximum_replace_count));

        if($this->maximum_replace_count <= 0){ return; }

        //保有カードの枚数が2枚未満のユーザーを参加希望者の中から取得
        //ただし、主催者は除く
        $this->get_one_card_attenders();

        //新規参加者と置き換えられる参加者のユーザーIDを取得
        $replaced_user_ids = collect();
        for($i = 0;$i < $this->maximum_replace_count;$i++){
            //実際に置き換え可能なユーザーがいなければ終了
            if(!$this->one_card_attenders->count()){ break; }
            $replaced_user_ids->push($this->one_card_attenders->pop());
        }

        Log::debug(sprintf('【%s】LINE:%s replaced_user_id:%s', __METHOD__, __LINE__, $replaced_user_ids->pluck('user_id')));

        $this->add_replaced_count_to_attended_newcomer_count($replaced_user_ids->count());
        //練習に参加できる新規参加者の数を更新する
        //0の場合は0で更新
        if(!$this->update_attended_newcomer_count()){ return; }
        if(!$replaced_user_ids->count()){ return; }

        //新規参加者との置き換えを実施
        $replaced_user_ids->each(function($value, $key){
            $this->rejected_attenders->push($value);
            $this->reject_attender_by_user_id($value->user_id);
        });
    }

    //参加上限の人数と参加者数の差（空席数）
    //1週間以上前に参加表明したユーザーをチェックする場合は、
    //テーブルに保存されている参加人数上限と比較する
    //1週間以内に参加表明したユーザーをチェックする場合は、
    //既存の空席数と比較する
    public function set_vacancy_count(bool $is_until)
    {
        Log::debug(sprintf('【%s】LINE:%s is_until:%s', __METHOD__, __LINE__, $is_until));
        Log::debug(sprintf('【%s】LINE:%s vacancy_count:%s', __METHOD__, __LINE__, $this->vacancy_count));
        Log::debug(sprintf('【%s】LINE:%s limit_number:%s', __METHOD__, __LINE__, $this->limit_number));
        Log::debug(sprintf('【%s】LINE:%s attenders->count:%s', __METHOD__, __LINE__, $this->attenders->count()));
        
        $this->vacancy_count = $is_until
        ? ( $this->limit_number - $this->attenders->count() )
        : ( $this->vacancy_count - $this->attenders->count() );

        Log::debug(sprintf('【%s】LINE:%s vacancy_count:%s', __METHOD__, __LINE__, $this->vacancy_count));
    }
    
    //空席数がマイナスになっていた場合、0になるように参加者を減らす
    public function pop_attender_until_limit_number()
    {
        if($this->vacancy_count >= 0){
            return;
        }

        $abs = $this->get_abs_vacancy_count();
        $this->pop_attender($abs);
    }

    //空席数の絶対値を取得
    private function get_abs_vacancy_count()
    {
        return abs($this->vacancy_count);
    }

    //参加の確定したユーザーのステータスを更新
    public function approve()
    {
        try{
            $user_ids = $this->attenders->pluck('user_id');
            Log::debug(sprintf('【%s】LINE:%s user_ids:%s', __METHOD__, __LINE__, $user_ids));
            Attend::FindByUserIds($user_ids->all())
                ->FindByEventId($this->event_id)
                ->update(['status' => 2]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
    }

    //キャンセル待ちになったユーザーのステータスを更新
    public function waiting()
    {
        try{
            $user_ids = $this->rejected_attenders->pluck('user_id');
            Log::debug(sprintf('【%s】LINE:%s user_ids:%s', __METHOD__, __LINE__, $user_ids));
            Attend::FindByUserIds($user_ids->all())
                ->FindByEventId($this->event_id)
                ->update(['status' => 3]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
    }

    //キャンセルに変更があったユーザーにキャンセルを反映する
    public function cancel()
    {
        try{
            Attend::FindByEventIdAndUserId($this->event_id, $this->user_id)
                ->update(['status' => 4]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }
    
        return true;
    }

    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->int_properties, true) && is_numeric($value)){
                $this->$name = $value;
            }else{
                throw new Exception(sprintf('【%s】LINE:%s %s is invalid value', __METHOD__, __LINE__, $name));
            }    
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            exit;
        }
    }

}