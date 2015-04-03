<?php

Route::get('sms/test',function() {
    return "Test page of sms service";
});

// Отправка СМС
Route::get('sms/send/{phone}/{text}/{creator}', function($phone,$text,$creator) {
	DB::table('outbox')->insert( array('DestinationNumber' => '+'.$phone, 'TextDecoded' => rawurldecode($text), 'CreatorID' => $creator, 'Coding' => 'Unicode_No_Compression'));
	Log::info("Send SMS to ".$phone." with  text = ".rawurldecode($text));
    return "Send SMS to ".$phone." with  text = ".rawurldecode($text);
});

// Получение списка исходящих СМС
Route::get('sms/outgoing/all',function(){
    $outgoing_sms = DB::table('sentitems')->select('SendingDateTime','DestinationNumber','TextDecoded','CreatorID','Status')->orderBy('SendingDateTime', 'desc')->get();
    return Response::json($outgoing_sms);
});

// Получение статистики по исходящим СМС в разрезе по дням и службам отправки
Route::get('sms/outstat/all',function(){
    $outgoing_stat = DB::table('sentitems')->select(DB::raw('count(*) as sms_count, DATE(SendingDateTime) as day, CreatorID'))->groupBy(DB::raw('DATE(SendingDateTime),CreatorID'))->orderBy('SendingDateTime', 'desc')->get();
    return Response::json($outgoing_stat);
});
                            
// Получение статистики по исходящим СМС в разрезе по дням
Route::get('sms/outstat/days',function(){
    $outgoing_stat = DB::table('sentitems')->select(DB::raw('count(*) as sms_count, DATE(SendingDateTime) as day'))->groupBy(DB::raw('DATE(SendingDateTime)'))->orderBy('SendingDateTime', 'desc')->get();
    return Response::json($outgoing_stat);
});
                                        
// Получение списка входящих СМС
Route::get('sms/incoming/all',function(){
    $incoming_sms = DB::table('inbox')->select('ReceivingDateTime','SenderNumber','TextDecoded')->orderBy('ReceivingDateTime', 'desc')->get();
    return Response::json($incoming_sms);
});
