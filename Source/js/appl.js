function set()
{
   if ($("#frm-event-repeat").val() == "0")
   {   
      $("#frm-event-interval").val("");
      $("#frm-event-interval").prop("disabled", true);
      $("#frm-event-interval").prop("required", false);
      $("#frm-event-unit").val("");
      $("#frm-event-unit").prop("disabled", true);
      $("#frm-event-unit").prop("required", false);
      $("#frm-event-start").val("");
      $("#frm-event-start").prop("disabled", true);
      $("#frm-event-end").val("");
      $("#frm-event-end").prop("disabled", true);
      $("#frm-event-end").prop("required", false);
      $(".form-div-interval").hide();
      $(".form-div-start").hide();
      $(".form-div-end").hide();
      $("#frm-event-run_at").prop("disabled", false);
      $("#frm-event-run_at").prop("required", true);
      $(".form-div-run_at").show();
   }
   else
   {   
      $("#frm-event-run_at").prop("disabled", true);
      $("#frm-event-run_at").prop("required", false);
      $(".form-div-run_at").hide();
      $("#frm-event-interval").prop("disabled", false);
      $("#frm-event-interval").prop("required", true);
      $("#frm-event-unit").prop("disabled", false);
      $("#frm-event-unit").prop("required", true);
      $("#frm-event-start").prop("disabled", false);
      $("#frm-event-end").prop("disabled", false);
      $(".form-div-interval").show();
      $(".form-div-start").show();
      $(".form-div-end").show();
   }
}

