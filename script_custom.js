jQuery(document).ready(function($) {


	var custom_survey_tracker = window.custom_survey_tracker = {

		load: function() {


			this.add_action();

		},

		add_action: function() {

							console.log("t add_action");


			$(document).on("click", ".survey_element.survey_answer_choice", function() {


				var ans = $(".survey_element.survey_answers.selected").length;
				var progress = $(".progress_counter").text();


				if (ans == 0)
					return;

				var survey_id = $(this).closest('.modal-survey-container.modal-survey-embed').attr("id");

				var question = $(".survey_element.survey_question > span").text();

				console.log(survey_id);
				console.log(question);

				        var data = {
				          'action' : 'custom_survey_tracker',
				          'survey_id' : survey_id,
				          'question' : question,
				          'progress' : progress
				        };

				        jQuery.post(plugin_data_custom_survey_tracker.ajax_url, data, function(response) {

				        	console.log(response);

				        });

			})

		}

	}

custom_survey_tracker.load();

})