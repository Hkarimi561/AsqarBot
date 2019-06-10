<?php

namespace App\Http\Controllers;

use App\GroupUsers;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;

class TelegramController extends Controller
{

    /** @var Api */
    protected $telegram;

    /**
     * BotController constructor.
     *
     * @param Api $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }
    /**
     * Get updates from Telegram.
     */
    public function getUpdates()
    {
        $updates = $this->telegram->getUpdates()->getResult();
        // Do something with the updates
    }
    /**
     * Set a webhook.
     */
    public function setWebhook()
    {
        // Edit this with your webhook URL.
        // You can also use: route('bot-webhook')
        $url = 'https://d241083e.ngrok.io/api/AAG-SYKASFonUkzgoayUPzj2d0Jx5Mv-SL8/webhook';
        $response = $this->telegram->setWebhook([
            "url"=>$url
        ]);

        return $response;
    }
    /**
     * Remove webhook.
     *
     * @return array
     */
    public function removeWebhook()
    {
        $response = $this->telegram->removeWebhook();
        return $response->getDecodedBody();
    }
    /**
     * Handles incoming webhook updates from Telegram.
     *
     * @return string
     */
    public function webhookHandler()
    {
        // If you're not using commands system, then you can enable this.
        $update = $this->telegram->getWebhookUpdate();
        // This fetchs webhook update + processes the update through the commands system.
//        $update = $this->telegram->commandsHandler(true);
        // Commands handler method returns an Update object.
        // So you can further process $update object
        // to however you want.
        // Below is an example
        $message = $update->getMessage();
        // Triggers when your bot receives text messages like:
        // - Can you inspire me?
        // - Do you have an inspiring quote?
        // - Tell me an inspirational quote
        // - inspire me
        // - Hey bot, tell me an inspiring quote please?
        if(str_contains($message->text, ['tagasqar'])) {
            $c_id=$message->chat->id;
            $c_id=str_replace("-100","",$c_id);
            $groupUsers=GroupUsers::where("group_id",'like',$c_id)->get()->toArray();
            $groupUsers=array_chunk($groupUsers, 10);
            foreach ($groupUsers as $groupList){
                $listOfUsers="";
                foreach ($groupList as $user){
                    $user="[".$user['name']."](tg://user?id=".$user['user_id'].")\n";
                    $listOfUsers.=$user;
                }

//                $response = $this->telegram->sendMessage([
//                    'chat_id' => $message->chat->id,
//                    'parse_mode'=>'Markdown',
//                    'text' => $listOfUsers
//                ]);
            }
        }else if ($message->newChatMembers){
            $c_id=$message->chat->id;
            $c_id=str_replace("-100","",$c_id);
            foreach ($message->newChatMembers as $newChatMember) {

                if($newChatMember['is_bot']){
                    $this->telegram->kickChatMember([
                        'chat_id' => $message->chat->id,
                        'user_id'=>$newChatMember['id']
                    ]);
                }else{
                    $gcheck=GroupUsers::where('user_id','like',$newChatMember['id'])->where('group_id','like',$c_id)->get()->count();
                    if($gcheck<1){
                        $userData=[
                            "username"=>$newChatMember['username'],
                            "name"=>$newChatMember['first_name'].' '.$newChatMember['last_name'],
                            'user_id'=>$newChatMember['id'],
                            'group'=>$message->chat->title,
                            'group_id'=>$c_id
                        ];
                        GroupUsers::create($userData);
                    }

                }
            }
        }
        return 'Ok';
    }

    public function hookTester()
    {
        // If you're not using commands system, then you can enable this.
        $update = $this->telegram->getWebhookUpdate();
        // This fetchs webhook update + processes the update through the commands system.
//        $update = $this->telegram->commandsHandler(true);
        // Commands handler method returns an Update object.
        // So you can further process $update object
        // to however you want.
        // Below is an example
        $message = $update->getMessage();
        // Triggers when your bot receives text messages like:
        // - Can you inspire me?
        // - Do you have an inspiring quote?
        // - Tell me an inspirational quote
        // - inspire me
        // - Hey bot, tell me an inspiring quote please?
        if(str_contains($message->text, ['tagasqar'])) {
            $c_id=$message->chat->id;
            $c_id=str_replace("-100","",$c_id);
            $groupUsers=GroupUsers::where("group_id",'like',$c_id)->get()->toArray();
            dd($groupUsers);


        }elseif (str_contains($message->text, ['ساخته شده روی دکمه وارد شوید کلیک کنید تا بهتون مجوز ورود به روستا رو بدم'])){
            $c_id=$message->chat->id;
            $c_id=str_replace("-100","",$c_id);
            $groupUsers=GroupUsers::where("group_id",'like',$c_id)->get()->toArray();
            dd($groupUsers);

        } else if ($message->newChatMembers){
            $c_id=$message->chat->id;
            $c_id=str_replace("-100","",$c_id);
            foreach ($message->newChatMembers as $newChatMember) {

                if($newChatMember['is_bot']){
                    $this->telegram->kickChatMember([
                        'chat_id' => $message->chat->id,
                        'user_id'=>$newChatMember['id']
                    ]);
                }else{
                    $gcheck=GroupUsers::where('user_id','like',$newChatMember['id'])->where('group_id','like',$c_id)->get()->count();
                    if($gcheck<1){
                        $userData=[
                            "username"=>$newChatMember['username'],
                            "name"=>$newChatMember['first_name'].' '.$newChatMember['last_name'],
                            'user_id'=>$newChatMember['id'],
                            'group'=>$message->chat->title,
                            'group_id'=>$c_id
                        ];
                        GroupUsers::create($userData);
                    }

                }
            }
        }
        return 'Ok';

    }
}
