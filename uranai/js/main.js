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
}

$(document).ready(function(){

  var src = '';
      src += TAG[2].open;

      for (var i = 0; i < json_data.length; i++) {
        src += '    <tr><td>' + json_data[i].subject + '</td>';
        src += '    <td>が</td>';
        src += '    <td>' + json_data[i].action + '</td></tr>'
      }

      src += TAG[2].close;



    $('#calamity_data').html(src);

  $('.btn_start').on('click',function(){

    var randSub = json_data[Math.floor(Math.random() * json_data.length)].subject;

    var randAct = json_data[Math.floor(Math.random() * json_data.length)].action;


    var subjectRa = TAG[0].open + randSub +  TAG[0].close;
    var actionRa = TAG[0].open + randAct + TAG[0].close;
    var particleRa = TAG[0].open + json_data[0].particle + TAG[0].close;

    var fortune = WORD.TODAY + subjectRa + WORD.PARTICLE + actionRa + WORD.FINISH;

    $('.fortune_text').html(fortune);

  });








});
