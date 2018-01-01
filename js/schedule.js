var database = firebase.database();
$(document).ready(function() {

  firebase.database().ref('/subjects/').once('value').then(function(snapshot) {
    $("#subject_list").empty();
    option = "<option>Select Class</option>";
    $("#subject_list").append(option);
    $.each(snapshot.val(), function(key) {
      curr_subject = JSON.parse(snapshot.val()[key]);
      for (subject in curr_subject) {
        option = "<option>" + curr_subject[subject] + "</option>";
        $("#subject_list").append(option);
      }
    });
  });

  firebase.database().ref('/terms/').once('value').then(function(snapshot) {
    // $("#subject_list").empty();
    // $.each(snapshot.val(), function(key) {
    //   curr_subject = JSON.parse(snapshot.val()[key]);
    //   console.log(curr_subject);
    //   for (subject in curr_subject) {
    //     option = "<option>" + curr_subject[subject] + "</option>";
    //     $("#subject_list").append(option);
    //   }
    // });
  });

  firebase.database().ref('/course_levels/').once('value').then(function(snapshot) {
    $("#level_list").empty();
    option = "<option>Select Level</option>";
    $("#level_list").append(option);
    $.each(snapshot.val(), function(key) {
      curr_level = JSON.parse(snapshot.val()[key]);
      for (level in curr_level) {
        option = "<option>" + curr_level[level] + "</option>";
        $("#level_list").append(option);
      }
    });
  });
});
