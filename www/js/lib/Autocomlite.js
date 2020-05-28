$('#studentAjaxPractic').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: "/admin/practic/searchStudent/",
      type: "GET",
      dataType: "json",
      data: {item: request.term},
      success: function (data) {
        var selectData = [];
        let i = 0;
        while (i <= data.length - 1) {
          selectData.push({"label": data[i]['name'],
            "value": data[i]['identificate']});
          i++;
        }
        response($.map( selectData, function(item){
          return item;
        }));
      }
    });
  },
  minLength: 1,
  delay: timer,
  select: function (e, ui) {
    $(location).attr('href', '/admin/practic/list/?student=' + ui.item.value);
  }
});

$('#studentAjaxHelp').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: "/admin/help/searchStudent/",
      type: "GET",
      dataType: "json",
      data: {item: request.term},
      success: function (data) {
        var selectData = [];
        let i = 0;
        while (i <= data.length - 1) {
          selectData.push({"label": data[i]['name'],
            "value": data[i]['identificate']});
          i++;
        }
        response($.map( selectData, function(item){
          return item;
        }));
      }
    });
  },
  minLength: 1,
  delay: timer,
  select: function (e, ui) {
    $(location).attr('href', '/admin/help/list/?student=' + ui.item.value);
  }
});