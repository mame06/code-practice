var json_data =[
  { "subject" : "雨", "particle" : "が" , "action" : "降る" },
  { "subject" : "ゴキブリ", "particle" : "が" , "action" : "あらわれる" },
  { "subject" : "スマホ", "particle" : "が" , "action" : "壊れる" },
  { "subject" : "おふろ", "particle" : "が" , "action" : "冷たい" },
  { "subject" : "彼女", "particle" : "が" , "action" : "怒る" },
  { "subject" : "彼氏", "particle" : "が" , "action" : "リストラされる" },
  { "subject" : "電気", "particle" : "が" , "action" : "止まる" },
  { "subject" : "ごはん", "particle" : "が" , "action" : "まずい" },
  { "subject" : "レモンの汁", "particle" : "が" , "action" : "とぶ" },

];

var TAG = [
  { "open" : "<span>" , "close" : "</span>" },
  { "open" : "<p>" , "close" : "</p>" },
  { "open" : '<table border="1"><thead><tr><th colspan="3">起こる災難</th></tr></thead><tbody>' , "close" : '</tbody></table>' },
  { "open" : '<tbody>' , "close" : '</tbody>' },
  { "open" : '<tr>' , "close" : '</tr>' },
  { "open" : '<td>' , "close" : '</td>' },
];

const WORD = {
  TODAY: '今日は',
  FINISH: '。',
  PARTICLE: 'が',
  SAN: 'さん',
  BIRTH: '生まれの',
  ERR_MSG: '書き忘れていますね？',
  AGAIN: 'もう一度！',
  START: 'スタート',
}

$(document).ready(function(){

  var src = '';
      src += TAG[2].open;

      for (var i = 0; i < json_data.length; i++) {
        src += '    <tr><td>' + json_data[i].subject + '</td>';
        src += '    <td>' + WORD.PARTICLE + '</td>';
        src += '    <td>' + json_data[i].action + '</td></tr>'
      }

      src += TAG[2].close;
    $('#calamity_data').html(src);


  $('#input_btn').on('click',function(){

    var out_name = $('#in_name').val();
    var out_birthday = $('#in_birthday').val();
    var again_btn = $('');
    var err_msg = '';

    $('#input_area').find('.err').hide();
    $('#input_area').find('input').removeClass('err_style');

    if(out_name === ''){
      var err_msg = WORD.ERR_MSG;
      $('#err_name').show();
      $('#in_name').addClass('err_style');
    }if(out_birthday === ''){
      var err_msg = WORD.ERR_MSG;
      $('#err_birthday').show();
      $('#in_birthday').addClass('err_style');
    }if(err_msg === ''){
      var out_text = TAG[1].open + out_birthday + WORD.BIRTH + out_name + WORD.SAN + TAG[1].close;
      $('#out_area').html(out_text);
      $('#input_area').toggle();
      $('#fortune_area').toggle();
    }else{
      alert(err_msg);
    }


  });


  $('#start_btn').on('click',function(){

    var randSub = json_data[Math.floor(Math.random() * json_data.length)].subject;
    var randAct = json_data[Math.floor(Math.random() * json_data.length)].action;

    var subjectRa = TAG[0].open + randSub +  TAG[0].close;
    var actionRa = TAG[0].open + randAct + TAG[0].close;
    var particleRa = TAG[0].open + json_data[0].particle + TAG[0].close;

    var fortune = WORD.TODAY + subjectRa + WORD.PARTICLE + actionRa + WORD.FINISH;

    $('#fortune_text').show();
    $('#fortune_text').html(fortune);
    $('#start_btn').text(WORD.AGAIN) ;
  });

  $('#back_btn').on('click',function(){
    $('#input_area').toggle();
    $('#fortune_area').toggle();
    $('#fortune_text').hide();
    $('#start_btn').text(WORD.START) ;
  });








});
