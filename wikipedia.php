
<?php 
// This File By :- @s_s_a_15
$API_KEY = "bot"; // Your Token
define('API_SAJAD',$API_KEY); 
function rues($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_SAJAD."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$chat_id = $message->chat->id;
$text = $message->text;
if ($text == "/start") {
	# code...
	bot('sendMessage',[
		'chat_id'=>$chat_id,
		'text'=>"_مرحبا بك في بوت الموسوعه الحره يمكنك الان البحث عن معلومات عامة عن اي شيئ فقط ارسال النص او لكلمه وسيتم البحث عنها_ \n اذا كنتة توجة مشكلة في البوت تواصل مع مبرمج البوت_  [sajad](https://t.me/s_s_abot)",
		'parse_mode'=>"markdown",
		'reply_markup'=>json_encode([
			'inline_keyboard'=>[
				[['text'=>"تابعنا 💫", 'url'=>"https://t.me/s_s_abot"]]
			]
		])
	]);
}
if ($text != "/start") {
	$req = file_get_contents('https://ar.wikipedia.org/w/api.php?action=query&list=search&srsearch='.urldecode($text).'&utf8=&format=json');
	$data = json_decode($req);
	if ($data->query->searchinfo->totalhits == 0) {
		bot('sendMessage',[
		'chat_id'=>$chat_id,
		'text'=>"لا يوجد اي نتيجه للبحث :- $text ",
		'parse_mode'=>"markdown",
	]);
	}else{
	for ($i=0; $i < count($data->query->searchinfo->totalhits); $i++) { 
		bot('sendMessage',[
			'chat_id'=>$chat_id,
			'text'=>"`".$data->query->search[$i]->title."`\n\n *".$data->query->search[$i]->snippet."*\n\n _تاريخ النشر_ : "$data->query->search[$i]->timestamp,
			'parse_mode'=>"markdown",
		]);
	}
	}


}
